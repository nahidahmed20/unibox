@extends('backend.layouts.app')

@section('title', 'Divisions')

@section('content')

<div class="container-fluid py-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Divisions</h3>
            <p class="text-muted mb-0">Manage all divisions from here</p>
        </div>
    </div>

    <div class="row">

        <!-- Add Division -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        Add Division
                    </h5>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('locations.divisions.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                Division Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control form-control-lg"
                                placeholder="Enter Division Name"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-save me-1"></i>
                            Save Division
                        </button>

                    </form>

                </div>

            </div>
        </div>

        <!-- Division List -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        Division List
                    </h5>

                    <span class="badge bg-primary">
                        Total: {{ $divisions->count() }}
                    </span>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table align-middle" id="divisionTable">

                            <thead>
                                <tr>
                                    <th width="70">#</th>
                                    <th>Division Name</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($divisions as $division)

                                <tr>
                                    <td>
                                        <span class="fw-semibold">
                                            {{ $loop->iteration }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">

                                            <div class="division-avatar me-3">
                                                {{ mb_substr($division->name,0,1) }}
                                            </div>

                                            <span class="fw-medium">
                                                {{ $division->name }}
                                            </span>

                                        </div>
                                    </td>
                                </tr>

                                @empty

                                <tr>
                                    <td colspan="2" class="text-center py-5">
                                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">
                                            No Division Found
                                        </p>
                                    </td>
                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')
<style>
    .card{
        border-radius:16px;
    }

    .form-control{
        border-radius:12px;
        border:1px solid #dfe3e8;
    }

    .form-control:focus{
        border-color:#008060;
        box-shadow:0 0 0 0.15rem rgba(0,128,96,.15);
    }

    .btn-primary{
        background:#002142;
        border:none;
        border-radius:12px;
    }

    .btn-primary:hover{
        background:#002a53;
    }

    .table thead th{
        background:#f6f6f7;
        border-bottom:none;
        font-size:13px;
        font-weight:600;
        color:#6d7175;
        text-transform:uppercase;
        letter-spacing:.5px;
    }

    .table tbody tr{
        transition:all .2s ease;
    }

    .table tbody tr:hover{
        background:#f9fafb;
    }

    .division-avatar{
        width:42px;
        height:42px;
        border-radius:12px;
        background:#eef7f4;
        color:#008060;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        font-size:16px;
    }

    .badge{
        padding:8px 12px;
        border-radius:10px;
    }

</style>
@endpush