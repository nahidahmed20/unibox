@extends('backend.layouts.app')

@section('title', 'Roles')

@section('content')

{{-- HEADER --}}
<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#212b36;">
                    Roles
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
                        Role List
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

            {{-- HEADER --}}
            <div class="modern-card-header d-flex align-items-center justify-content-between">

                <h4 class="card-title mb-0">
                    <i class="fa-solid fa-user-shield text-muted me-2"></i>
                    All Roles
                </h4>

                <a href="{{ route('roles.create') }}"
                    class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i>
                    Add Role
                </a>

            </div>

            {{-- TABLE --}}
            <div class="card-body p-0">
                <div class="p-4">

                    <table id="roleTable" class="table table-modern table-hover w-100">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Name</th>
                                <th>Permissions</th>
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

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    let table = $('#roleTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: "{{ route('roles.index') }}",

        columns: [
            { data: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name' },
            { data: 'permissions', orderable:false, searchable:false },
            { data: 'action', orderable:false, searchable:false, className:'text-center' }
        ],

        dom: '<"row align-items-center mb-4"<"col-md-4"l><"col-md-4 d-flex justify-content-center"B><"col-md-4 d-flex justify-content-end"f>>rt<"d-flex justify-content-between align-items-center mt-4"ip>',
        buttons: [
            {
                extend: 'copy', text: '<i class="fa-regular fa-copy"></i> Copy',
                exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
            },
            {
                extend: 'excel', text: '<i class="fa-regular fa-file-excel"></i> Excel',
                exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
            },
            {
                extend: 'csv', text: '<i class="fa-solid fa-file-csv"></i> CSV',
                exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
            },
            {
                extend: 'pdf', text: '<i class="fa-regular fa-file-pdf"></i> PDF',
                exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
            },
            {
                extend: 'print', text: '<i class="fa-solid fa-print"></i> Print',
                exportOptions: { columns: ':not(:nth-child(5)):not(:nth-child(9))' }
            }
        ],

        order: [[1, 'asc']],
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search purchase...",
            lengthMenu: "Show _MENU_ entries",
            paginate: {
                previous: '<i class="fa-solid fa-angle-left"></i>',
                next: '<i class="fa-solid fa-angle-right"></i>'
            }
        },
    });


});
</script>
@endpush