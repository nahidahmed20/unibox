<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
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
        $user = User::findOrFail($id);

        $divisions = Location::where('type', 'division')
            ->orderBy('name')
            ->get();

        $districts = Location::where('type', 'district')
            ->orderBy('name')
            ->get();

        $upazilas = Location::where('type', 'upazila')
            ->orderBy('name')
            ->get();

        return view(
            'backend.user_profile.edit',
            compact(
                'user',
                'divisions',
                'districts',
                'upazilas'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'              => 'required|string|max:255',
            'username'          => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email'             => 'nullable|email|unique:users,email,' . $user->id,

            'phone'             => 'nullable|string|max:20',
            'alternate_phone'   => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:20',

            'gender'            => 'nullable|in:male,female,other',
            'birth_date'        => 'nullable|date',

            'address'           => 'nullable|string',
            'office_address'    => 'nullable|string',

            'postal_code'       => 'nullable|string|max:20',
            'country'           => 'nullable|string|max:100',

            'division_id'       => 'nullable|exists:divisions,id',
            'district_id'       => 'nullable|exists:districts,id',
            'upazila_id'        => 'nullable|exists:upazilas,id',

            'image'             => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user->name               = $request->name;
        $user->username           = $request->username;
        $user->email              = $request->email;

        $user->phone              = $request->phone;
        $user->alternate_phone    = $request->alternate_phone;
        $user->emergency_contact  = $request->emergency_contact;

        $user->gender             = $request->gender;
        $user->birth_date         = $request->birth_date;

        $user->address            = $request->address;
        $user->office_address     = $request->office_address;

        $user->division_id        = $request->division_id;
        $user->district_id        = $request->district_id;
        $user->upazila_id         = $request->upazila_id;

        $user->postal_code        = $request->postal_code;
        $user->country            = $request->country;

        if ($request->hasFile('image')) {

            if ($user->image && file_exists(public_path($user->image))) {
                @unlink(public_path($user->image));
            }

            $image = $request->file('image');

            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/users'), $imageName);

            $user->image = 'uploads/users/' . $imageName;
        }

        $user->updated_by = auth()->id();

        $user->save();

        return redirect()
            ->route('user-profiles.edit', $user->id)
            ->with('success', 'Profile updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
