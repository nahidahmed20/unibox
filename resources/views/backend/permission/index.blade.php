@extends('backend.layouts.app')

@section('title', 'Permission List')

@section('content')

{{-- HEADER --}}
<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#212b36;">
                    Permissions
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
                        Permission List
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
                    <i class="fa-solid fa-key text-muted me-2"></i>
                    All Permissions
                </h4>

                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addPermissionBtn">
                    <i class="fa-solid fa-plus me-1"></i>
                    Add Permission
                </button>
            </div>

            {{-- TABLE --}}
            <div class="card-body p-0">
                <div class="p-4">

                    <table id="permissionTable" class="table table-modern table-hover w-100">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Name</th>
                                <th>Guard</th>
                                <th>Created At</th>
                                <th width="15%" class="text-center">Action</th>
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
<div class="modal fade" id="permissionModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="permissionForm">
                @csrf
                <input type="hidden" id="permission_id">

                {{-- HEADER --}}
                <div class="modern-card-header d-flex justify-content-between align-items-center"
                    style="background:#000032;color:#fff;padding:15px 20px;">

                    <h4 class="card-title mb-0 text-white">
                        <i class="fa-solid fa-key me-2"></i>
                        <span id="modalTitle">Add Permission</span>
                    </h4>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- BODY --}}
                <div class="card-body p-4">

                    <div class="mb-3">
                        <label class="form-label">Permission Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                </div>

                {{-- FOOTER --}}
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

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // DATATABLE (Supplier style)
    let table = $('#permissionTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('permissions.index') }}",
        lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', name:'name' },
            { data: 'guard_name', name:'guard_name' },
            { data: 'created_at', name:'created_at' },
            { data: 'action', orderable:false, searchable:false, className:'text-center' }
        ],

        dom: '<"row align-items-center mb-4"' +
            '<"col-md-4"l>' +
            '<"col-md-4 d-flex justify-content-center"B>' +
            '<"col-md-4 d-flex justify-content-end"f>' +
            '>rt' +
            '<"d-flex justify-content-between align-items-center mt-4"ip>',

        buttons: [
            { extend:'copy', text:'<i class="fa-regular fa-copy"></i> Copy' },
            { extend:'excel', text:'<i class="fa-regular fa-file-excel"></i> Excel' },
            { extend:'csv', text:'<i class="fa-solid fa-file-csv"></i> CSV' },
            { extend:'pdf', text:'<i class="fa-regular fa-file-pdf"></i> PDF' },
            { extend:'print', text:'<i class="fa-solid fa-print"></i> Print' }
        ],

        order: [[1,'asc']],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search permissions...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="fa-solid fa-angle-left"></i>',
                next: '<i class="fa-solid fa-angle-right"></i>'
            }
        }
    });


    // ADD
    $('#addPermissionBtn').click(function () {
        $('#permissionForm')[0].reset();
        $('#permission_id').val('');
        $('#modalTitle').text('Add Permission');
        $('#permissionModal').modal('show');
    });


    // EDIT
    $(document).on('click', '.btn-edit', function () {

        let id = $(this).data('id');

        $.get("{{ url('/admin/permissions') }}/" + id + "/edit", function (data) {

            $('#permission_id').val(data.id);
            $('#name').val(data.name);

            $('#modalTitle').text('Edit Permission');
            $('#permissionModal').modal('show');
        });

    });


    // SAVE (CREATE + UPDATE)
    $('#permissionForm').submit(function (e) {
        e.preventDefault();

        let id = $('#permission_id').val();

        let url = id
            ? "{{ url('/admin/permissions') }}/" + id
            : "{{ route('permissions.store') }}";

        let formData = new FormData(this);

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,

            success: function (data) {
                $('#permissionModal').modal('hide');
                toastr.success(data.message);
                table.ajax.reload(null, false);
            },

            error:function(xhr){
                showErrors(xhr);
            }
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


});
</script>
@endpush