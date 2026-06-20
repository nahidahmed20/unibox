@extends('backend.layouts.app')
@section('title', 'Expense Categories')

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">

                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Expense Categories
                    </h3>
                </div>

                <div class="col-sm-6 text-end">
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addCategoryBtn">
                        + Add Category
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ================= CONTENT ================= --}}
    <div class="app-content">
        <div class="container-fluid">

            <div class="card modern-card shadow-sm">

                <div class="card-body p-0">

                    <div class="p-4">

                        <table id="categoryTable" class="table table-modern table-hover w-100 align-middle">

                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th width="15%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- AJAX LOAD --}}
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </div>

    {{-- ================= MODAL ================= --}}
    <div class="modal fade" id="categoryModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-card">

                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="modalTitle">Add Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">

                    <form id="categoryForm">

                        @csrf

                        {{-- IMPORTANT FIX --}}
                        <input type="hidden" id="categoryId" name="categoryId">

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Enter category name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" placeholder="Enter description"></textarea>
                        </div>

                    </form>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-dark px-4" id="saveBtn">
                        Save
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('expenses-categories.index') }}",

                columns: [{
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
                        data: 'description',
                        name: 'description'
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

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search categories...",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });


            // =========================
            // OPEN ADD MODAL
            // =========================
            $('#addCategoryBtn').click(function() {

                $('#categoryForm')[0].reset();
                $('#categoryId').val('');
                $('#modalTitle').text('Add Category');

                $('#categoryModal').modal('show');
            });

            $('#saveBtn').click(function(e) {

                e.preventDefault();

                let id = $('#categoryId').val();

                let url = id ?
                    "{{ url('/admin/expenses-categories') }}/" + id :
                    "{{ route('expenses-categories.store') }}";

                let formData = new FormData($('#categoryForm')[0]);

                if (id) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(res) {

                        $('#categoryModal').modal('hide');

                        toastr.success(res.message ?? 'Success');

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

            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('/admin/expenses-categories') }}/" + id + "/edit",
                    type: "GET",

                    success: function(data) {

                        $('#categoryId').val(data.id);
                        $('#name').val(data.name);
                        $('#description').val(data.description);

                        $('#modalTitle').text('Edit Category');

                        $('#categoryModal').modal('show');
                    },
                    error:function(xhr){
                        showErrors(xhr);
                    }
                });

            });
        });
    </script>
@endpush
