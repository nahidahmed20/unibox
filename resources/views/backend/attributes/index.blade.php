@extends('backend.layouts.app')
@section('title', 'Attributes Management')

@section('content')
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">Attributes</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="card modern-card">
                <div class="modern-card-header">
                    <h4 class="card-title">
                        <i class="fa-solid fa-layer-group text-muted me-2"></i> All Attributes
                    </h4>
                    <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm" id="addAttributeBtn">
                        <i class="fa-solid fa-plus me-1"></i> Add Attribute
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="attributeTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Options / Values & Extra Price</th>
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

    <!-- Modal -->
    <div class="modal fade" id="attributeModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="attributeForm">
                    @csrf
                    <input type="hidden" id="attribute_id">
                    <div class="modern-card-header d-flex justify-content-between align-items-center" style="background:#000032; color:#fff; padding:15px 20px;">
                        <h4 id="modalTitle" style="color:#fff; margin-bottom:0;">Add Attribute</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Attribute Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="e.g. Acrylic Quality, Thickness">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Input Type</label>
                                <select id="type" name="type" class="form-select">
                                    <option value="select">Dropdown (Select)</option>
                                    <option value="text">Manual Text (Input)</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Status</label>
                                <select id="status" name="status" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dynamic Options Builder -->
                        <div id="optionsBuilder" class="mt-3 p-3 bg-light rounded border">
                            <label class="fw-bold mb-2 d-block">Attribute Options & Extra Price</label>
                            <div id="dynamicOptionsWrapper">
                                <div class="input-group mb-2 option-row">
                                    <input type="text" name="options[0][value]" class="form-control" placeholder="Option Value (e.g. PK Acrylic)">
                                    <input type="number" step="0.01" name="options[0][extra_price]" class="form-control" placeholder="Extra Price (৳)">
                                    <button type="button" class="btn btn-danger remove-option"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-dark mt-2" id="addOptionBtn">
                                <i class="fa-solid fa-plus me-1"></i> Add Another Option
                            </button>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save Attribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var table = $('#attributeTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('attributes.index') }}",
            lengthMenu: [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
            columns: [
                { data: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', className: 'fw-bold' },
                { data: 'type' },
                { data: 'options', orderable: false },
                { data: 'status' },
                { data: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom: '<"row align-items-center mb-4"' +
                '<"col-md-4"l>' +
                '<"col-md-4 d-flex justify-content-center"B>' +
                '<"col-md-4 d-flex justify-content-end"f>' +
                '>rt' +
                '<"d-flex justify-content-between align-items-center mt-4"ip>',
            buttons: [
                { extend: 'copy', text: '<i class="fa-regular fa-copy"></i> Copy' },
                { extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel' },
                { extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV' },
                { extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF' },
                { extend: 'print', text: '<i class="fa-solid fa-print"></i> Print' }
            ],
            order:[[1,'asc']],
            language:{
                search:"_INPUT_",
                searchPlaceholder:"Search attributes...",
                lengthMenu:"Show _MENU_ entries",
                paginate:{
                    previous:'<i class="fa-solid fa-angle-left"></i>',
                    next:'<i class="fa-solid fa-angle-right"></i>'
                }
            }
        });

        $('#type').on('change', function() {
            if ($(this).val() == 'text') {
                $('#optionsBuilder').slideUp();
            } else {
                $('#optionsBuilder').slideDown();
            }
        });

        let optionIndex = 1;
        $('#addOptionBtn').on('click', function() {
            let row = `
                <div class="input-group mb-2 option-row">
                    <input type="text" name="options[${optionIndex}][value]" class="form-control" placeholder="Option Value">
                    <input type="number" step="0.01" name="options[${optionIndex}][extra_price]" class="form-control" placeholder="Extra Price (৳)">
                    <button type="button" class="btn btn-danger remove-option"><i class="fa-solid fa-xmark"></i></button>
                </div>
            `;
            $('#dynamicOptionsWrapper').append(row);
            optionIndex++;
        });

        $(document).on('click', '.remove-option', function() {
            $(this).closest('.option-row').remove();
        });

        $('#addAttributeBtn').click(function() {
            $('#attributeForm')[0].reset();
            $('#attribute_id').val('');
            optionIndex = 1;
            $('#dynamicOptionsWrapper').html(`
                <div class="input-group mb-2 option-row">
                    <input type="text" name="options[0][value]" class="form-control" placeholder="Option Value (e.g. PK Acrylic)">
                    <input type="number" step="0.01" name="options[0][extra_price]" class="form-control" placeholder="Extra Price (৳)">
                    <button type="button" class="btn btn-danger remove-option"><i class="fa-solid fa-xmark"></i></button>
                </div>
            `);
            $('#optionsBuilder').show();
            $('#modalTitle').text('Add Attribute');
            $('#attributeModal').modal('show');
        });

        // Open Edit Modal
        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');
            $.get(`/admin/attributes/${id}/edit`, function(data) {
                $('#attribute_id').val(data.id);
                $('#name').val(data.name);
                $('#type').val(data.type).trigger('change');
                $('#status').val(data.status);
                
                $('#dynamicOptionsWrapper').empty();
                optionIndex = 0;
                if(data.type == 'select' && data.options.length > 0) {
                    data.options.forEach(function(opt) {
                        $('#dynamicOptionsWrapper').append(`
                            <div class="input-group mb-2 option-row">
                                <input type="text" name="options[${optionIndex}][value]" class="form-control" value="${opt.value}">
                                <input type="number" step="0.01" name="options[${optionIndex}][extra_price]" class="form-control" value="${opt.extra_price}">
                                <button type="button" class="btn btn-danger remove-option"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        `);
                        optionIndex++;
                    });
                } else {
                    $('#dynamicOptionsWrapper').append(`
                        <div class="input-group mb-2 option-row">
                            <input type="text" name="options[0][value]" class="form-control" placeholder="Option Value">
                            <input type="number" step="0.01" name="options[0][extra_price]" class="form-control" placeholder="Extra Price (৳)">
                            <button type="button" class="btn btn-danger remove-option"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    `);
                    optionIndex = 1;
                }

                $('#modalTitle').text('Edit Attribute');
                $('#attributeModal').modal('show');
            });
        });

        // Save Form Data
        $('#attributeForm').submit(function(e) {
            e.preventDefault();
            let id = $('#attribute_id').val();
            let url = id ? `/admin/attributes/${id}` : "{{ route('attributes.store') }}";
            let formData = $(this).serializeArray();
            if (id) { formData.push({ name: "_method", value: "PUT" }); }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(res) {
                    toastr.success(res.message);
                    $('#attributeModal').modal('hide');
                    table.ajax.reload(null, false);
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, val) { toastr.error(val); });
                    } else { toastr.error('Something went wrong!'); }
                }
            });
        });

    });
</script>
@endpush