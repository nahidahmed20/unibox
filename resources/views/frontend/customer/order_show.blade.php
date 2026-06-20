@extends('frontend.layouts.app')

@section('title', 'Order Details')

@section('content')

<section class="customer-dashboard pt-50 pb-100">
    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-3">
                @include('frontend.customer.partials.sidebar')
            </div>
            {{-- Content --}}
            <div class="col-lg-9">
                {{-- Order Header --}}
                <div class="dashboard-card mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Order Details</h4>
                            <p class="mb-0">
                                Order ID: <strong>{{ $order->order_number }}</strong>
                            </p>
                        </div>
                        <div>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">Processing</span>
                            @elseif($order->status == 'delivered')
                                <span class="badge bg-success">Delivered</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Order Info --}}
                <div class="dashboard-card mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Info</h5>
                            <p>
                                <strong>Name:</strong> {{ $order->full_name }} <br>
                                <strong>Phone:</strong> {{ $order->phone }} <br>
                                <strong>Address:</strong> {{ $order->address }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5>Order Info</h5>
                            <p>
                                <strong>Date:</strong> {{ $order->created_at->format('d M Y') }} <br>
                                <strong>Payment:</strong> {{ ucfirst($order->payment_method) }} <br>
                                <strong>Status:</strong> {{ ucfirst($order->status) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="dashboard-card mb-4">
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product_name }}
                                        </td>
                                        <td>
                                            ৳ {{ number_format($item->price, 2) }}
                                        </td>
                                        <td>
                                            {{ $item->quantity }}
                                        </td>
                                        <td>
                                            ৳ {{ number_format($item->total, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary --}}
                <div class="dashboard-card">
                    <div class="row">
                        <div class="col-md-6">
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th>Subtotal</th>
                                    <td>৳ {{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Shipping</th>
                                    <td>৳ {{ number_format($order->shipping, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>
                                        <strong>৳ {{ number_format($order->total, 2) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection