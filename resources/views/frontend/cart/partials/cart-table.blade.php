@php
    $subtotal = 0;
@endphp

@if($cart && count($cart) > 0)
    @foreach($cart as $id => $item)
        @php
            $itemTotal = $item['price'] * $item['quantity'];
            $subtotal += $itemTotal;
        @endphp
        <tr>
            <td>
                <div class="cart-product-info">
                    <div class="cart-img-wrap">
                        <img src="{{ asset($item['image']) }}" alt="product">
                    </div>
                    <div>
                        <h4 class="cart-product-title">{{ $item['name'] }}</h4>
                        
                        {{-- Color & Size --}}
                        @if(!empty($item['color']) || !empty($item['size']))
                            <div class="d-flex gap-2 flex-wrap mb-1">
                                @if(!empty($item['color'])) 
                                    <span class="var-badge">Color: {{ $item['color'] }}</span> 
                                @endif
                                @if(!empty($item['size'])) 
                                    <span class="var-badge">Size: {{ $item['size'] }}</span> 
                                @endif
                            </div>
                        @endif

                        {{-- 🟢 CUSTOM ATTRIBUTES & DIMENSION (Square Feet) 🟢 --}}
                        @if(!empty($item['attributes']))
                            <div class="d-flex gap-2 flex-wrap mt-1">
                                @foreach($item['attributes'] as $attrValue)
                                    <span class="var-badge" style="background: #e0f2fe; color: #1e3a8a; border: 1px solid #bae6fd;">
                                        {{ $attrValue }}
                                    </span> 
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            </td>

            <td class="text-center fw-medium text-dark currency" style="font-size: 15px;">
                ৳{{ number_format($item['price'], 2) }}
            </td>

            <td>
                <div class="qty-input-wrap">
                    <input type="number" class="qty-input" data-id="{{ $id }}"
                           name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1">
                </div>
            </td>

            <td class="text-end fw-bold currency" style="color: #008a7a; font-size: 16px;">
                ৳{{ number_format($itemTotal, 2) }}
            </td>

            <td>
                <button type="button" class="btn-remove remove-item" data-id="{{ $id }}" title="Remove item">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center py-5">
            <div class="py-4">
                <i class="fa-solid fa-cart-arrow-down mb-3" style="font-size: 40px; color: #cbd5e1;"></i>
                <h5 class="text-muted fw-medium">Your cart is currently empty.</h5>
                <a href="{{ url('/') }}" class="btn-modern btn-outline-modern mt-3 d-inline-block">Continue Shopping</a>
            </div>
        </td>
    </tr>
@endif