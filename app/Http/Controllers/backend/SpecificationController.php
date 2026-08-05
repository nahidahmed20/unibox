<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Specification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Specification::latest()->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    }
                    return '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (auth()->user()->can('specification-edit')) {
                        $btn .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="'.$row->id.'"
                                title="Edit Specification">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    
                            
                    if (auth()->user()->can('specification-delete')) {

                        $btn .= '
                            <form class="delete-form d-inline" action="' . route('specifications.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Specification">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }
                            
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.specifications.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specifications,name',
            'status' => 'required|boolean',
        ]);

        Specification::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Specification added successfully!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $specification = Specification::findOrFail($id);
        return response()->json($specification);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specifications,name,' . $id,
            'status' => 'required|boolean',
        ]);

        $specification = Specification::findOrFail($id);
        $specification->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Specification updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $specification = Specification::findOrFail($id);
        $specification->delete();

        return response()->json(['message' => 'Specification deleted successfully!']);
    }
}