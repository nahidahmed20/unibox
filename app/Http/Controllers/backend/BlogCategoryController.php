<?php

namespace App\Http\Controllers\backend;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = BlogCategory::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if(auth()->user()->can('blog-category-edit')){
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Blog Category">

                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }
                    // Delete Button
                    if(auth()->user()->can('blog-category-delete')){
                        $buttons .= '
                        <form method="POST"
                            action="'.route('blog-categories.destroy',$row->id).'"
                            class="d-inline delete-form">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Blog Category">
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

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.blog_categories.index');
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
            'name' =>'required|string|max:255',
            'slug' =>'nullable|string|max:255',
        ]);

        BlogCategory::create($request->all());

        return response()->json([
            'success'  => true,
            'message'  => 'Blog category created successfully.!',
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
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);
        return response()->json($category); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'slug' =>'nullable|string|max:255',
        ]);

        $category = BlogCategory::find($id);
        $category->update($request->all());

        return response()->json([
            'success'  => true,
            'message'  => 'Blog category updated successfully.!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::find($id);
        $category->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Blog category deleted successfully.!',
        ]);
    }
}
