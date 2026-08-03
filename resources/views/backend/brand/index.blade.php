@extends('backend.layouts.app')
@section('title', 'Brand List')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Brands
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
                            Brand List
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
                        All Brands
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addBrandBtn">

                        <i class="fa-solid fa-plus me-1"></i>
                        Add Brand

                    </button>

                </div>

                <div class="card-body p-0">

                    <div class="p-4">

                        <table id="brandTable" class="table table-modern table-hover w-100">

                            <thead>
                                <tr>
                                    <th width="8%">#</th>
                                    <th>Brand Name</th>
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

    <!--Sub Category Modal -->
    <div class="modal fade" id="brandModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="brandForm">
                    @csrf
                    <input type="hidden" id="brand_id" name="brand_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">

                        <h4 class="card-title mb-0" style="color:#fff;">
                            <i class="fa-solid fa-tags me-2" style="color:#fff;"></i>
                            <span id="modalTitle" style="color:#fff;">
                                Add Brand
                            </span>
                        </h4>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter Brand Name">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#brandTable').DataTable({

                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('brands.index') }}",
                lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],

                columns: [
                    {
                        data:'DT_RowIndex',
                        name:'DT_RowIndex',
                        orderable:false,
                        searchable:false
                    },
                    {
                        data:'name',
                        name:'name',
                        className:'fw-bold text-dark'
                    },
                    {
                        data:'action',
                        name:'action',
                        orderable:false,
                        searchable:false,
                        className:'text-center'
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
            // Open Add Modal
            $('#addBrandBtn').click(function() {
                $('#brandForm')[0].reset();
                $('#brand_id').val('');
                $('#modalTitle').text('Add Brand');
                $('#brandModal').modal('show');
            });

            // Open Edit Modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/brands') }}/" + id + "/edit", function(data) {
                    console.log(data);
                    $('#name').val(data.name);
                    $('#brand_id').val(data.id);
                    $('#modalTitle').text('Edit Brand');
                    $('#brandModal').modal('show');
                });
            });

            $('#brandForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#brand_id').val();

                if (id) {
                    updateBrand(id, formData);
                } else {
                    storeBrand(formData);
                }
            });

            function storeBrand(formData) {
                $.ajax({
                    url: "{{ route('brands.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#brandModal').modal('hide');
                        toastr.success(data.message);

                        $('#brandTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showAjaxErrors(xhr);
                    }
                });
            }


            function updateBrand(id, formData) {
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ url('/admin/brands') }}/" + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#brandModal').modal('hide');
                        toastr.success(data.message);

                        // DataTable reload for updated row
                        $('#brandTable').DataTable().ajax.reload(null, false);
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
