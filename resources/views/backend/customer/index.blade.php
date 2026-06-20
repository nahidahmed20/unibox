@extends('backend.layouts.app')

@section('title', 'Customer List')

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

        .customer-name {
            font-weight: 700;
            color: #0d6efd;
        }

        .badge-status {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="page-title mb-1">Customers</h3>
                <div class="page-subtitle">
                    Manage customer information and details
                </div>
            </div>

            <a href="{{ route('customers.create') }}" class="btn btn-dark rounded-3 px-4">
                <i class="fa-solid fa-plus me-2"></i>
                Add Customer
            </a>

        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">Total Customers</div>
                        <div class="summary-value">
                            {{ $customersCount ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">Active Customers</div>
                        <div class="summary-value text-success">
                            {{ $activeCustomers ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">Wholesale Customers</div>
                        <div class="summary-value text-primary">
                            {{ $wholesaleCustomers ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card summary-card">
                    <div class="card-body">
                        <div class="summary-label">Total Due</div>
                        <div class="summary-value text-danger">
                            {{ number_format($totalDueAmount ?? 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5">
                        <input type="text" id="customSearch" class="form-control"
                            placeholder="Search by code, name or phone">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="customerTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Alt Phone</th>
                                <th>City</th>
                                <th>Type</th>
                                <th>Opening Balance</th>
                                <th>Total Due</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('backend.customer.show')


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            let table = $('#customerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('customers.index') }}",
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
                        text: '<i class="fa-regular fa-copy"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-regular fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa-solid fa-file-csv"></i> CSV'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa-regular fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print"></i> Print'
                    }
                ],

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'customer_code',
                        name: 'customer_code'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return `<span class="customer-name">${data ?? '-'}</span>`;
                        }
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'alternate_phone',
                        name: 'alternate_phone'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            let badge = 'bg-primary';
                            if (data === 'wholesale') {
                                badge = 'bg-success';
                            }
                            if (data === 'walk-in') {
                                badge = 'bg-warning text-dark';
                            }
                            return `<span class="badge ${badge}">
                                ${data}
                            </span>`;
                        }
                    },
                    {
                        data: 'opening_balance',
                        name: 'opening_balance',
                        render: function(data) {
                            return parseFloat(data).toFixed(2);
                        }
                    },

                    {
                        data: 'total_due',
                        name: 'total_due',
                        render: function(data) {
                            return `<span class="fw-bold text-danger">
                                ${parseFloat(data).toFixed(2)}
                            </span>`;
                        }
                    },

                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {

                            if (data == 1) {

                                return `
                        <span class="badge bg-success badge-status">
                            Active
                        </span>`;
                            }

                            return `
                    <span class="badge bg-danger badge-status">
                        Inactive
                    </span>`;
                        }
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ]

            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $(document).on('click', '.showBtn', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: '/admin/customers/' + id,
                    type: 'GET',
                    success: function(res) {

                        $('#customer_code').text(res.customer_code ?? '-');
                        $('#customer_name').text(res.name ?? '-');
                        $('#customer_phone').text(res.phone ?? '-');
                        $('#alternate_phone').text(res.alternate_phone ?? '-');
                        $('#customer_type_text').text(res.type ?? '-');

                        // badge color
                        let badge = 'bg-primary';
                        if(res.type === 'wholesale') badge = 'bg-success';
                        if(res.type === 'walk-in') badge = 'bg-warning text-dark';

                        $('#customer_type_badge')
                            .removeClass()
                            .addClass('badge px-3 py-2 ' + badge)
                            .text(res.type ?? '-');

                        $('#customer_total_due').text(res.total_due ?? '0.00');
                        $('#customer_opening_balance').text(res.opening_balance ?? '0.00');

                        $('#address').text(res.address ?? '-');
                        $('#city').text(res.city ?? '-');
                        $('#state').text(res.state ?? '-');
                        $('#country').text(res.country ?? '-');
                        $('#note').text(res.note ?? '-');

                        $('#status_badge')
                            .removeClass()
                            .addClass(res.status == 1 ? 'badge bg-success px-4 py-2' : 'badge bg-danger px-4 py-2')
                            .text(res.status == 1 ? 'Active' : 'Inactive');

                        $('#customerModal').modal('show');
                    }
                });

            });

        });
    </script>
@endpush
