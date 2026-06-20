<?php

namespace App\Http\Controllers\backend;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

                    // View Button
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show"
                                    data-id="'.$row->id.'" title="View Order">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    // Edit Button
                    $editBtn = '<a href="'.route('orders.edit', $row->id).'"
                                    class="btn btn-icon btn-soft-primary"
                                    title="Edit Order">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    // Invoice Button
                    $invoiceBtn = '<a href="'.route('orders.invoice', $row->id).'"
                                        target="_blank"
                                        class="btn btn-icon btn-soft-success"
                                        title="Invoice">
                                        <i class="fa-regular fa-file-lines"></i>
                                    </a>';

                    // Delete Button
                    $deleteForm = '
                        <form class="delete-form d-inline"
                            action="'.route('orders.destroy', $row->id).'"
                            method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Order">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            '.$showBtn.'
                            '.$editBtn.'
                            '.$invoiceBtn.'
                            '.$deleteForm.'
                        </div>
                    ';
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
        $order = Order::findOrFail($id);

        $order->status = $request->status;

        if ($request->status === 'completed') {
            $order->payment_status = 'paid';
        }

        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully'
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function getPendingCount() {
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
        $order = Order::with('items.product')->findOrFail($id);
     
        return view('backend.order.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('backend.order.edit', compact('order'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);

        DB::beginTransaction();

        try {

            // =========================
            // 1. UPDATE ORDER INFO
            // =========================
            $order->update([
                'full_name'      => $request->customer_name,
                'phone'          => $request->customer_phone,
                'address'        => $request->customer_address,
                'shipping'       => $request->shipping,
                'payment_status' => $request->payment_status,
                'status'         => $request->status,
            ]);

            // =========================
            // 2. UPDATE ITEMS
            // =========================
            $subtotal = 0;

            if ($request->has('items')) {

                foreach ($request->items as $itemId => $itemData) {

                    $orderItem = $order->items->find($itemId);

                    if ($orderItem) {

                        $qty   = (int) $itemData['quantity'];
                        $price = (float) $itemData['price'];

                        $lineTotal = $qty * $price;

                        $orderItem->update([
                            'quantity' => $qty,
                            'price'    => $price,
                            'total'    => $lineTotal,
                        ]);

                        $subtotal += $lineTotal;
                    }
                }
            }

            // =========================
            // 3. FINAL TOTAL UPDATE
            // =========================
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
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found.'
            ], 404);
        }

        try {
            $order->items()->delete(); 
            $order->delete(); 

            return response()->json([
                'message' => 'Order deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete order. ' . $e->getMessage()
            ], 500);
        }
    }
}
