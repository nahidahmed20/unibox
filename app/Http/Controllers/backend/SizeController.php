<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Size;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $sizes = Size::query()->latest();

            return datatables()->of($sizes)
                ->addIndexColumn()

                ->addColumn('status', function ($row) {

                    return $row->status
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->addColumn('action', function ($row) {

                    $buttons = '';

                    if (auth()->user()->can('size-edit')) {

                        $buttons .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="'.$row->id.'"
                                title="Edit Size">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('size-delete')) {

                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('sizes.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Size">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            '.$buttons.'
                        </div>
                    ';
                })

                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('backend.size.index');
    }

    public function create()
    {
        return view('backend.size.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name',
        ]);

        Size::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Size created successfully'
        ]);
    }

    public function show(string $id)
    {

    }

    public function edit(Size $size)
    {
        return response()->json($size);
    }

    public function update(Request $request, Size $size)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name,' . $size->id,
        ]);

        $size->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Size updated successfully'
        ]);
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Size deleted successfully'
        ]);
    }
}
