@extends('backend.layouts.app')
@section('title', 'Size List')

@section('content')

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">

                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Sizes
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
                            Size List
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
                        <i class="fa-solid fa-ruler text-muted me-2"></i>
                        All Sizes
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addSizeBtn">

                        <i class="fa-solid fa-plus me-1"></i>
                        Add Size

                    </button>

                </div>

                <div class="card-body p-0">

                    <div class="p-4">

                        <table id="sizeTable" class="table table-modern table-hover w-100">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Size Name</th>
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

    <!-- Modal -->

    <div class="modal fade" id="sizeModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="sizeForm">
                    @csrf
                    <input type="hidden" id="size_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">
                            Add Size
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Size Name</label>
                            <input type="text" id="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select id="status" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            var table = $('#sizeTable').DataTable({

                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('sizes.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        className: 'fw-bold'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
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

            $('#addSizeBtn').click(function() {

                $('#sizeForm')[0].reset();

                $('#size_id').val('');

                $('#modalTitle').text('Add Size');

                $('#sizeModal').modal('show');
            });

            $(document).on('click', '.btn-edit', function() {

                let id = $(this).data('id');

                $.get(`/admin/sizes/${id}/edit`, function(data) {

                    $('#size_id').val(data.id);

                    $('#name').val(data.name);

                    $('#status').val(data.status);

                    $('#modalTitle').text('Edit Size');

                    $('#sizeModal').modal('show');
                });
            });

            $('#sizeForm').submit(function(e) {

                e.preventDefault();

                let id = $('#size_id').val();

                let url = id ?
                    `/admin/sizes/${id}` :
                    "{{ route('sizes.store') }}";

                let formData = {
                    name: $('#name').val(),
                    status: $('#status').val(),
                    _token: "{{ csrf_token() }}"
                };

                if (id) {
                    formData._method = "PUT";
                }

                $.ajax({

                    url: url,
                    type: 'POST',
                    data: formData,

                    success: function(res) {

                        toastr.success(res.message);

                        $('#sizeModal').modal('hide');

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
        });
    </script>
@endpush
