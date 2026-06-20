@extends('backend.layouts.app')

@section('title', 'Edit Role')

@section('content')

<style>
    body {
        background: #f6f6f7;
    }

    .shopify-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.04);
    }

    .page-title {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .sub-title {
        font-size: 13px;
        color: #6b7280;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .muted-text {
        font-size: 12px;
        color: #6b7280;
    }

    .permission-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 14px;
        border: 1px solid #eef0f2;
        border-radius: 10px;
        background: #fff;
        transition: .15s;
    }

    .permission-item:hover {
        border-color: #d1d5db;
        background: #fafafa;
    }

    .toggle {
        width: 40px;
        height: 22px;
        appearance: none;
        background: #d1d5db;
        border-radius: 999px;
        position: relative;
        cursor: pointer;
        transition: .2s;
    }

    .toggle:checked {
        background: #111827;
    }

    .toggle::before {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        background: #fff;
        border-radius: 50%;
        top: 2px;
        left: 2px;
        transition: .2s;
    }

    .toggle:checked::before {
        transform: translateX(18px);
    }

    .sticky-box {
        position: sticky;
        top: 20px;
    }

    .header-box {
        padding: 10px 0;
    }
</style>

<div class="app-content-header mt-3 mb-3">
    <div class="container-fluid">
        <div class="header-box">
            <div class="page-title">Edit Role</div>
            <div class="sub-title">Update role and permissions</div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- LEFT --}}
                <div class="col-lg-8">

                    {{-- ROLE CARD --}}
                    <div class="shopify-card p-4 mb-3">

                        <label class="section-title">Role Name</label>

                        <input type="text"
                               name="name"
                               class="form-control form-control-lg mt-2"
                               value="{{ old('name', $role->name) }}"
                               placeholder="e.g. Admin, Manager">

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </div>

                    {{-- PERMISSIONS --}}
                    <div class="shopify-card p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <div>
                                <div class="section-title">Permissions</div>
                                <div class="muted-text">Control access rights</div>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" id="allPermission" class="form-check-input">
                                <label class="form-check-label">Select All</label>
                            </div>

                        </div>

                        <div class="row">

                            @foreach($permissions as $permission)

                                <div class="col-md-6 mb-2">

                                    <div class="permission-item">

                                        <span class="fw-medium">
                                            {{ $permission->name }}
                                        </span>

                                        <input type="checkbox"
                                               class="toggle permission-checkbox"
                                               name="permissions[]"
                                               value="{{ $permission->name }}"
                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="col-lg-4">

                    <div class="shopify-card p-4 sticky-box">

                        <div class="section-title mb-1">Summary</div>
                        <div class="muted-text mb-3">
                            Review before updating
                        </div>

                        <button class="btn btn-dark w-100 rounded-pill">
                            Update Role
                        </button>

                        <a href="{{ route('roles.index') }}"
                           class="btn btn-light border w-100 rounded-pill mt-2">
                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    function syncGlobalCheckbox() {
        let total = $('.permission-checkbox').length;
        let checked = $('.permission-checkbox:checked').length;
        $('#allPermission').prop('checked', total === checked);
    }

    // initial sync on load
    syncGlobalCheckbox();

    // select all
    $('#allPermission').on('change', function () {
        let isChecked = $(this).is(':checked');
        $('.permission-checkbox').prop('checked', isChecked);
    });

    // single checkbox change
    $('.permission-checkbox').on('change', function () {
        syncGlobalCheckbox();
    });

});
</script>
@endpush