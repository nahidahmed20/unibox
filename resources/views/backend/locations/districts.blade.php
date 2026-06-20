@extends('backend.layouts.app')

@section('title', 'Districts')

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
            box-shadow: 0 0 0 .15rem rgba(0, 128, 96, .15);
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
            letter-spacing: .5px;
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

        .district-avatar {
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


        .badge-division {
            background: #eef7f4;
            color: #008060;
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
                District Management
            </h3>
            <div class="page-subtitle">
                Manage all districts from here
            </div>
        </div>

        <div class="row">
            <!-- Add District -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle text-success me-2"></i>
                            Add District
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('locations.districts.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    Division
                                </label>
                                <select name="division_id" class="form-select select2" required>
                                    <option value="">
                                        Select Division
                                    </option>
                                    @foreach ($divisions as $division)
                                        <option value="{{ $division->id }}">
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">
                                    District Name
                                </label>
                                <input type="text" name="name" class="form-control" placeholder="Enter District Name"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-save w-100">
                                <i class="fas fa-save me-2"></i>
                                Save District
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- District List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            District List
                        </h5>
                        <span class="badge bg-success">
                            Total: {{ $districts->count() }}
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="districtTable" class="table align-middle">
                                <thead>
                                    <tr>
                                        <th width="60">#</th>
                                        <th>District</th>
                                        <th>Division</th>
                                        <th width="120">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($districts as $district)
                                        <tr>
                                            <td>
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="district-avatar me-3">
                                                        {{ mb_substr($district->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $district->name }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge-division">
                                                    {{ $district->parent->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-action btn-edit editDistrictBtn"
                                                    data-id="{{ $district->id }}" data-name="{{ $district->name }}"
                                                    data-division="{{ $district->parent_id }}">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <button type="button" class="btn btn-action btn-delete-custom btn-delete"
                                                    data-id="{{ $district->id }}">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                                <form id="delete-form-{{ $district->id }}"
                                                    action="{{ route('locations.districts.destroy', $district->id) }}"
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


    @include('backend.locations.edit-district')


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select Division',
                allowClear: true
            });

            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: 'Select Division',
                    allowClear: true,
                    width: '100%'
                });

                var table = $('#districtTable').DataTable({
                    responsive: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ],

                    columnDefs: [{
                        orderable: false,
                        targets: [0, 3] // SL & Action disable sorting
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

                // Dynamic Serial Number
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
                    dropdownParent: $('#editDistrictModal'),
                    width: '100%'
                });
                
                $(document).on('click', '.editDistrictBtn', function () {

                    let id = $(this).data('id');
                    let name = $(this).data('name');
                    let division = $(this).data('division');

                    console.log('Division ID = ', division);

                    $('#edit_name').val(name);

                    $('#edit_division_id')
                        .val(String(division))
                        .trigger('change.select2');

                    $('#editDistrictForm').attr(
                        'action',
                        "{{ url('admin/districts') }}/" + id
                    );

                    $('#editDistrictModal').modal('show');
                });
            });
        });
    </script>
@endpush
