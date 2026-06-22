@extends('backend.layouts.app')
@section('title', 'Courier List')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Couriers
                    </h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active fw-bold text-dark">
                            Courier List
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-truck-fast text-muted me-2"></i>
                        All Couriers
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addCourierBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Courier
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="courierTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Courier Name</th>
                                    <th>Contact Number</th>
                                    <th>Status</th>
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

    <div class="modal fade" id="courierModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="courierForm">
                    @csrf
                    <input type="hidden" id="courier_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">
                            Add Courier
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Courier Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Pathao, Steadfast">
                        </div>

                        <div class="mb-3">
                            <label>Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="form-control" placeholder="Helpline Number">
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select id="is_active" name="is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        let table = $('#courierTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('couriers.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'contact_number' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom:
                '<"row align-items-center mb-4"' +
                '<"col-md-4"l>' +
                '<"col-md-4 d-flex justify-content-center"B>' +
                '<"col-md-4 d-flex justify-content-end"f>' +
                '>rt' +
                '<"d-flex justify-content-between align-items-center mt-4"ip>',
            buttons: [
                { extend: 'copy', text: '<i class="fa-regular fa-copy"></i> Copy' },
                { extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel' },
                { extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV' },
                { extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF' },
                { extend: 'print', text: '<i class="fa-solid fa-print"></i> Print' }
            ],
            order: [[1, 'asc']],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search couriers...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        // Error handling function
        function showErrors(xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                let errors = '';
                $.each(xhr.responseJSON.errors, function(key, value) {
                    errors += value + '<br>';
                });
                toastr.error(errors);
            } else {
                toastr.error('Something went wrong!');
            }
        }

        // Open Add Modal
        $('#addCourierBtn').click(function () {
            $('#courierForm')[0].reset();
            $('#courier_id').val('');
            $('#modalTitle').text('Add Courier');
            $('#courierModal').modal('show');
        });

        // Open Edit Modal
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.ajax({
                url: `/admin/couriers/${id}/edit`, // আপনার রাউটের প্রিফিক্স অনুযায়ী এটি ঠিক করে নেবেন
                type: "GET",
                success: function (data) {
                    $('#courier_id').val(data.id);
                    $('#name').val(data.name);
                    $('#contact_number').val(data.contact_number);
                    $('#is_active').val(data.is_active ? '1' : '0');

                    $('#modalTitle').text('Edit Courier');
                    $('#courierModal').modal('show');
                },
                error: function(xhr) {
                    showErrors(xhr);
                }
            });
        });

        // Save or Update Data via AJAX
        $('#courierForm').submit(function (e) {
            e.preventDefault();

            let id = $('#courier_id').val();
            let url = id ? `/admin/couriers/${id}` : "{{ route('couriers.store') }}";
            let methodType = id ? "PUT" : "POST";

            let formData = {
                name: $('#name').val(),
                contact_number: $('#contact_number').val(),
                is_active: $('#is_active').val(),
                _token: "{{ csrf_token() }}"
            };

            if (id) formData._method = "PUT";

            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                success: function (res) {
                    toastr.success(res.message);
                    $('#courierModal').modal('hide');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    showErrors(xhr);
                }
            });
        });

        // Handle Delete via AJAX (Assuming you are using SweetAlert for btn-delete outside this code. If not, just let it use normal form submit or add AJAX delete logic here).
        // Since your controller returns JSON for destroy, you should intercept the form submit.
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');

            if(confirm('Are you sure you want to delete this courier?')) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: form.serialize(),
                    success: function (res) {
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showErrors(xhr);
                    }
                });
            }
        });
    });
</script>
@endpush