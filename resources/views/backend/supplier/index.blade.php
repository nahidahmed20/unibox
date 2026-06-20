@extends('backend.layouts.app')

@section('title', 'Supplier List')

@section('content')
    {{-- HEADER --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Suppliers
                    </h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">
                                Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item active fw-bold text-dark">
                            Supplier List
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card shadow-sm">
                {{-- HEADER --}}
                <div class="modern-card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-truck-field text-muted me-2"></i>
                        All Suppliers
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addSupplierBtn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Supplier
                    </button>
                </div>
                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="supplierTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th width="15%" class="text-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD EDIT MODAL --}}
    <div class="modal fade" id="supplierModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="supplierForm">
                    @csrf
                    <input type="hidden" id="supplier_id" name="supplier_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032;color:#fff;padding:15px 20px;">
                        <h4 class="card-title mb-0 text-white">
                            <i class="fa-solid fa-truck-field me-2"></i>
                            <span id="modalTitle">
                                Add Supplier
                            </span>
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Email
                                </label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Phone
                                </label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Address
                                </label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    City
                                </label>
                                <input type="text" class="form-control" id="city" name="city">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    State
                                </label>
                                <input type="text" class="form-control" id="state" name="state">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Zip Code
                                </label>
                                <input type="text" class="form-control" id="zip_code" name="zip_code">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Website
                                </label>
                                <input type="text" class="form-control" id="website" name="website">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Status
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="1">
                                        Active
                                    </option>
                                    <option value="0">
                                        Inactive
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
   {{-- SHOW SUPPLIER MODAL --}}
    <div class="modal fade" id="showSupplierModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                {{-- HEADER --}}
                <div class="modal-header"
                    style="background:#000032;color:#fff;padding:18px 25px;">
                    <h5 class="modal-title fw-bold text-white">
                        <i class="fa-solid fa-truck-field me-2"></i>
                        Supplier Details
                    </h5>
                    <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>
                </div>
                {{-- BODY --}}
                <div class="modal-body p-4">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <tbody>
                                <tr>
                                    <th width="30%">
                                        <i class="fa-solid fa-user me-2 text-muted"></i>
                                        Name
                                    </th>
                                    <td id="show_name">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fa-solid fa-phone me-2 text-muted"></i>
                                        Phone
                                    </th>
                                    <td id="show_phone"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fa-solid fa-envelope me-2 text-muted"></i>
                                        Email
                                    </th>
                                    <td id="show_email"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fa-solid fa-location-dot me-2 text-muted"></i>
                                        Address
                                    </th>
                                    <td id="show_address"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fa-solid fa-city me-2 text-muted"></i>
                                        City
                                    </th>
                                    <td id="show_city"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <i class="fa-solid fa-toggle-on me-2 text-muted"></i>
                                        Status
                                    </th>
                                    <td id="show_status"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- FOOTER --}}
                <div class="modal-footer border-0">
                    <button type="button"
                        class="btn btn-secondary rounded-pill px-4"
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
            let table = $('#supplierTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('suppliers.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: '<"row align-items-center mb-4"' +
                    '<"col-md-4"l>' +
                    '<"col-md-4 d-flex justify-content-center"B>' +
                    '<"col-md-4 d-flex justify-content-end"f>' +
                    '>rt' +
                    '<"d-flex justify-content-between align-items-center mt-4"ip>',

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
                order:[[1,'asc']],

                language:{
                    search:"_INPUT_",
                    searchPlaceholder:"Search brands...",
                    lengthMenu:"Show _MENU_ entries",

                    paginate:{
                        previous:'<i class="fa-solid fa-angle-left"></i>',
                        next:'<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });
            // ADD
            $('#addSupplierBtn').click(function() {
                $('#supplierForm')[0].reset();
                $('#supplier_id').val('');
                $('#modalTitle').text('Add Supplier');
                $('#supplierModal').modal('show');
            });
            // EDIT
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/suppliers') }}/" + id + "/edit",
                    function(data) {
                        let s = data.supplier;
                        $('#supplier_id').val(s.id);
                        $('#name').val(s.name);
                        $('#email').val(s.email);
                        $('#phone').val(s.phone);
                        $('#address').val(s.address);
                        $('#city').val(s.city);
                        $('#state').val(s.state);
                        $('#zip_code').val(s.zip_code);
                        $('#website').val(s.website);
                        $('#status').val(s.status);
                        $('#modalTitle').text('Edit Supplier');
                        $('#supplierModal').modal('show');
                    });
            });
            // SAVE
            $('#supplierForm').submit(function(e) {
                e.preventDefault();
                let id = $('#supplier_id').val();
                let formData = new FormData(this);
                let url = id?
                    "{{ url('/admin/suppliers') }}/" + id :
                    "{{ route('suppliers.store') }}";
                if (id) {
                    formData.append('_method', 'PUT');
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#supplierModal').modal('hide');
                        toastr.success(data.message);
                        table.ajax.reload(null, false);
                    },
                    error:function(xhr){
                        showErrors(xhr);
                    }
                });
            });
            function showErrors(xhr) {
                if (xhr.responseJSON &&
                    xhr.responseJSON.errors) {
                    let errors = '';
                    $.each(xhr.responseJSON.errors,
                        function(key, value) {
                            errors += value + '<br>';
                        });
                    toastr.error(errors);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
            // SHOW
            $(document).on('click', '.show-btn', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/suppliers') }}/" + id,
                    function(data) {
                        let s = data.supplier;
                        $('#show_name').text(s.name);
                        $('#show_phone').text(s.phone);
                        $('#show_email').text(s.email);
                        $('#show_address').text(s.address);
                        $('#show_city').text(s.city);
                        $('#show_status').text(
                            s.status == 1 ? 'Active' : 'Inactive'
                        );
                        $('#showSupplierModal').modal('show');
                    });
            });
        });
    </script>
@endpush
