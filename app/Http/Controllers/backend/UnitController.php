<?php

namespace App\Http\Controllers\backend;

use App\Models\Unit;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class UnitController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view unit', only: ['index']),
            new Middleware('permission:create unit', only: ['create']),
            new Middleware('permission:edit unit', only: ['edit']),
            new Middleware('permission:destroy unit', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Unit::latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $buttons = '';
                    if (auth()->user()->can('unit-edit')) {
                        $buttons .= '
                            <button type="button"
                                    class="btn btn-icon btn-soft-primary btn-edit"
                                    data-id="'.$row->id.'"
                                    title="Edit Unit">

                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('unit-delete')) {
                        $buttons .= '
                            <form method="POST"
                                action="'.route('units.destroy', $row->id).'"
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

        return view('backend.unit.index');
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
            'short_name'=>'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($request->name);
        $unit = Unit::create($data);
        $msg = 'Unit created successfully';

        return response()->json([
            'message' => $msg,
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->name,
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
        $unit = Unit::findOrFail($id);

        return response()->json($unit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);
        $data = $request->validate([
            'name'=>'required|string|max:255',
            'short_name'=>'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($request->name);
        $unit->update($data);
        $msg = 'Unit updated successfully';

        return response()->json([
            'message' => $msg,
            'unit' => [
                'id' => $unit->id,
                'name' => $unit->name,
            ]
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();
        $msg = 'Unit deleted successfully';

        return response()->json([
            'message' => $msg,
        ]);
    }
}
