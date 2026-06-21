@extends('backend.layouts.app')

@section('title', 'Stock Adjustment List')

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
                    <h3 class="mb-0 fw-bold" style="color: #212b36;">Stock Adjustments</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                        <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Adjustment List</li>
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
                    <h4 class="card-title"><i class="fa-solid fa-list-ul text-muted me-2"></i> All Adjustments</h4>
                    <a href="{{ route('stock-adjustments.create') }}" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-plus me-1"></i> Add Adjustment
                    </a>
                </div>
                
                <div class="card-body p-0">
                    <div class="p-4">
                        <table class="table table-striped table-bordered" id="adjustmentTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Adjustment No</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Notes</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ================= MODAL ================= -->
    <div class="modal fade" id="adjustmentModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content shopify-modal">

                <div class="modal-header shopify-header">
                    <h5 class="modal-title">Adjustment Details</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="adjustmentModalBody"></div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            // ================= YAJRA DATATABLE INITIALIZATION =================
            var table = $('#adjustmentTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('stock-adjustments.index') }}",

                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    { data: 'adjustment_no', className: "text-center fw-bold text-dark" },
                    { data: 'type', className: "text-center" },
                    { data: 'reason', className: "text-center" },
                    {
                        data: 'adjustment_date',
                        className: "text-center",
                        render: function(data) {
                            if (!data) return '-';
                            let d = new Date(data);
                            return d.getDate() + ' ' + d.toLocaleString('en-US', { month: 'short' }) + ' ' + d.getFullYear();
                        }
                    },
                    { 
                        data: 'note', 
                        className: "text-secondary small",
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
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
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF',
                        exportOptions: { columns: ':not(:last-child)' }
                    },
                    {
                        extend: 'print', text: '<i class="fa-solid fa-print"></i> Print',
                        exportOptions: { columns: ':not(:last-child)' }
                    }
                ],

                order: [[1, 'desc']],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search adjustments...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });

            // ================= SWEETALERT2 DELETE HANDLER =================
            $(document).on('click', '.btn-delete', function() {
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This stock adjustment record will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000032',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((res) => {
                    if (res.isConfirmed) {
                        $.post(form.attr('action'), form.serialize(), function(res) {
                            toastr.success(res.message || "Record deleted successfully!");
                            table.ajax.reload();
                        }).fail(function() {
                            toastr.error("Something went wrong while deleting!");
                        });
                    }
                });
            });

            $(document).on('click', '.btn-show', function() {
                let id = $(this).data('id');

                $.get('{{ url("admin/stock-adjustments") }}/' + id, function(res) {
                    let p = res.data;

                    let typeBadge = p.type === 'addition' 
                        ? `<span class="badge bg-soft-success text-success fw-bold px-3 py-1 rounded-pill">Addition</span>` 
                        : `<span class="badge bg-soft-danger text-danger fw-bold px-3 py-1 rounded-pill">Subtraction</span>`;

                    let html = `
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="shopify-box shadow-sm">
                                    <p class="mb-2"><b>Adjustment No:</b> <span class="text-dark fw-bold">${p.adjustment_no}</span></p>
                                    <p class="mb-2"><b>Reason:</b> <span class="badge bg-secondary text-white text-capitalize">${p.reason}</span></p>
                                    <p class="mb-0"><b>Date:</b> ${p.adjustment_date}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="shopify-box shadow-sm">
                                    <p class="mb-2"><b>Type:</b> ${typeBadge}</p>
                                    <p class="mb-0"><b>Notes:</b> <span class="text-muted">${p.note ? p.note : '-'}</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th class="text-center">SKU</th>
                                        <th class="text-center">Size</th>
                                        <th class="text-center">Color</th>
                                        <th class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    if (p.items && p.items.length > 0) {
                        p.items.forEach((item, i) => {
                            html += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td class="fw-bold text-dark">${item.product?.name ?? '-'}</td>
                                    <td class="text-center">${item.product?.sku ?? '-'}</td>
                                    <td class="text-center">${item.size?.size ?? '-'}</td>
                                    <td class="text-center">${item.color?.name ?? '-'}</td>
                                    <td class="text-center fw-bold text-primary">${item.quantity}</td>
                                </tr>`;
                        });
                    } else {
                        html += `<tr><td colspan="6" class="text-center py-3 text-muted">No Items Found</td></tr>`;
                    }

                    html += `
                                </tbody>
                            </table>
                        </div>`;

                    $('#adjustmentModalBody').html(html);
                    $('#adjustmentModal').modal('show');
                }).fail(function() {
                    toastr.error("Failed to fetch adjustment details!");
                });
            });

        });
    </script>
@endpush