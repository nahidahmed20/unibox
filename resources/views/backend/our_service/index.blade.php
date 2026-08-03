@extends('backend.layouts.app')
@section('title', ' Our Services')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">Our Services</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-briefcase text-muted me-2"></i> All Services
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addServiceBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Service
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="serviceTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Image</th>
                                    <th width="40%">Title</th>
                                    <th width="15%">Status</th>
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="serviceModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="serviceForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="service_id" name="service_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Service</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="fw-semibold">Service Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="e.g., 3D Signage Solutions">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Short Description</label>
                                <textarea name="short_description" id="short_description" rows="2" class="form-control" placeholder="Brief summary for homepage..."></textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Detailed Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Full service details..."></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Icon (Class Name)</label>
                                <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g., fa-solid fa-star">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Service Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <div id="image_preview" class="mt-2" style="display: none;">
                                    <img src="" style="height: 60px; border-radius: 6px; border: 1px solid #ddd;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Initialize DataTable
        let table = $('#serviceTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('our-services.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'title', className: 'fw-bold' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
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
                searchPlaceholder: "Search sevices...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        // Open Modal for Add
        $('#addServiceBtn').click(function () {
            $('#serviceForm')[0].reset();
            $('#service_id').val('');
            $('#image_preview').hide();
            $('#modalTitle').text('Add New Service');
            $('#serviceModal').modal('show');
        });

        // Open Modal for Edit
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');

            $.ajax({
                url: `/admin/our-services/${id}/edit`, // Update this URL if your route prefix is different
                type: "GET",
                success: function (data) {
                    $('#service_id').val(data.id);
                    $('#title').val(data.title);
                    $('#status').val(data.status);
                    $('#short_description').val(data.short_description);
                    $('#description').val(data.description);
                    $('#icon').val(data.icon);
                    
                    // Show Image preview if exists
                    if(data.image) {
                        $('#image_preview img').attr('src', '/' + data.image);
                        $('#image_preview').show();
                    } else {
                        $('#image_preview').hide();
                    }

                    $('#modalTitle').text('Edit Service');
                    $('#serviceModal').modal('show');
                }
            });
        });

        // Save Data (Add / Edit via FormData)
        $('#serviceForm').submit(function (e) {
            e.preventDefault();
            
            let id = $('#service_id').val();
            let formData = new FormData(this);

            if (id) {
                formData.append('_method', 'PUT');
            }
            
            let url = id ? `/admin/our-services/${id}` : "{{ route('our-services.store') }}";

            $('#saveBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: "POST", 
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#saveBtn').prop('disabled', false).text('Save Service');
                    
                    if(res.status === 'success') {
                        toastr.success(res.message);
                        $('#serviceModal').modal('hide');
                        
                        setTimeout(function() {
                            window.location.reload();
                        }, 500); 
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').prop('disabled', false).text('Save Service');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = '';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errors += value + '<br>';
                        });
                        toastr.error(errors);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });
    });
</script>
@endpush