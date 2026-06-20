@extends('backend.layouts.app')
@section('title', 'Sub Categories')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Sub Categories
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
                            Sub Category List
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
                        <i class="fa-solid fa-sitemap text-muted me-2"></i>
                        All Sub Categories
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addSubCategoryBtn">

                        <i class="fa-solid fa-plus me-1"></i>
                        Add Sub Category

                    </button>

                </div>

                <div class="card-body p-0">

                    <div class="p-4">

                        <table id="subCategoryTable" class="table table-modern table-hover w-100">

                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Category</th>
                                    <th width="20%">Sub Category</th>
                                    <th width="15%">Image</th>
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

    <!-- Sub Category Modal -->
    <div class="modal fade" id="subCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="subCategoryForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="sub_category_id" name="sub_category_id">
                    <div class="modal-content">
                        <div class="modern-card-header d-flex justify-content-between align-items-center"
                            style="background:#000032; color:#fff; padding:15px 20px;">

                            <h4 class="card-title mb-0" style="color:#fff;">
                                <i class="fa-solid fa-sitemap me-2" style="color:#fff;"></i>
                                <span id="modalTitle" style="color:#fff;">
                                    Add Sub Category
                                </span>
                            </h4>

                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label">Category </label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sub Category Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" name="image">
                                <img id="previewImage" src="" style="max-height:100px;display:none" class="mt-2 rounded">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#subCategoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('sub-categories.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category_name',
                        name: 'category.name',
                        className: 'fw-semibold'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        className: 'fw-bold text-dark'
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
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

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search sub categories...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });
            // Open Add Modal
            $('#addSubCategoryBtn').click(function() {
            $('#subCategoryForm')[0].reset();
            $('#sub_category_id').val('');
            $('#modalTitle').text('Add Sub Category');
            $('#previewImage').hide();

            const modal = new bootstrap.Modal(
                document.getElementById('subCategoryModal')
            );

            modal.show();
            });

            // Open Edit Modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');

                $.get("{{ url('/admin/sub-categories') }}/" + id + "/edit", function(data) {
                    $('#category_id').val(data.category_id);
                    $('#name').val(data.name);
                    if (data.image) {
                        $('#previewImage').attr('src', data.image).show();
                    } else {
                        $('#previewImage').hide();
                    }
                    $('#sub_category_id').val(data.id);
                    $('#modalTitle').text('Edit Sub Category');
                    $('#subCategoryModal').modal('show');
                });
            });

            $('#subCategoryForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#sub_category_id').val();

                if (id) {
                    updateSubCategory(id, formData);
                } else {
                    storeSubCategory(formData);
                }
            });

            function storeSubCategory(formData) {
                $.ajax({
                    url: "{{ route('sub-categories.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#subCategoryModal').modal('hide');
                        toastr.success(data.message);

                        $('#subCategoryTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showAjaxErrors(xhr);
                    }
                });
            }


            function updateSubCategory(id, formData) {
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ url('/admin/sub-categories') }}/" + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#subCategoryModal').modal('hide');
                        toastr.success(data.message);

                        // DataTable reload for updated row
                        $('#subCategoryTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showAjaxErrors(xhr);
                    }
                });
            }

            function showAjaxErrors(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = xhr.responseJSON.errors;
                    let errorMsg = '';
                    $.each(errors, function(key, val) {
                        errorMsg += val + "<br>";
                    });
                    toastr.error(errorMsg, 'Error', {
                        timeOut: 5000,
                        closeButton: true,
                        escapeHtml: false
                    });
                } else {
                    toastr.error('Something went wrong!', 'Error');
                }
            }

        });
    </script>
@endpush
