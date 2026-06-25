@extends('backend.layouts.app')

@section('title', 'Sale List')

@push('styles')
<style>
    .modal-content{
        border:none;
        border-radius:20px;
        overflow:hidden;
        box-shadow:0 20px 60px rgba(0,0,0,.12);
    }

    .modal-header-modern{
        background:#fff;
        border-bottom:1px solid #eef2f7;
        padding:20px 24px;
    }

    .modal-title-modern{
        font-size:18px;
        font-weight:700;
        color:#111827;
    }

    .order-card{
        background:#fff;
        border:1px solid #eef2f7;
        border-radius:16px;
        padding:18px;
    }

    .info-label{
        font-size:12px;
        text-transform:uppercase;
        color:#6b7280;
        font-weight:600;
    }

    .info-value{
        font-size:15px;
        font-weight:600;
        color:#111827;
    }

    .summary-card{
        background:#f8fafc;
        border-radius:16px;
        padding:18px;
    }

    .summary-item{
        display:flex;
        justify-content:space-between;
        margin-bottom:10px;
    }

    .summary-item:last-child{
        margin-bottom:0;
    }

    .products-card{
        border:1px solid #eef2f7;
        border-radius:16px;
        overflow:hidden;
    }

    .product-row{
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:14px 18px;
        border-bottom:1px solid #f1f5f9;
    }

    .product-row:last-child{
        border-bottom:none;
    }

    .product-name{
        font-weight:600;
        color:#111827;
    }

    .product-meta{
        font-size:13px;
        color:#6b7280;
    }

    .badge-status{
        background:#dcfce7;
        color:#166534;
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:600;
    }
    #saleModal .modal-content{
        border:none;
        border-radius:18px;
        overflow:hidden;
    }

    #saleModal .card{
        border-radius:14px;
    }

    #saleModal .card-header{
        padding:16px 20px;
    }

    #saleModal .border-bottom:last-child{
        border-bottom:none !important;
    }
</style>
@endpush

@section('content')

<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #212b36;">
                    Sales
                </h3>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"
                            class="text-decoration-none text-muted">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item active fw-bold text-dark">
                        Sale List
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="app-content">
    <div class="container-fluid">

        <div class="card modern-card">

            <div class="modern-card-header">

                <h4 class="card-title">
                    <i class="fa-solid fa-cart-shopping text-muted me-2"></i>
                    All Sales
                </h4>

            </div>

            <div class="card-body p-0">

                <div class="p-4">

                    <table class="table table-modern table-hover w-100"
                        id="saleTable">

                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Invoice</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total (৳)</th>
                                <th>Delivery (৳)</th>
                                <th>Status</th>
                                <th width="12%" class="text-center">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan="4"
                                    class="text-end text-uppercase text-muted"
                                    style="font-size:12px;">

                                    Page Total:

                                </th>

                                <th id="totalAmount"
                                    class="text-success fw-bold fs-6">

                                </th>

                                <th colspan="3"></th>

                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>
</div>


<!--=========================
        SALE DETAILS MODAL
==========================-->

