<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $couriers = Courier::query()->latest();

            return datatables()->of($couriers)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->is_active 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    
                    // Edit Button
                    if (auth()->user()->can('courier-edit')) { 
                        $buttons .= '
                            <button type="button" class="btn btn-icon btn-soft-primary btn-edit" 
                                data-id="' . $row->id . '" title="Edit Courier">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('courier-delete')) { 
                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('couriers.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Courier">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . ' </div>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.courier.index');
    }

    /**
     * Store courier via AJAX
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:couriers,name',
            'contact_number' => 'nullable|string|max:20',
        ]);

        Courier::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Courier added successfully'
        ]);
    }

    /**
     * Get courier data for Edit Modal
     */
    public function edit($id)
    {
        $courier = Courier::findOrFail($id);
        return response()->json($courier);
    }

    /**
     * Update courier via AJAX
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:couriers,name,' . $id,
            'contact_number' => 'nullable|string|max:20',
        ]);

        $courier = Courier::findOrFail($id);

        $courier->update([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Courier updated successfully'
        ]);
    }

    /**
     * Delete courier via AJAX
     */
    public function destroy(Courier $courier)
    {
        $courier->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Courier deleted successfully',
        ]);
    }
}
