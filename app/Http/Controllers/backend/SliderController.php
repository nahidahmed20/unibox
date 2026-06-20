<?php

namespace App\Http\Controllers\backend;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slider::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('image', function ($row) {
                    $url = $row->image ? asset($row->image) : asset('default/no-image.png');
                    return '<img src="' . $url . '" width="80" class="img-thumbnail">';
                })
                ->editColumn('mobile_image', function ($row) {
                    $url = $row->mobile_image ? asset( $row->mobile_image) : asset('default/no-image.png');
                    return '<img src="' . $url . '" width="80" class="img-thumbnail">';
                })
                ->editColumn('status', function ($row) {
                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if(auth()->user()->can('slider-edit')){
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Slider">

                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    // Delete Button
                    if(auth()->user()->can('slider-delete')){
                        $buttons .= '
                        <form method="POST"
                            action="'.route('sliders.destroy',$row->id).'"
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
                ->rawColumns(['image', 'mobile_image', 'status', 'action'])
                ->make(true);
        }

        return view('backend.slider.index');
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
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'status'            => 'required|in:0,1',
            'link'              => 'nullable',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $data = $request->only(['title', 'link', 'short_description', 'status']);

        $destinationPath = public_path('uploads/sliders');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            $data['image'] = 'uploads/sliders/' . $filename;
        }

        if ($request->hasFile('mobile_image')) {
            $mobile_image = $request->file('mobile_image');
            $mobile_filename = time() . '_' . uniqid() . '.' . $mobile_image->getClientOriginalExtension();
            $mobile_image->move($destinationPath, $mobile_filename);
            $data['mobile_image'] = 'uploads/sliders/' . $mobile_filename;
        }

        Slider::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Slider added successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $slider = Slider::findOrFail($id);
        return response()->json($slider);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return response()->json($slider);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'status'            => 'required|in:0,1',
            'link'              => 'nullable|string|max:255',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'mobile_image'      => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $slider = Slider::findOrFail($id);
        $data = $request->only(['title', 'link', 'short_description', 'status']);

        $destinationPath = public_path('uploads/sliders');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        // Update Main Image
        if ($request->hasFile('image')) {
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($destinationPath, $filename);
            $data['image'] = 'uploads/sliders/' . $filename;
        } else {
            $data['image'] = $slider->image;
        }

        // Update Mobile Image
        if ($request->hasFile('mobile_image')) {
            if ($slider->mobile_image && file_exists(public_path($slider->mobile_image))) {
                unlink(public_path($slider->mobile_image));
            }
            $mobile_image = $request->file('mobile_image');
            $mobile_filename = time() . '_' . uniqid() . '.' . $mobile_image->getClientOriginalExtension();
            $mobile_image->move($destinationPath, $mobile_filename);
            $data['mobile_image'] = 'uploads/sliders/' . $mobile_filename;
        } else {
            $data['mobile_image'] = $slider->mobile_image;
        }

        $slider->update($data);

        return response()->json(['message' => 'Slider updated successfully!']);
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        if ($slider->mobile_image && file_exists(public_path($slider->mobile_image))) {
            unlink(public_path($slider->mobile_image));
        }

        $slider->delete();

        return response()->json(['message' => 'Slider deleted successfully!']);
    }
}
