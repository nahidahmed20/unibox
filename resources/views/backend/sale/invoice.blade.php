<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $sale->invoice_no }}</title>

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
                print-color-adjust: exact;
            }

            .invoice-wrapper {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .invoice-header {
                background: #111827 !important;
                color: #fff !important;
                -webkit-print-color-adjust: exact;
            }

            table thead {
                background: #111827 !important;
                color: #fff !important;
            }

            .total-card {
                background: #f9fafb !important;
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
                <p>#{{ $sale->invoice_no }}</p>
                <p>{{ date('d M, Y', strtotime($sale->sale_date)) }}</p>
            </div>
        </div>

        <!-- INFO -->
        <div class="info-section">
            <div class="info-box">
                <h4>Bill To</h4>
                <p><b>{{ $sale->customer->name ?? '' }}</b></p>
                <p>{{ $sale->customer->phone ?? '' }}</p>
                <p>{{ $sale->customer->address ?? '' }}</p>
            </div>

            <div class="info-box">
                <h4>From</h4>
                <p><b>Your Company</b></p>
                <p>Business Address</p>
                <p>+880 123456789</p>
            </div>
        </div>

        <!-- ITEMS -->
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sale->items as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <b>{{ $item->product->name ?? '' }}</b>
                            
                            @if($item->color_id || $item->size_id)
                                <br>
                                <small style="color: #6b7280;">
                                    @if($item->color_id)
                                        Color: {{ $item->color->name ?? '' }}
                                    @endif
                                    @if($item->color_id && $item->size_id) | @endif
                                    @if($item->size_id)
                                        Size: {{ $item->size->name ?? '' }}
                                    @endif
                                </small>
                            @endif
                        </td>
                        <td>৳{{ number_format($item->selling_price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>৳{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL -->
        <div class="total-box">
            <div class="total-card">

                <div class="total-row">
                    <span>Subtotal</span>
                    <span>৳{{ number_format($sale->total_amount, 2) }}</span>
                </div>

                <div class="total-row">
                    <span>Discount</span>
                    <span>৳{{ number_format($sale->discount, 2) }}</span>
                </div>

                <div class="total-row grand">
                    <span>Grand Total</span>
                    <span>৳{{ number_format($sale->grand_total, 2) }}</span>
                </div>

                <div class="total-row paid">
                    <span>Paid</span>
                    <span>৳{{ number_format($sale->paid_amount, 2) }}</span>
                </div>

                <div class="total-row due">
                    <span>Due</span>
                    <span>৳{{ number_format($sale->due_amount, 2) }}</span>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            Thank you for your business • Powered by POS System
        </div>

    </div>

    <script>
        window.onload = () => window.print();
    </script>

</body>

</html>
