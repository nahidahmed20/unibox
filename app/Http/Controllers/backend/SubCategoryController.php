<?php

namespace App\Http\Controllers\backend;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SubCategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view sub-category', only: ['index']),
            new Middleware('permission:create sub-category', only: ['create']),
            new Middleware('permission:edit sub-category', only: ['edit']),
            new Middleware('permission:destroy sub-category', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubCategory::latest()->with('category')->select('sub_categories.*'); 

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', function($row){
                    return $row->category ? $row->category->name : '—';
                })
                ->addColumn('image', function($row){
                    return $row->image ? '<img src="'.asset($row->image).'" width="50" height="50">' : 'No Image';
                })
                ->addColumn('action', function ($row) {
                    $editBtn = '
                        <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="'.$row->id.'"
                                title="Edit Sub Category">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                    ';

                    $deleteBtn = '
                        <form method="POST"
                            action="'.route('sub-categories.destroy', $row->id).'"
                            class="d-inline delete-form">

                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete"
                                    title="Delete Sub Category">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            '.$editBtn.'
                            '.$deleteBtn.'
                        </div>
                    ';
                })

                ->rawColumns(['image','action'])
                ->make(true);
        }

        $categories = Category::all();
        return view('backend.sub_category.index', compact('categories'));
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
        
        $data = $request->validate([
            'category_id' => 'required',
            'name'=>'required|string|max:255',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif|max:4096',
        ]);

        $data['slug'] = Str::slug($request->name);

        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/sub_categories'), $filename);
            $data['image'] = 'uploads/sub_categories/'.$filename;
        }

        $sub_category = SubCategory::create($data);

        return response()->json([
            'message' => 'Sub-Category created successfully',
            'sub_category' => [
                'id' => $sub_category->id,
                'name' => $sub_category->name,
                'category_id' => $sub_category->category_id, // add this
                'category_name' => $sub_category->category ? $sub_category->category->name : '—',
                'image' => $sub_category->image ? asset($sub_category->image) : null,
            ]
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
        $subCategory = SubCategory::findOrFail($id);
        if ($subCategory->image) {
            $subCategory->image = asset($subCategory->image);
        }

        return response()->json($subCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $data = $request->validate([
            'category_id' => 'required',
            'name'=>'required|string|max:255',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,gif|max:4096',
        ]);

        $data['slug'] = Str::slug($request->name);

        if($request->hasFile('image')){
            if($subCategory->image && file_exists(public_path($subCategory->image))){
                unlink(public_path($subCategory->image));
            }
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/sub_categories'), $filename);
            $data['image'] = 'uploads/sub_categories/'.$filename;
        }

        $subCategory->update($data);

        // **Return JSON response with new data**
        return response()->json([
            'message' => 'Sub-Category updated successfully',
            'sub_category' => [
                'id' => $subCategory->id,
                'name' => $subCategory->name,
                'image' => $subCategory->image ? asset($subCategory->image) : null,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);

        if ($subCategory->image && file_exists(public_path($subCategory->image))) {
            unlink(public_path($subCategory->image));
        }

        $subCategory->delete();

        return response()->json([
            'message' => 'Sub-Category deleted successfully'
        ]);
    }
}
        