<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function index()
    {
        return view('frontend.checkout.index');
    }
    public function singleView($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([ 'category','subCategory','brand', 'unit','images', 'colors','sizes','stocks',])->firstOrFail();

        $stockData = $product->stocks->map(function ($s) {
            return [
                'color_id' => (int) $s->color_id,
                'size_id'  => (int) $s->size_id,
                'qty'      => (int) $s->quantity,
            ];
        })->values();


        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->latest()
            ->take(8)
            ->get();

        return view(
            'frontend.checkout.single_show',
            compact('product', 'stockData', 'relatedProducts')
        );
    }

    public function checkout()
    {
        $cart = session('cart', []);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $zone = session('shipping_zone', 'inside_dhaka');
        $shippingRow = Shipping::where('zone', $zone)->first();
        $shipping = $shippingRow ? $shippingRow->shipping_cost : 0;
        $grandTotal = $subtotal + $shipping;

        return view('frontend.checkout.checkout', compact(
            'cart',
            'subtotal',
            'shipping',
            'grandTotal'
        ));
    }

    public function cartAdd(Request $request)
    {
        $cart = session('cart', []);
        $productId = $request->product_id;
        $qty       = (int) $request->qty;
        $colorId   = $request->color_id;
        $sizeId    = $request->size_id;

        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $cartKey = $productId.'_'.($colorId ?? 0).'_'.($sizeId ?? 0);
        $existingQty = $cart[$cartKey]['quantity'] ?? 0;

        $cart[$cartKey] = [
            'product_id' => $productId,
            'name'       => $product->name,
            'image'      => $product->image,
            'price'      => $product->selling_price,
            'quantity'   => $existingQty + $qty,
            'color_id'   => $colorId,
            'size_id'    => $sizeId,
            'color' => $colorId? optional($product->colors()->where('color_id', $colorId)->first())->color?->name: null,
            'size'       => $sizeId ? optional($product->sizes()->find($sizeId))->size : null,
        ];

        session(['cart' => $cart]);
        $cartCount = count($cart);
        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $zone = session('shipping_zone', 'inside_dhaka');
        $shippingRow = Shipping::where('zone', $zone)->first();
        $shipping = $shippingRow ? $shippingRow->shipping_cost : 0;
        session()->put('shipping_cost', $shipping);
        $total = $subtotal + $shipping;
        

        $html = view('frontend.cart.partials.header-cart', [
            'cart'       => $cart,
            'shipping'   => $shipping,
            'cart_count' => $cartCount,
        ])->render();

        return response()->json([
            'success'    => true,
            'message'    => 'Product added to cart!',
            'cart_count' => $cartCount,
            'shipping'   => $shipping,
            'subtotal'   => $subtotal,
            'cart_total' => $total,
            'html'       => $html,
            
        ]);
    }

    public function cartContent()
    {
        $cart = session('cart');
        return view('frontend.cart.partials.header-cart', compact('cart'))->render();
    }

    public function cartUpdate(Request $request)
    {
        $cart = session()->get('cart', []);
        foreach ($request->quantities as $id => $qty) {
            if (!isset($cart[$id])) {
                continue;
            }
            $productId = $cart[$id]['product_id'];
            $product = Product::with('stocks')->find($productId);
            $availableStock = $product->stocks->sum('quantity');
            if ((int)$qty > $availableStock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'চাহিদাকৃত পরিমাণ স্টকে নেই'
                ], 422);
            }
            $cart[$id]['quantity'] = (int)$qty;
        }
        session()->put('cart', $cart);
        $html = view('frontend.cart.partials.header-cart', [
            'cart' => session('cart')
        ])->render();

        $table = view('frontend.cart.partials.cart-table', [
            'cart' => $cart
        ])->render();

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = session('shipping_cost', 60);

        return response()->json([
            'status' => 'success',
            'table' => $table,
            'html' => $html,
            'cart_count' => count($cart),
            'subtotal' => $total,
            'shipping' => $shipping,
            'cart_total' => $total + $shipping
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            if (count($cart) > 0) {
                session()->put('cart', $cart);
            } else {
                session()->forget('cart');
            }
        }

        $html = view('frontend.cart.partials.header-cart', [
                'cart' => session('cart')
            ])->render();

        $table = view('frontend.cart.partials.cart-table', [
            'cart' => session('cart')
        ])->render();
        $subtotal = collect($cart)->sum(function($item){
            return $item['price'] * $item['quantity'];
        });

        $shipping = count($cart) > 0 ? session('shipping_cost', 0): 0;
        $total = $subtotal + $shipping;

        return response()->json([
            'status' => 'success',
            'table' => $table,
            'html' => $html,
            'cart_count' => count($cart),
            'cart_total' => $total,
            'subtotal' => $subtotal,
            'shipping' => $shipping
        ]);
    }

    public function updateShippingZone(Request $request)
    {
        $zone = $request->zone;
        $shipping = Shipping::where('zone', $zone)->first();
        if (!$shipping) {
            return response()->json([
                'success' => false,
                'message' => 'Shipping zone not found'
            ]);
        }
        session([
            'shipping_zone' => $zone,
            'shipping_cost' => $shipping->shipping_cost
        ]);
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $total = $subtotal + $shipping->shipping_cost;
        return response()->json([
            'success' => true,
            'shipping' => $shipping->shipping_cost,
            'total' => $total
        ]);
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'required|string|max:50',
            'address'         => 'required|string|max:500',
            'city'            => 'required|string|max:255',
            'province'        => 'required|string|max:255',
            'country'         => 'required|string|max:100',
            'payment_method'  => 'required|in:cod,online',
            'subtotal'        => 'required|numeric',
            'shipping'        => 'required|numeric',
            'total'           => 'required|numeric',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'আপনার কার্ট খালি আছে।');
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number' => 'ORD-' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
                'full_name'      => $request->name,
                'company_name'   => $request->company_name ?? null,
                'email'          => $request->email ?? null,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->city,
                'province'       => $request->province,
                'postcode'       => $request->postcode ?? null,
                'country'        => $request->country,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
                'subtotal'       => $request->subtotal,
                'shipping'       => $request->shipping,
                'total'          => $request->total,
                'order_note'     => $request->order_note ?? null,
                'status'         => 'pending',
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id'   => $item['id'] ?? null,
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'quantity'     => $item['quantity'],
                    'total'        => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            session()->forget('cart');
            session()->forget('shipping');

            return redirect()
                ->route('order.success', $order->order_number)
                ->with('success', 'আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'অর্ডার ব্যর্থ হয়েছে, আবার চেষ্টা করুন।');
        }
    }

    

}
