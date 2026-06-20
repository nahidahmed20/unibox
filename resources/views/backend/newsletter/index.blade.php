@extends('backend.layouts.app')
@section('title', 'Newsletter Subscriptions')

@section('content')

{{-- HEADER --}}
<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">

            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color:#212b36;">
                    Newsletter
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
                        Newsletter List
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
                    <i class="fa-solid fa-envelope text-muted me-2"></i>
                    All Subscriptions
                </h4>

            </div>

            {{-- TABLE --}}
            <div class="card-body p-0">
                <div class="p-4 table-responsive">

                    <table id="newsletterTable" class="table table-modern table-hover w-100">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Email</th>
                                <th>Subscribed Date</th>
                                <th width="15%" class="text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($newsletters as $key => $newsletter)
                            <tr>

                                <td>{{ $key + 1 }}</td>

                                <td>
                                    <span class="badge bg-light text-dark px-3 py-2">
                                        {{ $newsletter->email }}
                                    </span>
                                </td>

                                <td>
                                    {{ $newsletter->created_at->format('d M, Y H:i') }}
                                </td>

                                <td class="text-center">

                                    {{-- VIEW --}}
                                    <button class="btn btn-sm btn-light border btn-view"
                                        data-email="{{ $newsletter->email }}"
                                        data-created="{{ $newsletter->created_at->format('d M, Y H:i') }}">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>

                                    {{-- DELETE --}}
                                    <form class="d-inline delete-form"
                                        action="{{ route('newsletter.destroy', $newsletter->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                            class="btn btn-sm btn-danger-soft btn-delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
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

{{-- MODAL --}}
<div class="modal fade" id="newsletterModal">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content modern-card">

            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Subscription Details</h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <div class="mb-3">
                    <div class="text-muted small">Email</div>
                    <div class="fw-bold" id="n-email"></div>
                </div>

                <div>
                    <div class="text-muted small">Subscribed At</div>
                    <div class="fw-bold" id="n-created"></div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-dark rounded-pill px-4" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    // DATATABLE (same style as blog)
    $('#newsletterTable').DataTable({
        dom:
            '<"row align-items-center mb-4"' +
            '<"col-md-4"l>' +
            '<"col-md-4 d-flex justify-content-center"B>' +
            '<"col-md-4 d-flex justify-content-end"f>' +
            '>rt' +
            '<"d-flex justify-content-between align-items-center mt-4"ip>',

        buttons: [
            { extend:'copy', text:'<i class="fa-regular fa-copy"></i> Copy' },
            { extend:'excel', text:'<i class="fa-regular fa-file-excel"></i> Excel' },
            { extend:'csv', text:'<i class="fa-solid fa-file-csv"></i> CSV' },
            { extend:'pdf', text:'<i class="fa-regular fa-file-pdf"></i> PDF' },
            { extend:'print', text:'<i class="fa-solid fa-print"></i> Print' }
        ],

        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search newsletter...",
            paginate: {
                previous: '<i class="fa-solid fa-angle-left"></i>',
                next: '<i class="fa-solid fa-angle-right"></i>'
            }
        }
    });

    // VIEW
    $(document).on('click', '.btn-view', function () {
        $('#n-email').text($(this).data('email'));
        $('#n-created').text($(this).data('created'));

        new bootstrap.Modal(document.getElementById('newsletterModal')).show();
    });

});
</script>
@endpush