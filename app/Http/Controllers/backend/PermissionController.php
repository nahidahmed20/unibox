<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permission', only: ['index']),
            new Middleware('permission:create permission', only: ['create']),
            new Middleware('permission:edit permission', only: ['edit']),
            new Middleware('permission:destroy permission', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $permissions = Permission::query()->latest();

            return datatables()->of($permissions)
                ->addIndexColumn()

                ->addColumn('created_at', function ($row) {
                    return $row->created_at
                        ? $row->created_at->format('d M, Y')
                        : '—';
                })

                ->addColumn('action', function ($row) {

                    $buttons = '';

                    if (auth()->user()->can('permission-edit')) {
                        $buttons .= '
                            <button type="button"
                                class="btn btn-icon btn-soft-primary btn-edit"
                                data-id="' . $row->id . '">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('permission-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline"
                                action="' . route('permissions.destroy', $row->id) . '"
                                method="POST">
                                ' . csrf_field() . method_field('DELETE') . '

                                <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '<div class="d-flex gap-2 justify-content-center">' . $buttons . '</div>';
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        return response()->json(['message' => 'Permission created successfully']);
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
        return Permission::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->name
        ]);

        return response()->json(['message' => 'Permission updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return redirect()
                ->route('permissions.index')
                ->with('error', 'Permission not found');
        }

        if (!auth()->user()->can('permission-delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to delete this permission',
            ]);
        }

        try {
            $permission->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Color deleted successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while deleting',
            ]);
        }
    }
}
