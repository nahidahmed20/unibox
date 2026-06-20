@extends('backend.layouts.app')
@section('title', 'Features')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold" style="color:#212b36;">Why Choose Us (Features)</h3></div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fa-solid fa-star text-muted me-2"></i> All Features</h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addFeatureBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Feature
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="featureTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Media</th>
                                    <th>Title</th>
                                    <th>Sort</th>
                                    <th>Status</th>
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

    <div class="modal fade" id="featureModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="featureForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="feature_id" name="feature_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Feature</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Feature Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Description</label>
                                <textarea name="description" id="description" rows="3" class="form-control" placeholder="Short detail about this feature..."></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Icon Class (Optional)</label>
                                <input type="text" name="icon" id="icon" class="form-control" placeholder="e.g., fa-solid fa-shield">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Or Upload Image</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Sort Order</label>
                                <input type="number" name="sort_order" id="sort_order" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <div id="image_preview" class="mt-2" style="display: none;">
                                    <label class="fw-bold d-block text-muted">Current Image:</label>
                                    <img src="" style="height: 60px; border-radius: 6px; border: 1px solid #ddd; padding: 3px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save Feature</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#featureTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('features.index') }}",
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'media', orderable: false },
                { data: 'title', className: 'fw-bold' },
                { data: 'sort_order' },
                { data: 'status', orderable: false },
                { data: 'action', orderable: false, className: 'text-center' }
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
                searchPlaceholder: "Search features...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#addFeatureBtn').click(function () {
            $('#featureForm')[0].reset();
            $('#feature_id').val('');
            $('#image_preview').hide();
            $('#modalTitle').text('Add New Feature');
            $('#featureModal').modal('show');
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/features/${id}/edit`,
                type: "GET",
                success: function (data) {
                    $('#feature_id').val(data.id);
                    $('#title').val(data.title);
                    $('#description').val(data.description);
                    $('#icon').val(data.icon);
                    $('#sort_order').val(data.sort_order);
                    $('#status').val(data.status);
                    if(data.image) {
                        $('#image_preview img').attr('src', '/' + data.image);
                        $('#image_preview').show();
                    } else { $('#image_preview').hide(); }
                    $('#modalTitle').text('Edit Feature');
                    $('#featureModal').modal('show');
                }
            });
        });

        $('#featureForm').submit(function (e) {
            e.preventDefault();
            let id = $('#feature_id').val();
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
            let url = id ? `/admin/features/${id}` : "{{ route('features.store') }}";

            $('#saveBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: url, type: "POST", data: formData, contentType: false, processData: false,
                success: function (res) {
                    toastr.success(res.message);
                    $('#featureModal').modal('hide');
                    setTimeout(function() { window.location.reload(); }, 500);
                },
                error: function () {
                    $('#saveBtn').prop('disabled', false).text('Save Feature');
                    toastr.error('Something went wrong!');
                }
            });
        });

    });
</script>
@endpush