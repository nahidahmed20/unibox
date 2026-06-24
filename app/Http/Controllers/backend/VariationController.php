<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Variation;
use App\Models\VariationItem;
use App\Models\Size;

class VariationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $variations = Variation::with(['items.size'])->latest();
            return datatables()->of($variations)
                ->addIndexColumn()
                ->addColumn('values', function ($row) {
                    return $row->items
                        ->pluck('size.name')
                        ->map(function ($size) {
                            return '<span class="variation-tag">'.$size.'</span>';
                        })
                        ->implode(' ');
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
                                data-id="'.$row->id.'">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('variation-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline"
                                action="'.route('variations.destroy',$row->id).'"
                                method="POST">

                                '.csrf_field().'
                                '.method_field('DELETE').'

                                <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete">

                                    <i class="fa-regular fa-trash-can"></i>

                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex gap-2 justify-content-center">
                            '.$buttons.'
                        </div>
                    ';
                })

                ->rawColumns([
                    'values',
                    'status',
                    'action'
                ])

                ->make(true);
        }

        $sizes = Size::orderBy('name')->get();

        return view(
            'backend.variation.index',
            compact('sizes')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:variations,name',
            'sizes' => 'required|array|min:1',
            'status' => 'required'
        ]);

        $variation = Variation::create([
            'name' => $request->name,
            'status' => $request->status
        ]);

        foreach ($request->sizes as $sizeId) {

            VariationItem::create([
                'variation_id' => $variation->id,
                'size_id' => $sizeId
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Variation created successfully'
        ]);
    }

    public function edit($id)
    {
        $variation = Variation::with('items')->findOrFail($id);

        return response()->json([
            'id' => $variation->id,
            'name' => $variation->name,
            'status' => $variation->status,
            'sizes' => $variation->items->pluck('size_id')
        ]);
    }

    public function update(Request $request, $id)
    {
        $variation = Variation::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:variations,name,'.$id,
            'sizes' => 'required|array|min:1',
            'status' => 'required'
        ]);

        $variation->update([
            'name' => $request->name,
            'status' => $request->status
        ]);

        VariationItem::where(
            'variation_id',
            $variation->id
        )->delete();

        foreach ($request->sizes as $sizeId) {

            VariationItem::create([
                'variation_id' => $variation->id,
                'size_id' => $sizeId
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Variation updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $variation = Variation::findOrFail($id);

        VariationItem::where(
            'variation_id',
            $variation->id
        )->delete();

        $variation->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Variation deleted successfully'
        ]);
    }
}