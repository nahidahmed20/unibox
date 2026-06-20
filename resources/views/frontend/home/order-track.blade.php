@extends('frontend.layouts.app')
@section('title', 'Track Order')

@section('content')
<section class="track-order-section pt-100 pb-100" style="background-color: #f4f7f6;">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-7 text-center">
                <h2 class="fw-bold mb-3" style="color: #2b3445; font-size: 32px;">Track Your Parcel</h2>
                <p class="text-muted mb-4">Enter your order ID to get real-time updates on your shipment.</p>
                
                @if(session('error'))
                    <div class="alert alert-danger rounded-pill border-0 mb-3">{{ session('error') }}</div>
                @endif
                
                <form action="{{ route('track.order') }}" method="GET" class="tracking-search-form">
                    <div class="input-group p-1 bg-white shadow-sm rounded-pill">
                        <span class="input-group-text bg-transparent border-0 ps-4 text-muted">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text" name="order_id" value="{{ request('order_id') }}" class="form-control border-0 shadow-none bg-transparent" placeholder="Order ID (e.g. #ORD-12345)" required>
                        <button type="submit" class="btn rounded-pill px-4 fw-bold" style="background-color: #E53E3E; color: white;">Track Order</button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($order))
        @php
            $status = $order->status; // pending, accepted, on-the-way, completed, return, cancelled
            
            // Progress width bar setting
            $progressWidth = '0%';
            if($status == 'pending') $progressWidth = '0%';
            if($status == 'accepted') $progressWidth = '33%';
            if($status == 'on-the-way') $progressWidth = '66%';
            if($status == 'completed') $progressWidth = '100%';
            
            // Dynamic labels and badges
            $statusColors = [
                'pending'    => '#f59e0b',
                'accepted'   => '#3b82f6',
                'on-the-way' => '#6366f1',
                'return'     => '#6b7280',
                'completed'  => '#10b981',
                'cancelled'  => '#ef4444',
            ];
            $currentColor = $statusColors[$status] ?? '#2b3445';
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 px-md-5 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <p class="text-muted mb-1 fs-6">Order ID: <strong class="text-dark">#{{ $order->order_number }}</strong></p>
                            <p class="text-muted mb-0 fs-6">Placed on: <strong class="text-dark">{{ $order->created_at->format('d M, Y') }}</strong></p>
                        </div>
                        <div class="text-end">
                            <span class="badge rounded-pill px-3 py-2 fs-6" style="background-color: {{ $currentColor }}20; color: {{ $currentColor }}; text-transform: uppercase;">
                                <i class="fa-solid fa-circle-info me-1"></i> {{ str_replace('-', ' ', $status) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        
                        @if($status == 'cancelled')
                            <div class="alert text-center border-0 p-4" style="background-color: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 12px;">
                                <i class="fa-solid fa-circle-xmark fs-2 mb-2 d-block"></i>
                                <h5 class="fw-bold">This order has been cancelled!</h5>
                                <p class="mb-0 small">If you have any queries, please contact our support team.</p>
                            </div>
                        @elseif($status == 'return')
                            <div class="alert text-center border-0 p-4" style="background-color: rgba(107, 114, 128, 0.1); color: #6b7280; border-radius: 12px;">
                                <i class="fa-solid fa-rotate-left fs-2 mb-2 d-block"></i>
                                <h5 class="fw-bold">This order has been returned!</h5>
                                <p class="mb-0 small">The product has been returned to our warehouse successfully.</p>
                            </div>
                        @else
                            <div class="modern-tracker">
                                <div class="tracker-line"></div>
                                <div class="tracker-progress" style="width: {{ $progressWidth }}; background-color: {{ $currentColor }};"></div>

                                <div class="tracker-step {{ in_array($status, ['pending', 'accepted', 'on-the-way', 'completed']) ? 'completed' : '' }} {{ $status == 'pending' ? 'current' : '' }}">
                                    <div class="icon-box" style="background-color: {{ in_array($status, ['pending', 'accepted', 'on-the-way', 'completed']) ? '#f59e0b' : '#e2e8f0' }}; color: #fff;">
                                        <i class="fa-solid {{ $status == 'pending' ? 'fa-spinner fa-spin' : 'fa-check' }}"></i>
                                    </div>
                                    <h6 class="step-title">Pending</h6>
                                </div>

                                <div class="tracker-step {{ in_array($status, ['accepted', 'on-the-way', 'completed']) ? 'completed' : '' }} {{ $status == 'accepted' ? 'current' : '' }}">
                                    <div class="icon-box" style="background-color: {{ in_array($status, ['accepted', 'on-the-way', 'completed']) ? '#3b82f6' : '#e2e8f0' }}; color: #fff;">
                                        <i class="fa-solid {{ $status == 'accepted' ? 'fa-spinner fa-spin' : 'fa-check' }}"></i>
                                    </div>
                                    <h6 class="step-title">Accepted</h6>
                                </div>

                                <div class="tracker-step {{ in_array($status, ['on-the-way', 'completed']) ? 'completed' : '' }} {{ $status == 'on-the-way' ? 'current' : '' }}">
                                    <div class="icon-box {{ $status == 'on-the-way' ? 'pulse' : '' }}" style="background-color: {{ in_array($status, ['on-the-way', 'completed']) ? '#6366f1' : '#e2e8f0' }}; color: #fff;">
                                        <i class="fa-solid fa-truck-fast"></i>
                                    </div>
                                    <h6 class="step-title">On the way</h6>
                                </div>

                                <div class="tracker-step {{ $status == 'completed' ? 'completed current' : '' }}">
                                    <div class="icon-box" style="background-color: {{ $status == 'completed' ? '#10b981' : '#e2e8f0' }}; color: #fff;">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <h6 class="step-title">Completed</h6>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
    /* Tracking Form Focus & Base Line Styles */
    .tracking-search-form .form-control:focus { box-shadow: none; }
    .modern-tracker { display: flex; justify-content: space-between; position: relative; margin-top: 20px; margin-bottom: 20px; }
    .tracker-line { position: absolute; top: 25px; left: 5%; width: 90%; height: 4px; background-color: #e2e8f0; z-index: 1; border-radius: 4px; }
    .tracker-progress { position: absolute; top: 25px; left: 5%; height: 4px; z-index: 2; border-radius: 4px; transition: width 1s ease; }
    .tracker-step { position: relative; z-index: 3; text-align: center; width: 25%; }
    .icon-box { width: 54px; height: 54px; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; font-size: 18px; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: all 0.3s ease; }
    .step-title { font-weight: 700; color: #2b3445; font-size: 15px; }
    
    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4); }
        70% { box-shadow: 0 0 0 15px rgba(99, 102, 241, 0); }
        100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
    }

    @media (max-width: 767px) {
        .modern-tracker { flex-direction: column; align-items: flex-start; padding-left: 30px; }
        .tracker-line, .tracker-progress { width: 4px; height: 100%; left: 55px; top: 0; }
        .tracker-step { width: 100%; display: flex; align-items: center; text-align: left; margin-bottom: 40px; }
        .icon-box { margin: 0 20px 0 0; flex-shrink: 0; }
        .tracker-step:last-child { margin-bottom: 0; }
    }
</style>
@endsection