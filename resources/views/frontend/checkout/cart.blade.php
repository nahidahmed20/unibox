@extends('frontend.layouts.app')
@section('title', 'Shopping Cart | Unibox')

@section('content')

@php
    $cart = session('cart', []);
    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
    $shippingCost = session('shipping_cost', 0);
@endphp

<style>

    .cart-page-wrapper {
        background-color: #f8fafc;
        min-height: 80vh;
        padding: 60px 0;
    }

    /* Page Header */
    .cart-header {
        margin-bottom: 40px;
    }
    .cart-header h2 {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
    }
    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #64748b;
    }
    .breadcrumb-custom a {
        color: #008a7a;
        text-decoration: none;
        font-weight: 500;
    }
    .breadcrumb-custom i { font-size: 12px; }

    /* Cards */
    .modern-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #f1f5f9;
        padding: 30px;
        margin-bottom: 30px;
    }

    /* Table */
    .modern-cart-table { width: 100%; border-collapse: collapse; }
    .modern-cart-table thead th {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .modern-cart-table tbody td {
        padding: 25px 0;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .modern-cart-table tbody tr:last-child td { border-bottom: none; padding-bottom: 0; }

    /* Coupon Section */
    .coupon-wrapper {
        display: flex;
        gap: 15px;
        align-items: center;
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        margin-top: 30px;
    }
    .coupon-input {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 15px;
        width: 250px;
        outline: none;
    }
    .coupon-input:focus { border-color: #008a7a; box-shadow: 0 0 0 3px rgba(0, 138, 122, 0.1); }
    
    .btn-modern {
        background: #0f172a;
        color: #fff;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 15px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-modern:hover { background: #1e293b; color: #fff;  }
    
    .btn-outline-modern {
        background: #fff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }
    .btn-outline-modern:hover { background: #f8fafc; border-color: #cbd5e1; }

    .btn-primary-custom {
        background: #008a7a;
        width: 100%;
        display: flex;
        justify-content: center;
        padding: 16px;
        font-size: 16px;
        box-shadow: 0 10px 20px rgba(0, 138, 122, 0.15);
    }
    .btn-primary-custom:hover { background: #007063; }

    /* Summary Card */
    .summary-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 18px;
        font-size: 15px;
        color: #475569;
    }
    .summary-row.total {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px dashed #e2e8f0;
        font-size: 20px;
        font-weight: 700;
        color: #008a7a;
    }

    /* Shipping Options */
    .shipping-title {
        font-size: 14px;
        font-weight: 600;
        color: #0f172a;
        margin: 25px 0 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .shipping-box {
        display: block;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.3s;
        position: relative;
    }
    .shipping-box input[type="radio"] { display: none; }
    .shipping-box .check-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #008a7a;
        opacity: 0;
        transition: 0.2s;
    }
    .shipping-box:hover { border-color: #cbd5e1; background: #f8fafc; }
    
    /* Checked State */
    .shipping-box input[type="radio"]:checked + .shipping-content {
        color: #008a7a;
    }
    .shipping-box input[type="radio"]:checked ~ .check-icon { opacity: 1; }
    .shipping-box.active-box {
        border-color: #008a7a;
        background: #f0fdfa;
        box-shadow: 0 4px 10px rgba(0, 138, 122, 0.05);
    }
    .shipping-label { font-weight: 600; font-size: 15px; display: block; }
    .shipping-desc { font-size: 13px; color: #64748b; margin-top: 4px; display: block; }

    /* Utilities */
    .currency { font-family: "Noto Sans Bengali", sans-serif; }

    .cart-product-info { display: flex; align-items: center; gap: 15px; text-align: left; }
    .cart-img-wrap {
        width: 75px; height: 75px; border-radius: 10px; border: 1px solid #e2e8f0;
        padding: 4px; background: #f8fafc; flex-shrink: 0;
    }
    .cart-img-wrap img { width: 100%; height: 100%; object-fit: contain; border-radius: 6px; }
    
    .cart-product-title { font-size: 15px; font-weight: 600; color: #0f172a; margin: 0 0 8px; line-height: 1.4; }
    .var-badge {
        background: #f1f5f9; color: #475569; padding: 4px 10px;
        border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block;
    }
    
    .qty-input-wrap {
        width: 100px; margin: 0 auto;
    }
    .qty-input {
        width: 100%; padding: 8px; text-align: center; border: 1px solid #cbd5e1;
        border-radius: 8px; outline: none; font-weight: 600; color: #0f172a;
    }
    .qty-input:focus { border-color: #008a7a; }

    .btn-remove {
        background: #fef2f2; color: #ef4444; border: none; width: 35px; height: 35px;
        border-radius: 8px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: 0.2s; margin: 0 auto;
    }
    .btn-remove:hover { background: #fee2e2; color: #dc2626; transform: scale(1.05); }



    @media(max-width: 768px) {
        .modern-cart-table thead { display: none; }
        
        .modern-cart-table tbody tr { 
            display: block; 
            border: 1px solid #e2e8f0; 
            border-radius: 12px; 
            margin-bottom: 20px; 
            padding: 15px; 
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        
        .modern-cart-table tbody td { 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            padding: 12px 0; 
            border: none; 
            border-bottom: 1px dashed #f1f5f9; 
            text-align: right; 
        }

        .modern-cart-table tbody td:last-child { 
            border-bottom: none; 
            padding-bottom: 0; 
        }

        .modern-cart-table tbody td:nth-child(2)::before { content: "Unit Price"; font-weight: 600; color: #64748b; font-size: 14px; }
        .modern-cart-table tbody td:nth-child(3)::before { content: "Quantity"; font-weight: 600; color: #64748b; font-size: 14px; }
        .modern-cart-table tbody td:nth-child(4)::before { content: "Total Price"; font-weight: 600; color: #64748b; font-size: 14px; }
        .modern-cart-table tbody td:nth-child(5)::before { content: "Action"; font-weight: 600; color: #64748b; font-size: 14px; }

        .modern-cart-table tbody td:first-child { 
            display: flex; 
            justify-content: flex-start; 
        }
        
        .qty-input-wrap { margin: 0 !important; width: 120px; }
        .btn-remove { margin: 0 !important; }
        
        .coupon-wrapper {
            flex-direction: column; 
            align-items: stretch; 
            gap: 10px; 
            padding: 15px; 
        }
        
    }
</style>

<section class="cart-page-wrapper">
    <div class="container">

        <div class="row">
            <div class="col-lg-8">
                <div class="modern-card">
                    <div class="table-responsive">
                        <table class="modern-cart-table">
                            <thead>
                                <tr>
                                    <th style="width: 50%;">Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="cart-table-wrapper">
                                @include('frontend.cart.partials.cart-table', ['cart' => session('cart')])
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap cart-actions">
                        <div class="coupon-wrapper">
                            <input type="text" class="coupon-input" placeholder="Enter coupon code">
                            <button class="btn-modern">Apply</button>
                        </div>
                        <div class="mt-4 mt-lg-0">
                            <button class="btn-modern btn-outline-modern update-btn">
                                <i class="fa-solid fa-rotate-right me-2"></i> Update Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="modern-card">
                    <h4 class="summary-title">Order Summary</h4>
                    
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span class="fw-bold text-dark currency subtotal-value">৳{{ number_format($subtotal, 2) }}</span>
                    </div>

                    <h5 class="shipping-title">Shipping Method</h5>
                    @php
                        $selectedZone = session('shipping_zone', 'inside_dhaka');
                    @endphp
                    
                    <label class="shipping-box {{ $selectedZone == 'inside_dhaka' ? 'active-box' : '' }}">
                        <input id="inside_dhaka" type="radio" name="shipping" value="inside_dhaka" {{ $selectedZone == 'inside_dhaka' ? 'checked' : '' }}>
                        <div class="shipping-content">
                            <span class="shipping-label">Inside Dhaka</span>
                            <span class="shipping-desc">Delivery within 24-48 hours</span>
                        </div>
                        <i class="fa-solid fa-circle-check check-icon"></i>
                    </label>

                    <label class="shipping-box {{ $selectedZone == 'near_dhaka' ? 'active-box' : '' }}">
                        <input id="near_dhaka" type="radio" name="shipping" value="near_dhaka" {{ $selectedZone == 'near_dhaka' ? 'checked' : '' }}>
                        <div class="shipping-content">
                            <span class="shipping-label">Near Dhaka</span>
                            <span class="shipping-desc">Delivery within 2-3 days</span>
                        </div>
                        <i class="fa-solid fa-circle-check check-icon"></i>
                    </label>

                    <label class="shipping-box {{ $selectedZone == 'outside_dhaka' ? 'active-box' : '' }}">
                        <input id="outside_dhaka" type="radio" name="shipping" value="outside_dhaka" {{ $selectedZone == 'outside_dhaka' ? 'checked' : '' }}>
                        <div class="shipping-content">
                            <span class="shipping-label">Outside Dhaka</span>
                            <span class="shipping-desc">Delivery via courier (3-5 days)</span>
                        </div>
                        <i class="fa-solid fa-circle-check check-icon"></i>
                    </label>

                    <div class="summary-row mt-4">
                        <span>Shipping Cost</span>
                        <span class="fw-bold text-dark currency shipping-value">৳{{ number_format($shippingCost ?? 0, 2) }}</span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span class="currency total-value">৳{{ number_format($subtotal + $shippingCost, 2) }}</span>
                    </div>

                    @if(auth('customer')->check())
                        <a href="{{ route('cart.checkout') }}" class="btn-modern btn-primary-custom mt-4">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2 mt-1"></i>
                        </a>
                    @else
                        @php
                            session(['redirect_after_login' => route('cart.checkout')]);
                        @endphp
                        
                        <a href="{{ route('user.login') }}" class="btn-modern btn-primary-custom mt-4">
                            Proceed to Checkout <i class="fa-solid fa-arrow-right ms-2 mt-1"></i>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('javascript')
<script>
    window.dataLayer = window.dataLayer || [];
    
    function pushCartData(cart, subtotal, shipping) {
        let items = [];
        @foreach($cart as $id => $item)
            items.push({
                item_id: "{{ $id }}",
                item_name: "{{ $item['name'] }}",
                price: {{ $item['price'] }},
                quantity: {{ $item['quantity'] }}
            });
        @endforeach

        window.dataLayer.push({
            event: "view_cart",
            ecommerce: {
                currency: "BDT",
                value: subtotal + shipping,
                items: items
            }
        });
    }

    pushCartData(@json($cart), {{ $subtotal }}, {{ $shippingCost }});

    $(document).on('change', 'input[name="shipping"]', function () {
        let zone = $(this).val();
        
        $('.shipping-box').removeClass('active-box');
        $(this).closest('.shipping-box').addClass('active-box');

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

                    // শিপিং পরিবর্তনের পর ডেটা লেয়ার আপডেট
                    window.dataLayer.push({
                        event: "cart_shipping_updated",
                        ecommerce: {
                            shipping_tier: zone,
                            value: res.total
                        }
                    });
                }
            }
        });
    });
</script>
@endpush