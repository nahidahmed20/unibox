@extends('backend.layouts.app')
@section('title', 'Team Members')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">Team Members</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-users text-muted me-2"></i> All Members
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addTeamBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Member
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="teamTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Image</th>
                                    <th width="25%">Name</th>
                                    <th width="20%">Designation</th>
                                    <th width="15%">Status</th>
                                    <th width="20%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="teamModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="teamForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="team_id" name="team_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Team Member</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g., John Doe">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Designation</label>
                                <input type="text" name="designation" id="designation" class="form-control" placeholder="e.g., Design Director">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-semibold">Facebook URL</label>
                                <input type="url" name="facebook" id="facebook" class="form-control" placeholder="https://facebook.com/...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-semibold">Twitter URL</label>
                                <input type="url" name="twitter" id="twitter" class="form-control" placeholder="https://twitter.com/...">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-semibold">Instagram URL</label>
                                <input type="url" name="instagram" id="instagram" class="form-control" placeholder="https://instagram.com/...">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Member Image</label>
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
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#teamTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('teams.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'image', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'designation' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
            buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search members...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#addTeamBtn').click(function () {
            $('#teamForm')[0].reset();
            $('#team_id').val('');
            $('#image_preview').hide();
            $('#modalTitle').text('Add New Member');
            $('#teamModal').modal('show');
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/teams/${id}/edit`,
                type: "GET",
                success: function (data) {
                    $('#team_id').val(data.id);
                    $('#name').val(data.name);
                    $('#designation').val(data.designation);
                    $('#facebook').val(data.facebook);
                    $('#twitter').val(data.twitter);
                    $('#instagram').val(data.instagram);
                    $('#status').val(data.status);
                    
                    if(data.image) {
                        $('#image_preview img').attr('src', '/' + data.image);
                        $('#image_preview').show();
                    } else {
                        $('#image_preview').hide();
                    }

                    $('#modalTitle').text('Edit Member');
                    $('#teamModal').modal('show');
                }
            });
        });

        $('#teamForm').submit(function (e) {
            e.preventDefault();
            let id = $('#team_id').val();
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
            
            let url = id ? `/admin/teams/${id}` : "{{ route('teams.store') }}";
            $('#saveBtn').prop('disabled', true).text('Saving...');

            $.ajax({
                url: url,
                type: "POST", 
                data: formData,
                contentType: false,
                processData: false,
                success: function (res) {
                    $('#saveBtn').prop('disabled', false).text('Save Member');
                    if(res.status === 'success') {
                        toastr.success(res.message);
                        $('#teamModal').modal('hide');
                        setTimeout(function() { window.location.reload(); }, 500); 
                    }
                },
                error: function (xhr) {
                    $('#saveBtn').prop('disabled', false).text('Save Member');
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