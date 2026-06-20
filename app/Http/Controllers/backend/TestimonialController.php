<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $testimonials = Testimonial::query()->latest();

            return datatables()->of($testimonials)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image 
                        ? '<img src="' . asset($row->image) . '" style="width:50px; height:50px; object-fit:cover; border-radius:50%; border:1px solid #ddd;">' 
                        : '-';
                })
                ->addColumn('rating', function ($row) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $color = $i <= $row->rating ? '#ffc107' : '#ddd';
                        $stars .= '<i class="fa-solid fa-star" style="color: ' . $color . ';"></i>';
                    }
                    return $stars;
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 
                        ? '<span class="badge bg-success">Active</span>' 
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    
                    // Edit Button
                    $buttons .= '
                        <button type="button" class="btn btn-icon btn-soft-primary btn-edit" data-id="' . $row->id . '" title="Edit Testimonial">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    ';

                    // Delete Button
                    $buttons .= '
                        <form class="delete-form d-inline" action="' . route('testimonials.destroy', $row->id) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Testimonial">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '<div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . '</div>';
                })
                ->rawColumns(['image', 'rating', 'status', 'action'])
                ->make(true);
        }

        return view('backend.testimonial.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'title'       => 'nullable|string|max:255',
            'rating'      => 'required|integer|min:1|max:5',
            'review'      => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp',
            'status'      => 'required|boolean',
        ]);

        $testimonial = new Testimonial();
        $testimonial->name        = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->title       = $request->title;
        $testimonial->rating      = $request->rating;
        $testimonial->review      = $request->review;
        $testimonial->status      = $request->status;

        if ($request->hasFile('image')) {
            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/testimonials'), $imageName);
            $testimonial->image = 'uploads/testimonials/' . $imageName;
        }

        $testimonial->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Testimonial created successfully'
        ]);
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return response()->json($testimonial);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'title'       => 'nullable|string|max:255',
            'rating'      => 'required|integer|min:1|max:5',
            'review'      => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status'      => 'required|boolean',
        ]);

        $testimonial = Testimonial::findOrFail($id);
        $testimonial->name        = $request->name;
        $testimonial->designation = $request->designation;
        $testimonial->title       = $request->title;
        $testimonial->rating      = $request->rating;
        $testimonial->review      = $request->review;
        $testimonial->status      = $request->status;

        if ($request->hasFile('image')) {
            if ($testimonial->image && file_exists(public_path($testimonial->image))) {
                unlink(public_path($testimonial->image));
            }

            $image     = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/testimonials'), $imageName);
            $testimonial->image = 'uploads/testimonials/' . $imageName;
        }

        $testimonial->save();

        return response()->json([
            'status'  => 'success',
            'message' => 'Testimonial updated successfully'
        ]);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        if ($testimonial->image && file_exists(public_path($testimonial->image))) {
            unlink(public_path($testimonial->image));
        }
        $testimonial->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Testimonial deleted successfully',
        ]);
    }
}