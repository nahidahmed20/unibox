@extends('backend.layouts.app')

@section('title', 'Edit Profile')

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
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            min-height: 42px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #111827;
        }

        .sticky-sidebar {
            position: sticky;
            top: 20px;
        }

        .profile-image {
            width: 140px;
            height: 140px;
            border-radius: 16px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }

        .info-item {
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .btn-shopify {
            background: #111827;
            color: #fff;
            border-radius: 10px;
            height: 44px;
            border: none;
            font-weight: 600;
        }

        .btn-shopify:hover {
            background: #000;
            color: #fff;
        }

        .required {
            color: red;
        }

        .select2-container--default .select2-selection--single {
            height: 42px !important;
            border: 1px solid #d1d5db !important;
            border-radius: 2px !important;
            display: flex !important;
            align-items: center !important;
        }

        .select2-container--default .select2-selection__rendered {
            line-height: 40px !important;
        }

        .select2-container {
            width: 100% !important;
        }
    </style>

    <div class="app-content-header mt-3 mb-4">
        <div class="container-fluid">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h3 class="page-title mb-1">
                        Edit Profile
                    </h3>

                    <small class="text-muted">
                        Manage your profile information
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
            <form action="{{ route('user-profiles.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- LEFT --}}
                    <div class="col-lg-8">

                        {{-- PERSONAL --}}
                        <div class="shopify-card p-4">

                            <div class="section-title">
                                Personal Information
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Full Name <span class="required">*</span>
                                    </label>

                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $user->name) }}">

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Username
                                    </label>

                                    <input type="text" name="username" class="form-control"
                                        value="{{ old('username', $user->username) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Gender
                                    </label>

                                    <select name="gender" class="form-select select2">
                                        <option value="">Select Gender</option>

                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>
                                            Other
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Birth Date
                                    </label>

                                    <input type="date" name="birth_date" class="form-control"
                                        value="{{ old('birth_date', $user->birth_date) }}">
                                </div>

                            </div>

                        </div>

                        {{-- CONTACT --}}
                        <div class="shopify-card p-4">

                            <div class="section-title">
                                Contact Information
                            </div>

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <label class="form-label">Phone</label>

                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Alternate Phone
                                    </label>

                                    <input type="text" name="alternate_phone" class="form-control"
                                        value="{{ old('alternate_phone', $user->alternate_phone) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Emergency Contact
                                    </label>

                                    <input type="text" name="emergency_contact" class="form-control"
                                        value="{{ old('emergency_contact', $user->emergency_contact) }}">
                                </div>

                            </div>

                        </div>

                        {{-- ADDRESS --}}
                        <div class="shopify-card p-4">

                            <div class="section-title">
                                Address Information
                            </div>

                            <div class="row g-3">

                                <div class="col-12">
                                    <label class="form-label">Address</label>

                                    <textarea name="address" rows="3" class="form-control">{{ old('address', $user->address) }}</textarea>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">
                                        Office Address
                                    </label>

                                    <textarea name="office_address" rows="3" class="form-control">{{ old('office_address', $user->office_address) }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Division
                                    </label>

                                    <select name="division_id" id="division_id" class="form-select select2">

                                        <option value="">
                                            Select Division
                                        </option>

                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        District
                                    </label>

                                    <select name="district_id" id="district_id" class="form-select select2">

                                        <option value="">
                                            Select District
                                        </option>

                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}"
                                                {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                                {{ $district->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">
                                        Upazila
                                    </label>

                                    <select name="upazila_id" id="upazila_id" class="form-select select2">

                                        <option value="">
                                            Select Upazila
                                        </option>

                                        @foreach ($upazilas as $upazila)
                                            <option value="{{ $upazila->id }}"
                                                {{ old('upazila_id', $user->upazila_id) == $upazila->id ? 'selected' : '' }}>
                                                {{ $upazila->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Country
                                    </label>

                                    <input type="text" name="country" class="form-control"
                                        value="{{ old('country', $user->country) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        Postal Code
                                    </label>

                                    <input type="text" name="postal_code" class="form-control"
                                        value="{{ old('postal_code', $user->postal_code) }}">
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- RIGHT SIDEBAR --}}
                    <div class="col-lg-4">

                        <div class="shopify-card p-4 sticky-sidebar">

                            <div class="text-center mb-3">

                                <img id="previewImage"
                                    src="{{ $user->image ? asset($user->image) : asset('backend/assets/img/user_image.png') }}"
                                    class="profile-image">

                            </div>

                            <label class="form-label">
                                Profile Image
                            </label>

                            <input type="file" name="image" id="image" class="form-control">

                            <hr>

                            <div class="info-item">
                                <strong>Employee Code</strong><br>
                                {{ $user->employee_code ?? 'N/A' }}
                            </div>

                            <div class="info-item">
                                <strong>Designation</strong><br>
                                {{ $user->designation ?? 'N/A' }}
                            </div>

                            <div class="info-item">
                                <strong>User Type</strong><br>
                                {{ ucfirst($user->type) }}
                            </div>

                            <button type="submit" class="btn btn-shopify w-100 mt-4">

                                <i class="bi bi-check-circle"></i>
                                Update Profile

                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.select2').select2({
                width: '100%'
            });

            $('#image').change(function() {

                let reader = new FileReader();

                reader.onload = function(e) {

                    $('#previewImage').attr('src', e.target.result);

                }

                reader.readAsDataURL(this.files[0]);

            });

            $('#division_id').change(function() {

                let id = $(this).val();

                $('#district_id').html('<option>Loading...</option>');

                $.get('/admin/get-locations/' + id, function(data) {

                    let html = '<option value="">Select District</option>';

                    $.each(data, function(index, row) {

                        html += `<option value="${row.id}">
                            ${row.name}
                        </option>`;
                    });

                    $('#district_id').html(html);

                    $('#upazila_id').html(
                        '<option value="">Select Upazila</option>'
                    );

                });

            });

            $('#district_id').change(function() {

                let id = $(this).val();

                $('#upazila_id').html('<option>Loading...</option>');

                $.get('/admin/get-locations/' + id, function(data) {

                    let html = '<option value="">Select Upazila</option>';

                    $.each(data, function(index, row) {

                        html += `<option value="${row.id}">
                            ${row.name}
                        </option>`;
                    });

                    $('#upazila_id').html(html);

                });

            });

        });
    </script>
@endpush
