<?php

namespace App\Http\Controllers\backend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipping;
use Yajra\DataTables\Facades\DataTables;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Shipping::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // ======================
                // ZONE FORMAT
                // ======================
                ->editColumn('zone', function ($row) {
                    return match ($row->zone) {
                        'inside_dhaka'  => 'Inside Dhaka',
                        'near_dhaka'    => 'Near Dhaka',
                        'outside_dhaka' => 'Outside Dhaka',
                        default         => $row->zone,
                    };
                })

                // ======================
                // ACTION BUTTONS
                // ======================
                ->addColumn('action', function ($row) {

                    return '
                        <button class="btn btn-sm btn-primary editBtn" data-id="'.$row->id.'">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if (auth()->user()->can('shipping-edit')) {
                        $buttons .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary editBtn"
                                data-id="' . $row->id . '"
                                title="Edit Shipping">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('shipping-delete')) {

                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('shippings.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Shipping">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . ' </div>
                    ';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.shipping.index');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'zone'          => 'required|in:inside_dhaka,near_dhaka,outside_dhaka',
            'address'       => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        Shipping::create([
            'zone'          => $request->zone,
            'address'       => $request->address,
            'shipping_cost' => $request->shipping_cost ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipping created successfully'
        ]);
    }

    /**
     * EDIT
     */
    public function edit(string $id)
    {
        $shipping = Shipping::findOrFail($id);

        return response()->json($shipping);
    }

    /**
     * UPDATE
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'zone'          => 'required|in:inside_dhaka,near_dhaka,outside_dhaka',
            'address'       => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        $shipping = Shipping::findOrFail($id);

        $shipping->update([
            'zone'          => $request->zone,
            'address'       => $request->address,
            'shipping_cost' => $request->shipping_cost ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipping updated successfully'
        ]);
    }

    /**
     * DELETE
     */
    public function destroy(string $id)
    {
        $shipping = Shipping::findOrFail($id);
        $shipping->delete();

        return response()->json([
            'success' => true,
            'message' => 'Shipping deleted successfully'
        ]);
    }
}
