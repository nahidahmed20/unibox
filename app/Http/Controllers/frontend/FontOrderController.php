<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Location;
use App\Models\Color;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FontOrderController extends Controller
{
    public function placeOrder()
    {
        if (!auth('customer')->check()) {
            session(['redirect_after_login' => url()->current()]);
            return redirect()->route('user.login')->with('warning', 'Please login first to continue checkout.');
        }

        $cart = session('cart', []);
        foreach ($cart as $key => $item) {
            if (!empty($item['color_id'])) {
                $color = Color::find($item['color_id']);
                $cart[$key]['color'] = $color?->name;
            }
        }

        $divisions = Location::where('type', 'division')->get();
        $shippingCost = session('shipping_cost', 0);
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $total = $subtotal + $shippingCost;

        return view(
            'frontend.checkout.place-order',
            compact('cart', 'subtotal', 'shippingCost', 'total', 'divisions')
        );
    }

    public function getDistricts($division_id)
    {
        $districts = Location::where('parent_id', $division_id)
                        ->where('type', 'district')
                        ->get();

        return response()->json($districts);
    }

    public function getUpazilas($district_id)
    {
        $upazilas = Location::where('parent_id', $district_id)
                        ->where('type', 'upazila')
                        ->get();

        return response()->json($upazilas);
    }

    public function sendOtp(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name'       => 'nullable|string|max:100',
            'address'       => 'required|string|max:255',
            'division_id'   => 'nullable',
            'district_id'   => 'nullable',
            'upazila_id'    => 'nullable',
            'phone'          => 'required|regex:/^(01)[0-9]{9}$/',
            'payment_method' => 'required|in:cod,bank',
            'message'        => 'nullable|string|max:1000',
        ]);

        // $otp = rand(100000, 999999);
        $otp = '123456';
        session([
            'checkout_data' => $validated,
            'checkout_otp' => $otp,
            'checkout_otp_expire' => now()->addMinutes(2),
        ]);

        $phone = $validated['phone'];

        $message = "Your Unibox OTP is: $otp";
        $sms = sendSms($phone, $message);

        if (!$sms['success']) {
            return back()->with('error', 'SMS sending failed');
        }

        return redirect()->route('checkout.otp.form')->with('success', 'OTP sent successfully');
    }

    public function otpForm()
    {
        if (!session()->has('checkout_data')) {
            return redirect()->route('place.order');
        }
        return view('frontend.checkout.otp');
    }

    public function resendOtp()
    {
        if (!session()->has('checkout_data')) {
            return response()->json([
                'status' => false,
                'message' => 'Checkout session expired.'
            ]);
        }
        // $otp = rand(100000, 999999);
        $otp = '123456';
        $expireTime = now()->addMinutes(2);
        session([
            'checkout_otp' => $otp,
            'checkout_otp_expire' => $expireTime,
        ]);
        $phone = session('checkout_data.phone');
        $message = "Your Unibox OTP is: $otp";
        sendSms($phone, $message);
        return response()->json([
            'status'      => true,
            'message'     => 'New OTP sent successfully.',
            'expire_time' => $expireTime->timestamp * 1000
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        if (!session()->has('checkout_data')) {
            return redirect()->route('cart.index')->with('error', 'Session expired');
        }

        if ($request->otp != session('checkout_otp')) {
            return back()->withErrors([
                'otp' => 'Invalid OTP',
            ]);
        }

        $data = session('checkout_data');
        
        $user = User::where('phone', $data['phone'])->first();
        $plainPassword = null;

        if (!$user) {
            $plainPassword = Str::random(8);
            $user = User::create([
                'name'     => $data['name'] ?? '',
                'phone'    => $data['phone'],
                'address'  => $data['address'],
                'division_id' => $data['division_id'] ?? null,
                'distric_id'  => $data['district_id'] ?? null,
                'upazila_id'  => $data['upazila_id'] ?? null,
                'is_admin' => 0,
                'password' => bcrypt($plainPassword),
            ]);
                $message = "Your Unibox account has been created. Your password is: $plainPassword";
                sendSms($data['phone'], $message);
        }

        $cart = session('cart', []);
        
        $shipping = session('shipping_cost', 0);
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $total = $subtotal + $shipping;
        DB::beginTransaction();

        try {

            $order = Order::create([
                'order_number' => 'ORD-' . rand(10000, 99999),
                'user_id'       => $user->id,
                'full_name'     => $data['name'],
                'phone'         => $data['phone'],
                'address'       => $data['address'],
                'city'          => $data['district_id'],
                'province'      => $data['division_id'],
                'country'       => 'Bangladesh',
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_method'] == 'cod'? 'unpaid': 'pending',
                'subtotal'      => $subtotal,
                'shipping'      => $shipping,
                'total'         => $total,
                'type'          => 'customer',
                'order_note'    => $data['message'] ?? null,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'] ?? null,
                    'product_name' => $item['name'],
                    'color' => $item['color'] ?? null,
                    'size' => $item['size'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            session()->forget([
                'checkout_data',
                'checkout_otp',
                'cart',
                'shipping_cost',
            ]);

            return redirect()->route('order.success', $order->order_number)->with('success', 'Order placed successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function orderSuccess($order_number)
    {
        $order = Order::with('items')->where('order_number', $order_number)->firstOrFail();
        
        return view('frontend.checkout.success', compact('order'));
    }

    public function faq()
    {
        return view('frontend.home.faq');
    }
}
