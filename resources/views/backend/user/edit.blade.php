@extends('backend.layouts.app')
@section('title', 'Edit User')

@section('content')
    @push('styles')
        <style>
            /* Shopify Polaris Design Variables */
            :root {
                --p-surface: #ffffff;
                --p-background: #f4f6f8;
                --p-text: #202223;
                --p-text-subdued: #6d7175;
                --p-border: #c9cccf;
                --p-border-hover: #8c9196;
                --p-border-focus: #008060;
                --p-focused-shadow: 0 0 0 2px rgba(0, 128, 96, 0.2);
                --p-card-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
                --p-border-radius: 8px;
                --p-border-radius-input: 4px;
            }

            /* Page Header Typography */
            .page-title {
                font-size: 20px;
                font-weight: 600;
                color: var(--p-text);
                margin: 0 0 4px 0;
                line-height: 1.2;
            }

            .sub-title {
                font-size: 13px;
                color: var(--p-text-subdued);
                margin: 0;
            }

            /* Shopify Secondary Button (For 'Back') */
            .btn-shopify-secondary {
                background-color: #ffffff;
                color: #202223;
                border: 1px solid var(--p-border);
                border-radius: 4px;
                padding: 6px 12px;
                font-size: 14px;
                font-weight: 500;
                box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: background-color 0.2s, border-color 0.2s;
            }

            .btn-shopify-secondary:hover {
                background-color: #f4f6f8;
                border-color: var(--p-border-hover);
                color: #202223;
            }

            .btn-shopify-secondary svg {
                fill: currentColor;
                width: 16px;
                height: 16px;
            }

            /* Card Styling */
            .shopify-card {
                background-color: var(--p-surface);
                border-radius: var(--p-border-radius);
                box-shadow: var(--p-card-shadow);
                border: none;
                overflow: hidden;
            }

            .shopify-card-header {
                padding: 16px 20px;
                border-bottom: 1px solid var(--p-border);
            }

            .shopify-card-header h6 {
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: var(--p-text);
            }

            .shopify-card-body {
                padding: 20px;
            }

            /* Typography & Labels */
            .form-label {
                font-size: 13px;
                font-weight: 500;
                color: var(--p-text);
                margin-bottom: 4px;
                display: block;
            }

            /* Inputs & Selects */
            .form-control,
            .form-select {
                border: 1px solid var(--p-border);
                border-radius: var(--p-border-radius-input);
                min-height: 36px;
                padding: 6px 12px;
                font-size: 14px;
                color: var(--p-text);
                box-shadow: inset 0 1px 0 0 rgba(63, 63, 68, 0.05);
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .form-control:hover,
            .form-select:hover {
                border-color: var(--p-border-hover);
            }

            .form-control:focus,
            .form-select:focus {
                border-color: var(--p-border-focus);
                box-shadow: var(--p-focused-shadow);
                outline: none;
            }

            /* Image Upload Area */
            .image-upload-wrapper {
                border: 1px dashed var(--p-border-hover);
                border-radius: var(--p-border-radius);
                padding: 20px;
                text-align: center;
                background: #fafbfb;
                position: relative;
                cursor: pointer;
                transition: background 0.2s;
            }

            .image-upload-wrapper:hover {
                background: #f4f6f8;
            }

            .img-preview {
                width: 100%;
                max-height: 200px;
                border-radius: 6px;
                object-fit: contain;
                display: none;
                margin-top: 10px;
                border: 1px solid var(--p-border);
            }

            /* Select2 */
            .select2-container {
                width: 100% !important;
            }

            .select2-container--default .select2-selection--single {
                height: 36px !important;
                border: 1px solid var(--p-border) !important;
                border-radius: var(--p-border-radius-input) !important;
                background: var(--p-surface) !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 12px !important;
                box-shadow: inset 0 1px 0 0 rgba(63, 63, 68, 0.05);
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: var(--p-text) !important;
                font-size: 14px !important;
                line-height: 34px !important;
                padding-left: 0 !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 34px !important;
                right: 8px !important;
            }

            .select2-container--default.select2-container--focus .select2-selection--single,
            .select2-container--default .select2-selection--single:focus {
                border-color: var(--p-border-focus) !important;
                box-shadow: var(--p-focused-shadow) !important;
            }

            .select2-dropdown {
                border: 1px solid var(--p-border) !important;
                border-radius: var(--p-border-radius-input) !important;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .select2-search--dropdown .select2-search__field {
                border: 1px solid var(--p-border) !important;
                border-radius: 4px !important;
            }

            /* Primary Button Styling */
            .btn-shopify {
                background-color: #202223;
                color: #ffffff;
                border: 1px solid #202223;
                border-radius: 4px;
                padding: 8px 16px;
                font-size: 14px;
                font-weight: 500;
                box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05);
                transition: background-color 0.2s;
            }

            .btn-shopify:hover {
                background-color: #000000;
                color: #ffffff;
            }

            .sticky-top {
                top: 20px;
                z-index: 10;
            }
        </style>
    @endpush

    <div class="app-content-header mt-3 mb-4">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title" style="font-size: 20px; font-weight: 600; color: #202223; margin: 0;">Edit User</h1>
                <p class="sub-title" style="font-size: 13px; color: #6d7175; margin: 0;">Update information for: <strong class="text-dark">{{ $user->name }}</strong></p>
            </div>
            <a href="{{ route('users.index') }}" class="btn btn-light border px-4" style="border-radius: 4px; box-shadow: 0 1px 0 rgba(0,0,0,0.05);">
                Back
            </a>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-lg-8">

                        <div class="shopify-card mb-4 p-4 bg-white shadow-sm" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                            <div class="shopify-card-header mb-3 border-bottom pb-2">
                                <h6 class="fw-bold m-0 text-dark">Account Information</h6>
                            </div>
                            <div class="shopify-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="johndoe" value="{{ old('username', $user->username) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="john@example.com" value="{{ old('email', $user->email) }}">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">User Type</label>
                                        <select name="type" class="form-select select2">
                                            <option value="customer" {{ old('type', $user->type) == 'customer' ? 'selected' : '' }}>Customer</option>
                                            <option value="cashier" {{ old('type', $user->type) == 'cashier' ? 'selected' : '' }}>Cashier</option>
                                            <option value="manager" {{ old('type', $user->type) == 'manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="admin" {{ old('type', $user->type) == 'admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="super_admin" {{ old('type', $user->type) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Role <span class="text-danger">*</span></label>
                                        <select name="role" class="form-select select2" required>
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $role)
                                                @if ($role->name != 'Customer')
                                                    <option value="{{ $role->name }}" {{ in_array($role->name, $hasRoles) ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Password <span class="text-muted fw-normal" style="font-size: 11px;">(Leave blank to keep unchanged)</span></label>
                                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="shopify-card mb-4 p-4 bg-white shadow-sm" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                            <div class="shopify-card-header mb-3 border-bottom pb-2">
                                <h6 class="fw-bold m-0 text-dark">Personal Information</h6>
                            </div>
                            <div class="shopify-card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Employee Code</label>
                                        <input type="text" name="employee_code" class="form-control" placeholder="EMP-001" value="{{ old('employee_code', $user->employee_code) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Designation</label>
                                        <input type="text" name="designation" class="form-control" placeholder="Manager" value="{{ old('designation', $user->designation) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="+880" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Alternate Phone</label>
                                        <input type="text" name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $user->alternate_phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Emergency Contact</label>
                                        <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $user->emergency_contact) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Birth Date</label>
                                        <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date', $user->joining_date) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="shopify-card mb-4 p-4 bg-white shadow-sm" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                            <div class="shopify-card-header mb-3 border-bottom pb-2">
                                <h6 class="fw-bold m-0 text-dark">Address & Documents</h6>
                            </div>
                            <div class="shopify-card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Address</label>
                                        <textarea name="address" rows="3" class="form-control" placeholder="House/Street, Area...">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Division</label>
                                        <select name="division_id" id="division_id" class="form-select select2">
                                            <option value="">Select Division</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ old('division_id', $user->division_id) == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">District</label>
                                        <select name="district_id" id="district_id" class="form-select select2">
                                            <option value="">Select District</option>
                                            @if(isset($districts))
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}" {{ old('district_id', $user->district_id) == $district->id ? 'selected' : '' }}>
                                                        {{ $district->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Upazila</label>
                                        <select name="upazila_id" id="upazila_id" class="form-select select2">
                                            <option value="">Select Upazila</option>
                                            @if(isset($upazilas))
                                                @foreach ($upazilas as $upazila)
                                                    <option value="{{ $upazila->id }}" {{ old('upazila_id', $user->upazila_id) == $upazila->id ? 'selected' : '' }}>
                                                        {{ $upazila->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control" placeholder="1200" value="{{ old('postal_code', $user->postal_code) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Country</label>
                                        <input type="text" name="country" class="form-control" value="{{ old('country', $user->country ?? 'Bangladesh') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Salary</label>
                                        <input type="number" step="0.01" name="salary" class="form-control" placeholder="0.00" value="{{ old('salary', $user->salary) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">NID Number</label>
                                        <input type="text" name="nid_number" class="form-control" value="{{ old('nid_number', $user->nid_number) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Passport Number</label>
                                        <input type="text" name="passport_number" class="form-control" value="{{ old('passport_number', $user->passport_number) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="sticky-top" style="top: 20px;">
                            
                            <div class="shopify-card mb-4 p-4 bg-white shadow-sm" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                                <div class="shopify-card-header mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold m-0 text-dark">Profile Image</h6>
                                </div>
                                <div class="shopify-card-body text-center">
                                    <div class="image-upload-wrapper p-4" onclick="document.getElementById('image').click()" style="border: 2px dashed #c9cccf; border-radius: 8px; cursor: pointer; background: #f4f6f8; transition: 0.3s;">
                                        <input type="file" name="image" id="image" class="form-control d-none" accept="image/*">
                                        
                                        @if($user->image)
                                            <div id="uploadPlaceholder" style="display: none;">
                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg mb-2" focusable="false" aria-hidden="true" style="fill: #6d7175; width: 40px;">
                                                    <path d="M10 0c5.514 0 10 4.486 10 10s-4.486 10-10 10S0 15.514 0 10 4.486 0 10 0zm2 10.586l1.293 1.293a1 1 0 1 0 1.414-1.414l-3-3a.998.998 0 0 0-1.414 0l-3 3a1 1 0 0 0 1.414 1.414L10 10.586V15a1 1 0 1 0 2 0v-4.414zM14 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                                </svg>
                                                <p class="m-0" style="color: #6d7175; font-size: 13px;">Click to change image</p>
                                            </div>
                                            <img id="imagePreview" src="{{ asset($user->image) }}" class="img-preview w-100 mt-2 rounded" style="object-fit: cover; max-height: 200px;">
                                        @else
                                            <div id="uploadPlaceholder">
                                                <svg viewBox="0 0 20 20" class="Polaris-Icon__Svg mb-2" focusable="false" aria-hidden="true" style="fill: #6d7175; width: 40px;">
                                                    <path d="M10 0c5.514 0 10 4.486 10 10s-4.486 10-10 10S0 15.514 0 10 4.486 0 10 0zm2 10.586l1.293 1.293a1 1 0 1 0 1.414-1.414l-3-3a.998.998 0 0 0-1.414 0l-3 3a1 1 0 0 0 1.414 1.414L10 10.586V15a1 1 0 1 0 2 0v-4.414zM14 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                                </svg>
                                                <p class="m-0" style="color: #6d7175; font-size: 13px;">Click to upload image</p>
                                            </div>
                                            <img id="imagePreview" class="img-preview w-100 mt-2 rounded" style="display: none; object-fit: cover; max-height: 200px;">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="shopify-card mb-4 p-4 bg-white shadow-sm" style="border: 1px solid #e5e7eb; border-radius: 8px;">
                                <div class="shopify-card-header mb-3 border-bottom pb-2">
                                    <h6 class="fw-bold m-0 text-dark">Account Status</h6>
                                </div>
                                <div class="shopify-card-body">
                                    <label class="form-label fw-medium text-secondary" style="font-size: 13px;">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-dark fw-bold shadow-sm" style="background-color: #202223; padding: 10px; border-radius: 6px;">
                                    Update User
                                </button>
                            </div>
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
            // Select2 Initialization
            $('.select2').select2({
                placeholder: "Select Role",
                width: '100%',
                minimumResultsForSearch: 5
            });

            // Image Preview Logic
            $('#image').change(function() {
                let file = this.files[0];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        $('#uploadPlaceholder').hide();
                        $('#imagePreview').attr('src', e.target.result).show();
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
            });
            // When Division changes
            $('#division_id').on('change', function() {
                var division_id = $(this).val();
                $('#district_id').html('<option value="">Loading...</option>');
                $('#upazila_id').html('<option value="">Select Upazila</option>');

                if (division_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-locations') }}/" + division_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#district_id').html('<option value="">Select District</option>');
                            $.each(data, function(key, value) {
                                $('#district_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#district_id').html('<option value="">Select District</option>');
                }
            });

            $('#district_id').on('change', function() {
                var district_id = $(this).val();
                $('#upazila_id').html('<option value="">Loading...</option>');

                if (district_id) {
                    $.ajax({
                        url: "{{ url('/admin/get-locations') }}/" + district_id,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('#upazila_id').html('<option value="">Select Upazila</option>');
                            $.each(data, function(key, value) {
                                $('#upazila_id').append('<option value="' + value.id +
                                    '">' + value.name + '</option>');
                            });
                        }
                    });
                } else {
                    $('#upazila_id').html('<option value="">Select Upazila</option>');
                }
            });
        });
    </script>
@endpush
