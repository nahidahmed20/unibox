@extends('frontend.layouts.app')
@section('title', 'Checkout | Unibox')

@section('content')
@push('css')

<style>
    .form-title-two {
        color: #000000;
        font-size: 18px;
        font-weight: 500;
        margin-bottom: 10px;
    }

    .form-control-two {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
        outline: none;
        transition: 0.3s;
    }

    .form-control-two:focus {
        border: 1px solid #000;
        box-shadow: 0 0 0 3px rgba(0,0,0,0.1);
    }
    .select2-container--default .select2-selection--single {
        height: 48px;
        border: 1px solid #ddd;
        border-radius: 4px;
        display: flex;
        align-items: center;
        padding: 0 12px;
        font-size: 15px;
        transition: 0.3s;
        background: #fff;
    }

    /* focus effect */
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #000;
        box-shadow: 0 0 0 3px rgba(0,0,0,0.08);
    }

    /* arrow fix */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px;
        right: 10px;
    }

    /* selected text alignment */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px;
        color: #333;
    }

    /* dropdown box */
    .select2-container--default .select2-dropdown {
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }

    /* option hover */
    .select2-container--default .select2-results__option--highlighted {
        background-color: #000;
        color: #fff;
    }
    .place-order-header{
        display: flex;
        justify-content: space-between;
        font-weight:bold;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--rr-color-border-1);
        grid-gap: 20px;
    }
</style>
@endpush
@php
    $cart = session('cart', []);
    $shippingCost = session('shipping_cost', 0);

    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
    $total = $subtotal + $shippingCost;
@endphp

