@php
    $cart = session('cart');
    $subtotal = 0;
    $shipping = session('shipping_cost', 0);
@endphp

<div class="cart-body">
    <!-- CART COUNT -->
    <div class="cart-top">
        <span class="cart-count">
            {{ $cart ? count($cart) : 0 }} items
        </span>
    </div>

    @if($cart && count($cart) > 0)
        <!-- CART ITEMS -->
        @foreach($cart as $id => $item)
            @php
                $itemTotal = $item['price'] * $item['quantity'];
                $subtotal += $itemTotal;
            @endphp

            <div class="cart-item">
                <!-- IMAGE -->
                <div class="cart-img">
                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                </div>
                <!-- INFO -->
                <div class="cart-info">
                    <h5>{{ $item['name'] }}</h5>
                    
                    {{-- SIZE --}}
                    @if(!empty($item['size']))
                        <p class="size">
                            Size: {{ $item['size'] }}
                        </p>
                    @endif
                    
                    {{-- COLOR --}}
                    @if(!empty($item['color']))
                        <p class="color">
                            Color: {{ $item['color'] }}
                        </p>
                    @endif

                    {{-- 🟢 CUSTOM ATTRIBUTES / SPECIFICATIONS 🟢 --}}
                    @if(!empty($item['attributes']))
                        <p class="attribute" style="font-size: 13px; color: #6c757d; line-height: 1.4; margin-bottom: 5px;">
                            <strong>Spec:</strong> {{ implode(', ', $item['attributes']) }}
                        </p>
                    @endif

                    <p class="qty">Qty: {{ $item['quantity'] }}</p>
                    <p class="price">
                        ৳{{ number_format($item['price'], 2) }}
                    </p>
                    <p class="item-total">
                        Total: ৳{{ number_format($itemTotal, 2) }}
                    </p>
                </div>

                <!-- REMOVE -->
                <a href="javascript:void(0)"
                class="remove-item"
                data-id="{{ $id }}">
                    <i class="fa fa-trash"></i>
                </a>

            </div>
        @endforeach
        <!-- SUBTOTAL -->
        <div class="cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>৳{{ number_format($subtotal, 2) }}</span>
            </div>

            <!-- OPTIONAL -->
            <div class="summary-row">
                <span>Shipping</span>
                <span class="shipping-value">
                    ৳{{ $shipping ?? 0 }}
                </span>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <span class="total-value">
                    ৳{{ number_format($subtotal + $shipping, 2) }}
                </span>
            </div>
        </div>
    @else
        <!-- EMPTY CART -->
        <div class="cart-empty">
            <i class="fa fa-shopping-bag"></i>
            <p>Your cart is empty</p>
        </div>
    @endif
</div>