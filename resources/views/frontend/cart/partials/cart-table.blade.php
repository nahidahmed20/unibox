 @php
    $subtotal = 0;
    $shipping = $shipping ?? 60;
    $total = $subtotal + $shipping;
@endphp

    @if($cart && count($cart) > 0)
        @foreach($cart as $id => $item)
            @php
                $itemTotal = $item['price'] * $item['quantity'];
                $subtotal += $itemTotal;
            @endphp
            <tr>
                <!-- REMOVE -->
                <td class="product-remove">

                    <a href="javascript:void(0)"
                        class="remove-item"
                        data-id="{{ $id }}">
                            <i class="fa fa-trash"></i>
                        </a>
                </td>

                <!-- PRODUCT -->
                <td class="product-thumbnail">
                    <a href="#">
                        <img src="{{ asset($item['image']) }}" width="60">
                    </a>

                    <h4 class="title">
                        {{ $item['name'] }}
                    </h4>

                    @if(!empty($item['color']) || !empty($item['size']))
                        <div class="product-variation">
                            @if(!empty($item['color']))
                                <p class="mb-0">
                                    <strong>Color:</strong> {{ $item['color'] }}
                                </p>
                            @endif

                            @if(!empty($item['size']))
                                <p class="mb-0">
                                    <strong>Size:</strong> {{ $item['size'] }}
                                </p>
                            @endif
                        </div>
                    @endif
                </td>

                <!-- PRICE -->
                <td class="product-price">
                    <span class="amount">৳{{ number_format($item['price'], 2) }}</span>
                </td>

                <!-- QUANTITY -->
                <td class="product-quantity">
                    <div class="quantity__group">
                        <input type="number" class="qty-input" data-id="{{ $id }}"
                                name="quantities[{{ $id }}]" value="{{ $item['quantity'] }}" min="1">
                    </div>
                </td>
                <!-- SUBTOTAL -->
                <td class="product-subtotal">
                    <span class="amount">৳{{ number_format($itemTotal, 2) }}</span>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5" class="text-center">
                Your cart is empty
            </td>
        </tr>
    @endif

