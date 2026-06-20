@extends('backend.layouts.app')

@section('title', 'Purchase List')

@section('content')
@push('styles')
    <style>
        .shopify-modal {
            border-radius: 16px;
            overflow: hidden;
        }

        .shopify-header {
            background: #000032;
            color: #fff;
            padding: 15px 20px;
        }

        .shopify-box {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 12px;
            padding: 15px;
        }
    </style>
@endpush

    <!-- ================= HEADER ================= -->

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color: #212b36;">Purchases</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Purchase List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TABLE ================= -->

    <div class="app-content">
        <div class="container-fluid">
            
            <div class="card modern-card">
                <div class="modern-card-header">
                    <h4 class="card-title"><i class="fa-solid fa-list-ul text-muted me-2"></i> All Purchases</h4>
                    <a href="{{ route('purchases.create') }}" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Purchase
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="p-4">
                        <table class="table table-striped table-bordered" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>

                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <th id="totalAmount"></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= MODAL ================= -->
    <div class="modal fade" id="purchaseModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shopify-modal">

                <div class="modal-header shopify-header">
                    <h5 class="modal-title">Purchase Details</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="purchaseModalBody"></div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ================= DATATABLE =================
            var table = $('#purchaseTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('purchases.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {data: 'invoice_no',className: "text-center"},
                    {data: 'supplier_name',className: "text-center"},
                    {
                        data: 'purchase_date',
                        className: "text-center",
                        render: function(data) {
                            if (!data) return '-';
                            let d = new Date(data);
                            return d.getDate() + ' ' + d.toLocaleString('en-US', {
                                month: 'short'
                            }) + ' ' + d.getFullYear();
                        }
                    },
                    {data: 'total_amount',className: "text-center"},
                    {data: 'status',className: "text-center"},
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    },
                ],

                dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
                buttons: [
                    {
                        extend: 'copy', text: '<i class="fa-regular fa-copy"></i> Copy',
                        exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
                    },
                    {
                        extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel',
                        exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
                    },
                    {
                        extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV',
                        exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
                    },
                    {
                        extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF',
                        exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
                    },
                    {
                        extend: 'print', text: '<i class="fa-solid fa-print"></i> Print',
                        exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
                    }
                ],

                order: [[1, 'asc']],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search purchase...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                },
                footerCallback: function (row, data, start, end, display) {
                    let api = this.api();

                    let intVal = function (i) {
                        return typeof i === 'string'
                            ? i.replace(/[\$,]/g, '') * 1
                            : typeof i === 'number' ? i : 0;
                    };

                    let totalPurchase = api.column(5, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                    let totalSelling  = api.column(6, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                    $('#totalPurchase').html('<span class="taka-symbol">৳</span>' + totalPurchase.toLocaleString(undefined, {minimumFractionDigits: 2}));
                    $('#totalSelling').html('<span class="taka-symbol">৳</span>' + totalSelling.toLocaleString(undefined, {minimumFractionDigits: 2}));
                }
            });

            // ================= DELETE =================
            $(document).on('click', '.btn-delete', function() {

                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Delete?',
                    text: 'This purchase will be deleted!',
                    icon: 'warning',
                    showCancelButton: true
                }).then((res) => {
                    if (res.isConfirmed) {

                        $.post(form.attr('action'), form.serialize(), function(res) {
                            toastr.success(res.message);
                            table.ajax.reload();
                        });
                    }
                });

            });

            // ================= SHOW MODAL =================
            $(document).on('click', '.btn-show', function() {

                let id = $(this).data('id');

                $.get('/admin/purchases/' + id, function(res) {

                    let p = res.data;

                    let html = `
                        <div class="row g-3 mb-3">

                            <div class="col-md-6">
                                <div class="shopify-box">
                                    <p><b>Invoice:</b> ${p.invoice_no}</p>
                                    <p><b>Supplier:</b> ${p.supplier?.name ?? '-'}</p>
                                    <p><b>Date:</b> ${p.purchase_date}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="shopify-box">
                                    <p><b>Status:</b>
                                        ${p.status == 1
                                        ? `<span class="badge-active">Active</span>`
                                        : `<span class="badge-inactive">Inactive</span>`}
                                    </p>
                                    <p><b>Total:</b> ${p.total_amount}</p>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                        `;

                    if (p.details.length) {

                        p.details.forEach((item, i) => {

                            html += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${item.product?.name ?? '-'}</td>
                                    <td>${item.product?.sku ?? '-'}</td>

                                    <!-- ✅ FIXED SIZE -->
                                    <td>${item.size?.size ?? '-'}</td>

                                    <td>${item.color?.name ?? '-'}</td>
                                    <td>${item.buying_price}</td>
                                    <td>${item.quantity}</td>
                                    <td>${(item.buying_price*item.quantity).toFixed(2)}</td>
                                </tr>`;
                                        });

                                    } else {
                                        html += `<tr><td colspan="8" class="text-center">No Data</td></tr>`;
                                    }

                                    html += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-end">Grand Total</th>
                                    <th>${p.total_amount}</th>
                                </tr>
                            </tfoot>
                        </table>
                        </div>`;

                    $('#purchaseModalBody').html(html);
                    $('#purchaseModal').modal('show');
                });

            });

        });
    </script>
@endpush
