@extends('frontend.layouts.app')
@section('title', 'Checkout')

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
    /* =========================================
       Premium Select2 Custom Design 
    ========================================= */
    
    /* Main Input Box */
    .select2-container--default .select2-selection--single {
        height: 48px !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 16px;
        background-color: #fff;
        transition: all 0.3s ease;
    }

    /* Focus & Open State (Same as form-control-two) */
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #000 !important;
        box-shadow: 0 0 0 3px rgba(0,0,0,0.08) !important;
        outline: none;
    }

    /* Selected Text Alignment */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px !important;
        color: #000 !important;
        padding-left: 0 !important;
    }

    /* Customizing the Arrow Icon */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
        right: 15px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #888 transparent transparent transparent !important;
        border-width: 6px 5px 0 5px !important;
        transition: transform 0.3s ease;
    }
    
    /* Arrow points up when dropdown is open */
    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #888 transparent !important;
        border-width: 0 5px 6px 5px !important;
    }

    /* Dropdown Panel */
    .select2-container--default .select2-dropdown {
        border: 1px solid #eee !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        overflow: hidden;
        margin-top: 5px;
        z-index: 9999;
    }

    /* Search Input inside Dropdown */
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        padding: 10px 12px !important;
        font-size: 15px;
        outline: none !important;
        transition: border-color 0.3s;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #000 !important;
    }

    /* Options List Item */
    .select2-results__option {
        padding: 10px 15px !important;
        font-size: 15px;
        transition: all 0.2s ease;
        border-bottom: 1px solid #fcfcfc;
        color: #444;
    }

    /* Hovered Option (Soft Background) */
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #f8f9fa !important;
        color: #000 !important;
        font-weight: 500;
        padding-left: 20px !important; /* Smooth text shift on hover */
    }

    /* Selected Option (Dark Background) */
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #000 !important;
        color: #fff !important;
        font-weight: bold;
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
    /* Custom style for radio buttons */
    .address-type-box {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    .address-type-box .form-check-label {
        font-size: 16px;
        cursor: pointer;
        user-select: none;
    }
    .address-type-box .form-check-input:checked {
        background-color: #000;
        border-color: #000;
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
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-item name">
                                        <h4 class="form-title-two">Full Name*</h4>
                                        {{-- ?-> operator used for guest safety --}}
                                        <input type="text" name="name" 
                                            value="{{ old('name', auth('customer')->user()?->name ?? '') }}" 
                                            class="form-control-two" placeholder="Enter Your Full Name">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ADDRESS TYPE (HOME/OFFICE) --}}
                            <div class="form-group row mt-3">
                                <div class="col-md-12">
                                    <h4 class="form-title-two">Address Type*</h4>
                                    <div class="address-type-box">
                                        <div class="form-check">
                                            <input class="form-check-input address-type-radio" type="radio" name="address_type" id="home_address" value="home" checked>
                                            <label class="form-check-label" for="home_address">Home Address</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input address-type-radio" type="radio" name="address_type" id="office_address" value="office">
                                            <label class="form-check-label" for="office_address">Office Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ADDRESS --}}
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <input type="text" id="address" name="address" 
                                            value="{{ old('address', auth('customer')->user()?->address ?? '') }}" 
                                            class="form-control-two street-control" placeholder="Enter Your Home Address">
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
                                                    {{ auth('customer')->user()?->division_id == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-item">
                                        <h4 class="form-title-two">District</h4>
                                        <select name="district_id" id="district" class="form-control-two">
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Upazila</h4>
                                        <select name="upazila_id" id="upazila" class="form-control-two">
                                            <option value="">Select Upazila</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item">
                                        <h4 class="form-title-two">Phone*</h4>
                                        <input type="text" id="phone" name="phone" 
                                            value="{{ old('phone', auth('customer')->user()?->phone ?? '') }}" 
                                            class="form-control-two" placeholder="Enter Your Phone Number">
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
                        <div class="place-order-header">
                            <span>Product</span>
                            <span>Price</span>
                        </div>

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
                        
                        <div class="order-item item-1">
                            <span>Subtotal</span>
                            <span>৳{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="order-item item-1">
                            <span>Shipping</span>
                            <span>৳{{ number_format($shippingCost, 2) }}</span>
                        </div>
                        <div class="order-item item-1">
                            <strong>Total</strong>
                            <strong>৳{{ number_format($total, 2) }}</strong>
                        </div>
                    </div>

                    <div class="payment-option-wrap mt-3">
                        <div class="shipping-option">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <label>Cash On Delivery</label>
                        </div>
                        <p class="desc">
                            * We will process your order once we receive the confirmation.
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
            
            // =========================
            // ADDRESS TYPE TOGGLE LOGIC
            // =========================
            let isCustomer = {{ auth('customer')->check() ? 'true' : 'false' }};
            // ডাটাবেস থেকে ইউজার এর এড্রেসগুলো ভেরিয়েবলে নিয়ে আসা হলো (যদি লগইন করা থাকে)
            let savedHome = "{!! addslashes(auth('customer')->user()?->address ?? '') !!}";
            let savedOffice = "{!! addslashes(auth('customer')->user()?->office_address ?? '') !!}";

            $('.address-type-radio').on('change', function() {
                let type = $(this).val();

                // যদি ইউজার লগইন করা থাকে, তবে তার সেভ করা এড্রেস বসিয়ে দেবে
                if(isCustomer) {
                    if(type === 'home') {
                        $('#address').val(savedHome);
                    } else {
                        $('#address').val(savedOffice);
                    }
                }
                
                // প্লেসহোল্ডার চেঞ্জ হবে
                if(type === 'home') {
                    $('#address').attr('placeholder', 'Enter Your Home Address');
                } else {
                    $('#address').attr('placeholder', 'Enter Your Office Address');
                }
            });


            // =========================
            // LOCATION SELECT LOGIC
            // =========================
            $('#division, #district, #upazila').select2({
                width: '100%'
            });

            let selectedDivision = "{{ auth('customer')->user()?->division_id }}";
            let selectedDistrict = "{{ auth('customer')->user()?->district_id }}";
            let selectedUpazila = "{{ auth('customer')->user()?->upazila_id }}";

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

            // AUTO LOAD USER DATA
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