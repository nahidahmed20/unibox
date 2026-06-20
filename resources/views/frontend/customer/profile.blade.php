

<section class="customer-dashboard pt-50 pb-100">
    <div class="container">
        <div class="row">
            {{-- Content --}}
            <div class="col-lg-9" id="main-content">

                <div class="dashboard-card mb-4 pb-3 border-bottom">
                    <h3 class="fw-bold">My Profile</h3>
                    <p class="text-muted m-0">Update your personal and contact information</p>
                </div>
                
                <div class="dashboard-card">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}">
                            </div>
                            {{-- Email --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}">
                            </div>
                            {{-- Phone --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                            </div>
                            {{-- Birth Date --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Birth Date</label>
                                <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $customer->birth_date) }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Division</label>
                                <select name="division_id" id="division" class="form-select custom-select">
                                    <option value="">Select Division</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ (string)$customer->division_id === (string)$division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">District</label>
                                <select name="district_id" id="district" class="form-select custom-select">
                                    <option value="">Select District</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-semibold">Upazila</label>
                                <select name="upazila_id" id="upazila" class="form-select custom-select">
                                    <option value="">Select Upazila</option>
                                </select>
                            </div>

                            <input type="hidden" id="selected_district" value="{{ $customer->district_id ?? '' }}">
                            <input type="hidden" id="selected_upazila" value="{{ $customer->upazila_id ?? '' }}">
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-primary">
                                    <i class="fa-solid fa-house me-1"></i> Home Address
                                </label>
                                <textarea name="address" rows="2" class="form-control" placeholder="House/Flat, Street name...">{{ old('address', $customer->address ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-info">
                                    <i class="fa-solid fa-building me-1"></i> Office Address
                                </label>
                                <textarea name="office_address" rows="2" class="form-control" placeholder="Company name, Floor, Area...">{{ old('office_address', $customer->office_address ?? '') }}</textarea>
                            </div>

                            {{-- Image --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Profile Image</label>
                                <input type="file" name="image" class="form-control">
                            </div>

                            {{-- Current Image Preview --}}
                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                @if($customer->image)
                                    <img src="{{ asset($customer->image) }}" width="80" height="80" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                                @else
                                    <img src="{{ asset('frontend/assets/img/user.png') }}" width="80" height="80" class="rounded-circle border shadow-sm" style="object-fit: cover;">
                                @endif
                            </div>

                            {{-- Submit --}}
                            <div class="col-md-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                                    Save Changes
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>



