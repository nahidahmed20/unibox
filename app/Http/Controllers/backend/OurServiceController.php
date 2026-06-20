<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\OurService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OurServiceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $services = OurService::query()->latest();

            return datatables()->of($services)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image 
                        ? '<img src="' . asset($row->image) . '" style="width:50px; height:50px; object-fit:cover; border-radius:6px; border:1px solid #ddd;">' 
                        : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    
                    // Edit Button for Modal
                    if (auth()->user()->can('service-edit')) {
                        $buttons .= '
                            <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '" title="Edit Service">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('service-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('our-services.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Service">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '<div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . '</div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.our_service.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'icon'              => 'nullable|string|max:100',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'            => 'required|boolean',
        ]);

        $service = new OurService();
        $service->title             = $request->title;
        $service->slug              = Str::slug($request->title);
        $service->short_description = $request->short_description;
        $service->description       = $request->description;
        $service->icon              = $request->icon;
        $service->status            = $request->status;
        $service->created_by        = auth()->id();

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/services'), $imageName);
            $service->image = 'uploads/services/' . $imageName;
        }

        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service created successfully'
        ]);
    }

    public function edit($id)
    {
        $service = OurService::findOrFail($id);
        return response()->json($service);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'icon'              => 'nullable|string|max:100',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'            => 'required|boolean',
        ]);

        $service = OurService::findOrFail($id);
        $service->title             = $request->title;
        $service->slug              = Str::slug($request->title);
        $service->short_description = $request->short_description;
        $service->description       = $request->description;
        $service->icon              = $request->icon;
        $service->status            = $request->status;
        $service->updated_by        = auth()->id();

        if ($request->hasFile('image')) {
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }

            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/services'), $imageName);
            $service->image = 'uploads/services/' . $imageName;
        }

        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $service = OurService::findOrFail($id);
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }
        $service->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Service deleted successfully',
        ]);
    }
}