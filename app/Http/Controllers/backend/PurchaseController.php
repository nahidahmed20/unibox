<?php

namespace App\Http\Controllers\backend;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Purchase::with('supplier')
                ->select('id', 'supplier_id', 'invoice_no', 'purchase_date', 'total_amount', 'status', 'created_at')
                ->latest();

            return DataTables::of($data)

                ->addIndexColumn()

                // Supplier
                ->addColumn('supplier_name', fn($row) => $row->supplier?->name ?? '—')

                // Status (safe version)
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge-soft-success">Active</span>'
                        : '<span class="badge-soft-danger">Inactive</span>';
                })

                // ACTION (modern UI)
                ->addColumn('action', function($row) {

                    // View Button
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    // Edit Button
                    $editBtn = '<a href="'.route('purchases.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit Product">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    // Delete Button
                    $deleteForm = '
                        <form class="delete-form d-inline" action="'.route('purchases.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Product">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    // Wrapping all buttons in a centered flex div
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $lastInvoice = Purchase::orderBy('id', 'desc')->first();

        $nextInvoiceNo = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 5, '0', STR_PAD_LEFT);

        return view('backend.purchase.create', compact('suppliers', 'nextInvoiceNo'));
    }

    public function search(Request $request)
    {
        $query = $request->q;

        $products = Product::query()
            ->select(['id', 'name', 'sku', 'purchase_price'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%");
            })
            ->where('status', 1)
            ->with(['sizes', 'colors.color'])
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'        => 'required|exists:suppliers,id',
            'invoice_no'         => 'required|unique:purchases,invoice_no',
            'purchase_date'      => 'required|date',

            'products'           => 'required|array|min:1',

            'products.*.id'      => 'required|exists:products,id',
            'products.*.size_id' => 'nullable',
            'products.*.color_id' => 'nullable',
            'products.*.qty'     => 'required|numeric|min:1',
            'products.*.price'   => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {

            $totalAmount = collect($request->products)->sum(function ($item) {
                return $item['qty'] * $item['price'];
            });

            $purchase = Purchase::create([
                'supplier_id'    => $request->supplier_id,
                'invoice_no'     => $request->invoice_no,
                'purchase_date'  => $request->purchase_date,
                'total_amount'   => $totalAmount,
                'status'         => 1,
                'payment_status' => 1,
                'notes'          => $request->notes,
            ]);

            foreach ($request->products as $item) {

                $sizeId  = !empty($item['size_id']) ? $item['size_id'] : null;
                $colorId = !empty($item['color_id']) ? $item['color_id'] : null;

                PurchaseDetail::create([
                    'purchase_id'  => $purchase->id,
                    'product_id'   => $item['id'],
                    'product_size_id' => $sizeId,
                    'color_id'     => $colorId,
                    'quantity'     => $item['qty'],
                    'buying_price' => $item['price'],
                    'total_price'  => $item['qty'] * $item['price'],
                ]);

                $stock = ProductStock::where([
                    'product_id' => $item['id'],
                    'size_id'    => $sizeId,
                    'color_id'   => $colorId,
                ])->first();

                if ($stock) {
                    $stock->increment('quantity', $item['qty']);
                } else {
                    ProductStock::create([
                        'product_id' => $item['id'],
                        'size_id'    => $sizeId,
                        'color_id'   => $colorId,
                        'quantity'   => $item['qty'],
                    ]);
                }
                Product::where('id', $item['id'])
                    ->update([
                        'is_purchased' => 1
                    ]);
            }
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Purchase created successfully & stock updated!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'details.product', 'details.color', 'details.size'])->findOrFail($id);
// dd($purchase);
        return response()->json([
            'status' => 'success',
            'data'   => $purchase
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $purchase = Purchase::with([
            'supplier',
            'details',
            'details.product',
            'details.product.sizes',
            'details.product.colors.color',
        ])->findOrFail($id);

        $suppliers = Supplier::where('status', 1)
            ->select('id', 'name')
            ->get();

        return view(
            'backend.purchase.edit',
            compact(
                'purchase',
                'suppliers'
            )
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_no' => 'required|unique:purchases,invoice_no,' . $id,
            'purchase_date' => 'required|date',

            'products' => 'required|array|min:1',

            'products.*.product_id' => 'required|exists:products,id',
            'products.*.qty' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.size_id' => 'nullable',
            'products.*.color_id' => 'nullable',
        ]);

        DB::beginTransaction();

        try {

            $purchase = Purchase::with('details')->findOrFail($id);

            /*
            =====================================================
            1. REVERT OLD STOCK
            =====================================================
            */
            foreach ($purchase->details as $old) {

                $stock = ProductStock::where('product_id', $old->product_id)
                    ->where(function ($q) use ($old) {
                        $old->size_id
                            ? $q->where('size_id', $old->size_id)
                            : $q->whereNull('size_id');
                    })
                    ->where(function ($q) use ($old) {
                        $old->color_id
                            ? $q->where('color_id', $old->color_id)
                            : $q->whereNull('color_id');
                    })
                    ->first();

                if ($stock) {
                    $stock->decrement('quantity', $old->quantity);

                    if ($stock->quantity < 0) {
                        $stock->update(['quantity' => 0]);
                    }
                }
            }

            /*
            =====================================================
            2. UPDATE PURCHASE MAIN
            =====================================================
            */
            $total = collect($request->products)->sum(function ($item) {
                return $item['qty'] * $item['price'];
            });

            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'invoice_no' => $request->invoice_no,
                'purchase_date' => $request->purchase_date,
                'total_amount' => $total,
            ]);

            /*
            =====================================================
            3. DELETE OLD DETAILS
            =====================================================
            */
            $purchase->details()->delete();

            /*
            =====================================================
            4. INSERT NEW + STOCK UPDATE
            =====================================================
            */
            foreach ($request->products as $item) {

                $productId = $item['product_id'];
                $sizeId = $item['size_id'] ?? null;
                $colorId = $item['color_id'] ?? null;

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'product_size_id' => $sizeId,
                    'color_id' => $colorId,
                    'quantity' => $item['qty'],
                    'buying_price' => $item['price'],
                    'total_price' => $item['qty'] * $item['price'],
                ]);

                $stock = ProductStock::where('product_id', $productId)
                    ->where(function ($q) use ($sizeId) {
                        $sizeId ? $q->where('size_id', $sizeId) : $q->whereNull('size_id');
                    })
                    ->where(function ($q) use ($colorId) {
                        $colorId ? $q->where('color_id', $colorId) : $q->whereNull('color_id');
                    })
                    ->first();

                if ($stock) {
                    $stock->increment('quantity', $item['qty']);
                } else {
                    ProductStock::create([
                        'product_id' => $productId,
                        'size_id' => $sizeId,
                        'color_id' => $colorId,
                        'quantity' => $item['qty'],
                    ]);
                }

                Product::where('id', $productId)
                    ->update(['is_purchased' => 1]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase updated successfully!'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $purchase = Purchase::with('details')->findOrFail($id);

            if ($purchase->is_sale == 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Purchase has been sold, cannot delete!',
                ], 400);
            }

            $productIds = [];

            foreach ($purchase->details as $detail) {

                $productIds[] = $detail->product_id;

                $stock = ProductStock::where('product_id', $detail->product_id)
                    ->where('color_id', $detail->color_id)
                    ->where('size_id', $detail->size_id)
                    ->first();

                if ($stock) {

                    $newQty = $stock->quantity - $detail->quantity;

                    $stock->update([
                        'quantity' => max(0, $newQty)
                    ]);
                }
            }

            // delete details FIRST using relation data already loaded
            $purchase->details()->delete();

            // update products safely
            Product::whereIn('id', array_unique($productIds))
                ->update(['is_purchased' => 0]);

            // delete purchase
            $purchase->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Purchase deleted successfully & stock adjusted!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete purchase: ' . $e->getMessage(),
            ], 500);
        }
    }
}
