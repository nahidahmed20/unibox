<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variation;
use App\Models\Size;

class VariationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $variations = Variation::latest();
            return datatables()->of($variations)
                ->addIndexColumn()
                ->addColumn('values', function ($row) {
                    $badges = '';
                    foreach ($row->values as $value) {
                        $badges .= '<span class="badge bg-info me-1">'.$value.'</span>';
                    }
                    return $badges;
                })

                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->addColumn('action', function ($row) {
                    $buttons = '';
                    if (auth()->user()->can('variation-edit')) {
                        $buttons .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="'.$row->id.'"
                                title="Edit Size">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('variation-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('variations.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Size">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            '.$buttons.'
                        </div>
                    ';
                })

                ->rawColumns(['values','status','action'])
                ->make(true);
        }

        return view('backend.variation.index');
    }

    public function create()
    {
        $sizes = Size::orderBy('name')->get();
        return view('backend.variation.create', compact('sizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:variations,name',
            'values' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        Variation::create([
            'name' => $request->name,
            'values' => array_map('trim', explode(',', $request->values)),
            'status' => $request->status ?? 1,
        ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Variation created successfully'
        ]);
    }

    public function edit($id)
    {
        $variation = Variation::findOrFail($id);

        return response()->json([
            'id' => $variation->id,
            'name' => $variation->name,
            'values' => $variation->values,
            'status' => $variation->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $variation = Variation::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:variations,name,'.$id,
            'values' => 'required|string',
            'status' => 'nullable|boolean',
        ]);


        $variation->update([
            'name'=>$request->name,
            'values'=>array_map('trim', explode(',', $request->values)),
            'status'=>$request->status ?? 1,
        ]);


        return response()->json([
            'status'=>'success',
            'message'=>'Variation updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $variation = Variation::findOrFail($id);
        $variation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Variation deleted successfully'
        ]);
    }
}
