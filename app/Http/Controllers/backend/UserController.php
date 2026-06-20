<?php

namespace App\Http\Controllers\backend;

use App\Models\User;
use App\Models\Location;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view user', only: ['index']),
            new Middleware('permission:create user', only: ['create']),
            new Middleware('permission:edit user', only: ['edit']),
            new Middleware('permission:destroy user', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $users = User::with('roles')->latest();

            return DataTables::of($users)
                ->addIndexColumn()

                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function ($role) {
                        return '<span class="badge bg-info text-dark">'.ucfirst($role->name).'</span>';
                    })->implode(' ');
                })

                ->addColumn('action', function($row) {
                    $user = auth()->user();
                    
                    $showBtn = '';
                    $editBtn = '';
                    $deleteForm = '';

                    // View Button
                    if ($user->can('user-show')) {
                        $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>';
                    }
                    
                    // Edit Button
                    if ($user->can('user-edit')) { 
                        $editBtn = '<a href="'.route('users.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit User">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>';
                    }
                    
                    // Delete Button
                    if ($user->can('user-delete')) { 
                        $deleteForm = '
                            <form class="delete-form d-inline" action="'.route('users.destroy', $row->id).'" method="POST">
                                '.csrf_field().method_field('DELETE').'
                                <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete User">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        ';
                    }

                    // Wrapping all buttons in a centered flex div
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })

                ->rawColumns(['roles','action'])
                ->make(true);
        }

        return view('backend.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        $divisions = Location::where('type', 'division')->orderBy('name', 'ASC')->get();
        
        return view('backend.user.create', compact('roles', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|max:255',
            'username'          => 'nullable|string|unique:users,username',
            'email'             => 'nullable|email|unique:users,email',
            'password'          => 'required|min:6|confirmed',
            'role'              => 'required',
            'employee_code'     => 'nullable|string|unique:users,employee_code',
            'designation'       => 'nullable|string|max:255',
            'gender'            => 'nullable|in:male,female,other',
            'phone'             => 'nullable|max:20',
            'alternate_phone'   => 'nullable|max:20',
            'emergency_contact' => 'nullable|max:20',
            'address'           => 'nullable|string',
            'division_id'       => 'nullable|exists:locations,id',
            'district_id'       => 'nullable|exists:locations,id',
            'upazila_id'        => 'nullable|exists:locations,id',
            'postal_code'       => 'nullable|string|max:50',
            'country'           => 'nullable|string|max:100',
            'nid_number'        => 'nullable|string|max:100',
            'passport_number'   => 'nullable|string|max:100',
            'joining_date'      => 'nullable|date',
            'birth_date'        => 'nullable|date',
            'salary'            => 'nullable|numeric|min:0',
            'status'            => 'required|boolean',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        
        $user->employee_code = $request->employee_code;
        $user->designation = $request->designation;
        $user->gender = $request->gender;
        
        $user->phone = $request->phone;
        $user->alternate_phone = $request->alternate_phone;
        $user->emergency_contact = $request->emergency_contact;
        
        $user->address = $request->address;
        $user->division_id = $request->division_id;
        $user->district_id = $request->district_id;
        $user->upazila_id  = $request->upazila_id;
        $user->postal_code = $request->postal_code;
        $user->country = $request->country ?? 'Bangladesh';
        
        $user->nid_number = $request->nid_number;
        $user->passport_number = $request->passport_number;
        
        $user->joining_date = $request->joining_date;
        $user->birth_date = $request->birth_date;
        $user->salary = $request->salary ?? 0;
        
        $user->status = $request->status;
        $user->created_by = auth()->id(); // কে তৈরি করেছে

        if ($request->hasFile('image')) {
            $image      = $request->file('image');
            $imageName  = time().'_'.$image->getClientOriginalName();
            $imagePath  = $image->move(public_path('uploads/users'), $imageName);
            $user->image = 'uploads/users/'.$imageName;
        }

        $user->save();

        if ($request->filled('role')) {
            $user->syncRoles($request->role);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with(['roles', 'division', 'district', 'upazila'])->findOrFail($id);

        return response()->json([
            'name'              => $user->name,
            'username'          => $user->username ?? '-',
            'email'             => $user->email ?? '-',
            'roles'             => $user->roles->pluck('name')->toArray(),
            'image'             => $user->image ? asset($user->image) : asset('backend/assets/img/user_image.png'),
            
            // Work Info
            'employee_code'     => $user->employee_code ?? '-',
            'designation'       => $user->designation ?? '-',
            'salary'            => $user->salary > 0 ? number_format($user->salary, 2) : '-',
            'joining_date'      => $user->joining_date ? date('d M Y', strtotime($user->joining_date)) : '-',
            
            // Personal Info
            'gender'            => ucfirst($user->gender) ?? '-',
            'birth_date'        => $user->birth_date ? date('d M Y', strtotime($user->birth_date)) : '-',
            'nid_number'        => $user->nid_number ?? '-',
            'passport_number'   => $user->passport_number ?? '-',
            
            // Contact & Address
            'phone'             => $user->phone ?? '-',
            'alternate_phone'   => $user->alternate_phone ?? '-',
            'emergency_contact' => $user->emergency_contact ?? '-',
            'address'           => $user->address ?? '-',
            'division'          => $user->division ? $user->division->name : '',
            'district'          => $user->district ? $user->district->name : '',
            'upazila'           => $user->upazila ? $user->upazila->name : '',
            'postal_code'       => $user->postal_code ?? '-',
            'country'           => $user->country ?? '-',
            
            'status'            => $user->status == 1 ? 'Active' : 'Inactive',
            'created_at'        => $user->created_at->format('d M Y, h:i A'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();
        $hasRoles = $user->roles->pluck('name')->toArray();
    
        $divisions = Location::where('type', 'division')->orderBy('name', 'ASC')->get();
        $districts = $user->division_id ? Location::where('parent_id', $user->division_id)->orderBy('name', 'ASC')->get() : [];
        $upazilas = $user->district_id ? Location::where('parent_id', $user->district_id)->orderBy('name', 'ASC')->get() : [];

        return view('backend.user.edit', compact('user', 'roles', 'hasRoles', 'divisions', 'districts', 'upazilas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'              => 'required|max:255',
            'username'          => "nullable|string|unique:users,username,$id",
            'email'             => "nullable|email|unique:users,email,$id",
            'password'          => 'nullable|min:6|confirmed',
            'role'              => 'required',
            'employee_code'     => "nullable|string|unique:users,employee_code,$id",
            'designation'       => 'nullable|string|max:255',
            'gender'            => 'nullable|in:male,female,other',
            'phone'             => 'nullable|max:20',
            'alternate_phone'   => 'nullable|max:20',
            'emergency_contact' => 'nullable|max:20',
            'address'           => 'nullable|string',
            'division_id'       => 'nullable|exists:locations,id',
            'district_id'       => 'nullable|exists:locations,id',
            'upazila_id'        => 'nullable|exists:locations,id',
            'postal_code'       => 'nullable|string|max:50',
            'country'           => 'nullable|string|max:100',
            'nid_number'        => 'nullable|string|max:100',
            'passport_number'   => 'nullable|string|max:100',
            'joining_date'      => 'nullable|date',
            'birth_date'        => 'nullable|date',
            'salary'            => 'nullable|numeric|min:0',
            'status'            => 'required|boolean',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        
        $user->employee_code = $request->employee_code;
        $user->designation = $request->designation;
        $user->gender = $request->gender;
        
        $user->phone = $request->phone;
        $user->alternate_phone = $request->alternate_phone;
        $user->emergency_contact = $request->emergency_contact;
        
        $user->address = $request->address;
        $user->division_id = $request->division_id;
        $user->district_id = $request->district_id;
        $user->upazila_id  = $request->upazila_id;
        $user->postal_code = $request->postal_code;
        $user->country = $request->country ?? 'Bangladesh';
        
        $user->nid_number = $request->nid_number;
        $user->passport_number = $request->passport_number;
        
        $user->joining_date = $request->joining_date;
        $user->birth_date = $request->birth_date;
        $user->salary = $request->salary ?? 0;
        
        $user->status = $request->status;
        $user->updated_by = auth()->id(); 

        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }
            $image      = $request->file('image');
            $imageName  = time().'_'.$image->getClientOriginalName();
            $imagePath  = $image->move(public_path('uploads/users'), $imageName);
            $user->image = 'uploads/users/'.$imageName;
        }

        $user->save();

        if ($request->role) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->image && file_exists(public_path($user->image))) {
            unlink(public_path($user->image));
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

}
