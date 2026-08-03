@extends('backend.layouts.app')

@section('title', 'Blog Categories List')

@section('content')
    {{-- HEADER --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Blog Categories
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
                            Blog Categories
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
                {{-- CARD HEADER --}}
                <div class="modern-card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-layer-group text-muted me-2"></i>
                        All Categories
                    </h4>
                    @can('category.add')
                        <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addCategoryBtn">
                            <i class="fa-solid fa-plus me-1"></i>
                            Add Category
                        </button>
                    @endcan
                </div>

                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="categoryTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%"> # </th>
                                    <th> Name </th>
                                    <th>  Slug </th>
                                    <th width="15%" class="text-center"> Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALS --}}

    @include('backend.blog_categories.create')

    @include('backend.blog_categories.edit')



@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('blog-categories.index') }}",
                lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
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
                        data: 'slug',
                        name: 'slug'
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

                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search blog category...",
                    lengthMenu: "Show _MENU_ entries",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });
            // ADD MODAL
            $('#addCategoryBtn').click(function() {
                $('#createCategoryForm')[0].reset();
                $('#createCategoryModal').modal('show');
            });

            // CREATE
            $('#createCategoryForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('blog-categories.store') }}",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#createCategoryModal').modal('hide');
                        toastr.success(res.message);
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


            // EDIT OPEN
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/blog-categories') }}/" + id + "/edit",
                    function(data) {
                        $('#editCategoryId').val(data.id);
                        $('#editCategoryName').val(data.name);
                        $('#editCategorySlug').val(data.slug);
                        $('#editCategoryModal').modal('show');
                    });
            });

            // UPDATE
            $('#editCategoryForm').submit(function(e) {
                e.preventDefault();
                let id = $('#editCategoryId').val();
                $.ajax({
                    url: "{{ url('/admin/blog-categories') }}/" + id,
                    method: "PUT",
                    data: $(this).serialize(),
                    success: function(res) {
                        $('#editCategoryModal').modal('hide');
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    },
                    error:function(xhr){
                        showErrors(xhr);
                    }
                });
            });

            // SLUG GENERATE
            function generateSlug(value) {
                return value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
            $('#categoryName').on('input', function() {
                $('#categorySlug').val(
                    generateSlug($(this).val())
                );
            });
            $('#editCategoryName').on('input', function() {
                $('#editCategorySlug').val(
                    generateSlug($(this).val())
                );
            });
        });
    </script>
@endpush
