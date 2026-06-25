<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Shipping;
use App\Models\User;
use Illuminate\Contracts\Pipeline\Hub;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view sale', only: ['index']),
            new Middleware('permission:create sale', only: ['create']),
            new Middleware('permission:edit sale', only: ['edit']),
            new Middleware('permission:destroy sale', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Sale::with('customer')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', fn($row) => $row->customer?->name ?? '—')

                ->addColumn('delivery_charge', function ($row) {
                    if ($row->delivery_charge == 0) {
                        return 'Free Delivery (0)';
                    } elseif ($row->delivery_charge == 60) {
                        return 'Inside Dhaka (60)';
                    } elseif ($row->delivery_charge == 120) {
                        return 'Outside Dhaka (120)';
                    }
                    return '' . $row->delivery_charge;
                })

                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                
                ->addColumn('action', function($row) {

                    // View Button
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    // Edit Button
                    $editBtn = '<a href="'.route('sales.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit Product">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    // Delete Button
                    $deleteForm = '
                        <form class="delete-form d-inline" action="'.route('sales.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Product">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    // Wrapping all buttons in a centered flex div
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })
                ->rawColumns(['status', 'action', 'delivery_charge'])
                ->make(true);
        }

        return view('backend.sale.index'); // Blade view
    }


    public function create()
    {
        $customers = Customer::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();

        $products = Product::with([
            'category',
            'variants.color', // Variant-er color load hobe
            'variants.size',  // Variant-er size load hobe
            'colors.color'
        ])
        ->where('status', 1)
        ->get();

        return view('backend.sale.create', compact('customers', 'products', 'categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'sale_date'        => 'required|date',
            'product_id.*'     => 'required|exists:products,id',
            'quantity.*'       => 'required|numeric|min:1',
            'selling_price.*'  => 'required|numeric|min:0',
            'discount_type'    => 'nullable|in:flat,percent',
            'delivery_type'    => 'nullable|in:inside,outside,free',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                
                $latestSale = Sale::latest()->first();
                $invoice_no = $latestSale 
                    ? 'INV' . str_pad($latestSale->id + 1, 4, '0', STR_PAD_LEFT) 
                    : 'INV000001';

                $totalAmount = array_sum($request->total_price);
                $discount = $request->discount ?? 0;
                $discountAmount = $request->discount_type === 'percent' 
                    ? ($totalAmount * $discount / 100) 
                    : $discount;

                $deliveryCharge = match ($request->delivery_type) {
                    'inside'  => 60,
                    'outside' => 120,
                    'free'    => 0,
                    default   => 0,
                };

                $grandTotal = ($totalAmount - $discountAmount) + $deliveryCharge;
                $paidAmount = $request->paid_amount ?? 0;
                $dueAmount  = max(0, $grandTotal - $paidAmount);

                $sale = Sale::create([
                    'invoice_no'      => $invoice_no,
                    'customer_id'     => $request->customer_id,
                    'user_id'         => $request->user_id ?? Auth::id(),
                    'branch_id'       => $request->branch_id,
                    'sale_date'       => $request->sale_date,
                    'total_amount'    => $totalAmount,
                    'discount'        => $discountAmount,
                    'discount_type'   => $request->discount_type,
                    'delivery_charge' => $deliveryCharge,
                    'grand_total'     => $grandTotal,
                    'paid_amount'     => $paidAmount,
                    'due_amount'      => $dueAmount,
                    'payment_method'  => $request->payment_method,
                    'created_by'      => Auth::id(),
                    'status'          => 1,
                ]);

                foreach ($request->product_id as $index => $pid) {
                    $colorId = !empty($request->color_id[$index]) ? $request->color_id[$index] : null;
                    $sizeId  = !empty($request->size_id[$index]) ? $request->size_id[$index] : null;
                    $qty     = $request->quantity[$index];
                    $price   = $request->selling_price[$index];

                    $mainProduct = Product::find($pid);

                    if (!$mainProduct) {
                        throw new \Exception("Product ID: {$pid} not found.");
                    }

                    if ($mainProduct->product_type === 'multiple') {
                        $variant = ProductVariant::query() 
                            ->where('product_id', $pid)
                            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
                            ->first();

                        if (!$variant || $variant->stock < $qty) {
                            throw new \Exception("Insufficient variant stock for: {$mainProduct->name}");
                        }
                        $variant->decrement('stock', $qty);
                    } 
                    else {
                        if ($mainProduct->stock < $qty) {
                            throw new \Exception("Insufficient stock for: {$mainProduct->name}");
                        }
                    }

                    $mainProduct->decrement('stock', $qty);

                    // ৪. Sale Item Create
                    SaleItem::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $pid,
                        'color_id'      => $colorId,
                        'size_id'       => $sizeId,
                        'quantity'      => $qty,
                        'selling_price' => $price,
                        'total_price'   => $qty * $price,
                    ]);
                }

                /* ---------------- Payment ---------------- */
                if ($paidAmount > 0) {
                    Payment::create([
                        'sale_id'        => $sale->id,
                        'customer_id'    => $sale->customer_id,
                        'payment_date'   => now(),
                        'payment_method' => $request->payment_method,
                        'amount'         => $paidAmount,
                        'status'         => 'success',
                    ]);
                }

                return $sale;
            });

            return response()->json([
                'success' => true,
                'message' => 'Sale created successfully!',
                'invoice_url' => route('sales.invoice', $sale->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function invoice($id)
    {
        $sale = Sale::with([
            'items.product',
            'items.color',
            'items.size',
            'customer'
        ])->where('id', $id)->firstOrFail();

        return view('backend.sale.invoice', compact('sale'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sale = Sale::with(['customer', 'items.product', 'items.color', 'items.size'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $sale
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sale = Sale::with(['items.product', 'items.color', 'items.size', 'customer'])->findOrFail($id);
        $customers = Customer::all();
        $products = Product::with(['colors', 'sizes'])->get();
        $users = User::all();

        return view('backend.sale.edit', compact('sale','customers','products','users'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'sale_date'        => 'required|date',
            'product_id.*'     => 'required|exists:products,id',
            'quantity.*'       => 'required|numeric|min:1',
            'selling_price.*'  => 'required|numeric|min:0',
            'discount_type'    => 'nullable|in:flat,percent',
            'delivery_type'    => 'nullable|in:inside,outside,free',
        ]);

        try {

            DB::transaction(function () use ($request, $id) {

                $sale = Sale::with('items')->findOrFail($id);

                /* ---------------- RESTORE OLD STOCK ---------------- */
                foreach ($sale->items as $item) {
                    $stock = ProductStock::where('product_id', $item->product_id)
                        ->where(function ($q) use ($item) {
                            $item->color_id ? $q->where('color_id', $item->color_id) : $q->whereNull('color_id');
                        })
                        ->where(function ($q) use ($item) {
                            $item->size_id ? $q->where('size_id', $item->size_id) : $q->whereNull('size_id');
                        })
                        ->first();

                    if ($stock) {
                        $stock->increment('quantity', $item->quantity);
                    }
                }

                /* ---------------- DELETE OLD ITEMS ---------------- */
                $sale->items()->delete();

                $totalAmount = 0;

                foreach ($request->product_id as $index => $pid) {

                    $colorId = $request->color_id[$index] ?? null;
                    $sizeId  = $request->size_id[$index] ?? null;
                    $qty     = $request->quantity[$index];
                    $price   = $request->selling_price[$index];

                    
                    $stock = ProductStock::where('product_id', $pid)
                        ->where(function ($q) use ($colorId) {
                            if ($colorId) {
                                $q->where('color_id', $colorId);
                            } else {
                                $q->whereNull('color_id');
                            }
                        })
                        ->where(function ($q) use ($sizeId) {
                            if ($sizeId) {
                                $q->where('size_id', $sizeId);
                            } else {
                                $q->whereNull('size_id');
                            }
                        })
                        ->first();
                    if (!$stock || $stock->quantity < $qty) {
                        $productName = Product::find($pid)?->name ?? 'Unknown';
                        throw new \Exception("Insufficient stock for {$productName}");
                    }
                    
                    $totalAmount += $qty * $price;

                    SaleItem::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $pid,
                        'color_id'      => $colorId,
                        'size_id'       => $sizeId,
                        'quantity'      => $qty,
                        'selling_price' => $price,
                        'total_price'   => $qty * $price,
                    ]);

                    $stock->decrement('quantity', $qty);
                }

                /* ---------------- CALCULATION ---------------- */
                $discount = $request->discount ?? 0;

                $discountAmount = $request->discount_type === 'percent'
                    ? ($totalAmount * $discount / 100)
                    : $discount;

                $deliveryCharge = match ($request->delivery_type) {
                    'inside'  => 60,
                    'outside' => 120,
                    default   => 0,
                };

                $grandTotal = ($totalAmount - $discountAmount) + $deliveryCharge;
                $paidAmount = $request->paid_amount ?? 0;
                $dueAmount  = $grandTotal - $paidAmount;

                /* ---------------- UPDATE SALE ---------------- */
                $sale->update([
                    'customer_id'     => $request->customer_id,
                    'user_id'         => $request->user_id,
                    'sale_date'       => $request->sale_date,
                    'total_amount'    => $totalAmount,
                    'discount'        => $discountAmount,
                    'discount_type'   => $request->discount_type,
                    'delivery_charge' => $deliveryCharge,
                    'grand_total'     => $grandTotal,
                    'paid_amount'     => $paidAmount,
                    'due_amount'      => $dueAmount,
                ]);
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Sale updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $sale = Sale::with('items', 'payment')->findOrFail($id);
                foreach ($sale->items as $item) {
                    ProductStock::where('product_id', $item->product_id)
                        ->when($item->color_id, fn($q) => $q->where('color_id', $item->color_id))
                        ->when($item->size_id, fn($q) => $q->where('size_id', $item->size_id))
                        ->increment('quantity', $item->quantity);
                    $detailsQuery = PurchaseDetail::where('product_id', $item->product_id);
                    if ($item->color_id) $detailsQuery->where('color_id', $item->color_id);
                    if ($item->size_id)  $detailsQuery->where('size_id', $item->size_id);

                    $detailsQuery->update(['is_sale' => 0]);
                }


                $sale->items()->delete();

                if ($sale->payment) {
                    $sale->payment->delete();
                }

                $sale->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Sale deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getProductDetails(Request $request)
    {
        $productId = $request->product_id;

        $product = Product::with(['colors.color', 'sizes'])
            ->find($productId);

        $colors = $product->colors->map(function ($item) {
            return [
                'id' => $item->color->id,
                'name' => $item->color->name,
            ];
        });

        $sizes = $product->sizes->map(function ($item) {
            return [
                'id' => $item->id,
                'size' => $item->size,
            ];
        });

        return response()->json([
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }

    public function orderSales(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('order_status', 'completed')
                ->latest();


            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('invoice_no', fn($row) => $row->order_number)

                ->addColumn('customer_name', fn($row) => $row->customer_name)

                ->addColumn(
                    'order_date',
                    fn($row) =>
                    $row->created_at->format('d M, Y')
                )

                ->addColumn(
                    'total_amount',
                    fn($row) =>
                    number_format($row->total, 2) . ' ৳'
                )

                ->addColumn('payment_status', function ($row) {
                    $class = match ($row->payment_status) {
                        'paid' => 'success',
                        'due' => 'danger',
                        default => 'secondary',
                    };
                    return '<span class="badge bg-' . $class . '">' . ucfirst($row->payment_status) . '</span>';
                })

                ->addColumn('status', function ($row) {
                    $statusColors = [
                        'pending'     => 'btn-warning',
                        'accepted'    => 'btn-info',
                        'on-the-way'  => 'btn-primary',
                        'return'      => 'btn-secondary',
                        'completed'   => 'btn-success',
                        'cancelled'   => 'btn-danger',
                    ];

                    $statusText = $row->order_status ?? 'pending';
                    $btnClass = $statusColors[$statusText] ?? 'btn-secondary';
                    $disabled = in_array($statusText, ['completed', 'cancelled']) ? 'disabled' : '';

                    return '
                        <div class="text-center">
                            <button class="btn btn-sm ' . $btnClass . ' btn-status"
                                data-id="' . $row->id . '" ' . $disabled . '>
                                ' . ucfirst(str_replace('-', ' ', $statusText)) . '
                            </button>
                        </div>
                    ';
                })
                ->addColumn('action', function ($row) {

                    $viewBtn = '<button class="btn btn-sm btn-primary btn-show" data-id="' . $row->id . '" title="View">
                                    <i class="fas fa-eye"></i>
                                </button>';

                    $editBtn = '<a href="' . route('orders.edit', $row->id) . '" class="btn btn-sm btn-warning" title="Edit Invoice">
                                    <i class="fas fa-edit"></i>
                                </a>';

                    $invoiceBtn = '<a href="' . route('orders.invoice', $row->id) . '" target="_blank" class="btn btn-sm btn-secondary" title="Invoice">
                                    <i class="fas fa-file-invoice"></i>
                                </a>';

                    $disabled = in_array($row->status, ['completed', 'cancelled']) ? 'disabled' : '';

                    $deleteBtn = '
                        <button type="button"
                            class="btn btn-sm btn-danger btn-delete"
                            data-id="' . $row->id . '"
                            ' . $disabled . '
                            title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>';

                    return '
                        <div class="text-center" >
                            <div class="btn-group btn-group-sm" role="group">
                                ' . $viewBtn . $editBtn . $invoiceBtn . $deleteBtn . '
                            </div>
                        </div>

                        <form id="delete-form-' . $row->id . '" action="' . route('orders.destroy', $row->id) . '" method="POST" style="display:none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    ';
                })

                ->rawColumns(['payment_status', 'status', 'action'])
                ->make(true);
        }
        return view('backend.order.sale');
    }

    public function orderReturn(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('status', 'return')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', fn ($row) => $row->order_number)
                ->addColumn('customer_name', fn ($row) => $row->full_name ?? $row->customer_name)
                ->addColumn('order_date', fn ($row) => $row->created_at->format('d M, Y'))
                ->addColumn('total_amount', fn ($row) => number_format($row->total, 2) . ' ৳')
                ->addColumn('return_charge', fn ($row) => number_format($row->return_charge ?? 0, 2) . ' ৳')
                ->addColumn('payment_status', function ($row) {
                    $class = match ($row->payment_status) {
                        'paid' => 'success',
                        'due', 'pending' => 'warning',
                        default => 'secondary',
                    };
                    return '<span class="badge bg-' . $class . '">' . ucfirst($row->payment_status) . '</span>';
                })
                ->addColumn('status', function ($row) {
                    $colors = [
                        'pending'    => '#f59e0b',
                        'accepted'   => '#3b82f6',
                        'on-the-way' => '#6366f1',
                        'return'     => '#6b7280', 
                        'completed'  => '#10b981',
                        'cancelled'  => '#ef4444',
                    ];

                    $status = $row->status ?? 'return';
                    $color = $colors[$status] ?? '#6b7280';

                    return '
                        <button class="btn-order-status btn-status-change"
                            data-id="'.$row->id.'"
                            style="
                                background: '.$color.'15;
                                color: '.$color.';
                                border: 1px solid '.$color.';
                                padding: 4px 10px;
                                border-radius: 6px;
                                font-size: 12px;
                                font-weight: 500;
                            ">
                            '.ucfirst(str_replace('-', ' ', $status)).'
                        </button>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show"
                                    data-id="'.$row->id.'" title="View Order">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    $editBtn = '<a href="'.route('orders.edit', $row->id).'"
                                    class="btn btn-icon btn-soft-primary" title="Edit Order">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    $invoiceBtn = '<a href="'.route('orders.invoice', $row->id).'"
                                        target="_blank" class="btn btn-icon btn-soft-success" title="Invoice">
                                        <i class="fa-regular fa-file-lines"></i>
                                    </a>';

                    $deleteForm = '
                        <form class="delete-form d-inline"
                            action="'.route('orders.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Order">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '<div class="d-flex align-items-center justify-content-center gap-2">
                                '.$showBtn.' '.$editBtn.' '.$invoiceBtn.' '.$deleteForm.'
                            </div>';
                })
                ->rawColumns(['payment_status','status','action'])
                ->make(true);
        }
        return view('backend.order.return');
    }

    public function orderCancelled(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::where('status', 'cancelled')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', fn ($row) => $row->order_number)
                ->addColumn('customer_name', fn ($row) => $row->full_name ?? $row->customer_name)
                ->addColumn('order_date', fn ($row) => $row->created_at->format('d M, Y'))
                ->addColumn('total_amount', fn ($row) => number_format($row->total, 2) . ' ৳')
                ->addColumn('payment_status', function ($row) {
                    $class = match ($row->payment_status) {
                        'paid' => 'success',
                        'due', 'pending' => 'warning',
                        default => 'secondary',
                    };
                    return '<span class="badge bg-' . $class . '">' . ucfirst($row->payment_status) . '</span>';
                })
                ->addColumn('status', function ($row) {
                    $colors = [
                        'pending'    => '#f59e0b',
                        'accepted'   => '#3b82f6',
                        'on-the-way' => '#6366f1',
                        'return'     => '#6b7280',
                        'completed'  => '#10b981',
                        'cancelled'  => '#ef4444', // Red for cancelled
                    ];

                    $status = $row->status ?? 'cancelled';
                    $color = $colors[$status] ?? '#ef4444';

                    return '
                        <button class="btn-order-status btn-status-change"
                            data-id="'.$row->id.'"
                            style="
                                background: '.$color.'15;
                                color: '.$color.';
                                border: 1px solid '.$color.';
                                padding: 4px 10px;
                                border-radius: 6px;
                                font-size: 12px;
                                font-weight: 500;
                            ">
                            '.ucfirst(str_replace('-', ' ', $status)).'
                        </button>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show"
                                    data-id="'.$row->id.'" title="View Order">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    $editBtn = '<a href="'.route('orders.edit', $row->id).'"
                                    class="btn btn-icon btn-soft-primary" title="Edit Order">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    $invoiceBtn = '<a href="'.route('orders.invoice', $row->id).'"
                                        target="_blank" class="btn btn-icon btn-soft-success" title="Invoice">
                                        <i class="fa-regular fa-file-lines"></i>
                                    </a>';

                    $deleteForm = '
                        <form class="delete-form d-inline"
                            action="'.route('orders.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Order">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '<div class="d-flex align-items-center justify-content-center gap-2">
                                '.$showBtn.' '.$editBtn.' '.$invoiceBtn.' '.$deleteForm.'
                            </div>';
                })
                ->rawColumns(['payment_status','status','action'])
                ->make(true);
        }
        return view('backend.order.cancelled');
    }
}
