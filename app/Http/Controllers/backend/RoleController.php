<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RoleController extends Controller implements HasMiddleware
{
    
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view role', only: ['index']),
            new Middleware('permission:create role', only: ['create']),
            new Middleware('permission:edit role', only: ['edit']),
            new Middleware('permission:destroy role', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $roles = Role::with('permissions')->latest();

            return datatables()->of($roles)
                ->addIndexColumn()

                ->addColumn('permissions', function ($row) {
                    $badges = '';

                    foreach ($row->permissions as $permission) {
                        $badges .= '<span class="badge bg-info text-dark me-1">'
                            . $permission->name .
                        '</span>';
                    }

                    return $badges ?: '-';
                })

                ->addColumn('action', function ($row) {

                    $buttons = '';

                    if (auth()->user()->can('role-edit')) {
                        $buttons .= '
                            <a href="'.route('roles.edit', $row->id).'"
                                class="btn btn-icon btn-soft-warning">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        ';
                    }

                    if (auth()->user()->can('role-delete')) {
                        $buttons .= '
                            <form class="delete-form d-inline"
                                action="'.route('roles.destroy', $row->id).'"
                                method="POST">
                                '.csrf_field().method_field('DELETE').'

                                <button type="button"
                                    class="btn btn-icon btn-soft-danger btn-delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    return '<div class="d-flex justify-content-center gap-2">'.$buttons.'</div>';
                })

                ->rawColumns(['permissions','action'])
                ->make(true);
        }

        return view('backend.role.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('backend.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
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
        $role = Role::findOrFail($id);
        $permissions = Permission::all(); // Get all permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Role's assigned permissions IDs

        return view('backend.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);

        // Update role name
        $role->name = $request->name;
        $role->save();

        // Sync permissions
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name == 'Admin') {
            return redirect()->route('roles.index')->with('error', 'Admin role cannot be deleted!');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
