@extends('backend.layouts.app')
@section('title', 'Testimonials')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">Testimonials</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-comments text-muted me-2"></i> All Testimonials
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addTestiBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Testimonial
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="testiTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="10%">Image</th>
                                    <th width="25%">Name & Info</th>
                                    <th width="20%">Rating</th>
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

    <div class="modal fade" id="testiModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="testiForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="testimonial_id" name="testimonial_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Testimonial</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g., Alaxis D. Dowson">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Designation / Company</label>
                                <input type="text" name="designation" id="designation" class="form-control" placeholder="e.g., Head Of Idea">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Review Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="e.g., Product Quality">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Rating <span class="text-danger">*</span></label>
                                <select name="rating" id="rating" class="form-select">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Review Content <span class="text-danger">*</span></label>
                                <textarea name="review" id="review" rows="4" class="form-control" placeholder="Write customer feedback here..."></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Customer Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <div id="image_preview" class="mt-2" style="display: none;">
                                    <img src="" style="height: 60px; width: 60px; object-fit: cover; border-radius: 50%; border: 1px solid #ddd;">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Testimonial</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#testiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('testimonials.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'rating', orderable: false, searchable: false },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search testimonials...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#addTestiBtn').click(function () {
            $('#testiForm')[0].reset();
            $('#testimonial_id').val('');
            $('#image_preview').hide();
            $('#modalTitle').text('Add New Testimonial');
            $('#testiModal').modal('show');
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/testimonials/${id}/edit`,
                type: "GET",
                success: function (data) {
                    $('#testimonial_id').val(data.id);
                    $('#name').val(data.name);
                    $('#designation').val(data.designation);
                    $('#title').val(data.title);
                    $('#rating').val(data.rating);
                    $('#review').val(data.review);
                    $('#status').val(data.status);
                    
                    if(data.image) {
                        $('#image_preview img').attr('src', '/' + data.image);
                        $('#image_preview').show();
                    } else {
                        $('#image_preview').hide();
                    }

                    $('#modalTitle').text('Edit Testimonial');
                    $('#testiModal').modal('show');
                }
            });
        });

        $('#testiForm').submit(function (e) {
            e.preventDefault();
            let id = $('#testimonial_id').val();
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
            
            let url = id ? `/admin/testimonials/${id}` : "{{ route('testimonials.store') }}";
            $('#saveBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: "POST", 
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#saveBtn').prop('disabled', false).text('Save Testimonial');
                    if(res.status === 'success') {
                        toastr.success(res.message);
                        $('#testiModal').modal('hide');
                        setTimeout(function() { window.location.reload(); }, 500); 
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').prop('disabled', false).text('Save Testimonial');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = '';
                        $.each(xhr.responseJSON.errors, function(key, value) { errors += value + '<br>'; });
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