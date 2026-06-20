<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Material::orderBy('sort_order', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="'.asset($row->image).'" style="height:50px; border-radius:6px;">';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '"><i class="fa-regular fa-pen-to-square"></i></button>
                        <form class="delete-form d-inline" action="' . route('materials.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                        </form>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view('backend.material.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|max:2048'
        ]);

        $material = new Material();
        $material->name = $request->name;
        $material->description = $request->description;
        $material->sort_order = $request->sort_order ?? 0;
        $material->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/materials'), $filename);
            $material->image = 'uploads/materials/' . $filename;
        }
        
        $material->save();
        return response()->json(['status' => 'success', 'message' => 'Material added']);
    }

    public function edit($id)
    {
        return response()->json(Material::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);
        $material->name = $request->name;
        $material->description = $request->description;
        $material->sort_order = $request->sort_order ?? 0;
        $material->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            if ($material->image && file_exists(public_path($material->image))) {
                unlink(public_path($material->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/materials'), $filename);
            $material->image = 'uploads/materials/' . $filename;
        }
        
        $material->save();
        return response()->json(['status' => 'success', 'message' => 'Material updated']);
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        if ($material->image && file_exists(public_path($material->image))) {
            unlink(public_path($material->image));
        }
        $material->delete();
        return response()->json(['status' => 'success', 'message' => 'Material deleted']);
    }
}