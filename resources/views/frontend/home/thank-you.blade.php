@extends('frontend.layouts.app')
@section('title', 'Thank You | Unibox')

@section('content')
@push('css')
    <style>
        .thankyou-card {
            background: #fff;
            padding: 45px 35px;
            max-width: 420px;
            width: 100%;
            border-radius: 14px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
            opacity: 0;
        }

        .thankyou-title {
            font-size: 40px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .thankyou-desc {
            font-size: 15px;
            color: #555;
            opacity: 0;
        }

        .order-no {
            display: block;
            margin-top: 6px;
            font-weight: 600;
            color: #000;
        }

        .home-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 26px;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 30px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .home-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(40,167,69,0.35);
        }

    </style>
@endpush
<main id="MainContent" class="content-for-layout">
    <div class="container d-flex justify-content-center" style="margin-top:80px">

        <div class="thankyou-card text-center">

            <h1 id="thankyouText" class="thankyou-title">
                ধন্যবাদ
            </h1>

            <p id="orderText" class="thankyou-desc">
                আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে <br>
                <span class="order-no">
                    Order Number: {{ $order->order_number }}
                </span>
            </p>

            <a href="{{ route('home') }}" class="home-btn">
                হোমে ফিরে যান
            </a>

        </div>

    </div>
</main>
@endsection
@push('js')
    <script>
    $(document).ready(function () {

        $('.thankyou-card').fadeTo(800, 1, function () {
            $('#orderText').fadeTo(1200, 1);
        });

    });
    </script>
@endpush
