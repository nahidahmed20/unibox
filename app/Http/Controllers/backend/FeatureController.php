<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Feature::orderBy('sort_order', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('media', function ($row) {
                    if ($row->image) {
                        return '<img src="'.asset($row->image).'" style="height:40px; border-radius:4px;">';
                    } elseif ($row->icon) {
                        return '<i class="' . $row->icon . ' fa-2x text-primary"></i>';
                    }
                    return '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '"><i class="fa-regular fa-pen-to-square"></i></button>
                        <form class="delete-form d-inline" action="' . route('features.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                        </form>';
                })
                ->rawColumns(['media', 'status', 'action'])
                ->make(true);
        }
        return view('backend.feature.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048'
        ]);

        $feature = new Feature();
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->icon = $request->icon;
        $feature->sort_order = $request->sort_order ?? 0;
        $feature->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/features'), $filename);
            $feature->image = 'uploads/features/' . $filename;
        }
        
        $feature->save();
        return response()->json(['status' => 'success', 'message' => 'Feature added successfully']);
    }

    public function edit($id)
    {
        return response()->json(Feature::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $feature = Feature::findOrFail($id);
        $feature->title = $request->title;
        $feature->description = $request->description;
        $feature->icon = $request->icon;
        $feature->sort_order = $request->sort_order ?? 0;
        $feature->status = $request->status ?? 1;

        if ($request->hasFile('image')) {
            if ($feature->image && file_exists(public_path($feature->image))) {
                unlink(public_path($feature->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/features'), $filename);
            $feature->image = 'uploads/features/' . $filename;
        }
        
        $feature->save();
        return response()->json(['status' => 'success', 'message' => 'Feature updated successfully']);
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        if ($feature->image && file_exists(public_path($feature->image))) {
            unlink(public_path($feature->image));
        }
        $feature->delete();
        return response()->json(['status' => 'success', 'message' => 'Feature deleted successfully']);
    }
}