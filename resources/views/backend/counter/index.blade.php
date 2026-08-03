@extends('backend.layouts.app')
@section('title', 'Counters')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6"><h3 class="mb-0 fw-bold" style="color:#212b36;">Success Counters</h3></div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0"><i class="fa-solid fa-calculator text-muted me-2"></i> All Counters</h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addCounterBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Counter
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="counterTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Icon</th>
                                    <th>Title</th>
                                    <th>Display Number</th>
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

    <div class="modal fade" id="counterModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="counterForm">
                    @csrf
                    <input type="hidden" id="counter_id">

                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Counter</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">Counter Title <span class="text-danger">*</span></label>
                                <input type="text" id="title" class="form-control" placeholder="e.g., Happy Clients" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Number <span class="text-danger">*</span></label>
                                <input type="number" id="number" class="form-control" placeholder="e.g., 500" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Suffix</label>
                                <input type="text" id="suffix" class="form-control" placeholder="e.g., +, %, K">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="fw-semibold">FontAwesome Icon Class</label>
                                <input type="text" id="icon" class="form-control" placeholder="e.g., fa-solid fa-face-smile">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Sort Order</label>
                                <input type="number" id="sort_order" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-semibold">Status</label>
                                <select id="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save Counter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let table = $('#counterTable').DataTable({
            processing: true, serverSide: true,
            ajax: "{{ route('counters.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'icon', orderable: false },
                { data: 'title', className: 'fw-bold' },
                { data: 'count_display' },
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
                searchPlaceholder: "Search counters...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    previous: '<i class="fa-solid fa-angle-left"></i>',
                    next: '<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#addCounterBtn').click(function () {
            $('#counterForm')[0].reset();
            $('#counter_id').val('');
            $('#modalTitle').text('Add New Counter');
            $('#counterModal').modal('show');
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.ajax({
                url: `/admin/counters/${id}/edit`,
                type: "GET",
                success: function (data) {
                    $('#counter_id').val(data.id);
                    $('#title').val(data.title);
                    $('#number').val(data.number);
                    $('#suffix').val(data.suffix);
                    $('#icon').val(data.icon);
                    $('#sort_order').val(data.sort_order);
                    $('#status').val(data.status);
                    $('#modalTitle').text('Edit Counter');
                    $('#counterModal').modal('show');
                }
            });
        });

        $('#counterForm').submit(function (e) {
            e.preventDefault();
            let id = $('#counter_id').val();
            let url = id ? `/admin/counters/${id}` : "{{ route('counters.store') }}";
            let formData = {
                title: $('#title').val(), number: $('#number').val(), suffix: $('#suffix').val(),
                icon: $('#icon').val(), sort_order: $('#sort_order').val(), status: $('#status').val(),
                _token: "{{ csrf_token() }}"
            };
            if (id) formData._method = "PUT";

            $('#saveBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: url, type: "POST", data: formData,
                success: function (res) {
                    toastr.success(res.message);
                    $('#counterModal').modal('hide');
                    setTimeout(function() { window.location.reload(); }, 500);
                },
                error: function () {
                    $('#saveBtn').prop('disabled', false).text('Save Counter');
                    toastr.error('Something went wrong!');
                }
            });
        });

    });
</script>
@endpush