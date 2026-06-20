@extends('frontend.layouts.app')
@section('title', 'Order Success | Unibox')

@section('content')

<style>
    .success-wrapper{
        background:#f8fafc;
        min-height:100vh;
        padding:80px 0;
    }

    .success-card{
        background:#fff;
        border-radius:20px;
        overflow:hidden;
        box-shadow:0 20px 50px rgba(0,0,0,.08);
    }

    .success-top{
        padding:55px 30px;
        text-align:center;
        background:linear-gradient(135deg,#E53E3E,#C53030);
        color:#fff;
        position:relative;
    }

    .success-check{
        width:90px;
        height:90px;
        background:#fff;
        color:#E53E3E;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 25px;
        box-shadow:0 10px 30px rgba(0,0,0,.15);
    }

    .success-check svg{
        width:42px;
        height:42px;
    }

    .success-top h2{
        font-size:34px;
        font-weight:700;
        margin-bottom:10px;
        color:#fff;
    }

    .success-top p{
        color:rgba(255,255,255,.9);
        margin-bottom:0;
        font-size:15px;
    }

    .order-number{
        display:inline-block;
        margin-top:20px;
        padding:10px 20px;
        background:rgba(255,255,255,.15);
        border:1px solid rgba(255,255,255,.2);
        border-radius:50px;
        font-size:14px;
        color:#fff;
    }

    .success-body{
        padding:40px;
    }

    .info-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:20px;
        margin-bottom:40px;
    }

    .info-box{
        background:#fff;
        border:1px solid #f1f1f1;
        border-radius:14px;
        padding:20px;
        transition:.3s;
        box-shadow:0 5px 20px rgba(0,0,0,.03);
    }

    .info-box:hover{
        transform:translateY(-4px);
        border-color:#E53E3E;
    }

    .info-box label{
        display:block;
        font-size:12px;
        text-transform:uppercase;
        letter-spacing:1px;
        color:#888;
        margin-bottom:8px;
    }

    .info-box span{
        font-size:16px;
        font-weight:700;
        color:#222;
    }

    .order-table{
        width:100%;
        border-collapse:collapse;
    }

    .order-table thead th{
        background:#E53E3E;
        color:#fff;
        padding:16px;
        font-size:14px;
        font-weight:600;
    }

    .order-table tbody td{
        padding:18px 16px;
        border-bottom:1px solid #f1f1f1;
        font-size:15px;
    }

    .order-table tbody tr:hover{
        background:#fff5f5;
    }

    .summary-card{
        margin-top:35px;
        background:#fff8f8;
        border-radius:16px;
        padding:30px;
        border:1px solid #ffd9d9;
    }

    .summary-item{
        display:flex;
        justify-content:space-between;
        margin-bottom:18px;
        font-size:15px;
    }

    .summary-item:last-child{
        margin-bottom:0;
        padding-top:18px;
        border-top:2px dashed #E53E3E;
        font-size:22px;
        font-weight:700;
        color:#E53E3E;
    }

    .payment-badge{
        display:inline-flex;
        align-items:center;
        gap:8px;
        background:#ffeaea;
        color:#E53E3E;
        padding:8px 15px;
        border-radius:50px;
        font-size:13px;
        font-weight:700;
    }

    .success-actions{
        margin-top:40px;
        display:flex;
        gap:15px;
        flex-wrap:wrap;
    }

    .success-btn{
        padding:14px 28px;
        border-radius:10px;
        font-weight:600;
        text-decoration:none;
        transition:.3s;
    }

    .btn-dark-custom{
        background:#E53E3E;
        color:#fff;
    }

    .btn-dark-custom:hover{
        background:#C53030;
        color:#fff;
        transform:translateY(-2px);
    }

    .btn-light-custom{
        background:#fff;
        color:#E53E3E;
        border:1px solid #E53E3E;
    }

    .btn-light-custom:hover{
        background:#E53E3E;
        color:#fff;
    }

    @media(max-width:991px){

        .info-grid{
            grid-template-columns:repeat(2,1fr);
        }

        .success-body{
            padding:25px;
        }

    }

    @media(max-width:575px){

        .info-grid{
            grid-template-columns:1fr;
        }

        .success-top h2{
            font-size:28px;
        }

        .success-body{
            padding:20px;
        }

        .order-table thead{
            display:none;
        }

        .order-table,
        .order-table tbody,
        .order-table tr,
        .order-table td{
            display:block;
            width:100%;
        }

        .order-table tr{
            margin-bottom:15px;
            background:#fff;
            border:1px solid #eee;
            border-radius:10px;
            overflow:hidden;
        }

        .order-table td{
            text-align:right;
            position:relative;
            padding-left:50%;
        }

        .order-table td:first-child::before{
            content:"Product";
        }

        .order-table td:nth-child(2)::before{
            content:"Price";
        }

        .order-table td:nth-child(3)::before{
            content:"Qty";
        }

        .order-table td:nth-child(4)::before{
            content:"Total";
        }

        .order-table td::before{
            position:absolute;
            left:15px;
            font-weight:700;
            color:#E53E3E;
        }

    }
