@extends('backend.layouts.app')

@section('title', 'Purchase List')

@section('content')
@push('styles')
    <style>
        /* ================= SHOPIFY MODERN UI ================= */
        .shopify-modal {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .shopify-header {
            background: #ffffff;
            color: #202223;
            padding: 20px 24px;
            border-bottom: 1px solid #e1e3e5;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .shopify-header .modal-title {
            font-size: 1.15rem;
            color: #202223;
            letter-spacing: -0.3px;
        }

        .shopify-header .btn-close {
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .shopify-header .btn-close:hover {
            opacity: 1;
        }

        .shopify-card-custom {
            background: #ffffff;
            border: 1px solid #e1e3e5;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        }

        .shopify-card-custom h6 {
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #6d7175;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .shopify-data-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }
        .shopify-data-row:last-child {
            margin-bottom: 0;
        }

        .shopify-data-label {
            color: #6d7175;
        }

        .shopify-data-value {
            color: #202223;
            font-weight: 500;
        }

        /* Modern Table inside Modal */
        .shopify-table-container {
            border: 1px solid #e1e3e5;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .shopify-table {
            margin-bottom: 0;
        }

        .shopify-table th {
            background-color: #f4f6f8;
            color: #495057;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 1px solid #e1e3e5;
        }

        .shopify-table td {
            vertical-align: middle;
            padding: 16px;
            color: #202223;
            font-size: 0.9rem;
            border-bottom: 1px solid #f1f2f4;
        }

        /* Status Badges - Shopify Style */
        .badge-shopify-success {
            background-color: #c0ebd7;
            color: #008060;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .badge-shopify-danger {
            background-color: #ffd7d7;
            color: #d82c0d;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .badge-variant {
            background-color: #f4f6f8;
            border: 1px solid #e1e3e5;
            color: #202223;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
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

    <!-- Shopify Style Modal -->
    <div class="modal fade" id="purchaseModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shopify-modal">
                <div class="modal-header shopify-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold mb-0" id="modalInvoiceTitle">Purchase Details</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="purchaseModalBody" style="background-color: #f6f6f7;">
                    <!-- Ajax content goes here -->
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
                lengthMenu: [[10, 25, 100, 500, -1], [10, 25, 100, 500, "All"]],
                pageLength: 10,
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
                order: [[1, 'desc']], 
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
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    let totalAmount = api.column(4, { page: 'current' }).data().reduce((a, b) => intVal(a) + intVal(b), 0);
                    $('#totalAmount').html('<span class="me-1">৳</span>' + totalAmount.toLocaleString('en-US', {minimumFractionDigits: 2}));
                }
            });

            // ================= SHOW MODAL (Shopify Style) =================
            $(document).on('click', '.btn-show', function() {
                let id = $(this).data('id');
                let btn = $(this);
                
                btn.html('<i class="fa-solid fa-spinner fa-spin"></i>').addClass('disabled');

                $.get('/admin/purchases/' + id, function(res) {
                    let p = res.data;
                    let badgeClass = p.status == 1 ? 'badge-shopify-success' : 'badge-shopify-danger';
                    let statusText = p.status == 1 ? 'Active' : 'Inactive';
                    
                    // Update Header Title
                    $('#modalInvoiceTitle').text('Purchase: ' + p.invoice_no);

                    let html = `
                        <!-- Top Cards Info -->
                        <div class="row g-3 mb-4">
                            <!-- Supplier Card -->
                            <div class="col-md-6">
                                <div class="shopify-card-custom h-100">
                                    <h6>Supplier Details</h6>
                                    <div class="shopify-data-row">
                                        <span class="shopify-data-label">Supplier Name</span>
                                        <span class="shopify-data-value">${p.supplier?.name ?? 'Unknown Supplier'}</span>
                                    </div>
                                    <div class="shopify-data-row">
                                        <span class="shopify-data-label">Phone</span>
                                        <span class="shopify-data-value">${p.supplier?.phone ?? '-'}</span>
                                    </div>
                                    <div class="shopify-data-row">
                                        <span class="shopify-data-label">Address</span>
                                        <span class="shopify-data-value text-end" style="max-width: 60%;">${p.supplier?.address ?? '-'}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Summary Card -->
                            <div class="col-md-6">
                                <div class="shopify-card-custom h-100">
                                    <h6>Document Summary</h6>
                                    <div class="shopify-data-row">
                                        <span class="shopify-data-label">Purchase Date</span>
                                        <span class="shopify-data-value">${new Date(p.purchase_date).toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' })}</span>
                                    </div>
                                    <div class="shopify-data-row">
                                        <span class="shopify-data-label">Status</span>
                                        <span class="${badgeClass}">${statusText}</span>
                                    </div>
                                    <div class="shopify-data-row mt-3 pt-2" style="border-top: 1px dashed #e1e3e5;">
                                        <span class="shopify-data-label fw-bold text-dark">Total Amount</span>
                                        <span class="fs-5 fw-bold" style="color: #008060;">৳ ${parseFloat(p.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Table -->
                        <h6 class="fw-bold mb-3" style="color: #202223; font-size: 1rem;">Purchased Items</h6>
                        <div class="shopify-table-container bg-white">
                            <div class="table-responsive">
                                <table class="table shopify-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">#</th>
                                            <th>Product Details</th>
                                            <th class="text-center">Variant</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                    if (p.details && p.details.length > 0) {
                        p.details.forEach((item, i) => {
                            let sizeName = item.variant?.size?.name;
                            let colorName = item.variant?.color?.name;
                            
                            let variantHtml = '';
                            if (sizeName || colorName) {
                                variantHtml += '<div class="d-flex gap-1 justify-content-center flex-wrap">';
                                if (sizeName) variantHtml += `<span class="badge-variant">Size: ${sizeName}</span>`;
                                if (colorName) variantHtml += `<span class="badge-variant">Color: ${colorName}</span>`;
                                variantHtml += '</div>';
                            } else {
                                variantHtml = '<span class="text-muted" style="font-size:0.85rem;">Standard</span>';
                            }
                            
                            let productName = item.product?.name ?? 'Unknown Product';
                            let productSku = item.product_variant_id ? (item.variant?.sku ?? item.product?.sku) : (item.product?.sku ?? '-');

                            html += `
                                <tr>
                                    <td class="text-center text-muted">${i + 1}</td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1">${productName}</div>
                                        <div class="text-muted" style="font-size: 0.8rem;">SKU: ${productSku}</div>
                                    </td>
                                    <td class="text-center align-middle">${variantHtml}</td>
                                    <td class="text-end">৳ ${parseFloat(item.buying_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                    <td class="text-center fw-bold text-dark">${item.quantity}</td>
                                    <td class="text-end fw-bold text-dark">৳ ${parseFloat(item.total_price).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                </tr>`;
                        });
                    } else {
                        html += `<tr><td colspan="6" class="text-center text-muted py-4">No products found for this purchase.</td></tr>`;
                    }

                    html += `       </tbody>
                                </table>
                            </div>
                        </div>`;

                    $('#purchaseModalBody').html(html);
                    $('#purchaseModal').modal('show');
                    
                    btn.html('<i class="fa-regular fa-eye"></i>').removeClass('disabled');
                    
                }).fail(function() {
                    alert("Failed to load purchase details. Please try again.");
                    btn.html('<i class="fa-regular fa-eye"></i>').removeClass('disabled');
                });
            });            

        });
    </script>
@endpush