<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Shipping;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function cart()
    {
        $cart = session('cart', []);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        $zone = session('shipping_zone', 'inside_dhaka');
        $shippingRow = Shipping::where('zone', $zone)->first();
        $shipping = $shippingRow ? $shippingRow->shipping_cost : 0;
        $grandTotal = $subtotal + $shipping;

        return view('frontend.checkout.cart', compact(
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
        
        $customAttributes = $request->custom_attributes ?? [];
        $dimensions       = $request->dimensions ?? null;

        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }

        $price = $product->selling_price;
        
        if ($product->is_calculator == 1 && !empty($dimensions)) {
            $sqft = (float) $dimensions['sqft'];
            $price = $sqft * $product->price_per_sqft; 
            
            $dimText = "Dimension: {$dimensions['w_ft']}ft {$dimensions['w_in']}in × {$dimensions['h_ft']}ft {$dimensions['h_in']}in ({$sqft} sq.ft)";
            $customAttributes['dimension'] = $dimText; 
        }

        $attrHash = !empty($customAttributes) ? '_' . md5(json_encode($customAttributes)) : '';
        $cartKey = $productId . '_' . ($colorId ?? 0) . '_' . ($sizeId ?? 0) . $attrHash;
        
        $existingQty = $cart[$cartKey]['quantity'] ?? 0;
        $requestedTotalQty = $existingQty + $qty;

        $colorName = null;
        $sizeName = null;

        if ($product->product_type === 'multiple') {
            
            $variantQuery = ProductVariant::where('product_id', $productId);
            if ($colorId) $variantQuery->where('color_id', $colorId);
            if ($sizeId) $variantQuery->where('size_id', $sizeId);
            
            $variant = $variantQuery->with(['color', 'size'])->first();

            if (!$variant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select the required Size and Color.'
                ]);
            }

            if ($requestedTotalQty > $variant->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available for this variation!'
                ]);
            }

            if ($product->is_calculator == 0) {
                $price = $variant->selling_price > 0 ? $variant->selling_price : $product->selling_price;
            }
            
            $colorName = $variant->color ? $variant->color->name : null;
            $sizeName  = $variant->size ? $variant->size->name : null;

        } else {
            if ($requestedTotalQty > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not enough stock available!'
                ]);
            }
        }

        $extraPrice = 0;
        if (!empty($customAttributes)) {
            foreach ($customAttributes as $attrId => $attrValue) {
                if ($attrId === 'dimension') continue; 

                $attributeOption = \App\Models\AttributeOption::where('attribute_id', $attrId)
                                    ->where('value', $attrValue)
                                    ->first();
                
                if ($attributeOption && $attributeOption->extra_price > 0) {
                    $extraPrice += (float) $attributeOption->extra_price;
                }
            }
        }
        
        $finalPrice = $price + $extraPrice;

        $cart[$cartKey] = [
            'product_id' => $productId,
            'name'       => $product->name,
            'image'      => $product->image,
            'price'      => $finalPrice, 
            'quantity'   => $requestedTotalQty,
            'color_id'   => $colorId,
            'size_id'    => $sizeId,
            'color'      => $colorName,
            'size'       => $sizeName,
            'attributes' => array_values($customAttributes), 
        ];

        session(['cart' => $cart]);
        session()->save();
        
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
            'cart_count' => count(session('cart')),
        ])->render();

        return response()->json([
            'success'    => true,
            'message'    => 'Product added to cart!',
            'cart_count' => count(session('cart')),
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
            if (!isset($cart[$id])) continue;

            $item = $cart[$id];
            $productId = $item['product_id'];
            $colorId   = $item['color_id'] ?? null;
            $sizeId    = $item['size_id'] ?? null;

            $product = Product::find($productId);
            
            if ($product->product_type === 'multiple') {
                $variant = ProductVariant::where('product_id', $productId)
                    ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                    ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
                    ->first();
                    
                $availableStock = $variant ? $variant->stock : 0; // quantity এর বদলে stock
            } else {
                $availableStock = $product->stock;
            }

            if ((int)$qty > $availableStock) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'চাহিদাকৃত পরিমাণ স্টকে নেই (' . $availableStock . ' টি উপলব্ধ)'
                ], 422);
            }
            
            $cart[$id]['quantity'] = (int)$qty;
        }
        
        session()->put('cart', $cart);
        session()->save();

        $html = view('frontend.cart.partials.header-cart', ['cart' => $cart])->render();
        $table = view('frontend.cart.partials.cart-table', ['cart' => $cart])->render();
        
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

        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            session()->save(); 
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
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:50',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:255',
            'payment_method' => 'required|in:cod,online',
            'subtotal'       => 'required|numeric',
            'shipping'       => 'required|numeric',
            'total'          => 'required|numeric',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'আপনার কার্ট খালি আছে।');
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                'full_name'      => $request->name,
                'company_name'   => $request->company_name ?? null,
                'email'          => $request->email ?? null,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'city'           => $request->city,
                'province'       => $request->province ?? 'N/A',
                'postcode'       => $request->postcode ?? null,
                'country'        => $request->country ?? 'Bangladesh',
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'unpaid',
                'subtotal'       => $request->subtotal,
                'shipping'       => $request->shipping,
                'total'          => $request->total,
                'order_note'     => $request->order_note ?? null,
                'status'         => 'pending',
            ]);

            foreach ($cart as $item) {
                $productId = $item['product_id'];
                $colorId   = $item['color_id'] ?? null;
                $sizeId    = $item['size_id'] ?? null;
                $qty       = (int)$item['quantity'];

                $order->items()->create([
                    'product_id'   => $productId,
                    'product_name' => $item['name'],
                    'color_id'     => $colorId,
                    'size_id'      => $sizeId,
                    'price'        => $item['price'],
                    'quantity'     => $qty,
                    'total'        => $item['price'] * $qty,
                ]);

                $product = Product::find($productId);
                
                if ($product) {
                    $product->decrement('stock', $qty);

                    if ($product->product_type === 'multiple') {
                        ProductVariant::where('product_id', $productId)
                            ->when($colorId, fn($q) => $q->where('color_id', $colorId))
                            ->when($sizeId, fn($q) => $q->where('size_id', $sizeId))
                            ->decrement('stock', $qty);
                    }
                }
            }

            DB::commit();

            session()->forget(['cart', 'shipping_cost']);

            return redirect()
                ->route('order.success', $order->order_number)
                ->with('success', 'আপনার অর্ডার সফলভাবে সম্পন্ন হয়েছে।');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order Store Error: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'অর্ডার ব্যর্থ হয়েছে, আবার চেষ্টা করুন।');
        }
    }

}
