@extends('backend.layouts.app')
@section('title', 'Blogs List')
@section('content')
    {{-- HEADER --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Blogs
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
                            Blog List
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
                        <i class="fa-solid fa-blog text-muted me-2"></i>
                        All Blogs
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addBlogBtn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Blog
                    </button>
                </div>
                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="blogTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th> Author</th>
                                    <th> Date </th>
                                    <th> Status </th>
                                    <th> Image </th>
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
    {{-- MODALS --}}
    @include('backend.blogs.create')
    @include('backend.blogs.edit')

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#blogTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('blogs.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'author',
                        name: 'author'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
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
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search blogs...",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                }
            });
            // ADD BLOG
            $('#addBlogBtn').click(function() {
                $('#createBlogForm')[0].reset();
                $('#previewCreateImage').hide();
                $('#createBlogModal').modal('show');
            });
            // CREATE
            $('#createBlogForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('blogs.store') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#createBlogModal').modal('hide');
                        toastr.success(res.message);
                        table.ajax.reload(null, false);
                    },
                    error:function(xhr){
                        showErrors(xhr);
                    }
                });
            });
            // EDIT
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/blogs') }}/" + id + "/edit", function(data) {
                    $('#editBlogId').val(data.id);
                    $('#editBlogTitle').val(data.title);
                    $('#editBlogSlug').val(data.slug);
                    $('#editBlogCategory').val(data.blog_category_id);
                    $('#editStatus').val(data.status);
                    $('#editBlogDate').val(data.date);
                    $('#editDescription').val(data.description);
                    if (data.image) {
                        $('#previewEditImage')
                            .attr('src', '/' + data.image)
                            .show();
                    } else {
                        $('#previewEditImage').hide();
                    }
                    $('#editBlogModal').modal('show');
                });
            });
            // UPDATE
            $('#editBlogForm').submit(function(e) {
                e.preventDefault();
                let id = $('#editBlogId').val();
                let formData = new FormData(this);
                formData.append('_method', 'PUT');
                $.ajax({
                    url: "{{ url('/admin/blogs') }}/" + id,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#editBlogModal').modal('hide');
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
            // SLUG
            function generateSlug(value) {
                return value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }

            $('#blogTitle').on('input', function() {
                $('#blogSlug').val(
                    generateSlug($(this).val())
                );
            });
            $('#editBlogTitle').on('input', function() {
                $('#editBlogSlug').val(
                    generateSlug($(this).val())
                );
            });
        });
    </script>
@endpush
