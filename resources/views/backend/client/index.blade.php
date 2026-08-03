@extends('backend.layouts.app')
@section('title', 'Clients')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">Our Clients</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fa-solid fa-users text-muted me-2"></i> All Clients</h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addClientBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Client
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="clientTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>URL</th>
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

    
    <div class="modal fade" id="clientModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="clientForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="client_id" name="client_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Client</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Client Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="e.g., Dhaka Plastic">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Website URL</label>
                                <input type="url" name="url" id="url" class="form-control" placeholder="https://example.com">
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

                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Client Logo <span class="text-danger">*</span></label>
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                <div id="logo_preview" class="mt-3" style="display: none;">
                                    <img src="" style="height: 60px; border: 1px solid #ddd; padding: 4px; border-radius: 6px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#clientTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('clients.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'logo', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'url', orderable: false },
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
                searchPlaceholder: "Search clients...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#addClientBtn').click(function () {
            $('#clientForm')[0].reset();
            $('#client_id').val('');
            $('#logo_preview').hide();
            $('#modalTitle').text('Add New Client');
            $('#clientModal').modal('show');
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/clients/${id}/edit`,
                type: "GET",
                success: function (data) {
                    $('#client_id').val(data.id);
                    $('#name').val(data.name);
                    $('#url').val(data.url);
                    $('#sort_order').val(data.sort_order);
                    $('#status').val(data.status);
                    if(data.logo) {
                        $('#logo_preview img').attr('src', '/' + data.logo);
                        $('#logo_preview').show();
                    }
                    $('#modalTitle').text('Edit Client');
                    $('#clientModal').modal('show');
                }
            });
        });

        $('#clientForm').submit(function (e) {
            e.preventDefault();
            let id = $('#client_id').val();
            let formData = new FormData(this);
            if (id) formData.append('_method', 'PUT');
            let url = id ? `/admin/clients/${id}` : "{{ route('clients.store') }}";

            $('#saveBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: url, type: "POST", data: formData, contentType: false, processData: false,
                success: function (res) {
                    toastr.success(res.message);
                    $('#clientModal').modal('hide');
                    setTimeout(function() { window.location.reload(); }, 500);
                },
                error: function (xhr) {
                    $('#saveBtn').prop('disabled', false).text('Save Client');
                    toastr.error('Something went wrong!');
                }
            });
        });

    });
</script>
@endpush