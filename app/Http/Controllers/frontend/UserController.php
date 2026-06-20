<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show Customer Login Form
     */
    public function userLogin()
    {
        // Prevent admin/staff from accessing customer login page
        if (Auth::check() && Auth::user()->type !== 'customer') {
            return redirect('/')->with('error', 'Please logout from your administrative account first.');
        }

        return view('frontend.user.login');
    }

    /**
     * Show Customer Registration Form
     */
    public function userRegister()
    {
        // Prevent admin/staff from accessing customer register page
        if (Auth::check() && Auth::user()->type !== 'customer') {
            return redirect('/')->with('error', 'Please logout from your administrative account first.');
        }

        return view('frontend.user.register');
    }

    /**
     * Handle Customer Registration
     */
    public function storeRegister(Request $request)
    {
        if (Auth::check() && Auth::user()->type !== 'customer') {
            return back()->with('error', 'An administrative account is already logged in. Please logout first.');
        }

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'nullable|email|unique:users,email',
            'phone'    => [
                'required',
                'regex:/^(?:\+88|88)?01[3-9]\d{8}$/',
                'unique:users,phone'
            ],
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'type'     => 'customer', // Strictly assign as customer
            'status'   => 1, // Default Active
        ]);

        Auth::guard('customer')->login($user);
        $request->session()->regenerate();

        $redirect = session()->pull('redirect_after_login', route('customer.dashboard'));

        return redirect($redirect)->with('success', 'Registration successful!');
    }

    /**
     * Handle Customer Login
     */
    public function storeLogin(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        if (Auth::check() && Auth::user()->type !== 'customer') {
            return back()->with('error', 'Please logout from your admin account first.');
        }

        $request->validate([
            'name'     => 'required', // Can be email or phone
            'password' => 'required',
        ]);

        $login    = trim($request->name);
        $password = $request->password;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Search ONLY for customers
        $user = User::where($field, $login)->where('type', 'customer')->first();

        if (!$user) {
            return back()->withInput($request->only('name', 'remember'))->with('error', 'This account for admin not a customer.');
        }

        if ($user->status == 0) {
            return back()->with('error', 'Your account is inactive. Please contact support.');
        }

        if (!Hash::check($password, $user->password)) {
            return back()->withInput($request->only('name', 'remember'))->with('error', 'Incorrect password.');
        }

        // Login user with 'customer' guard
        $remember = $request->has('remember'); // Make sure your blade input name is 'remember'
        Auth::guard('customer')->login($user, $remember);

        $request->session()->regenerate();

        $redirect = session()->pull('redirect_after_login', route('customer.dashboard'));

        return redirect($redirect)->with('success', 'Login successful!');
    }
    /**
     * Customer Dashboard
     */
    public function dashboard()
    {
        $userId = auth('customer')->id();

        if (!$userId) {
            return redirect()->route('user.login');
        }

        $orders = Order::where('user_id', $userId)->latest()->paginate(5);
        $totalOrders = Order::where('user_id', $userId)->count();
        $processingOrders = Order::where('user_id', $userId)->whereIn('status', ['pending', 'processing'])->count();
        $completedOrders = Order::where('user_id', $userId)->where('status', 'delivered')->count();

        return view('frontend.customer.dashboard', compact(
            'orders',
            'totalOrders',
            'processingOrders',
            'completedOrders'
        ));
    }

    /**
     * Customer Orders List
     */
    public function orders()
    {
        $userId = auth('customer')->id();

        if (!$userId) {
            return redirect()->route('user.login');
        }
        
        $orders = Order::where('user_id', $userId)->latest()->paginate(10);

        return view('frontend.customer.orders', compact('orders'));
    }

    /**
     * Single Order Details
     */
    public function orderShow($orderNumber)
    {
        $userId = auth('customer')->id();

        if (!$userId) {
            return redirect()->route('user.login');
        }

        $order = Order::where('user_id', $userId)->where('order_number', $orderNumber)->firstOrFail();

        return view('frontend.customer.order_show', compact('order'));
    }

    /**
     * Customer Profile Form
     */
    public function profile()
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('user.login');
        }

        $divisions = Location::where('type', 'division')->get();
        return view('frontend.customer.profile', compact('customer', 'divisions'));
    }

    /**
     * Update Customer Profile
     */
    public function profileUpdate(Request $request)
    {
        $user = auth('customer')->user();

        if (!$user) {
            return redirect()->route('user.login');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'nullable|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|unique:users,phone,' . $user->id,
            'address'     => 'nullable|string',
            'office_address'=> 'nullable|string',
            'division_id' => 'nullable|exists:locations,id',
            'district_id' => 'nullable|exists:locations,id',
            'upazila_id'  => 'nullable|exists:locations,id',
            'country'     => 'nullable|string|max:100',
            'birth_date'  => 'nullable|date',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'address','office_address', 'division_id', 
            'district_id', 'upazila_id', 'country', 'birth_date'
        ]);

        // Image Upload Logic
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/users'), $fileName);
            $data['image'] = 'uploads/users/' . $fileName;
        }

        $user->update($data);
        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change Password Form
     */
    public function customerChangePassword()
    {
        $customer = auth('customer')->user();

        if (!$customer) {
            return redirect()->route('user.login');
        }

        return view('frontend.customer.change_password', compact('customer'));
    }

    /**
     * Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $user = auth('customer')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Logout Customer
     */
    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
