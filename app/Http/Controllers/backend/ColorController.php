<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        // AJAX for DataTable
        if ($request->ajax()) {

            $colors = Color::query()->latest();

            return datatables()->of($colors)
                ->addIndexColumn()

                ->addColumn('preview', function ($row) {
                    return $row->code
                        ? '<span style="width:25px;height:25px;display:inline-block;
                            background:'.$row->code.';
                            border-radius:50%;border:1px solid #ddd;"></span>'
                        : '-';
                })

                ->addColumn('action', function ($row) {
                    $buttons = '';
                    // Edit Button
                    if (auth()->user()->can('color-edit')) {
                        $buttons .= '
                            <button type="button" class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="' . $row->id . '" title="Edit Color">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    // Delete Button
                    if (auth()->user()->can('color-delete')) {

                        $buttons .= '
                            <form class="delete-form d-inline" action="' . route('colors.destroy', $row->id) . '" method="POST">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button
                                    type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Color">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '
                        <div class="d-flex align-items-center justify-content-center gap-2">' . $buttons . ' </div>
                    ';
                })

                ->rawColumns(['preview','action'])
                ->make(true);
        }

        return view('backend.color.index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('backend.color.create');
    }

    /**
     * Store color
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
        ]);

        Color::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('colors.index')->with('success', 'Color created successfully');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $color = Color::findOrFail($id);

        return response()->json($color);
    }

    /**
     * Update color
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:20',
        ]);

        $color = Color::findOrFail($id);

        $color->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return redirect()->route('colors.index')
            ->with('success', 'Color updated successfully');
    }

    /**
     * Delete color
     */
    public function destroy(Color $color)
    {
        $color->delete();

        return response()->json([
                'status' => 'success',
                'message' => 'Color deleted successfully',
            ]);
    }
}
