@extends('frontend.layouts.app')
@section('title', 'Order Success | Unibox')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .success-wrapper {
        background: #f8fafc;
        min-height: 100vh;
        padding: 60px 15px;
        font-family: 'Inter', sans-serif;
    }

    .success-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.06);
        max-width: 850px;
        margin: 0 auto;
    }

    /* TOP SECTION */
    .success-top {
        padding: 50px 30px 40px;
        text-align: center;
        background: linear-gradient(135deg, #008a7a 0%, #00665a 100%);
        color: #fff;
        position: relative;
    }

    .success-check {
        width: 80px;
        height: 80px;
        background: #fff;
        color: #008a7a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .success-check svg { width: 40px; height: 40px; }

    .success-top h2 { font-size: 30px; font-weight: 700; margin-bottom: 12px; color: #fff; font-family: "Noto Sans Bengali", sans-serif; }
    .success-top p { color: rgba(255,255,255,0.9); margin-bottom: 0; font-size: 16px; font-family: "Noto Sans Bengali", sans-serif; }

    .order-number-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 25px;
        padding: 10px 24px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 50px;
        font-size: 15px;
        color: #fff;
        backdrop-filter: blur(5px);
    }
    .order-number-badge strong { font-size: 17px; letter-spacing: 0.5px; }

    /* BODY SECTION */
    .success-body { padding: 40px; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .info-box {
        background: #fdfdfd;
        border: 1px solid #f1f3f5;
        border-radius: 12px;
        padding: 20px;
    }

    .info-box label {
        display: block; font-size: 12px; text-transform: uppercase;
        letter-spacing: 0.5px; color: #64748b; margin-bottom: 6px; font-weight: 600;
    }

    .info-box span { font-size: 15px; font-weight: 700; color: #0f172a; }

    /* MODERN PRODUCT LIST */
    .section-title { font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 20px; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }

    .product-list {
        display: flex; flex-direction: column; gap: 15px; margin-bottom: 35px;
    }

    .product-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 15px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
    }

    .product-info-wrapper { display: flex; align-items: center; gap: 15px; }

    .product-img {
        width: 65px; height: 65px; border-radius: 8px; border: 1px solid #f1f5f9;
        object-fit: contain; background: #fff; padding: 2px;
    }

    .product-name { font-size: 15px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
    
    .variation-tag {
        background: #f1f5f9; color: #475569; padding: 3px 10px;
        border-radius: 6px; font-size: 12px; font-weight: 500; display: inline-block; margin-right: 5px;
    }

    .product-price-wrapper { text-align: right; }
    .product-price { font-size: 16px; font-weight: 700; color: #0f172a; }
    .product-qty { font-size: 13px; color: #64748b; font-weight: 500; }

    /* SUMMARY CARD */
    .summary-card {
        background: #f8fafc; border-radius: 12px; padding: 25px; border: 1px solid #e2e8f0;
    }

    .summary-item {
        display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; color: #475569;
    }

    .summary-item:last-child {
        margin-bottom: 0; padding-top: 15px; border-top: 2px dashed #cbd5e1;
        font-size: 20px; font-weight: 700; color: #008a7a;
    }

    /* ACTIONS */
    .success-actions { display: flex; gap: 15px; justify-content: center; margin-top: 40px; }

    .success-btn {
        padding: 14px 28px; border-radius: 8px; font-weight: 600; text-decoration: none;
        transition: all 0.3s; font-size: 15px; display: inline-flex; align-items: center; gap: 8px;
    }

    .btn-primary-custom { background: #008a7a; color: #fff; border: 1px solid #008a7a; }
    .btn-primary-custom:hover { background: #007063; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0, 138, 122, 0.2); }

    .btn-outline-custom { background: #fff; color: #008a7a; border: 1px solid #008a7a; }
    .btn-outline-custom:hover { background: #f0fdfa; color: #008a7a; transform: translateY(-2px); }

    @media(max-width: 768px) {
        .info-grid { grid-template-columns: repeat(2, 1fr); }
        .success-body { padding: 25px; }
        .success-top h2 { font-size: 24px; }
    }

    @media(max-width: 575px) {
        .info-grid { grid-template-columns: 1fr; gap: 12px; }
        .product-item { flex-direction: column; align-items: flex-start; gap: 15px; }
        .product-price-wrapper { text-align: left; display: flex; width: 100%; justify-content: space-between; align-items: center; border-top: 1px dashed #e2e8f0; padding-top: 12px; }
        .success-actions { flex-direction: column; }
        .success-btn { width: 100%; justify-content: center; }
    }
</style>

<div class="success-wrapper">
    <div class="container">
        <div class="success-card">
            
            {{-- TOP SECTION --}}
            <div class="success-top">
                <div class="success-check">
                    <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2>অর্ডার সফলভাবে সম্পন্ন হয়েছে</h2>
                <p>ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে। খুব শীঘ্রই আমাদের টিম আপনার সাথে যোগাযোগ করবে।</p>
                <div class="order-number-badge">
                    Order No: <strong>{{ $order->order_number }}</strong>
                </div>
            </div>

            {{-- BODY SECTION --}}
            <div class="success-body">
                
                {{-- INFO GRID --}}
                <div class="info-grid">
                    <div class="info-box">
                        <label>Date</label>
                        <span>{{ $order->created_at->format('d M, Y') }}</span>
                    </div>
                    <div class="info-box">
                        <label>Total Amount</label>
                        <span>৳{{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="info-box">
                        <label>Payment Method</label>
                        <span>{{ strtolower($order->payment_method) == 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</span>
                    </div>
                    <div class="info-box">
                        <label>Order Status</label>
                        <span style="color: #f59e0b;">● {{ ucfirst($order->status) }}</span>
                    </div>
                </div>

                {{-- ORDER ITEMS --}}
                <h4 class="section-title">Order Items</h4>
                <div class="product-list">
                    @foreach($order->items as $item)
                        <div class="product-item">
                            <div class="product-info-wrapper">
                                <img src="{{ asset($item->product->image ?? 'default.png') }}" class="product-img" alt="Product">
                                <div>
                                    <div class="product-name">{{ $item->product_name ?? ($item->product->name ?? 'Unknown Product') }}</div>
                                    <div>
                                        @if(!empty($item->color->name))
                                            <span class="variation-tag">Color: {{ $item->color->name }}</span>
                                        @endif
                                        @if(!empty($item->size->name))
                                            <span class="variation-tag">Size: {{ $item->size->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="product-price-wrapper">
                                <div class="product-qty">Qty: {{ $item->quantity }}</div>
                                <div class="product-price">৳{{ number_format($item->total, 2) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SUMMARY --}}
                <div class="summary-card">
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping Cost</span>
                        <span>৳{{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="summary-item">
                        <span>Payment Status</span>
                        <span>{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    <div class="summary-item">
                        <span>Grand Total</span>
                        <span>৳{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="success-actions">
                    <a href="{{ url('/') }}" class="success-btn btn-primary-custom">
                        <i class="fa-solid fa-cart-shopping"></i> Continue Shopping
                    </a>
                    <a href="{{ url('/shop') }}" class="success-btn btn-outline-custom">
                        Explore More Products
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection