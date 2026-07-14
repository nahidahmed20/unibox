@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('content')
@push('css')
<style>
    .checkout-section {
        background-color: #f4f7fb;
        padding: 60px 0 100px 0;
        font-family: 'Poppins', sans-serif;
    }

    /* Card Containers */
    .checkout-card {
        background: #ffffff;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
    }

    .form-header {
        font-size: 22px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e5e7eb;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
        color: #4b5563;
        margin-bottom: 8px;
        display: block;
    }

    /* Modern Inputs */
    .modern-input {
        width: 100%;
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 15px;
        color: #1f2937;
        transition: all 0.3s ease;
        outline: none;
    }
    .modern-input:focus {
        border-color: #008a7a;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 138, 122, 0.1);
    }
    .modern-input::placeholder {
        color: #9ca3af;
    }

    /* Address Type Radio Buttons */
    .address-type-box {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .address-type-box .radio-card {
        flex: 1;
        position: relative;
    }
    .address-type-box input[type="radio"] {
        display: none;
    }
    .address-type-box label {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        font-weight: 500;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .address-type-box input[type="radio"]:checked + label {
        border-color: #008a7a;
        background: rgba(0, 138, 122, 0.05);
        color: #008a7a;
    }

    /* Premium Select2 Custom Design */
    .select2-container--default .select2-selection--single {
        height: 52px !important;
        background: #f9fafb !important;
        border: 1.5px solid #e5e7eb !important;
        border-radius: 12px !important;
        display: flex;
        align-items: center;
        padding: 0 15px;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #008a7a !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 4px rgba(0, 138, 122, 0.1) !important;
        outline: none;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1f2937 !important;
        padding-left: 0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 52px !important;
        right: 15px !important;
    }
    .select2-dropdown {
        border: 1px solid #e5e7eb !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
    .select2-search__field {
        border: 1.5px solid #e5e7eb !important;
        border-radius: 8px !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #008a7a !important;
    }

    /* Order Summary Box */
    .order-summary-wrapper {
        background: #f9fafb;
        border-radius: 16px;
        padding: 25px;
        border: 1px solid #e5e7eb;
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 15px;
        margin-bottom: 15px;
        border-bottom: 1px dashed #d1d5db;
    }
    .order-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .order-item .order-left {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .order-img {
        width: 65px;
        height: 65px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    .order-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .order-right .title {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    .order-right small {
        color: #6b7280;
        font-size: 13px;
    }
    .order-price {
        font-weight: 600;
        color: #111827;
    }
    .totals-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        font-size: 15px;
        color: #4b5563;
    }
    .totals-row.grand-total {
        font-size: 18px;
        font-weight: 700;
        color: #008a7a;
        border-top: 1px solid #e5e7eb;
        margin-top: 10px;
        padding-top: 15px;
    }

    /* Payment Method & Button */
    .payment-option-wrap {
        margin-top: 25px;
        background: #fff;
        padding: 15px;
        border-radius: 12px;
        border: 1.5px solid #008a7a;
    }
    .payment-option-wrap label {
        font-weight: 600;
        color: #1f2937;
        margin-left: 8px;
    }
    .order-btn {
        background: linear-gradient(135deg, #008a7a 0%, #006b5e 100%);
        color: white;
        width: 100%;
        padding: 16px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 138, 122, 0.2);
        margin-top: 20px;
    }
    .order-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 138, 122, 0.3);
    }
</style>
@endpush

@php
    $cart = session('cart', []);
    $shippingCost = session('shipping_cost', 0);

    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
    $total = $subtotal + $shippingCost;

    // --- DATA LAYER এর জন্য আইটেম প্রসেসিং ---
    $dataLayerItems = [];
    foreach($cart as $item) {
        $variantParts = [];
        if(!empty($item['size'])) $variantParts[] = $item['size'];
        if(!empty($item['color'])) $variantParts[] = $item['color'];
        
        $dataLayerItems[] = [
            'item_id' => $item['product_id'] ?? '',
            'item_name' => $item['name'] ?? '',
            'price' => (float) ($item['price'] ?? 0),
            'quantity' => (int) ($item['quantity'] ?? 1),
            'item_variant' => implode(' - ', $variantParts)
        ];
    }
@endphp

<section class="checkout-section">
    <div class="container">
        <form method="POST" action="{{ route('checkout.placeOrder') }}">
            @csrf
            <div class="row">
                
                {{-- LEFT SIDE - BILLING FORM --}}
                <div class="col-lg-7 col-md-12">
                    <div class="checkout-card">
                        <h3 class="form-header"><i class="fas fa-map-marker-alt" style="color:#008a7a;"></i> Billing Details</h3>
                        
                        <div class="row">
                            {{-- NAME --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" 
                                    value="{{ old('name', auth('customer')->user()?->name ?? '') }}" 
                                    class="modern-input" placeholder="Enter Your Full Name">
                                @error('name') <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $message }}</span> @enderror
                            </div>

                            {{-- ADDRESS TYPE --}}
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Address Type *</label>
                                <div class="address-type-box">
                                    <div class="radio-card">
                                        <input class="address-type-radio" type="radio" name="address_type" id="home_address" value="home" checked>
                                        <label for="home_address"><i class="fas fa-home mr-2"></i> Home</label>
                                    </div>
                                    <div class="radio-card">
                                        <input class="address-type-radio" type="radio" name="address_type" id="office_address" value="office">
                                        <label for="office_address"><i class="fas fa-building mr-2"></i> Office</label>
                                    </div>
                                </div>
                            </div>

                            {{-- ADDRESS --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Street Address *</label>
                                <input type="text" id="address" name="address" 
                                    value="{{ old('address', auth('customer')->user()?->address ?? '') }}" 
                                    class="modern-input" placeholder="Enter Your Home Address">
                                @error('address') <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $message }}</span> @enderror
                            </div>

                            {{-- LOCATION DROPDOWNS --}}
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Division *</label>
                                <select name="division_id" id="division" class="modern-input select2-location">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ auth('customer')->user()?->division_id == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">District *</label>
                                <select name="district_id" id="district" class="modern-input select2-location">
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-4">
                                <label class="form-label">Upazila *</label>
                                <select name="upazila_id" id="upazila" class="modern-input select2-location">
                                    <option value="">Select Upazila</option>
                                </select>
                            </div>

                            {{-- PHONE --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Phone Number *</label>
                                <input type="text" id="phone" name="phone" 
                                    value="{{ old('phone', auth('customer')->user()?->phone ?? '') }}" 
                                    class="modern-input" placeholder="Enter Your Phone Number">
                                @error('phone') <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $message }}</span> @enderror
                            </div>

                            {{-- ORDER NOTES --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Order Notes (Optional)</label>
                                <textarea id="message" name="message" rows="3" class="modern-input" placeholder="Notes about your order, e.g. special notes for delivery.">{{ old('message') }}</textarea>
                                @error('message') <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE - ORDER SUMMARY --}}
                <div class="col-lg-5 col-md-12">
                    <div class="checkout-card">
                        <h3 class="form-header"><i class="fas fa-shopping-bag" style="color:#008a7a;"></i> Order Summary</h3>
                        
                        <div class="order-summary-wrapper">
                            @forelse($cart as $item)
                                <div class="order-item">
                                    <div class="order-left">
                                        <div class="order-img">
                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}">
                                        </div>
                                        <div class="order-right">
                                            <h4 class="title">{{ Str::limit($item['name'], 25) }}</h4>
                                            <small>Qty: {{ $item['quantity'] }} 
                                                @if(!empty($item['size'])) | Size: {{ $item['size'] }} @endif
                                                @if(!empty($item['color_id'])) | Color: {{ $item['color'] }} @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="order-price">৳{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <p class="text-muted mb-0">Your cart is empty</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-4 px-2">
                            <div class="totals-row">
                                <span>Subtotal</span>
                                <span style="font-weight: 600; color: #111;">৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="totals-row">
                                <span>Shipping Cost</span>
                                <span style="font-weight: 600; color: #111;">৳{{ number_format($shippingCost, 2) }}</span>
                            </div>
                            <div class="totals-row grand-total">
                                <span>Total Amount</span>
                                <span>৳{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <div class="payment-option-wrap">
                            <div style="display: flex; align-items: center;">
                                <input type="radio" name="payment_method" value="cod" id="cod" checked style="width: 18px; height: 18px; accent-color: #008a7a;">
                                <label for="cod" style="margin-bottom: 0;">Cash On Delivery (COD)</label>
                            </div>
                            <p class="text-muted mt-2 mb-0" style="font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Pay with cash upon delivery.
                            </p>
                        </div>

                        <div class="terms-condition-wrap mt-3 px-2">
                            <div style="display: flex; align-items: flex-start; gap: 8px;">
                                <input type="checkbox" name="terms_agreed" id="terms" required style="margin-top: 5px; width: 16px; height: 16px; accent-color: #008a7a; cursor: pointer;">
                                <label for="terms" style="margin-bottom: 0; font-size: 14px; cursor: pointer; color: #555;">
                                    I have read and agree to the website 
                                    <a href="#" style="color: #008a7a; text-decoration: underline;">terms and conditions</a>. <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="order-btn">
                            Place Order <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@endsection

@push('javascript')
    {{-- =========================
         DATA LAYER INTEGRATION 
         ========================= --}}
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: "begin_checkout",
            ecommerce: {
                currency: "BDT",
                value: {{ (float) $total }},
                items: @json($dataLayerItems)
            }
        });
    </script>
    {{-- ========================= --}}

    <script>
        $(document).ready(function () {
            
            // =========================
            // ADDRESS TYPE TOGGLE LOGIC
            // =========================
            let isCustomer = {{ auth('customer')->check() ? 'true' : 'false' }};
            let savedHome = "{!! addslashes(auth('customer')->user()?->address ?? '') !!}";
            let savedOffice = "{!! addslashes(auth('customer')->user()?->office_address ?? '') !!}";

            $('.address-type-radio').on('change', function() {
                let type = $(this).val();

                if(isCustomer) {
                    if(type === 'home') {
                        $('#address').val(savedHome);
                    } else {
                        $('#address').val(savedOffice);
                    }
                }
                
                if(type === 'home') {
                    $('#address').attr('placeholder', 'Enter Your Home Address');
                } else {
                    $('#address').attr('placeholder', 'Enter Your Office Address');
                }
            });


            // =========================
            // LOCATION SELECT LOGIC
            // =========================
            $('.select2-location').select2({
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