<section class="checkout-section pt-50 pb-100">
    <div class="container">
        <div class="row">

            {{-- LEFT SIDE - BILLING FORM --}}
            <div class="col-lg-6 col-md-12">
                <div class="checkout-left">
                    <h3 class="form-header">Billing Details</h3>
                    <form method="POST" action="{{ route('checkout.otp.send') }}">
                        @csrf
                        <div class="checkout-form-wrap">
                            {{-- NAME --}}
                            <div class="form-group ">
                                <div class="col-md-12">
                                    <div class="form-item name">
                                        <h4 class="form-title-two">Full Name*</h4>
                                        <input type="text"
                                            name="name"
                                            value="{{ old('name', auth('customer')->user()->name ?? '') }}"
                                            class="form-control-two"
                                            placeholder="Enter Your Full Name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item ">
                                        <h4 class="form-title-two">Address*</h4>
                                        <input type="text" id="address" name="address" value="{{ old('address', auth('customer')->user()->address ?? '') }}" class="form-control-two street-control" placeholder="Enter Your Address">
                                        @error('address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4 mb-4">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Division</h4>
                                        <select name="division_id" id="division" class="form-control-two country">
                                            <option value="">Select Division</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    {{ auth('customer')->user()->division_id== $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Distric</h4>
                                        <select name="district_id" id="district" class="form-control-two  ">
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Upazila</h4>
                                        <select name="upazila_id" id="upazila" class="form-control-two  ">
                                            <option value="">Select Upazila</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Phone*</h4>
                                        <input type="text" id="phone" name="phone" value="{{ old('phone', auth('customer')->user()->phone ?? '') }}" class="form-control-two" placeholder="Enter Your Phone Number">
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Order Notes</h4>
                                        <textarea id="message" name="message" cols="30" rows="5" class="form-control-two address" placeholder="Enter Your Order Notes">{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>

            {{-- RIGHT SIDE - ORDER SUMMARY --}}
            <div class="col-lg-6 col-md-12">
                <div class="checkout-right">
                    <h3 class="form-header">Your Order</h3>
                    <div class="order-box">
                        {{-- PRODUCT HEADER --}}
                        <div class="place-order-header">
                            <span>Product</span>
                            <span>Price</span>
                        </div>

                        {{-- CART ITEMS --}}
                        @forelse($cart as $item)
                            <div class="order-item">
                                <div class="order-left">
                                    <div class="order-img">
                                        <img src="{{ asset($item['image']) }}" width="60" >
                                    </div>
                                </div>
                                <div class="order-right">
                                    <div class="content">
                                        <h4 class="title">{{ $item['name'] }}</h4>
                                        <small>Qty: {{ $item['quantity'] }}</small>
                                        @if(!empty($item['size']))
                                            <br> <small>Size: {{ $item['size'] }}</small>
                                        @endif
                                        @if(!empty($item['color_id']))
                                            <br> <small>Color: {{ $item['color'] }}</small>
                                        @endif
                                    </div>
                                    <span class="price">
                                        ৳{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p>Your cart is empty</p>
                        @endforelse
                        {{-- SUBTOTAL --}}
                        <div class="order-item item-1">
                            <span>Subtotal</span>
                            <span>৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        {{-- SHIPPING --}}
                        <div class="order-item item-1">
                            <span>Shipping</span>
                            <span>৳{{ number_format($shippingCost, 2) }}</span>
                        </div>
                        {{-- TOTAL --}}
                        <div class="order-item item-1">
                            <strong>Total</strong>
                            <strong>৳{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    {{-- PAYMENT OPTIONS --}}
                    <div class="payment-option-wrap mt-3">
                        <div class="shipping-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <label>Cash On Delivery</label>
                        </div>
                        {{-- <div class="shipping-option">
                            <input type="radio" name="payment_method" value="bank">
                            <label>Bank Transfer</label>
                        </div> --}}
                        <p class="desc">
                            * For Bank Transfer, please send the payment to our account and include your order details in the transaction notes. We will process your order once we receive the payment confirmation.
                        </p>
                        <button type="submit" class="rr-primary-btn order-btn">
                             Order
                        </button>
                    </div>
                </div>
            </div>
            </form>

        </div>
    </div>
</section>

@endsection

@push('javascript')
    <script>
        $(document).ready(function () {
            // Init Select2
            $('#division, #district, #upazila').select2({
                width: '100%'
            });

            let selectedDivision = "{{ auth('customer')->user()->division_id }}";
            let selectedDistrict = "{{ auth('customer')->user()->district_id }}";
            let selectedUpazila = "{{ auth('customer')->user()->upazila_id }}";

            // =========================
            // 1. DIVISION CHANGE EVENT
            // =========================
            $('#division').on('change', function () {

                let division_id = $(this).val();

                $('#district').html('<option value="">Loading...</option>').trigger('change');
                $('#upazila').html('<option value="">Select Upazila</option>').trigger('change');

                if (!division_id) return;

                $.ajax({
                    url: '/get-districts/' + division_id,
                    type: 'GET',
                    success: function (data) {

                        let html = '<option value="">Select District</option>';

                        $.each(data, function (key, value) {
                            html += `<option value="${value.id}">${value.name}</option>`;
                        });

                        $('#district').html(html).trigger('change.select2');
                    }
                });
            });

            // =========================
            // 2. DISTRICT CHANGE EVENT
            // =========================
            $('#district').on('change', function () {

                let district_id = $(this).val();

                $('#upazila').html('<option value="">Loading...</option>').trigger('change');

                if (!district_id) return;

                $.ajax({
                    url: '/get-upazilas/' + district_id,
                    type: 'GET',
                    success: function (data) {

                        let html = '<option value="">Select Upazila</option>';

                        $.each(data, function (key, value) {
                            html += `<option value="${value.id}">${value.name}</option>`;
                        });

                        $('#upazila').html(html).trigger('change.select2');
                    }
                });
            });

            // =========================
            // 3. AUTO LOAD USER DATA
            // =========================
            if (selectedDivision) {

                $('#division').val(selectedDivision).trigger('change');

                $.ajax({
                    url: '/get-districts/' + selectedDivision,
                    type: 'GET',
                    success: function (data) {
                        let html = '<option value="">Select District</option>';
                        $.each(data, function (key, value) {
                            let selected = (value.id == selectedDistrict) ? 'selected' : '';
                            html += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                        });
                        $('#district').html(html).trigger('change.select2');
                        // LOAD UPAZILA AFTER DISTRICT
                        if (selectedDistrict) {
                            $.ajax({
                                url: '/get-upazilas/' + selectedDistrict,
                                type: 'GET',
                                success: function (data) {
                                    let html = '<option value="">Select Upazila</option>';
                                    $.each(data, function (key, value) {
                                        let selected = (value.id == selectedUpazila) ? 'selected' : '';
                                        html += `<option value="${value.id}" ${selected}>${value.name}</option>`;
                                    });
                                    $('#upazila').html(html).trigger('change.select2');
                                }
                            });
                        }
                    }
                });
            }

        });
    </script>
@endpush