<div class="modal fade" id="saleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-modern">
                <h5 class="modal-title modal-title-modern">
                    <i class="fa-solid fa-receipt text-muted me-2"></i>
                    Sale Details
                </h5>
                <button type="button"
                    class="btn-close shadow-none"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="saleModalBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-dark"
                        role="status"
                        style="width:3rem;height:3rem;">
                    </div>
                    <p class="mt-3 text-muted fw-bold">
                        Loading details...
                    </p>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button"
                    class="btn btn-secondary rounded-pill px-4 fw-bold"
                    data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    let table = $('#saleTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('sales.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'invoice_no', name: 'invoice_no' },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'sale_date', name: 'sale_date' },
            { data: 'grand_total', name: 'grand_total' },
            { data: 'delivery_charge', name: 'delivery_charge' },
            { data:'status', name:'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy me-1"></i> Copy',
                className: 'dt-btn'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'dt-btn dt-excel'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv me-1"></i> CSV',
                className: 'dt-btn dt-csv'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                className: 'dt-btn dt-pdf'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Print',
                className: 'dt-btn dt-print'
            }
        ],
        order: [[1, 'desc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search sales...",
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
                    : typeof i === 'number'
                    ? i
                    : 0;
            };

            let totalAmount = api
                .column(4, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);

            $('#totalAmount').html(
                '<span class="taka-symbol">৳</span>' +
                totalAmount.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                })
            );
        }
    });

    // 👁️ View Sale Details
    $(document).on('click', '.btn-show', function () {
        let id = $(this).data('id');
        $.ajax({
            url: '/admin/sales/' + id,
            type: 'GET',
            success: function (res) {
                let statusClass = 'bg-secondary';
                if (res.data.status === 'Completed') {
                    statusClass = 'bg-success';
                } else if (res.data.status === 'Pending') {
                    statusClass = 'bg-warning text-dark';
                } else if (res.data.status === 'Cancelled') {
                    statusClass = 'bg-danger';
                }
                let html = `
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="fw-bold mb-1">
                                #${res.data.invoice_no}
                            </h4>
                            <div class="text-muted small">
                                ${res.data.sale_date}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge ${statusClass} px-3 py-2 rounded-pill">
                                ${res.data.status ?? 'N/A'}
                            </span>
                            <a href="/admin/sales/invoice/${id}"
                                target="_blank"
                                class="btn btn-dark rounded-pill px-4">
                                <i class="fa-solid fa-print me-2"></i>
                                Invoice
                            </a>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">
                                        Customer Information
                                    </h6>
                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            Customer Name
                                        </small>

                                        <strong>
                                            ${res.data.customer?.name ?? '-'}
                                        </strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            Payment Method
                                        </small>

                                        <strong>
                                            ${res.data.payment_method ?? '-'}
                                        </strong>
                                    </div>

                                    <div>
                                        <small class="text-muted d-block">
                                            Delivery Charge
                                        </small>

                                        <strong>
                                            ${
                                                res.data.delivery_charge == 0
                                                    ? 'Free Delivery'
                                                    : res.data.delivery_charge == 60
                                                    ? 'Inside Dhaka (৳60)'
                                                    : 'Outside Dhaka (৳120)'
                                            }
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3"> Payment Summary </h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"> Grand Total </span>
                                        <strong> ৳ ${parseFloat(res.data.grand_total).toFixed(2)} </strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"> Paid Amount </span>
                                        <strong class="text-success">
                                            ৳ ${parseFloat(res.data.paid_amount).toFixed(2)}
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">  Due Amount </span>
                                        <strong class="text-danger">
                                            ৳ ${parseFloat(res.data.due_amount).toFixed(2)}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="fw-bold mb-0"> Sale Items </h6>
                        </div>
                        <div class="card-body p-0">
                `;

                if (res.data.items && res.data.items.length > 0) {

                    res.data.items.forEach((item, index) => {
    html += `
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <div>
                <div class="fw-semibold">
                    ${item.product?.name ?? '-'}
                </div>
                <div class="small text-muted mt-1">
                    SKU: ${item.product?.sku ?? '-'}
                    
                    ${item.size || item.color ? `
                        &nbsp;•&nbsp; Size: ${item.size?.name ?? 'N/A'} &nbsp;•&nbsp; Color: ${item.color?.name ?? 'N/A'}
                    ` : ''}
                </div>
            </div>
            <div class="text-end">
                <div class="fw-bold">
                    ${item.quantity} × ৳${parseFloat(item.selling_price).toFixed(2)}
                </div>
                <small class="text-muted">
                    ৳${(parseFloat(item.selling_price) * parseInt(item.quantity)).toFixed(2)}
                </small>
            </div>
        </div>
    `;
});
                } else {

                    html += `
                        <div class="text-center py-5 text-muted">
                            No sale items found.
                        </div>
                    `;
                }

                html += `
                        </div>

                    </div>
                `;

                $('#saleModalBody').html(html);

                $('#saleModal').modal('show');
            }
        });
    });

});
</script>
@endpush
