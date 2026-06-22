@extends('backend.layouts.app')

@section('title', 'Edit Order')

@push('styles')
    <style>
        .shopify-wrapper {
            background: #f6f7fb;
            padding: 20px;
            min-height: 100vh;
        }

        .shopify-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
            margin-bottom: 20px; /* Added spacing between cards */
        }

        .shopify-header {
            padding: 18px 22px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .shopify-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .shopify-body {
            padding: 22px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .order-table {
            border-radius: 12px;
            overflow: hidden;
        }

        .order-table thead {
            background: #f3f4f6;
        }

        .order-summary {
            position: sticky;
            top: 20px;
        }

        .btn-primary-shopify {
            background: #111827;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
        }

        .btn-primary-shopify:hover {
            background: #000;
        }

        .btn-light-shopify {
            border-radius: 10px;
            padding: 10px 18px;
        }
    </style>
@endpush

@section('content')
<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#212b36;">
                    Edit Order
                </h3>
                <small class="text-muted">
                    Manage and update order information
                </small>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"
                           class="text-decoration-none text-muted">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('orders.index') }}"
                           class="text-decoration-none text-muted">
                            Orders
                        </a>
                    </li>

                    <li class="breadcrumb-item active fw-bold text-dark">
                        Edit Order
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

    <div class="shopify-wrapper">

        <div class="container-fluid">
            <div class="row g-4">

                {{-- LEFT CONTENT --}}
                <div class="col-lg-8">

                    <form id="editOrderForm" action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- CARD 1: Customer Information --}}
                        <div class="shopify-card">
                            <div class="shopify-header">
                                <h3 class="shopify-title">Order #{{ $order->order_number }}</h3>
                            </div>
                            <div class="shopify-body">
                                <div class="section-title">Customer Information</div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <input type="text" name="customer_name" class="form-control"
                                            value="{{ $order->full_name }}" placeholder="Customer Name">
                                    </div>

                                    <div class="col-md-4">
                                        <input type="text" name="customer_phone" class="form-control"
                                            value="{{ $order->phone }}" placeholder="Phone">
                                    </div>

                                    <div class="col-md-4">
                                        <textarea name="customer_address" class="form-control" rows="1" placeholder="Address">{{ $order->address }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 2: Shipping & Courier (NEW SECTION) --}}
                        <div class="shopify-card">
                            <div class="shopify-body">
                                <div class="section-title">Shipping & Courier</div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Select Courier</label>
                                        <select name="courier_id" class="form-select">
                                            <option value="">Select Courier...</option>
                                            @foreach($couriers as $courier)
                                                <option value="{{ $courier->id }}" {{ $order->courier_id == $courier->id ? 'selected' : '' }}>
                                                    {{ $courier->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Tracking Number</label>
                                        <input type="text" name="tracking_number" class="form-control"
                                            value="{{ $order->tracking_number }}" placeholder="e.g. STEAD12345">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label text-muted small mb-1">Shipping Cost (৳)</label>
                                        <input type="number" name="shipping" class="form-control"
                                            value="{{ $order->shipping }}" placeholder="Shipping Cost">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CARD 3: Order Status & Items --}}
                        <div class="shopify-card">
                            <div class="shopify-body">
                                <div class="section-title">Order Status</div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Payment Status</label>
                                        <select name="payment_status" class="form-select">
                                            <option value="due" {{ $order->payment_status == 'due' ? 'selected' : '' }}>Due</option>
                                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label text-muted small mb-1">Order Status</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="on-the-way" {{ $order->status == 'on-the-way' ? 'selected' : '' }}>On The Way</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="section-title mt-4">Order Items</div>
                                <div class="table-responsive order-table">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                                        <small class="text-muted">
                                                            @if($item->color) Color: {{ $item->color }} | @endif
                                                            @if($item->size) Size: {{ $item->size }} @endif
                                                        </small>
                                                    </td>
                                                    <td width="100">
                                                        <input type="number" name="items[{{ $item->id }}][quantity]"
                                                            class="form-control" value="{{ $item->quantity }}" min="1">
                                                    </td>
                                                    <td width="120">
                                                        <input type="number" name="items[{{ $item->id }}][price]"
                                                            class="form-control" value="{{ $item->price }}" min="0">
                                                    </td>
                                                    <td>৳{{ number_format($item->price * $item->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                {{-- RIGHT SIDEBAR --}}
                <div class="col-lg-4">

                    <div class="shopify-card order-summary">

                        <div class="shopify-header">
                            <h3 class="shopify-title">Summary</h3>
                        </div>

                        <div class="shopify-body">

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <strong>৳{{ number_format($order->subtotal, 2) }}</strong>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping</span>
                                <strong>৳{{ number_format($order->shipping, 2) }}</strong>
                            </div>

                            {{-- Show Return Charge if it exists --}}
                            @if($order->return_charge > 0)
                                <div class="d-flex justify-content-between mb-2 text-danger">
                                    <span>Return Penalty</span>
                                    <strong>- ৳{{ number_format($order->return_charge, 2) }}</strong>
                                </div>
                            @endif

                            <hr>

                            <div class="d-flex justify-content-between fs-5">
                                <span><b>Total</b></span>
                                <strong>৳{{ number_format($order->total, 2) }}</strong>
                            </div>

                            <div class="mt-4 d-grid gap-2">
                                <button type="submit" form="editOrderForm" class="btn btn-primary-shopify text-white">
                                    Update Order
                                </button>

                                <a href="{{ route('orders.index') }}" class="btn btn-light-shopify border">
                                    Cancel
                                </a>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection