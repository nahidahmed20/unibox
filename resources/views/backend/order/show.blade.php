<div class="container-fluid">
    <div class="invoice-card">
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
        <div class="invoice-body">
            <div class="info-wrapper">
                <div class="info-box">
                    <h5 class="info-title">Customer Info</h5>
                    <h3>{{ $order->full_name }}</h3>
                    <p>Address: {{ $order->address }}</p>
                    <p>Email: {{ $order->email ?? 'N/A' }}</p>
                    <p>Phone: {{ $order->phone }}</p>
                </div>
                <div class="info-box">
                    <h5 class="info-title">Company Info</h5>
                    <h3>Unibox</h3>
                    <p>Kataban, Dhaka, Bangladesh</p>
                    <p>unibox4u@gmail.com</p>
                    <p>+8801627188836</p>
                </div>
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
                                @php
                                    $statusClass = match(strtolower($order->status)) {
                                        'pending' => 'pending',
                                        'accepted' => 'processing',
                                        'on-the-way' => 'processing',
                                        'completed' => 'completed',
                                        'return' => 'return',
                                        'cancelled' => 'cancelled',
                                        default => 'pending',
                                    };
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    {{ ucfirst(str_replace('-', ' ', $order->status)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Payment:</td>
                            <td>
                                @if (strtolower($order->payment_status) == 'paid')
                                    <span class="status paid">Paid</span>
                                @elseif (strtolower($order->payment_status) == 'due')
                                    <span class="status unpaid">Due</span>
                                @else
                                    <span class="status pending">{{ ucfirst($order->payment_status ?? 'Unpaid') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <!-- ================= NEW ORDER SUMMARY ================= -->
            <div class="summary-area mt-4">
                <h4 class="summary-title mb-3">
                    Order Summary
                </h4>
                <div class="modern-table-wrapper">
                    <table class="modern-product-table">
                        <thead>
                            <tr>
                                <th style="width:45%">Product Details</th>
                                <th class="text-center">Variation</th>
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="modern-product-info">
                                            <div class="modern-product-image">
                                                <img src="{{ asset($item->product->image ?? 'default.png') }}" alt="">
                                            </div>
                                            <div class="modern-product-details">
                                                <h6>{{ $item->product_name ?? ($item->product->name ?? 'Unknown Product') }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        @php
                                            $itemAttributes = is_string($item->attributes) ? json_decode($item->attributes, true) : $item->attributes;
                                            $hasVariations = !empty($item->color->name) || !empty($item->size->name) || (!empty($itemAttributes) && is_array($itemAttributes));
                                        @endphp

                                        @if($hasVariations)
                                            <div class="variation-badges">
                                                @if(!empty($item->color->name))
                                                    <span class="var-badge"><strong class="text-muted fw-normal">Color:</strong> {{ $item->color->name }}</span>
                                                @endif
                                                @if(!empty($item->size->name))
                                                    <span class="var-badge"><strong class="text-muted fw-normal">Size:</strong> {{ $item->size->name }}</span>
                                                @endif
                                                
                                                @if(!empty($itemAttributes) && is_array($itemAttributes))
                                                    @foreach($itemAttributes as $attr)
                                                        <span class="var-badge"><strong class="text-muted fw-normal">Spec:</strong> {{ $attr }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center fw-medium text-dark currency">৳{{ number_format($item->price, 2) }}</td>
                                    
                                    <td class="text-center">
                                        <span class="qty-badge">{{ $item->quantity }}</span>
                                    </td>
                                    
                                    <td class="text-right fw-bold text-primary currency" style="font-size: 16px;">
                                        ৳{{ number_format($item->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ================= END ORDER SUMMARY ================= -->

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
                        @if($order->return_charge > 0)
                        <tr>
                            <td>Return Charge</td>
                            <td class="currency text-danger">
                                -৳{{ number_format($order->return_charge, 2) }}
                            </td>
                        </tr>
                        @endif
                        <tr class="grand-total">
                            <td>Grand Total</td>
                            <td class="currency">
                                @php
                                    $finalTotal = $order->total - ($order->return_charge ?? 0);
                                @endphp
                                ৳{{ number_format($finalTotal, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>Paid</td>
                            <td class="currency">
                                {{ strtolower($order->payment_status) == 'paid' ? '৳' . number_format($finalTotal, 2) : '৳0.00' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Due</td>
                            <td class="text-danger currency">
                                {{ strtolower($order->payment_status) == 'paid' ? '৳0.00' : '৳' . number_format($finalTotal, 2) }}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    .currency {
        font-size: 18px;
        font-weight: 600;
        font-family: "Noto Sans Bengali", sans-serif;
    }

    .invoice-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
        font-family: 'Inter', sans-serif;
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

    .print-btn:hover { background: #f5f5f5; color: #111; }

    .back-btn {
        padding: 11px 18px;
        background: #132b45;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }

    .back-btn:hover { background: #1a3b5d; color: #fff; }

    .invoice-body { padding: 30px; background: #fafafa; }

    .info-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 30px;
        border-bottom: 1px solid #ececec;
        padding-bottom: 30px;
        margin-bottom: 30px;
    }

    .info-title { font-size: 18px; font-weight: 600; color: #16324f; margin-bottom: 18px; }

    .info-box { display: flex; flex-direction: column; align-items: flex-start; }
    .info-box h3 { margin: 0 0 12px; font-size: 20px; font-weight: 600; }
    .info-box p { margin: 0; width: 100%; color: #666; font-size: 15px; line-height: 24px; }

    .invoice-table { width: 100%; border-collapse: collapse; }
    .invoice-table td { padding: 4px 0; font-size: 15px; }
    .invoice-table td:first-child { width: 110px; color: #666; }
    .invoice-table td:last-child { font-weight: 500; }

    .status {
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #fff;
        display: inline-block;
    }

    .pending { background: #ffc107; color: #000; }
    .processing { background: #0dcaf0; color: #000; }
    .completed { background: #198754; }
    .return { background: #6c757d; }
    .cancelled { background: #dc3545; }
    .paid { background: #198754; }
    .unpaid { background: #dc3545; }

    .summary-title { font-size: 22px; margin-bottom: 18px; color: #16324f; font-weight: 600; }

    /* ================= NEW SUMMARY CSS ================= */
    .mt-4 { margin-top: 1.5rem !important; }
    .mb-3 { margin-bottom: 1rem !important; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-primary { color: #3b82f6 !important; }
    .text-muted { color: #6b7280 !important; }
    .text-dark { color: #111827 !important; }
    .fw-medium { font-weight: 500; }
    .fw-bold { font-weight: 700; }
    .fw-normal { font-weight: 400; }

    .modern-table-wrapper {
        background: #fff;
        border: 1px solid #e1e3e5;
        border-radius: 8px;
        overflow-x: auto;
    }

    .modern-product-table { width: 100%; border-collapse: collapse; }

    .modern-product-table thead { background: #f8fafc; border-bottom: 1px solid #e1e3e5; }
    .modern-product-table th {
        padding: 14px 20px;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
    }

    .modern-product-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background 0.2s; }
    .modern-product-table tbody tr:hover { background: #fcfcfd; }
    .modern-product-table tbody tr:last-child { border-bottom: none; }

    .modern-product-table td { padding: 16px 20px; vertical-align: middle; color: #334155; font-size: 15px; }

    .modern-product-info { display: flex; align-items: center; gap: 15px; }
    
    .modern-product-image {
        width: 55px; height: 55px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 3px;
        background: #fff;
        flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .modern-product-image img { width: 100%; height: 100%; object-fit: contain; border-radius: 5px; }

    .modern-product-details h6 { margin: 0; font-size: 15px; font-weight: 600; color: #0f172a; line-height: 1.4; }

    .variation-badges { display: flex; flex-direction: column; gap: 6px; align-items: center; }
    .var-badge {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #334155;
        display: inline-block;
    }

    .qty-badge {
        background: #e2e8f0;
        color: #0f172a;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 13px;
    }

    .total-section { display: flex; justify-content: flex-end; margin-top: 30px; }

    .total-box { width: 360px; background: #fff; border: 1px solid #ececec; border-radius: 8px; overflow: hidden; }
    .total-box table { width: 100%; border-collapse: collapse; }
    .total-box td { padding: 14px 18px; border-bottom: 1px solid #ececec; font-size: 15px; }
    .total-box tr:last-child td { border-bottom: none; }
    .total-box td:last-child { text-align: right; font-weight: 500; }

    .grand-total td { font-size: 18px; font-weight: 700; color: #198754; background: #f8fafc; }
    .text-danger { color: #dc3545 !important; }
    .text-warning { color: #f59e0b !important; }

    @media(max-width:991px) {
        .info-wrapper { grid-template-columns: 1fr; }
        .total-section { justify-content: stretch; }
        .total-box { width: 100%; }
        .invoice-header { flex-direction: column; gap: 15px; }
    }

    @media(max-width:768px) {
        .invoice-body { padding: 15px; }
        .modern-product-table { min-width: 650px; }
    }
</style>