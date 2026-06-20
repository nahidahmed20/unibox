@extends('backend.layouts.app')

@section('title', 'Upazilas')

@push('styles')
    <style>
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #eef1f4;
            padding: 18px 24px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #212b36;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
        }

        .form-control,
        .form-select {
            border: 1px solid #dfe3e8;
            border-radius: 12px;
            height: 45px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #002142;
            box-shadow: 0 0 0 .15rem rgba(0, 33, 66, .15);
        }

        .btn-save {
            background: #002142;
            border: none;
            color: #fff;
            border-radius: 12px;
            height: 45px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #03274b;
            color: #fff;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f6f6f7;
            color: #6d7175;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: none;
        }

        .table tbody tr {
            transition: .2s;
        }

        .table tbody tr:hover {
            background: #f9fafb;
        }

        .table td {
            vertical-align: middle;
        }

        .upazila-avatar {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eef7f4;
            color: #008060;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-district {
            background: #eef7f4;
            color: #008060;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .badge-division {
            background: #eef2ff;
            color: #4338ca;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 600;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 45px !important;
            border: 1px solid #dfe3e8 !important;
            border-radius: 2px !important;
        }

        .select2-selection__rendered {
            line-height: 43px !important;
        }

        .select2-selection__arrow {
            height: 43px !important;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h3 class="page-title mb-1">
                Upazila Management
            </h3>
            <div class="page-subtitle">
                Manage all upazilas from here
            </div>
        </div>

        <div class="row">
            <!-- Add Upazila -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Add Upazila
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('locations.upazilas.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    District
                                </label>
                                <select name="district_id" class="form-select select2" required>
                                    <option value="">
                                        Select District
                                    </option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">
                                            {{ $district->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    Upazila Name
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="Enter Upazila Name" required>
                            </div>
                            <button type="submit" class="btn btn-save w-100">
                                <i class="fas fa-save me-2"></i>
                                Save Upazila
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            Upazila List
                        </h5>
                        <span class="badge bg-success">
                            Total : {{ $upazilas->count() }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="upazilaTable" class="table align-middle">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>Upazila</th>
                                        <th>District</th>
                                        <th>Division</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($upazilas as $upazila)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="upazila-avatar me-3">
                                                        {{ mb_substr($upazila->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $upazila->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-district">
                                                    {{ $upazila->parent->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-division">
                                                    {{ $upazila->parent->parent->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)"
                                                    class="btn btn-action btn-edit editUpazilaBtn"
                                                    data-id="{{ $upazila->id }}"
                                                    data-name="{{ $upazila->name }}"
                                                    data-district="{{ $upazila->parent_id }}">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-delete-custom btn-delete"
                                                    data-id="{{ $upazila->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                                <form id="delete-form-{{ $upazila->id }}"
                                                    action="{{ route('locations.upazilas.destroy', $upazila->id) }}"
                                                    method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.locations.edit-upazila')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select District',
                allowClear: true
            });
            let table = $('#upazilaTable').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [0, 4]
                }],
                order: [
                    [1, 'asc']
                ],
                dom: '<"row align-items-center mb-4"' +
                    '<"col-md-4"l>' +
                    '<"col-md-4 text-center"B>' +
                    '<"col-md-4"f>' +
                    '>' +
                    'rt' +
                    '<"row mt-4"' +
                    '<"col-md-6"i>' +
                    '<"col-md-6"p>' +
                    '>',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-light btn-sm',
                        text: '<i class="fa-regular fa-copy me-1"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-success btn-sm',
                        text: '<i class="fa-regular fa-file-excel me-1"></i> Excel'
                    },
                    {
                        extend: 'csv',
                        className: 'btn btn-info btn-sm',
                        text: '<i class="fa-solid fa-file-csv me-1"></i> CSV'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-danger btn-sm',
                        text: '<i class="fa-regular fa-file-pdf me-1"></i> PDF'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-dark btn-sm',
                        text: '<i class="fa-solid fa-print me-1"></i> Print'
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

            table.on('order.dt search.dt draw.dt', function() {
                let i = 1;
                table.cells(null, 0, {
                    search: 'applied',
                    order: 'applied'
                }).every(function() {
                    this.data(i++);
                });
            }).draw();

            $('.edit-select2').select2({
                dropdownParent: $('#editUpazilaModal'),
                width: '100%'
            });


            $(document).on('click', '.editUpazilaBtn', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let district = $(this).data('district');
                $('#edit_name').val(name);
                $('#edit_district_id')
                    .val(String(district))
                    .trigger('change');
                $('#editUpazilaForm').attr(
                    'action',
                    "{{ url('admin/upazilas') }}/" + id
                );
                $('#editUpazilaModal').modal('show');

            });
        });
    </script>
@endpush
