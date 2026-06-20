<?php

namespace App\Http\Controllers\backend;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SupplierController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view supplier', only: ['index']),
            new Middleware('permission:create supplier', only: ['create']),
            new Middleware('permission:edit supplier', only: ['edit']),
            new Middleware('permission:destroy supplier', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $suppliers = Supplier::latest();
            return DataTables::of($suppliers)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';

                })

                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Show Button
                    $buttons .= '
                        <button type="button"
                                class="btn btn-icon btn-soft-info show-btn"
                                data-id="'.$row->id.'"
                                title="View Supplier">

                            <i class="fa-regular fa-eye"></i>
                        </button>
                    ';
                    // Edit Button
                    if(auth()->user()->can('supplier-edit')){
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Supplier">

                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    // Delete Button
                    if(auth()->user()->can('supplier-delete')){
                        $buttons .= '
                        <form method="POST"
                            action="'.route('suppliers.destroy',$row->id).'"
                            class="d-inline delete-form">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Supplier">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                        ';
                    }
                    return $buttons
                        ? '<div class="d-flex align-items-center justify-content-center gap-2">
                                '.$buttons.'
                        </div>'
                        : '—';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('backend.supplier.index');
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
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|unique:suppliers,email,' . $request->supplier_id,
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
            'city'      => 'nullable|string|max:255',
            'state'     => 'nullable|string|max:255',
            'zip_code'  => 'nullable|string|max:20',
            'website'   => 'nullable|url|max:255',
            'status'    => 'required|boolean',
        ]);

        $supplier = Supplier::updateOrCreate(
            ['id' => $request->supplier_id],
            $validated
        );

        // AJAX Request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $request->supplier_id
                    ? 'Supplier updated successfully.'
                    : 'Supplier created successfully.',
                'supplier' => $supplier
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                $request->supplier_id
                    ? 'Supplier updated successfully.'
                    : 'Supplier created successfully.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::find($id);

        return response()->json([
            'success' => true,
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier = Supplier::find($id);
        return response()->json([
            'success' => true,
            'supplier' => $supplier
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'nullable|email|unique:suppliers,email,' . $supplier->id . ',id',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
            'city'      => 'nullable|string|max:255',
            'state'     => 'nullable|string|max:255',
            'zip_code'  => 'nullable|string|max:20',
            'website'   => 'nullable|url|max:255',
            'status'    => 'required|boolean',
        ]);

        // Update supplier
        $supplier->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully.',
            'supplier' => $supplier
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found.'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully.'
        ]);
    }

}
