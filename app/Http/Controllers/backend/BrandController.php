<?php

namespace App\Http\Controllers\backend;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class BrandController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view brand', only: ['index']),
            new Middleware('permission:create brand', only: ['create']),
            new Middleware('permission:edit brand', only: ['edit']),
            new Middleware('permission:destroy brand', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {

                    $buttons = '';
                    if (auth()->user()->can('brand-edit')) {
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Brand">

                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('brand-delete')) {
                        $buttons .= '
                            <form method="POST"
                                action="'.route('brands.destroy', $row->id).'"
                                class="d-inline delete-form">
                                '.csrf_field().method_field('DELETE').'
                                <button type="button"
                                        class="btn btn-icon btn-soft-danger btn-delete"
                                        title="Delete Brand">

                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return $buttons
                        ? '<div class="d-flex align-items-center justify-content-center gap-2">'.$buttons.'</div>'
                        : '—';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.brand.index');
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
            'name'=>'required|string|max:255',
        ]);

        $data['slug'] = Str::slug($request->name);
        $brand = Brand::create($data);
        $msg = 'Brand created successfully';

        return response()->json([
            'message' => $msg,
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
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
        $brand = Brand::findOrFail($id);
        if ($brand->image) {
            $brand->image = asset($brand->image);
        }

        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = $request->validate([
            'name'=>'required|string|max:255',
        ]);

        $data['slug'] = Str::slug($request->name);
        $brand->update($data);
        $msg = 'Brand updated successfully';

        return response()->json([
            'message' => $msg,
            'brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
            ]
        ]);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        $msg = 'Brand deleted successfully';

        return response()->json([
            'message' => $msg,
        ]);
    }
}
