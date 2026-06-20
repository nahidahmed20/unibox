@extends('frontend.layouts.app')
@section('title', 'Checkout | Unibox')

@section('content')

@php
    $cart = session('cart', []);
    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);

    $shippingCost = session('shipping_cost', 0);
@endphp
<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1 class="title">Cart Page</h1>
            <h4 class="sub-title">
                <span class="home">
                    <a href="{{ url('/') }}">
                        <span>Home</span>
                    </a>
                </span>
                <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                <span class="inner">
                    <span>Cart Page</span>
                </span>
            </h4>
        </div>
    </div>
</section>

<section class="cart-section pt-50 pb-100">
    <div class="container">
        <div class="row">

            <!-- LEFT SIDE -->
            <div class="col-lg-8">

                <!-- TOP MESSAGE -->
                <div class="cart-top-content d-none">
                    <p>
                        Add <span>৳59.69</span> to cart and get free shipping
                    </p>
                    <div class="line"></div>
                </div>

                <!-- TABLE -->
                <div class="table-content cart-table">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="product-remove"></th>
                                <th class="cart-product-name text-center">Products</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Subtotal</th>
                            </tr>
                        </thead>

                         <tbody id="cart-table-wrapper">
                            @include('frontend.cart.partials.cart-table', ['cart' => session('cart')])
                        </tbody>
                    </table>
                </div>

                <!-- COUPON + UPDATE -->
                <div class="cart-btn-wrap">
                    <div class="left-item ">
                        <input type="text" class="form-control" placeholder="Coupon Code">
                        <button class="rr-primary-btn">Apply Coupon</button>
                    </div>
                    <button class="rr-primary-btn update-btn">Update Cart</button>
                </div>
            </div>


            <!-- RIGHT SIDE -->
            <div class="col-lg-4">
                <div class="checkout-wrapper">
                    <div class="checkout-top checkout-item item-1">
                        <h4 class="title">Cart Totals</h4>
                    </div>
                    <!-- SUBTOTAL -->
                    <div class="checkout-top checkout-item">
                        <h4 class="title">Subtotal</h4>
                        <span class="price subtotal-value">
                            ৳{{ number_format($subtotal, 2) }}
                        </span>
                    </div>
                    <div class="checkout-top checkout-item">
                        <h4 class="title">Shipping</h4>
                        <span class="shipping-value">
                            ৳{{ number_format($shipping ?? 0, 2) }}
                        </span>
                    </div>
                    <!-- SHIPPING -->
                    @php
                        $selectedZone = session('shipping_zone', 'inside_dhaka');
                    @endphp
                    <!-- SHIPPING -->
                    <div class="checkout-shipping checkout-item">
                        <h4 class="title">Shipping</h4>
                        <div class="shipping-right">
                            <div class="checkout-option-wrapper">
                                <!-- INSIDE DHAKA -->
                                <div class="shipping-option">
                                    <input id="inside_dhaka"
                                        type="radio"
                                        name="shipping"
                                        value="inside_dhaka"
                                        {{ $selectedZone == 'inside_dhaka' ? 'checked' : '' }}>
                                    <label for="inside_dhaka">Inside Dhaka</label>
                                </div>

                                <!-- NEAR DHAKA -->
                                <div class="shipping-option">
                                    <input id="near_dhaka"
                                        type="radio"
                                        name="shipping"
                                        value="near_dhaka"
                                        {{ $selectedZone == 'near_dhaka' ? 'checked' : '' }}>
                                    <label for="near_dhaka">Near Dhaka</label>
                                </div>

                                <!-- OUTSIDE DHAKA -->
                                <div class="shipping-option">
                                    <input id="outside_dhaka"
                                        type="radio"
                                        name="shipping"
                                        value="outside_dhaka"
                                        {{ $selectedZone == 'outside_dhaka' ? 'checked' : '' }}>
                                    <label for="outside_dhaka">Outside Dhaka</label>
                                </div>
                            </div>
                            <p>Shipping cost will be added to total during checkout</p>
                        </div>
                    </div>

                    <!-- TOTAL -->
                    <div class="checkout-total checkout-item">
                        <h4 class="title">Total</h4>
                        <span class="total-value">
                            ৳{{ number_format($subtotal + $shipping, 2) }}
                        </span>
                    </div>

                </div>

                <!-- PLACE ORDER -->
                <div class="checkout-proceed">
                    <a href="{{ route('place.order') }}" class="rr-primary-btn checkout-btn" style="width: 100%;display: flex; justify-content: center;">
                        Place Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('javascript')
<script>
    function formatMoney(amount) {
        return parseFloat(amount).toFixed(2);
    }

    $(document).on('change', 'input[name="shipping"]', function () {
        let zone = $(this).val();

        $.ajax({
            url: "{{ url('/cart/update-shipping-zone') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                zone: zone
            },
            success: function (res) {
                if (res.success) {
                    $('.total-value').text('৳' + formatMoney(res.total));
                    $('.shipping-value').text('৳' + formatMoney(res.shipping));
                }
            }
        });
    });
</script>
@endpush
