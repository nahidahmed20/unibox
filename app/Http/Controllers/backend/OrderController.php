<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::whereNotIn('status', ['return', 'completed', 'cancelled'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('invoice_no', fn ($row) => $row->order_number)
                ->addColumn('customer_name', fn ($row) => $row->full_name)
                ->addColumn('order_date', fn ($row) => $row->created_at->format('d M, Y'))
                ->addColumn('total_amount', fn ($row) => number_format($row->total, 2) . ' ৳')
                ->addColumn('payment_status', function ($row) {
                    $class = match (strtolower($row->payment_status)) {
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

                    $status = $row->status ?? 'pending';
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
                        <form class="delete-form d-inline" action="'.route('orders.destroy', $row->id).'" method="POST">
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

        return view('backend.order.index');
    }

    public function statusModal($id)
    {
        $order = Order::findOrFail($id);
        
        $statuses = [
            'pending'     => 'Pending',
            'accepted'    => 'Accepted',
            'on-the-way'  => 'On The Way',
            'return'      => 'Return',
            'completed'   => 'Completed',
            'cancelled'   => 'Cancelled',
        ];

        return view('backend.order.partials.status_modal', compact('order', 'statuses'));
    }

    public function statusUpdate(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $order = Order::with('items')->findOrFail($id);
            $oldStatus = $order->status; 
            $newStatus = $request->status;

            $order->status = $newStatus;

            if ($request->has('return_charge')) {
                $order->return_charge = $request->return_charge;
            }

            if ($newStatus === 'completed') {
                $order->payment_status = 'paid';
            }

            if (in_array($newStatus, ['return', 'cancelled']) && !in_array($oldStatus, ['return', 'cancelled'])) {
                foreach ($order->items as $item) {
                    $this->updateStockQuantity($item, 'increment');
                }
            }
            elseif (!in_array($newStatus, ['return', 'cancelled']) && in_array($oldStatus, ['return', 'cancelled'])) {
                foreach ($order->items as $item) {
                    $this->updateStockQuantity($item, 'decrement');
                }
            }

            $order->save();
            DB::commit(); 
            
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'errors' => ['Failed to update status: ' . $e->getMessage()] 
            ], 500);
        }
    }

    private function updateStockQuantity($item, $action = 'increment')
    {
        $mainProduct = Product::find($item->product_id);
        if (!$mainProduct) return;

        $qty = (int) $item->quantity;

        if ($action === 'increment') {
            $mainProduct->increment('stock', $qty);
        } else {
            $mainProduct->decrement('stock', $qty);
        }

        if ($mainProduct->product_type === 'multiple') {
            
            $colorId = is_numeric($item->color_id) ? $item->color_id : ($item->color ? Color::where('name', $item->color)->value('id') : null);
            $sizeId = is_numeric($item->size_id) ? $item->size_id : ($item->size ? Size::where('name', $item->size)->value('id') : null);

            $query = ProductVariant::where('product_id', $item->product_id)
                ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                ->when($sizeId, fn($q) => $q->where('size_id', $sizeId));

            if ($action === 'increment') {
                $query->increment('stock', $qty);
            } else {
                $query->decrement('stock', $qty);
            }
        }
    }

    public function create()
    {
        // 
    }

    public function store(Request $request)
    {
        // 
    }

    public function getPendingCount() 
    {
        $count = Order::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }

    public function invoice($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('backend.order.partials.invoice', compact('order'));
    }

    public function show($id)
    { 
        $order = Order::with(['items.product', 'items.color', 'items.size'])->findOrFail($id);
       
        return view('backend.order.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        $couriers = Courier::where('is_active', true)->get(); 
        
        return view('backend.order.edit', compact('order', 'couriers'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        DB::beginTransaction();

        try {
            $order->update([
                'full_name'       => $request->customer_name,
                'phone'           => $request->customer_phone,
                'address'         => $request->customer_address,
                'shipping'        => $request->shipping,
                'payment_status'  => $request->payment_status,
                'status'          => $request->status,
                'courier_id'      => $request->courier_id,
                'tracking_number' => $request->tracking_number,
                'return_charge'   => $request->return_charge ?? 0,
            ]);

            $subtotal = 0;
            if ($request->has('items')) {
                foreach ($request->items as $itemId => $itemData) {
                    $orderItem = $order->items->find($itemId);
                    if ($orderItem) {
                        $newQty   = (int) $itemData['quantity'];
                        $oldQty   = $orderItem->quantity;
                        $price = (float) $itemData['price'];
                        $lineTotal = $newQty * $price;
                        
                        if ($newQty != $oldQty && !in_array($order->status, ['return', 'cancelled'])) {
                            $difference = $newQty - $oldQty;
                            $pseudoItem = clone $orderItem;
                            
                            if ($difference > 0) {
                                $pseudoItem->quantity = abs($difference);
                                $this->updateStockQuantity($pseudoItem, 'decrement');
                            } else {
                                $pseudoItem->quantity = abs($difference);
                                $this->updateStockQuantity($pseudoItem, 'increment');
                            }
                        }

                        $orderItem->update([
                            'quantity' => $newQty,
                            'price'    => $price,
                            'total'    => $lineTotal,
                        ]);
                        $subtotal += $lineTotal;
                    }
                }
            }
            
            $order->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + (float) $request->shipping,
            ]);

            DB::commit();
            return redirect()
                ->route('orders.index')
                ->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();
            if (!in_array($order->status, ['return', 'cancelled'])) {
                foreach ($order->items as $item) {
                    $this->updateStockQuantity($item, 'increment');
                }
            }

            $order->items()->delete(); 
            $order->delete(); 
            DB::commit();
            return response()->json([
                'message' => 'Order deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete order. ' . $e->getMessage()
            ], 500);
        }
    }
}