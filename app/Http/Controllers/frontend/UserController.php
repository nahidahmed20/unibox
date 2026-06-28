<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // For generating random passwords
use Laravel\Socialite\Facades\Socialite; // For Social Login

class UserController extends Controller
{
    public function userLogin()
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard'); 
        }

        if (Auth::check() && Auth::user()->type !== 'customer') {
            return redirect('/')->with('error', 'Please logout from your administrative account first.');
        }
        
        if (!session()->has('otp_login')) {
            $previousUrl = url()->previous();

            if (str_contains($previousUrl, 'cart') || str_contains($previousUrl, 'checkout')) {
                session(['redirect_after_login' => route('cart.checkout')]);
            } else {
                session()->forget('redirect_after_login'); 
            }
        }

        return response()
            ->view('frontend.user.login')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function sendOtp(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            return redirect()->route('customer.dashboard');
        }

        $request->validate([
            'name' => [
                'required',
                function ($attribute, $value, $fail) {

                    $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                    
                    $isPhone = preg_match('/^01[3-9][0-9]{8}$/', $value);

                    if (!$isEmail && !$isPhone) {
                        $fail('দয়া করে একটি সঠিক ফোন নাম্বার (১১ ডিজিট) বা ইমেইল প্রদান করুন।');
                    }
                },
            ],
        ], [
            'name.required' => 'ফোন নাম্বার বা ইমেইল দেওয়া বাধ্যতামূলক।',
        ]);

        $login = trim($request->name);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $otp = rand(1000, 9999); 
        
        session()->put('otp_login', $login);
        session()->put('otp_code', '1234'); 
        session()->put('otp_field', $field);
        session()->put('otp_expires_at', now()->addMinutes(2)->timestamp);

        \Log::info("OTP for {$login} is: {$otp}");

        return back();
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:4',
        ]);

        if (now()->timestamp > session('otp_expires_at')) {
            return back()->with('error', 'OTP has expired! Please request a new one.')->withInput();
        }

        $sessionOtp = session()->get('otp_code');
        $login      = session()->get('otp_login');
        $field      = session()->get('otp_field');

        if ($request->otp != $sessionOtp) {
            return back()->with('error', 'Invalid OTP! Please try again.')->withInput();
        }

        if ($request->otp != $sessionOtp) {
            return back()->with('error', 'Invalid OTP! Please try again.')->withInput();
        }

        $user = User::where($field, $login)->where('type', 'customer')->first();

        if (!$user) {
            $user = User::create([
                'name'     => 'New Customer',
                $field     => $login,
                'password' => Hash::make(Str::random(12)),
                'type'     => 'customer',
                'status'   => 1,
            ]);
        } else {
            if ($user->status == 0) {
                return redirect()->route('user.login')->with('error', 'Your account is inactive.');
            }
        }

        // $message = "Your Unibox OTP is: $otp";
        // $sms = sendSms($phone, $message); 

        // Auth::guard('customer')->login($user, true);
        // session()->forget(['otp_login', 'otp_code', 'otp_field', 'otp_expires_at']);

        // $redirect = session()->pull('redirect_after_login', route('cart.checkout'));
        
        // return redirect($redirect);

        Auth::guard('customer')->login($user, true);

        $redirect = session()->pull('redirect_after_login', route('customer.dashboard')); 
        
        session()->forget(['otp_login', 'otp_code', 'otp_field', 'otp_expires_at']);
        
        return redirect($redirect);
    }

    public function cancelOtp()
    {
        session()->forget(['otp_login', 'otp_code', 'otp_field', 'otp_expires_at']);
        return redirect()->route('user.login');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            return $this->processSocialLogin($socialUser);
        } catch (\Exception $e) {
            return redirect()->route('user.login')->with('error', 'Google login failed or cancelled.');
        }
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $socialUser = Socialite::driver('facebook')->user();
            return $this->processSocialLogin($socialUser);
        } catch (\Exception $e) {
            return redirect()->route('user.login')->with('error', 'Facebook login failed or cancelled.');
        }
    }

    private function processSocialLogin($socialUser)
    {
        if (!$socialUser->getEmail()) {
            return redirect()->route('user.login')->with('error', 'Email is required from your social account.');
        }

        $user = User::where('email', $socialUser->getEmail())->where('type', 'customer')->first();

        if (!$user) {
            // Google ba Facebook theke profile image URL niye asha
            $avatarUrl = $socialUser->getAvatar(); 

            $user = User::create([
                'name'     => $socialUser->getName() ?? 'New Customer',
                'email'    => $socialUser->getEmail(),
                'image'    => $avatarUrl, // Eikhane google image URL save hobe
                'password' => Hash::make(Str::random(16)), 
                'type'     => 'customer',
                'status'   => 1,
            ]);
        } else {
            // Purono user er jodi image database e na thake, tobe update kore neya
            if (!$user->image && $socialUser->getAvatar()) {
                $user->update([
                    'image' => $socialUser->getAvatar()
                ]);
            }

            if ($user->status == 0) {
                return redirect()->route('user.login')->with('error', 'Your account is inactive. Please contact support.');
            }
        }

        Auth::guard('customer')->login($user, true);

        $redirect = session()->pull('redirect_after_login', route('customer.dashboard'));
        return redirect($redirect)->with('success', 'Successfully logged in!');
    }

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

    public function orders()
    {
        $userId = auth('customer')->id();
        if (!$userId) {
            return redirect()->route('user.login');
        }
        $orders = Order::where('user_id', $userId)->latest()->paginate(10);
        return view('frontend.customer.orders', compact('orders'));
    }

    public function orderShow($orderNumber)
    {
        $userId = auth('customer')->id();
        if (!$userId) {
            return redirect()->route('user.login');
        }
        $order = Order::where('user_id', $userId)->where('order_number', $orderNumber)->firstOrFail();
        return view('frontend.customer.order_show', compact('order'));
    }

    public function profile()
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return redirect()->route('user.login');
        }
        $divisions = Location::where('type', 'division')->get();
        return view('frontend.customer.profile', compact('customer', 'divisions'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth('customer')->user();
        if (!$user) {
            return redirect()->route('user.login');
        }
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string|unique:users,phone,' . $user->id,
            'address'       => 'nullable|string',
            'office_address'=> 'nullable|string',
            'division_id'   => 'nullable|exists:locations,id',
            'district_id'   => 'nullable|exists:locations,id',
            'upazila_id'    => 'nullable|exists:locations,id',
            'country'       => 'nullable|string|max:100',
            'birth_date'    => 'nullable|date',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'address','office_address', 'division_id', 
            'district_id', 'upazila_id', 'country', 'birth_date'
        ]);

        if ($request->hasFile('image')) {
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

    public function customerChangePassword()
    {
        $customer = auth('customer')->user();
        if (!$customer) {
            return redirect()->route('user.login');
        }
        return view('frontend.customer.change_password', compact('customer'));
    }

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

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
}