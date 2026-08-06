<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Location;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class FontOrderController extends Controller
{
    public function cartCheckout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty!');
        }

        foreach ($cart as $key => $item) {
            $product = Product::find($item['product_id']);
            
            if (!$product) {
                unset($cart[$key]); 
                continue;
            }

            if (!empty($item['color_id'])) {
                $cart[$key]['color'] = Color::find($item['color_id'])?->name;
            }
            if (!empty($item['size_id'])) {
                $cart[$key]['size'] = Size::find($item['size_id'])?->name;
            }
        }

        session(['cart' => $cart]);

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

    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session('cart', []);
        $shipping = session('shipping_cost', 0);
        
        if (count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Cart is empty');
        }

        $userId = auth('customer')->id() ?? auth()->id();

        if (!$userId) {
            $user = User::where('phone', $request->phone)->first();
            $userId = $user ? $user->id : null; 
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
        $total = $subtotal + $shipping;
        
        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number'   => 'ORD-' . rand(10000, 99999),
                'user_id'        => $userId, 
                'full_name'      => $request->name,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->district_id ?? null,
                'province'       => $request->division_id ?? null,
                'country'        => 'Bangladesh',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method == 'cod' ? 'unpaid' : 'pending',
                'subtotal'       => $subtotal,
                'shipping'       => $shipping,
                'total'          => $total,
                'type'           => 'customer',
                'order_note'     => $request->message ?? null,
                'status'         => 'pending',
            ]);

            foreach ($cart as $item) {
                $colorId = $item['color_id'] ?? null;
                if (!$colorId && !empty($item['color'])) {
                    $colorId = Color::where('name', $item['color'])->value('id');
                }

                $sizeId = $item['size_id'] ?? null;
                if (!$sizeId && !empty($item['size'])) {
                    $sizeId = Size::where('name', $item['size'])->value('id'); 
                }

                $itemAttributes = !empty($item['attributes']) ? json_encode($item['attributes']) : null;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'] ?? null,
                    'product_name' => $item['name'],
                    'color'        => $item['color'] ?? null,
                    'color_id'     => $colorId, 
                    'size_id'      => $sizeId,  
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'total'        => $item['price'] * $item['quantity'],
                    'attributes'   => $itemAttributes, 
                ]);

                $mainProduct = Product::find($item['product_id']);
                
                if ($mainProduct) {
                    $mainProduct->decrement('stock', $item['quantity']);

                    if ($mainProduct->product_type === 'multiple') {
                        ProductVariant::where('product_id', $item['product_id'])
                            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
                            ->decrement('stock', $item['quantity']);
                    }
                }
            }

            DB::commit();
            
            session()->forget([
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
        $order = Order::with(['items.product', 'items.color', 'items.size'])
                      ->where('order_number', $order_number)
                      ->firstOrFail();
                      
        return view('frontend.checkout.success', compact('order'));
    }

    public function faq()
    {
        return view('frontend.home.faq');
    }
}
