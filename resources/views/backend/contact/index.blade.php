@extends('backend.layouts.app')
@section('title', 'Contact Messages')

@section('content')
    {{-- HEADER --}}
    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Contact Messages
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
                            Contact Messages
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
                {{-- CARD HEADER --}}
                <div class="modern-card-header d-flex align-items-center justify-content-between">
                    <h4 class="card-title mb-0">
                        <i class="fa-solid fa-envelope text-muted me-2"></i>
                        All Messages
                    </h4>
                </div>
                {{-- TABLE --}}
                <div class="card-body p-0">
                    <div class="p-4">
                        <table id="contactTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Phone</th>
                                    <th>Message</th>
                                    <th width="15%" class="text-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $key => $contact)
                                    <tr>
                                        <td> {{ $key + 1 }} </td>
                                        <td> {{ $contact->name }} </td>
                                        <td> {{ $contact->email }} </td>
                                        <td> {{ $contact->subject ?? '—' }} </td>
                                        <td> {{ $contact->phone ?? '—' }} </td>
                                        <td> {{ Str::limit($contact->message, 40) }} </td>
                                        <td class="text-center">
                                            <button class="btn btn-action btn-view" data-id="{{ $contact->id }}">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <form class="d-inline delete-form" data-id="{{ $contact->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-action btn-delete-custom btn-delete">
                                                    <i class="fa-solid fa-trash"></i>
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

    {{-- VIEW MODAL --}}
    <style>
    .shopify-modal .modal-content {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: #fff;
    }

    .shopify-modal .modal-header {
        background: #111827;
        color: #fff;
        padding: 16px 20px;
        border-bottom: none;
    }

    .shopify-modal .modal-title {
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .shopify-modal .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }

    .shopify-modal .modal-body {
        padding: 24px;
        background: #f9fafb;
    }

    .info-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 16px;
        transition: 0.2s ease;
        height: 100%;
    }

    .info-card:hover {
        border-color: #6366f1;
        box-shadow: 0 4px 14px rgba(99,102,241,0.08);
    }

    .info-card span {
        font-size: 12px;
        color: #6b7280;
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
    }

    .info-card p {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        word-break: break-word;
    }

    .message-card {
        min-height: 120px;
    }

    .shopify-modal .modal-footer {
        background: #fff;
        border-top: 1px solid #e5e7eb;
        padding: 14px 20px;
    }

    .btn-shopify {
        background: #111827;
        color: #fff;
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 14px;
        border: none;
    }

    .btn-shopify:hover {
        background: #000;
    }
</style>

<div class="modal fade shopify-modal" id="contactModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">
                <h5 class="modal-title">
                    📩 Contact Details
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <div class="info-card">
                            <span>Name</span>
                            <p id="c-name"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <span>Email</span>
                            <p id="c-email"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <span>Phone</span>
                            <p id="c-phone"></p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-card">
                            <span>Subject</span>
                            <p id="c-subject"></p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-card message-card">
                            <span>Message</span>
                            <p id="c-message"></p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button class="btn btn-shopify" data-bs-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
@push('styles')
    <style>
        .info-box {
            background: #f8f9fa;
            padding: 14px 18px;
            border-radius: 12px;
        }
        .info-box span {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
        }
        .info-box p {
            margin: 5px 0 0;
            font-size: 15px;
            font-weight: 500;
            color: #212529;
        }
        .message-box {
            min-height: 130px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#contactTable').DataTable({
                processing: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search messages...",
                    paginate: {
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    }
                },
                dom: '<"row align-items-center mb-4"' +
                    '<"col-md-4"l>' +
                    '<"col-md-4 d-flex justify-content-center"B>' +
                    '<"col-md-4 d-flex justify-content-end"f>' +
                    '>rt' +
                    '<"d-flex justify-content-between mt-4"ip>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fa-regular fa-copy"></i> Copy'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-regular fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa-solid fa-file-csv"></i> CSV'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa-regular fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-solid fa-print"></i> Print'
                    }
                ]
            });

            // VIEW
            $(document).on('click', '.btn-view', function() {
                let id = $(this).data('id');
                $.get("{{ url('/admin/contacts') }}/" + id, function(data) {
                    $('#c-name').text(data.name);
                    $('#c-email').text(data.email ?? 'N/A');
                    $('#c-phone').text(data.phone ?? 'N/A');
                    $('#c-subject').text(data.subject ?? 'N/A');
                    $('#c-message').text(data.message);
                    $('#contactModal').modal('show');
                });
            });
        });
    </script>
@endpush
