<div class="container-fluid">
    <div class="invoice-card">
        <!-- Header -->
        <div class="invoice-header">
            <div class="header-left">
                <h4>Sales Detail</h4>
            </div>
            <div class="header-right">
                <a href="{{ route('orders.invoice', $order->id) }}" target="_blank" class="print-btn">
                    <i class="fas fa-print"></i>
                </a>
                <a href="{{ route('orders.index') }}" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Back to Sales
                </a>
            </div>
        </div>
        <!-- Body -->
        <div class="invoice-body">
            <!-- Top Info -->
            <div class="info-wrapper">
                <!-- Customer -->
                <div class="info-box">
                    <h5 class="info-title">Customer Info</h5>
                    <h3>{{ $order->full_name }}</h3>
                    <p>Address: {{ $order->address }}</p>
                    <p>Email: {{ $order->email }}</p>
                    <p>Phone: {{ $order->phone }}</p>
                </div>
                <!-- Company -->
                <div class="info-box">
                    <h5 class="info-title">Company Info</h5>
                    <h3>Unibox</h3>
                    <p>Kataban, Dhaka, Bangladesh</p>
                    <p>unibox4u@gmail.com</p>
                    <p>+8801627188836</p>
                </div>
                <!-- Invoice -->
                <div class="info-box">
                    <h5 class="info-title">Invoice Info</h5>
                    <table class="invoice-table">
                        <tr>
                            <td>Reference:</td>
                            <td>
                                <strong class="text-warning">
                                    {{ $order->order_number }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Date:</td>
                            <td>
                                {{ $order->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td>
                                @if ($order->status == 'pending')
                                    <span class="status pending">
                                        Pending
                                    </span>
                                @elseif($order->status == 'processing')
                                    <span class="status processing">
                                        Processing
                                    </span>
                                @else
                                    <span class="status completed">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Payment:</td>
                            <td>
                                @if ($order->payment_status == 'paid')
                                    <span class="status paid">
                                        Paid
                                    </span>
                                @else
                                    <span class="status unpaid">
                                        Unpaid
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- Order Summary -->
            <div class="summary-area">
                <h4 class="summary-title">
                    Order Summary
                </h4>
                <div class="table-wrapper">
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th style="width:40%">Product</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="product-info">
                                            <div class="product-image">
                                                <img src="{{ asset($item->product->image ?? 'default.png') }}" alt="">
                                            </div>
                                            <div class="product-details">
                                                <h6>{{ $item->product_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->color }}</td>
                                    <td>{{ $item->size }}</td>
                                    <td class="currency">{{ $item->quantity }}</td>
                                    <td class="currency">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="currency">৳{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Total -->
            <div class="total-section">
                <div class="total-box">
                    <table>
                        <tr>
                            <td>Subtotal</td>
                            <td class="currency">
                                ৳{{ number_format($order->subtotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Shipping</td>
                            <td class="currency">
                                ৳{{ number_format($order->shipping, 2) }}
                            </td>
                        </tr>
                        <tr class="grand-total">
                            <td>Grand Total</td>
                            <td class="currency">
                                ৳{{ number_format($order->total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Paid</td>
                            <td class="currency">
                                {{ $order->payment_status == 'paid' ? '৳' . number_format($order->total, 2) : '৳0.00' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Due</td>
                            <td class="text-danger currency">
                                {{ $order->payment_status == 'paid' ? '৳0.00' : '৳' . number_format($order->total, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .currency{
        font-size:18px;
        font-weight:600;
        font-family: "Noto Sans Bengali", sans-serif;
    }
    .invoice-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
    }

    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        border-bottom: 1px solid #ececec;
        background: #fff;
    }

    .header-left h4 {
        margin: 0;
        font-size: 22px;
        font-weight: 600;
        color: #1b2a41;
    }

    .header-right {
        display: flex;
        gap: 10px;
    }

    .print-btn {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dcdcdc;
        border-radius: 5px;
        background: #fff;
        color: #444;
        text-decoration: none;
    }

    .print-btn:hover {
        background: #f5f5f5;
        color: #111;
    }

    .back-btn {
        padding: 11px 18px;
        background: #132b45;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .back-btn:hover {
        background: #1a3b5d;
        color: #fff;
    }

    .invoice-body {
        padding: 30px;
        background: #fafafa;
    }

    .info-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 30px;
        border-bottom: 1px solid #ececec;
        padding-bottom: 30px;
        margin-bottom: 30px;
    }

    .info-title {
        font-size: 18px;
        font-weight: 600;
        color: #16324f;
        margin-bottom: 18px;
    }

    .info-box{
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .info-box h3{
        margin: 0 0 12px;
        font-size: 20px;
        font-weight: 600;
    }

    .info-box p{
        margin: 0 0 0px;
        display: block;
        width: 100%;
        color: #666;
        font-size: 15px;
        line-height: 24px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-table td {
        padding: 4px 0;
        font-size: 15px;
    }

    .invoice-table td:first-child {
        width: 110px;
        color: #666;
    }

    .invoice-table td:last-child {
        font-weight: 500;
    }

    .status {
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
    }

    .pending {
        background: #ffc107;
    }

    .processing {
        background: #0dcaf0;
    }

    .completed {
        background: #198754;
    }

    .paid {
        background: #198754;
    }

    .unpaid {
        background: #dc3545;
    }

    .summary-title {
        font-size: 22px;
        margin-bottom: 18px;
        color: #16324f;
        font-weight: 600;
    }

    .table-wrapper {
        background: #fff;
        border: 1px solid #ececec;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
    }

    .product-table thead {
        background: #eceff3;
    }

    .product-table thead th {
        padding: 16px 18px;
        font-size: 15px;
        font-weight: 600;
        color: #444;
        text-align: left;
        border: none;
    }

    .product-table tbody td {
        padding: 18px;
        border-top: 1px solid #ececec;
        vertical-align: middle;
        color: #555;
        font-size: 15px;
    }

    .product-table tbody tr:hover {
        background: #fafafa;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .product-image {
        width: 45px;
        height: 45px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #ececec;
        background: #fff;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details h6 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #222;
    }

    .total-section {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
    }

    .total-box {
        width: 360px;
        background: #fff;
        border: 1px solid #ececec;
    }

    .total-box table {
        width: 100%;
        border-collapse: collapse;
    }

    .total-box td {
        padding: 14px 18px;
        border: 1px solid #ececec;
        font-size: 15px;
    }

    .total-box td:last-child {
        text-align: right;
        font-weight: 500;
    }

    .grand-total td {
        font-size: 18px;
        font-weight: 700;
        color: #198754;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-warning {
        color: #f59e0b !important;
    }

    @media(max-width:991px) {

        .info-wrapper {
            grid-template-columns: 1fr;
        }

        .total-section {
            justify-content: stretch;
        }

        .total-box {
            width: 100%;
        }

        .invoice-header {
            flex-direction: column;
            gap: 15px;
        }

    }

    @media(max-width:768px) {

        .invoice-body {
            padding: 15px;
        }

        .product-table {
            min-width: 700px;
        }

    }
</style>
