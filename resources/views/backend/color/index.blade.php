@extends('backend.layouts.app')
@section('title', 'Color List')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Colors
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
                            Color List
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
                        <i class="fa-solid fa-tags text-muted me-2"></i>
                        All Colors
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addColorBtn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Color
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="colorTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Color Name</th>
                                    <th>Code</th>
                                    <th>Preview</th>
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

    <!--Sub Category Modal -->
    <div class="modal fade" id="colorModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="colorForm">
                    @csrf
                    <input type="hidden" id="color_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">
                            Add Color
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label>Color Name</label>
                            <input type="text" id="name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Color Code</label>
                            <input type="color" id="code" class="form-control form-control-color w-100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

    let table = $('#colorTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('colors.index') }}",
        lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', className:'fw-bold' },
            { data: 'code' },
            { data: 'preview', orderable:false, searchable:false },
            { data: 'action', orderable:false, searchable:false, className:'text-center' }
        ],
        dom:
            '<"row align-items-center mb-4"' +
            '<"col-md-4"l>' +
            '<"col-md-4 d-flex justify-content-center"B>' +
            '<"col-md-4 d-flex justify-content-end"f>' +
            '>rt' +
            '<"d-flex justify-content-between align-items-center mt-4"ip>',

        buttons: [
            {
                extend:'copy',
                text:'<i class="fa-regular fa-copy"></i> Copy'
            },
            {
                extend:'excel',
                text:'<i class="fa-regular fa-file-excel"></i> Excel'
            },
            {
                extend:'csv',
                text:'<i class="fa-solid fa-file-csv"></i> CSV'
            },
            {
                extend:'pdf',
                text:'<i class="fa-regular fa-file-pdf"></i> PDF'
            },
            {
                extend:'print',
                text:'<i class="fa-solid fa-print"></i> Print'
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
    $('#addColorBtn').click(function () {
        $('#colorForm')[0].reset();
        $('#color_id').val('');
        $('#modalTitle').text('Add Color');
        $('#colorModal').modal('show');
    });

    // EDIT
    $(document).on('click', '.btn-edit', function () {

        let id = $(this).data('id');

        $.ajax({
            url: `/admin/colors/${id}/edit`,
            type: "GET",
            success: function (data) {

                console.log(data); // 🔥 debug check

                $('#color_id').val(data.id);
                $('#name').val(data.name);
                $('#code').val(data.code);

                $('#modalTitle').text('Edit Color');
                $('#colorModal').modal('show');
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

    // SAVE
    $('#colorForm').submit(function (e) {
        e.preventDefault();

        let id = $('#color_id').val();

        let url = id
            ? `/admin/colors/${id}`
            : "{{ route('colors.store') }}";

        let formData = {
            name: $('#name').val(),
            code: $('#code').val(),
            _token: "{{ csrf_token() }}"
        };

        if (id) formData._method = "PUT";

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            success: function (res) {
                toastr.success(res.message);
                $('#colorModal').modal('hide');
                table.ajax.reload(null, false);
            },
            error:function(xhr){
                showErrors(xhr);
            }
        });
    });

    
});
</script>
@endpush
