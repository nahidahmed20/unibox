@extends('backend.layouts.app')
@section('title', 'Order Sale List')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Order Sale</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Order Sale</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title mb-0 me-auto">Order Sale List</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="orderTable" class="table table-striped table-bordered w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th class="text-center">Status</th>
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

<!-- View Order Modal -->
<div class="modal fade" id="showOrderModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="orderDetails">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered rounded-0">
        <div class="modal-content border-0 shadow-lg rounded-0">
            <div class="modal-header bg-primary text-white rounded-0">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="statusModalBody"></div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    $(function () {
        let table = $('#orderTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('order.sales') }}",
            columns: [
                { data: 'DT_RowIndex', orderable:false, searchable:false },
                { data: 'invoice_no' },
                { data: 'customer_name' },
                { data: 'order_date' },
                { data: 'total_amount' },
                { data: 'payment_status', orderable:false },
                { data: 'status', orderable:false },
                { data: 'action', orderable:false },
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
                searchPlaceholder: "Search orders...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            },

        });

        $(document).on('click', '.btn-show', function () {
            let id = $(this).data('id');

            $('#orderDetails').html('<div class="text-center py-5">Loading...</div>');
            $('#showOrderModal').modal('show');

            $.get("{{ url('orders') }}/" + id, function (html) {
                $('#orderDetails').html(html);
            });
        });


        $(document).on('click', '.btn-delete', function () {
            if (confirm('Are you sure want to delete this order?')) {
                $(this).closest('form').submit();
            }
        });
    });

    $(document).on('click', '.btn-status', function () {
        let orderId = $(this).data('id');

        $('#order_id').val(orderId); 

        $('#statusModalBody').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
        $('#statusModal').modal('show');

        $.get("{{ url('orders/status-modal') }}/" + orderId, function (html) {
            $('#statusModalBody').html(html);
        });
    });


    $(document).on('submit', '#statusForm', function (e) {
        e.preventDefault();

        let orderId = $('#order_id').val();
        let status  = $('#statusSelect').val();

        $.ajax({
            url: "{{ route('single.status.update', ':id') }}".replace(':id', orderId),
            type: "PATCH",
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function (res) {
                toastr.success(res.message);
                $('#statusModal').modal('hide');
                $('#orderTable').DataTable().ajax.reload(null, false);
            },
            error: function () {
                toastr.error('Something went wrong!');
            }
        });
    });


</script>
@endpush
