<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        $division = Location::find($user->division_id);
        $district = Location::find($user->district_id);
        $upazila  = Location::find($user->upazila_id);

        return view('profile.edit', compact(
            'user',
            'division',
            'district',
            'upazila'
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = 'uploads/profile/';
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($path), $filename);
            $user->image = $path . $filename;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
