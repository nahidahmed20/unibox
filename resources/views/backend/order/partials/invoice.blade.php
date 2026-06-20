<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $order->order_number }}</title>

    <style>
        body {
            font-family: Inter, Arial, sans-serif;
            background: #f6f7fb;
            margin: 0;
            padding: 0;
            color: #1f2937;
        }

        .invoice-wrapper {
            max-width: 900px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        /* HEADER */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 25px 30px;
            background: linear-gradient(135deg, #111827, #1f2937);
            color: #fff;
        }

        .logo {
            height: 45px;
        }

        .invoice-meta h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 2px;
        }

        .invoice-meta p {
            margin: 3px 0;
            font-size: 13px;
            opacity: 0.85;
        }

        /* INFO */
        .info-section {
            display: flex;
            justify-content: space-between;
            padding: 25px 30px;
            background: #f9fafb;
            border-bottom: 1px solid #eee;
        }

        .info-box h4 {
            margin-bottom: 8px;
            font-size: 14px;
            color: #111827;
        }

        .info-box p {
            margin: 3px 0;
            font-size: 13px;
            color: #6b7280;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #111827;
            color: #fff;
        }

        th,
        td {
            padding: 14px;
            font-size: 13px;
            border-bottom: 1px solid #eee;
        }

        tbody tr:hover {
            background: #f3f4f6;
        }

        /* TOTAL */
        .total-box {
            padding: 20px 30px;
            display: flex;
            justify-content: flex-end;
        }

        .total-card {
            width: 320px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #eee;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
        }

        .grand {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            border-top: 1px solid #ddd;
            margin-top: 8px;
            padding-top: 8px;
        }

        .paid {
            color: #16a34a;
        }

        .due {
            color: #dc2626;
        }

        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #eee;
        }

        /* PRINT */
        @media print {
            body {
                background: #fff !important;
                -webkit-print-color-adjust: exact;
            }

            .invoice-wrapper {
                box-shadow: none !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .invoice-header {
                background: #111827 !important;
                color: #fff !important;
            }

            table thead {
                background: #111827 !important;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-wrapper">

        <!-- HEADER -->
        <div class="invoice-header">
            <img src="{{ asset('uploads/Unibox-Logo.png') }}" class="logo">

            <div class="invoice-meta">
                <h1>INVOICE</h1>
                <p>#{{ $order->order_number }}</p>
                <p>{{ $order->created_at->format('d M, Y') }}</p>
            </div>
        </div>

        <!-- INFO -->
        <div class="info-section">
            <div class="info-box">
                <h4>Bill To</h4>
                <p><b>{{ $order->full_name }}</b></p>
                <p>{{ $order->phone }}</p>
                <p>{{ $order->email ?? '' }}</p>
                <p>{{ $order->address }}</p>
                <p>{{ $order->city ?? '' }}, {{ $order->province ?? '' }}</p>
            </div>

            <div class="info-box">
                <h4>From</h4>
                <p><b>Unibox</b></p>
                <p>Kataban, Dhaka</p>
                <p>+8801627188836</p>
                <p>uniboxbd4u@gmail.com</p>
            </div>
        </div>

        <!-- ITEMS -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($order->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>৳{{ number_format($item->price, 2) }}</td>
                        <td>৳{{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL -->
        <div class="total-box">
            <div class="total-card">

                <div class="total-row">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($order->subtotal, 2) }}</span>
                </div>

                <div class="total-row">
                    <span>Shipping</span>
                    <span>৳{{ number_format($order->shipping, 2) }}</span>
                </div>

                <div class="total-row grand">
                    <span>Grand Total</span>
                    <span>৳{{ number_format($order->total, 2) }}</span>
                </div>

                <div class="total-row paid">
                    <span>Paid</span>
                    <span>
                        {{ $order->payment_status == 'paid' ? '৳' . number_format($order->total, 2) : '৳0.00' }}
                    </span>
                </div>

                <div class="total-row due">
                    <span>Due</span>
                    <span>
                        {{ $order->payment_status == 'paid' ? '৳0.00' : '৳' . number_format($order->total, 2) }}
                    </span>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Thank you for your order • Powered by Unibox
        </div>

    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>