</style>

<div class="success-wrapper">
    <div class="container">
        <div class="success-card">
            {{-- TOP --}}
            <div class="success-top">
                <div class="success-check">
                    <svg fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <h2>অর্ডার সফলভাবে সম্পন্ন হয়েছে</h2>
                <p>
                    ধন্যবাদ! আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।
                    খুব শীঘ্রই আমাদের টিম আপনার সাথে যোগাযোগ করবে।
                </p>
                <div class="order-number">
                    Order No:
                    <strong>{{ $order->order_number }}</strong>
                </div>

            </div>

            {{-- BODY --}}
            <div class="success-body">
                {{-- INFO --}}
                <div class="info-grid">
                    <div class="info-box">
                        <label>তারিখ</label>
                        <span>{{ $order->created_at->format('d M, Y') }}</span>
                    </div>

                    <div class="info-box">
                        <label>মোট পরিমাণ</label>
                        <span>৳{{ number_format($order->total, 2) }}</span>
                    </div>

                    <div class="info-box">
                        <label>পেমেন্ট</label>
                        <span>
                            {{ $order->payment_method == 'cod'? 'Cash on Delivery': 'Online Payment' }}
                        </span>
                    </div>

                    <div class="info-box">
                        <label>স্ট্যাটাস</label>
                        <div class="payment-badge">
                            ● {{ ucfirst($order->status) }}
                        </div>
                    </div>
                </div>

                {{-- ORDER ITEMS --}}
                <div class="table-responsive">
                    <table class="order-table">
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
                                        <div style="font-weight:600;font-size:16px;">
                                            {{ $item->product_name }}
                                        </div>

                                        <div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap;">

                                            @if(!empty($item->color))
                                                <span style="
                                                    display:inline-block;
                                                    background:#E53E3E;
                                                    color:#fff;
                                                    padding:4px 12px;
                                                    border-radius:20px;
                                                    font-size:12px;
                                                    font-weight:600;
                                                ">
                                                    Color : {{ $item->color }}
                                                </span>
                                            @endif

                                            @if(!empty($item->size))
                                                <span style="
                                                    display:inline-block;
                                                    background:#E53E3E;
                                                    color:#fff;
                                                    padding:4px 12px;
                                                    border-radius:20px;
                                                    font-size:12px;
                                                    font-weight:600;
                                                ">
                                                    Size : {{ $item->size }}
                                                </span>
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        ৳{{ number_format($item->price, 2) }}
                                    </td>
                                    <td>
                                        {{ $item->quantity }}
                                    </td>
                                    <td>
                                        ৳{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- SUMMARY --}}
                <div class="summary-card">

                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>

                    <div class="summary-item">
                        <span>Shipping</span>
                        <span>৳{{ number_format($order->shipping, 2) }}</span>
                    </div>

                    <div class="summary-item">
                        <span>Payment Status</span>

                        <span>
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>

                    <div class="summary-item">
                        <span>Grand Total</span>
                        <span>৳{{ number_format($order->total, 2) }}</span>
                    </div>

                </div>
                <div class="success-actions">
                    <a href="{{ url('/') }}"class="success-btn btn-dark-custom">
                        Continue Shopping
                    </a>
                    <a href=""
                       class="success-btn btn-light-custom">
                        Explore More Products
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection