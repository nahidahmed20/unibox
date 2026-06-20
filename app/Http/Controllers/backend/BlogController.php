<?php

namespace App\Http\Controllers\backend;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Blog::with(['category', 'user'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('category', fn($row) => $row->category?->name ?? 'N/A')
                ->addColumn('author', fn($row) => $row->user?->name ?? 'N/A')

                ->addColumn('status', function ($row) {
                    return $row->status === 'active'
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->addColumn('image', function ($row) {
                    return $row->image
                        ? '<img src="'.asset($row->image).'" width="60">'
                        : 'N/A';
                })

                 ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if(auth()->user()->can('blog-edit')){
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Blog">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    // Delete Button
                    if(auth()->user()->can('blog-delete')){
                        $buttons .= '
                        <form method="POST"
                            action="'.route('blogs.destroy',$row->id).'"
                            class="d-inline delete-form">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Blog ">
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

                ->rawColumns(['status', 'image', 'action'])
                ->make(true);
        }

        return view('backend.blogs.index');
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
            'title' => 'required|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
            'date' => 'nullable|date',
        ]);

        $blog               = new Blog();
        $blog->title        = $request->title;
        $blog->slug         = $request->slug;
        $blog->blog_category_id = $request->blog_category_id; 
        $blog->description  = $request->description;
        $blog->user_id      = Auth::id();
        $blog->status       = $request->status;
        $blog->date         = $request->date ?? now(); 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName(); 
            $image->move(public_path('uploads/blogs'), $filename);
            $blog->image = 'uploads/blogs/'.$filename;
        }

        $blog->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Blog created successfully.!',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $blog = Blog::findOrFail($id);
        return response()->json($blog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:active,inactive',
            'date' => 'nullable|date',
        ]);

        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->blog_category_id = $request->blog_category_id; 
        $blog->description = $request->description;
        $blog->status = $request->status;
        $blog->date = $request->date ?? now(); 

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName(); 
            $image->move(public_path('uploads/blogs'), $filename);
            $blog->image = 'uploads/blogs/'.$filename;
        }

        $blog->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Blog updated successfully.!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $blog = Blog::find($id);
        $blog->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Blog deleted successfully.!',
        ]);
    }

    
}
