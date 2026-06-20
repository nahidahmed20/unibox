@extends('backend.layouts.app')

@section('title', 'My Profile')

@section('content')

    <style>
        body {
            background: #f6f6f7;
        }

        .shopify-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
            overflow: hidden;
        }

        .card-header-custom {
            padding: 16px 20px;
            border-bottom: 1px solid #eef0f3;
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            background: #fff;
        }

        .card-body-custom {
            padding: 20px;
        }

        .profile-avatar {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f3f4f6;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .profile-email {
            color: #6b7280;
            font-size: 14px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            color: #111827;
            font-weight: 500;
        }

        .info-box {
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-box:last-child {
            border-bottom: none;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        .active-badge {
            background: #dcfce7;
            color: #15803d;
        }

        .inactive-badge {
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>

    <div class="app-content-header mt-3 mb-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="page-title mb-1">
                        My Profile
                    </h2>
                    <small class="text-muted">
                        View account information and details
                    </small>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-light border">
                    Back
                </a>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                {{-- SIDEBAR --}}
                <div class="col-lg-4 mb-4">
                    <div class="shopify-card">
                        <div class="card-body-custom text-center">
                            <img src="{{ $user->image ? asset($user->image) : 'https://ui-avatars.com/api/?name=' . $user->name }}"
                                class="profile-avatar">
                            <div class="mt-3">
                                <h3 class="profile-name mb-1">
                                    {{ $user->name }}
                                </h3>
                                <div class="profile-email">
                                    {{ $user->email ?? 'No Email' }}
                                </div>
                            </div>
                            <div class="mt-3">
                                @if ($user->status)
                                    <span class="status-badge active-badge">
                                        Active User
                                    </span>
                                @else
                                    <span class="status-badge inactive-badge">
                                        Inactive User
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="shopify-card mt-4">
                        <div class="card-header-custom">
                            Account Information
                        </div>
                        <div class="card-body-custom">
                            <div class="info-box">
                                <div class="info-label">User Type</div>
                                <div class="info-value">
                                    {{ ucfirst(str_replace('_', ' ', $user->type)) }}
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-label">Employee Code</div>
                                <div class="info-value">
                                    {{ $user->employee_code ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-label">Designation</div>
                                <div class="info-value">
                                    {{ $user->designation ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-label">Joining Date</div>
                                <div class="info-value">
                                    {{ $user->joining_date ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-box">
                                <div class="info-label">Last Login</div>
                                <div class="info-value">
                                    {{ $user->last_login_at ?? 'Never' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MAIN CONTENT --}}
                <div class="col-lg-8">
                    <div class="shopify-card mb-4">
                        <div class="card-header-custom">
                            Personal Information
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Full Name</div>
                                    <div class="info-value">{{ $user->name }}</div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Username</div>
                                    <div class="info-value">
                                        {{ $user->username ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">
                                        {{ $user->email ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Gender</div>
                                    <div class="info-value">
                                        {{ ucfirst($user->gender ?? 'N/A') }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-label">Birth Date</div>
                                    <div class="info-value">
                                        {{ $user->birth_date ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shopify-card mb-4">
                        <div class="card-header-custom">
                            Contact Information
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="info-label">Phone</div>
                                    <div class="info-value">
                                        {{ $user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="info-label">Alternate Phone</div>
                                    <div class="info-value">
                                        {{ $user->alternate_phone ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <div class="info-label">Emergency Contact</div>
                                    <div class="info-value">
                                        {{ $user->emergency_contact ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shopify-card">
                        <div class="card-header-custom">
                            Address Information
                        </div>
                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Division</div>
                                    <div class="info-value">
                                        {{ optional($division)->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">District</div>
                                    <div class="info-value">
                                        {{ optional($district)->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Upazila</div>
                                    <div class="info-value">
                                        {{ optional($upazila)->name ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-label">Postal Code</div>
                                    <div class="info-value">
                                        {{ $user->postal_code ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <div class="info-label">Address</div>
                                    <div class="info-value">
                                        {{ $user->address ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="info-label">Office Address</div>
                                    <div class="info-value">
                                        {{ $user->office_address ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
