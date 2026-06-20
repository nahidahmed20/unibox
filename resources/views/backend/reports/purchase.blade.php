@extends('backend.layouts.app')

@section('title', 'Purchase Report')

@push('styles')
    <style>
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #212b36;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .summary-card {
            transition: .2s;
        }

        .summary-card:hover {
            transform: translateY(-2px);
        }

        .summary-label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .invoice-no {
            font-weight: 700;
            color: #0d6efd;
        }


        .badge-paid {
            background: #e7f8ee;
            color: #198754;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }


        .badge-partial {
            background: #e0f2fe;
            color: #0284c7;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }


        .badge-unpaid {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }


        .badge-approved {
            background: #e7f8ee;
            color: #198754;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }


        .badge-pending {
            background: #fff4db;
            color: #b7791f;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }


        .badge-cancel {
            background: #e5e7eb;
            color: #374151;
            padding: 8px 14px;
            border-radius: 30px;
            font-weight: 600;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="page-title mb-1">
                    Purchase Report
                </h3>
                <div class="page-subtitle">
                    Monitor purchase history and supplier payments
                </div>
            </div>
            <button class="btn btn-dark rounded-3 px-4" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i>
                Print
            </button>
        </div>
        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">
                            Total Purchase
                        </div>
                        <div class="summary-value">
                            ৳ {{ number_format($purchases->sum('total_amount'), 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">
                            Paid Purchase
                        </div>
                        <div class="summary-value text-success">
                            ৳ {{ number_format($purchases->where('payment_status', 3)->sum('total_amount'), 2) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">
                            Pending Purchase
                        </div>
                        <div class="summary-value text-danger">
                            {{ $purchases->where('status', 1)->count() }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">
                            Total Orders
                        </div>
                        <div class="summary-value">
                            {{ $purchases->count() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-3 mb-lg-0">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="customSearch" class="form-control" placeholder="Search invoice or supplier">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <form method="GET" action="{{ route('reports.purchase') }}"
                            class="d-flex justify-content-lg-end gap-2 flex-wrap">
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="form-control w-auto">
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="form-control w-auto">
                            <button class="btn btn-dark px-4">
                                Filter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="purchaseTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Payment Status</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $p)
                                <tr>
                                    <td>
                                        <span class="invoice-no">
                                            {{ $p->invoice_no }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($p->purchase_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        {{ $p->supplier->name ?? 'N/A' }}
                                    </td>
                                    <td class="fw-semibold">
                                        ৳ {{ number_format($p->total_amount, 2) }}
                                    </td>
                                    <td>
                                        @if ($p->payment_status == 3)
                                            <span class="badge-paid">
                                                Paid
                                            </span>
                                        @elseif($p->payment_status == 2)
                                            <span class="badge-partial">
                                                Partial
                                            </span>
                                        @else
                                            <span class="badge-unpaid">
                                                Unpaid
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($p->status == 2)
                                            <span class="badge-approved">
                                                Approved
                                            </span>
                                        @elseif($p->status == 3)
                                            <span class="badge-cancel">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="badge-pending">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#purchaseTable').DataTable({
                responsive: true,
                pageLength: 10,
                dom: '<"row align-items-center mb-4"' +
                    '<"col-md-4"l>' +
                    '<"col-md-4 text-center"B>' +
                    '<"col-md-4 d-flex justify-content-end"f>' +
                    '>rt' +
                    '<"row mt-4"' +
                    '<"col-md-6"i>' +
                    '<"col-md-6 d-flex justify-content-end"p>' +
                    '>',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-light btn-sm'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info btn-sm'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-dark btn-sm'
                    }
                ],

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search purchase...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
@endpush
