<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Attribute::with('options')->latest()->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('options', function ($row) {
                    if ($row->type == 'text') {
                        return '<span class="badge bg-info text-dark">Custom Text Input</span>';
                    }
                    $badges = '';
                    foreach ($row->options as $opt) {
                        $extra = $opt->extra_price > 0 ? ' (+৳' . $opt->extra_price . ')' : '';
                        $badges .= '<span class="badge bg-light text-dark border me-1 mb-1">' . $opt->value . '<small class="text-success ms-1">' . $extra . '</small></span>';
                    }
                    return $badges ?: '<span class="text-muted small">No options</span>';
                })
                ->addColumn('type', function ($row) {
                    return $row->type == 'select' ? 'Dropdown (Select)' : 'Text Box (Manual)';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    
                    if (auth()->user()->can('attribute-edit')) {
                        $btn .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit me-1"
                                data-id="'.$row->id.'"
                                title="Edit Attribute">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    
                    if (auth()->user()->can('attribute-delete')) {
                        $btn .= '
                            <form class="delete-form d-inline" action="' . route('attributes.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Attribute">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }
                            
                    return $btn;
                })
                ->rawColumns(['options', 'status', 'action'])
                ->make(true);
        }

        return view('backend.attributes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'type' => 'required|in:select,text',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request) {
            $attribute = Attribute::create([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
            ]);

            if ($request->type == 'select' && !empty($request->options)) {
                foreach ($request->options as $optData) {
                    if (!empty($optData['value'])) {
                        AttributeOption::create([
                            'attribute_id' => $attribute->id,
                            'value' => $optData['value'],
                            'extra_price' => $optData['extra_price'] ?? 0
                        ]);
                    }
                }
            }
        });

        return response()->json(['message' => 'Attribute created successfully!']);
    }

    public function edit($id)
    {
        $attribute = Attribute::with('options')->findOrFail($id);
        return response()->json($attribute);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $id,
            'type' => 'required|in:select,text',
            'status' => 'required|boolean',
        ]);

        DB::transaction(function () use ($request, $id) {
            $attribute = Attribute::findOrFail($id);
            $attribute->update([
                'name' => $request->name,
                'type' => $request->type,
                'status' => $request->status,
            ]);

            AttributeOption::where('attribute_id', $attribute->id)->delete();

            if ($request->type == 'select' && !empty($request->options)) {
                foreach ($request->options as $optData) {
                    if (!empty($optData['value'])) {
                        AttributeOption::create([
                            'attribute_id' => $attribute->id,
                            'value' => $optData['value'],
                            'extra_price' => $optData['extra_price'] ?? 0
                        ]);
                    }
                }
            }
        });

        return response()->json(['message' => 'Attribute updated successfully!']);
    }

    public function destroy($id)
    {
        Attribute::findOrFail($id)->delete(); 
        return response()->json(['message' => 'Attribute deleted successfully!']);
    }
}