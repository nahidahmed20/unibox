@extends('backend.layouts.app')
@section('title', 'Unit List')

@section('content')

    {{-- HEADER --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">

                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Units
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
                            Unit List
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
                        <i class="fa-solid fa-ruler text-muted me-2"></i>
                        All Units
                    </h4>

                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addUnitBtn">
                        <i class="fa-solid fa-plus me-1"></i>
                        Add Unit
                    </button>

                </div>

                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="p-4">

                        <table id="unitTable" class="table table-modern table-hover w-100">

                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Unit Name</th>
                                    <th>Short Name</th>
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

    {{-- MODAL --}}

    <div class="modal fade" id="unitModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <form id="unitForm">
                    @csrf
                    <input type="hidden" id="unit_id" name="unit_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center"
                        style="background:#000032; color:#fff; padding:15px 20px;">

                        <h4 class="card-title mb-0" style="color:#fff;">
                            <i class="fa-solid fa-ruler me-2" style="color:#fff;"></i>
                            <span id="modalTitle" style="color:#fff;">
                                Add Unit
                            </span>
                        </h4>

                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">

                        <div class="mb-3">
                            <label class="form-label">Unit Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Short Name</label>
                            <input type="text" class="form-control" id="short_name" name="short_name">
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
            var table = $('#unitTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('units.index') }}",
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
                        data: 'short_name',
                        name: 'short_name'
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
            $('#addUnitBtn').click(function() {
                $('#unitForm')[0].reset();
                $('#unit_id').val('');
                $('#modalTitle').text('Add Unit');
                $('#unitModal').modal('show');
            });

            // Open Edit Modal
            $(document).on('click', '.btn-edit', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/units') }}/" + id + "/edit", function(data) {
                    console.log(data);
                    $('#name').val(data.name);
                    $('#short_name').val(data.short_name);
                    $('#unit_id').val(data.id);
                    $('#modalTitle').text('Edit Unit');
                    $('#unitModal').modal('show');
                });
            });

            $('#unitForm').submit(function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let id = $('#unit_id').val();

                if (id) {
                    updateUnit(id, formData);
                } else {
                    storeUnit(formData);
                }
            });

            function storeUnit(formData) {
                $.ajax({
                    url: "{{ route('units.store') }}",
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#unitModal').modal('hide');
                        toastr.success(data.message);

                        $('#unitTable').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        showAjaxErrors(xhr);
                    }
                });
            }


            function updateUnit(id, formData) {
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ url('/admin/units') }}/" + id,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#unitModal').modal('hide');
                        toastr.success(data.message);

                        // DataTable reload for updated row
                        $('#unitTable').DataTable().ajax.reload(null, false);
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
