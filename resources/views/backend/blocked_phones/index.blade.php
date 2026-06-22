@extends('backend.layouts.app')
@section('title', 'Return Track & Block List')

@section('content')
<style>
    .shopify-swal-popup {
        border-radius: 12px !important;
        padding: 2em !important;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .shopify-swal-title {
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        color: #111827 !important;
    }
    .shopify-swal-text {
        color: #6b7280 !important;
        font-size: 0.95rem !important;
    }
    .shopify-swal-confirm-btn {
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 500 !important;
        box-shadow: none !important;
    }
    .shopify-swal-cancel-btn {
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 500 !important;
        background-color: #ffffff !important;
        color: #374151 !important;
        border: 1px solid #d1d5db !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }
    .shopify-swal-cancel-btn:hover {
        background-color: #f9fafb !important;
    }
</style>
<div class="content-wrapper bg-light" style="min-height: 100vh; padding-top: 20px;">
    <div class="container-fluid px-4">
        
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="m-0 fw-bold text-dark" style="font-size: 1.5rem;">Return Tracking</h2>
                <small class="text-muted">Track customers with multiple returns and manage blocks</small>
            </div>
        </div>

        {{-- Main Card --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff;">
            <div class="card-body p-0">
                <div class="p-4">
                    <table id="returnTrackTable" class="table table-hover align-middle mb-0 w-100" style="color: #303030;">
                        <thead class="bg-light" style="border-bottom: 1px solid #ebebeb;">
                            <tr>
                                <th class="text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">#SL</th>
                                <th class="text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">CUSTOMER NAME</th>
                                <th class="text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">PHONE NUMBER</th>
                                <th class="text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">TOTAL RETURNS</th>
                                <th class="text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">STATUS</th>
                                <th class="text-end text-muted fw-semibold py-3 border-0" style="font-size: 0.85rem;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#returnTrackTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('blocked-phones.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'phone', className: 'fw-medium' },
                { data: 'return_count', className: 'text-danger fw-bold' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
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
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search phone or name...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="bi bi-chevron-left"></i>',
                    next: '<i class="bi bi-chevron-right"></i>'
                }
            }
        });

        $(document).on('click', '.btn-block', function () {
            let phone = $(this).data('phone');

            Swal.fire({
                title: 'Block this number?',
                text: "They will not be able to place any new orders.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d82c0d', // Shopify Destructive Red
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'Block Number',
                cancelButtonText: 'Cancel',
                reverseButtons: true, // Cancel বাটন বামে, Main বাটন ডানে রাখার জন্য
                customClass: {
                    popup: 'shopify-swal-popup',
                    title: 'shopify-swal-title',
                    htmlContainer: 'shopify-swal-text',
                    confirmButton: 'shopify-swal-confirm-btn',
                    cancelButton: 'shopify-swal-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('blocked-phones.block') }}", { _token: "{{ csrf_token() }}", phone: phone }, function(res) {
                        toastr.success(res.message);
                        $('#returnTrackTable').DataTable().ajax.reload(null, false);
                    });
                }
            });
        });

        // Unblock Phone (Shopify SweetAlert)
        $(document).on('click', '.btn-unblock', function () {
            let phone = $(this).data('phone');

            Swal.fire({
                title: 'Unblock this number?',
                text: "This customer will be able to place orders again.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#111827', // Shopify Dark Primary
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'Yes, Unblock',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'shopify-swal-popup',
                    title: 'shopify-swal-title',
                    htmlContainer: 'shopify-swal-text',
                    confirmButton: 'shopify-swal-confirm-btn',
                    cancelButton: 'shopify-swal-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("{{ route('blocked-phones.unblock') }}", { _token: "{{ csrf_token() }}", phone: phone }, function(res) {
                        toastr.success(res.message);
                        $('#returnTrackTable').DataTable().ajax.reload(null, false);
                    });
                }
            });
        });
    });
</script>
@endpush