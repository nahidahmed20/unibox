@extends('frontend.layouts.app')

@section('title', 'Customer Dashboard | Unibox')

@section('content')
<style>
    /* ===== PAGE WRAPPER ===== */
    .customer-dashboard {
        background: #f5f7fb;
    }

    /* ===== CARD ===== */
    .dashboard-card {
        background: #fff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        margin-bottom: 20px;
        transition: 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
    }

    /* ===== TITLE ===== */
    .dashboard-card h3 {
        font-size: 22px;
        font-weight: 600;
        color: #2c3e50;
    }

    .dashboard-card p {
        font-size: 14px;
        color: #7f8c8d;
    }

    /* ===== FORM ===== */
    label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #34495e;
        font-size: 14px;
    }

    .form-control {
        border-radius: 2px;
        padding: 10px 14px;
        border: 1px solid #e0e0e0;
        transition: 0.3s;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #000;
        box-shadow: 0 0 0 0.1rem rgba(0, 0, 0, 0.15);
        outline: none;
    }

    /* ===== BUTTON ===== */
    .btn-primary {
        background: #E53E3E;
        border: none;
        padding: 10px 22px;
        border-radius: 3px;
        font-weight: 500;
        transition: 0.3s;
        
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        background: #E53E3E;
    }

    /* ===== IMAGE ===== */
    .profile-img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #eee;
        display: block;
        margin-top: 10px;
    }


    /* Select2 Container */
    .custom-select {
        height: 48px;
        border: 1px solid #dcdcdc;
        border-radius: 2px;
        padding: 0 15px;
        font-size: 14px;
        color: #333;
        background-color: #fff;
        transition: all .3s ease;
    }

    .custom-select:focus {
        border-color: #E53E3E;
        box-shadow: 0 0 0 0.15rem rgba(229, 62, 62, 0.15);
    }

    .custom-select:hover {
        border-color: #bdbdbd;
    }

    .form-label {
        margin-bottom: 6px;
        font-size: 14px;
        color: #555;
    }
</style>

<section class="page-header">
    <div class="container">
        <div class="page-header-content">
            <h1 class="title">Customer Dashboard</h1>
            <h4 class="sub-title">
                <span class="home">
                    <a href="{{ route('home') }}">
                        <span>Home</span>
                    </a>
                </span>
                <span class="icon">
                    <i class="fa-solid fa-angle-right"></i>
                </span>
                <span class="inner">
                    <span>Dashboard</span>
                </span>
            </h4>
        </div>
    </div>
</section>

<section class="customer-dashboard pt-50 pb-100">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                @include('frontend.customer.partials.sidebar')
            </div>

            <!-- Content -->
            <div class="col-lg-9" id="main-content">
                <div class="dashboard-card mb-4">
                    <h3 class="mb-2">
                        Welcome,
                        {{ auth('customer')->user()->name }}
                    </h3>
                    <p class="mb-0">
                        From your dashboard you can manage orders,
                        addresses and account information.
                    </p>
                </div>

                <!-- Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="dashboard-card stats-card">
                            <div class="stats-icon">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </div>

                            <div>
                                <h3>{{ $totalOrders }}</h3>
                                <p>Total Orders</p>
                            </div>
                        </div>
                    </div>

                    <!-- Processing -->
                    <div class="col-md-4">
                        <div class="dashboard-card stats-card">
                            <div class="stats-icon processing">
                                <i class="fa-solid fa-truck"></i>
                            </div>

                            <div>
                                <h3>{{ $processingOrders }}</h3>
                                <p>Processing</p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div class="col-md-4">
                        <div class="dashboard-card stats-card">
                            <div class="stats-icon complete">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>

                            <div>
                                <h3>{{ $completedOrders }}</h3>
                                <p>Completed</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Recent Orders -->
                <div class="dashboard-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Recent Orders</h3>
  
                        <a href="{{ route('customer.orders') }}"class="menu-link view-all-btn {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                                View All
                            </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table dashboard-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($orders as $order)
                                    <tr>
                                        <td>
                                            #ORD-{{ $order->id }}
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($order->status) {
                                                    'pending' => 'bg-warning',
                                                    'processing' => 'bg-info',
                                                    'delivered' => 'bg-success',
                                                    'cancelled' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>

                                        <td>
                                            ৳ {{ number_format($order->total, 2) }}
                                        </td>

                                        <td>
                                            <a href="{{ route('customer.order.show', $order->order_number) }}"class="order-btn">
                                                View
                                            </a>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
@push('javascript')
<script>
    function initProfilePage() {

        if (!$('#division').length) return;

        let selectedDivision = $('#division').val();
        let selectedDistrict = $('#selected_district').val();
        let selectedUpazila = $('#selected_upazila').val();

        function loadDistricts(divisionId, callback = null) {
            $.get('/get-districts/' + divisionId, function (data) {
                let html = '<option value="">Select District</option>';
                $.each(data, function (i, v) {
                    let selected = (String(v.id) === String(selectedDistrict))? 'selected': '';
                    html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
                });
                $('#district').html(html);
                if (selectedDistrict) {
                    $('#district').val(selectedDistrict);
                }
                if (callback) {
                    callback();
                }
            });
        }

        function loadUpazilas(districtId) {
            $.get('/get-upazilas/' + districtId, function (data) {
                let html = '<option value="">Select Upazila</option>';
                $.each(data, function (i, v) {
                    let selected = (String(v.id) === String(selectedUpazila))? 'selected': '';
                    html += `<option value="${v.id}" ${selected}>${v.name}</option>`;
                });
                $('#upazila').html(html);
                if (selectedUpazila) {
                    $('#upazila').val(selectedUpazila);
                }
            });
        }

        // Edit Mode
        if (selectedDivision) {
            loadDistricts(selectedDivision, function () {
                if (selectedDistrict) {
                    loadUpazilas(selectedDistrict);
                }
            });
        }

        // Division Change
        $('#division').off('change').on('change', function () {

            let divisionId = $(this).val();

            $('#district')
                .html('<option value="">Select District</option>')
                .trigger('change');

            $('#upazila')
                .html('<option value="">Select Upazila</option>')
                .trigger('change');

            if (!divisionId) return;

            $.ajax({
                url: '/get-districts/' + divisionId,
                type: 'GET',
                success: function (data) {

                    let html = '<option value="">Select District</option>';

                    $.each(data, function (i, v) {
                        html += `<option value="${v.id}">${v.name}</option>`;
                    });

                    $('#district').html(html);
                }
            });
        });

        // District Change
        $('#district').off('change').on('change', function () {

            let districtId = $(this).val();

            $('#upazila')
                .html('<option value="">Select Upazila</option>')
                .trigger('change');

            if (!districtId) return;

            $.ajax({
                url: '/get-upazilas/' + districtId,
                type: 'GET',
                success: function (data) {

                    let html = '<option value="">Select Upazila</option>';

                    $.each(data, function (i, v) {
                        html += `<option value="${v.id}">${v.name}</option>`;
                    });

                    $('#upazila').html(html);
                }
            });
        });

    }

    // AJAX Menu Load
    $(document).on('click', '.menu-link', function (e) {

        e.preventDefault();

        let url = $(this).attr('href');

        $('#main-content').load(url + ' #main-content > *', function () {
            initProfilePage();
        });

    });

    // First Load
    $(document).ready(function () {
        initProfilePage();
    });
</script>

@endpush