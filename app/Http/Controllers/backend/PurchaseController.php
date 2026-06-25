<?php

namespace App\Http\Controllers\backend;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductVariant; // নতুন ভ্যারিয়েন্ট মডেল
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PurchaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view purchase', only: ['index']),
            new Middleware('permission:create purchase', only: ['create']),
            new Middleware('permission:edit purchase', only: ['edit']),
            new Middleware('permission:destroy purchase', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Purchase::with('supplier')
                ->select('id', 'supplier_id', 'invoice_no', 'purchase_date', 'total_amount', 'status', 'created_at')
                ->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('supplier_name', fn($row) => $row->supplier?->name ?? '—')
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge-soft-success">Active</span>'
                        : '<span class="badge-soft-danger">Inactive</span>';
                })
                ->addColumn('action', function($row) {
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details"><i class="fa-regular fa-eye"></i></button>';
                    $editBtn = '<a href="'.route('purchases.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit Purchase"><i class="fa-regular fa-pen-to-square"></i></a>';
                    $deleteForm = '
                        <form class="delete-form d-inline" action="'.route('purchases.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Purchase">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.purchase.index');
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 1)->get();
        $lastInvoice = Purchase::orderBy('id', 'desc')->first();
        $nextInvoiceNo = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        return view('backend.purchase.create', compact('suppliers', 'nextInvoiceNo'));
    }

    // [MODIFIED] Search ekti product er sathe tar variants gulo pull korbe
    public function search(Request $request)
    {
        $query = $request->q;

        $products = Product::query()
            ->select(['id', 'name', 'sku', 'purchase_price', 'product_type'])
            ->where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                ->orWhere('sku', 'like', "%{$query}%")
                
                ->orWhereHas('variants', function($vQ) use ($query) {
                    $vQ->where('sku', 'like', "%{$query}%")
                        ->orWhereHas('color', function($cQ) use ($query) {
                            $cQ->where('name', 'like', "%{$query}%");
                        })
                        ->orWhereHas('size', function($sQ) use ($query) {
                            $sQ->where('name', 'like', "%{$query}%");
                        });
                });
            })
            ->with(['variants.color', 'variants.size']) 
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'         => 'required|exists:suppliers,id',
            'invoice_no'          => 'required|unique:purchases,invoice_no',
            'purchase_date'       => 'required|date',
            'products'            => 'required|array|min:1',
            'products.*.id'       => 'required|exists:products,id',
            'products.*.variant_id'=> 'nullable', // Added variant_id
            'products.*.qty'      => 'required|numeric|min:1',
            'products.*.price'    => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = collect($request->products)->sum(fn($item) => $item['qty'] * $item['price']);

            $purchase = Purchase::create([
                'supplier_id'    => $request->supplier_id,
                'invoice_no'     => $request->invoice_no,
                'purchase_date'  => $request->purchase_date,
                'total_amount'   => $totalAmount,
                'status'         => 1,
                'payment_status' => 1,
                'notes'          => $request->notes ?? null,
            ]);

            foreach ($request->products as $item) {
                $variantId = $item['variant_id'] ?? null;
                $variant = $variantId ? ProductVariant::find($variantId) : null;

                PurchaseDetail::create([
                    'purchase_id'        => $purchase->id,
                    'product_id'         => $item['id'],
                    'product_variant_id' => $variantId,
                    'quantity'           => $item['qty'],
                    'buying_price'       => $item['price'],
                    'total_price'        => $item['qty'] * $item['price'],
                ]);

                // Update Stock
                $this->adjustStock($item['id'], $variantId, $item['qty'], 'add');

                // Mark product as purchased
                Product::where('id', $item['id'])->update(['is_purchased' => 1]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Purchase created successfully & stock updated!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'details.product',
            'details.variant.size',  
            'details.variant.color'  
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $purchase
        ]);
    }

    public function edit($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'details.product.variants.color',
            'details.product.variants.size', 
        ])->findOrFail($id);

        $suppliers = Supplier::where('status', 1)->select('id', 'name')->get();

        return view('backend.purchase.edit', compact('purchase', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id'           => 'required|exists:suppliers,id',
            'invoice_no'            => 'required|unique:purchases,invoice_no,' . $id,
            'purchase_date'         => 'required|date',
            'products'              => 'required|array|min:1',
            'products.*.id'         => 'required|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.qty'        => 'required|numeric|min:1',
            'products.*.price'      => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $purchase = Purchase::with('details')->findOrFail($id);

            if ($purchase->is_sale == 1) {
                return response()->json(['status' => 'error', 'message' => 'Purchase has been sold, cannot update!'], 400);
            }

            foreach ($purchase->details as $old) {
                $this->adjustStock($old->product_id, $old->product_variant_id, $old->quantity, 'subtract');
            }

            $totalAmount = collect($request->products)->sum(fn($item) => $item['qty'] * $item['price']);
            $purchase->update([
                'supplier_id'   => $request->supplier_id,
                'invoice_no'    => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'total_amount'  => $totalAmount,
            ]);

            $purchase->details()->delete();

            $variantIds = collect($request->products)->pluck('variant_id')->filter()->unique()->toArray();
            $variants = ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');

            foreach ($request->products as $item) {
                $variantId = $item['variant_id'] ?? null;
                $variant = $variantId ? $variants->get($variantId) : null;

                PurchaseDetail::create([
                    'purchase_id'        => $purchase->id,
                    'product_id'         => $item['id'],
                    'product_variant_id' => $variantId,
                    'color_id'           => $variant ? $variant->color_id : null,
                    'product_size_id'    => $variant ? $variant->size_id : null,
                    'quantity'           => $item['qty'],
                    'buying_price'       => $item['price'],
                    'total_price'        => $item['qty'] * $item['price'],
                ]);

                $this->adjustStock($item['id'], $variantId, $item['qty'], 'add');
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Purchase updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::with('details')->findOrFail($id);

            if ($purchase->is_sale == 1) {
                return response()->json(['status' => 'error', 'message' => 'Purchase has been sold, cannot delete!'], 400);
            }

            $productIds = [];
            foreach ($purchase->details as $detail) {
                $productIds[] = $detail->product_id;
                $this->adjustStock($detail->product_id, $detail->product_variant_id, $detail->quantity, 'subtract');
            }

            $purchase->details()->delete();
            $purchase->delete();

            $uniqueProductIds = array_unique($productIds);
            foreach ($uniqueProductIds as $productId) {
                $hasOtherPurchases = PurchaseDetail::where('product_id', $productId)->exists();
                if (!$hasOtherPurchases) {
                    Product::where('id', $productId)->update(['is_purchased' => 0]);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Purchase deleted successfully & stock adjusted!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Failed to delete purchase: ' . $e->getMessage()], 500);
        }
    }

    private function adjustStock($productId, $variantId, $qty, $action = 'add')
    {
        $product = Product::find($productId);
        if (!$product) return;

        // Add or Subtract Main Product Stock
        if ($action === 'add') {
            $product->increment('stock', $qty);
        } else {
            $newStock = max(0, $product->stock - $qty);
            $product->update([
                'stock' => $newStock
            ]);
        }

        // Add or Subtract Variant Stock
        if ($product->product_type === 'multiple' && $variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant) {
                if ($action === 'add') {
                    $variant->increment('stock', $qty);
                } else {
                    $variant->decrement('stock', $qty);
                    if ($variant->stock < 0) $variant->update(['stock' => 0]);
                }
            }
        }
    }
}