@extends('backend.layouts.app')

@section('title', 'Purchase List')

@section('content')
@push('styles')
    <style>
        .shopify-modal {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .shopify-header {
            background: #000032;
            color: #fff;
            padding: 16px 24px;
            border-bottom: none;
        }

        .shopify-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            height: 100%;
        }
        
        .shopify-box p {
            margin-bottom: 8px;
            color: #334155;
        }
        
        .shopify-box p:last-child {
            margin-bottom: 0;
        }
        #purchaseModalBody {
            transition: opacity 0.3s ease-in-out;
        }
        .loading-state {
            opacity: 0.5;
        }
    </style>
@endpush

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color: #0f172a;">Purchases</h3>
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

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="modern-card-header d-flex justify-content-between align-items-center p-4 border-bottom">
                    <h5 class="card-title mb-0 fw-bold text-slate-800">
                        <i class="fa-solid fa-list-ul text-muted me-2"></i> All Purchases
                    </h5>
                    <a href="{{ route('purchases.create') }}" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Purchase
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="p-4">
                        <table class="table table-hover table-bordered" id="purchaseTable">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th class="text-center">Invoice</th>
                                    <th class="text-center">Supplier</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="4" class="text-end fw-bold">Current Page Total:</th>
                                    <th id="totalAmount" class="text-center fw-bold text-primary"></th>
                                    <th colspan="2"></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="purchaseModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shopify-modal">
                <div class="modal-header shopify-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0">Purchase Details</h5>
                    <button class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="purchaseModalBody">
                    </div>
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
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'invoice_no', className: "text-center fw-semibold" },
                    { data: 'supplier_name', className: "text-center" },
                    {
                        data: 'purchase_date',
                        className: "text-center",
                        render: function(data) {
                            if (!data) return '-';
                            let d = new Date(data);
                            return d.getDate() + ' ' + d.toLocaleString('en-US', { month: 'short' }) + ' ' + d.getFullYear();
                        }
                    },
                    { data: 'total_amount', className: "text-center fw-bold" },
                    { data: 'status', className: "text-center" },
                    { data: 'action', orderable: false, searchable: false, className: "text-center" },
                ],
                dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
                buttons: [
                    { extend: 'copy', text: '<i class="fa-regular fa-copy"></i> Copy', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
                    { extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
                    { extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
                    { extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } },
                    { extend: 'print', text: '<i class="fa-solid fa-print"></i> Print', exportOptions: { columns: [0, 1, 2, 3, 4, 5] } }
                ],
                order: [[1, 'desc']], // Usually it's better to show the latest purchases first
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
                
                // ================= FIXED FOOTER CALLBACK =================
                footerCallback: function (row, data, start, end, display) {
                    let api = this.api();

                    let intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };

                    // Total amount is at Index 4 (0:#, 1:Invoice, 2:Supplier, 3:Date, 4:Total)
                    let totalAmount = api.column(4, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);

                    // Update the correct ID
                    $('#totalAmount').html('<span class="taka-symbol me-1">৳</span>' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2}));
                }
            });

            // ================= SHOW MODAL =================
            $(document).on('click', '.btn-show', function() {
                let id = $(this).data('id');
                
                $(this).html('<i class="fa-solid fa-spinner fa-spin"></i>').addClass('disabled');

                $.get('/admin/purchases/' + id, function(res) {
                    let p = res.data;
                    let badgeClass = p.status == 1 ? 'bg-success' : 'bg-danger';
                    let statusText = p.status == 1 ? 'Active' : 'Inactive';

                    let html = `
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="shopify-box">
                                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.8rem;">Supplier Info</h6>
                                    <p><b>Invoice:</b> <span class="text-primary fw-bold">${p.invoice_no}</span></p>
                                    <p><b>Supplier:</b> ${p.supplier?.name ?? '-'}</p>
                                    <p><b>Date:</b> ${p.purchase_date}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="shopify-box">
                                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size:0.8rem;">Summary</h6>
                                    <p><b>Status:</b> <span class="badge ${badgeClass}">${statusText}</span></p>
                                    <p><b>Total Amount:</b> <span class="fs-5 fw-bold text-dark">৳ ${parseFloat(p.total_amount).toFixed(2)}</span></p>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-slate-800 mb-3">Product List</h6>
                        <div class="table-responsive border rounded-3">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Product Name</th>
                                        <th class="text-center">SKU</th>
                                        <th class="text-center">Variant (S/C)</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    if (p.details && p.details.length > 0) {
                        p.details.forEach((item, i) => {
                            let sizeName = item.variant?.size?.name ?? '-';
                            let colorName = item.variant?.color?.name ?? '-';
                            
                            html += `
                                <tr>
                                    <td class="text-center">${i + 1}</td>
                                    <td class="fw-medium">${item.product?.name ?? '-'}</td>
                                    <td class="text-center text-muted">${item.product?.sku ?? '-'}</td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border">S: ${sizeName}</span>
                                        <span class="badge bg-light text-dark border">C: ${colorName}</span>
                                    </td> 
                                    <td class="text-end">৳ ${parseFloat(item.buying_price).toFixed(2)}</td>
                                    <td class="text-center fw-bold">${item.quantity}</td>
                                    <td class="text-end fw-bold text-dark">৳ ${parseFloat(item.total_price).toFixed(2)}</td>
                                </tr>`;
                        });
                    }

                    html += `</tbody></table></div>`;

                    $('#purchaseModalBody').html(html);
                    $('#purchaseModal').modal('show');
                    
                    $('.btn-show').html('<i class="fa-regular fa-eye"></i>').removeClass('disabled');
                    
                }).fail(function() {
                    alert("Failed to load data.");
                    $('.btn-show').html('<i class="fa-regular fa-eye"></i>').removeClass('disabled');
                });
            });             

        });
    </script>
@endpush