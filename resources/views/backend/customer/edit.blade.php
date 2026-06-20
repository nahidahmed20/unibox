@extends('backend.layouts.app')

@section('title', 'Edit Customer')

@push('styles')
    <style>
        .wrapper {
            padding: 25px;
        }

        /* CARD */
        .customer-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* HEADER */
        .header {
            padding: 25px 30px;
            border-bottom: 1px solid #eef0f2;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
        }

        .subtitle {
            font-size: 13px;
            color: #6b7280;
        }

        /* SECTION */
        .section {
            padding: 25px 30px;
            border-bottom: 1px solid #f1f3f5;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #374151;
            margin-bottom: 15px;
        }

        /* INPUTS */
        .form-control,
        .form-select {
            height: 44px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        /* FOOTER */
        .footer {
            padding: 20px 30px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            background: #fafafa;
        }

        .btn-primary {
            background: #111827;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
        }

        .btn-light {
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 10px;
            padding: 10px 18px;
        }
    </style>
@endpush

@section('content')

    <div class="wrapper">

        <form id="updateForm">

            @csrf
            @method('PUT')

            <div class="customer-card">

                <!-- HEADER -->
                <div class="header">
                    <div class="title">Edit Customer</div>
                    <div class="subtitle">Update customer information</div>
                </div>

                <!-- BASIC -->
                <div class="section">

                    <div class="section-title">Basic Information</div>

                    <div class="row g-3">

                        <div class="col-md-4">
                            <input type="text" name="customer_code" class="form-control"
                                value="{{ $customer->customer_code }}">
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="name" class="form-control" value="{{ $customer->name }}">
                        </div>

                        <div class="col-md-4">
                            <select name="type" class="form-select">
                                <option value="regular" {{ $customer->type == 'regular' ? 'selected' : '' }}>Regular
                                </option>
                                <option value="wholesale" {{ $customer->type == 'wholesale' ? 'selected' : '' }}>Wholesale
                                </option>
                                <option value="walk-in" {{ $customer->type == 'walk-in' ? 'selected' : '' }}>Walk-in
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="phone" class="form-control" value="{{ $customer->phone }}">
                        </div>

                        <div class="col-md-4">
                            <input type="text" name="alternate_phone" class="form-control"
                                value="{{ $customer->alternate_phone }}">
                        </div>

                        <div class="col-md-4">
                            <select name="status" class="form-select">
                                <option value="1" {{ $customer->status ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$customer->status ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                    </div>

                </div>

                <!-- ADDRESS -->
                <div class="section">

                    <div class="section-title">Address Information</div>

                    <div class="row g-3">

                        <div class="col-md-12">
                            <input type="text" name="address" class="form-control" value="{{ $customer->address }}">
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="city" class="form-control" value="{{ $customer->city }}">
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="state" class="form-control" value="{{ $customer->state }}">
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="postal_code" class="form-control"
                                value="{{ $customer->postal_code }}">
                        </div>

                        <div class="col-md-3">
                            <input type="text" name="country" class="form-control" value="{{ $customer->country }}">
                        </div>

                    </div>

                </div>

                <!-- FINANCIAL -->
                <div class="section">

                    <div class="section-title">Financial Information</div>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <input type="number" step="0.01" name="opening_balance" class="form-control"
                                value="{{ $customer->opening_balance }}">
                        </div>

                        <div class="col-md-6">
                            <input type="number" step="0.01" name="total_due" class="form-control"
                                value="{{ $customer->total_due }}">
                        </div>

                    </div>

                </div>

                <!-- NOTE -->
                <div class="section" style="border-bottom:none;">

                    <textarea name="note" class="form-control" rows="3">{{ $customer->note }}</textarea>

                </div>

                <!-- FOOTER -->
                <div class="footer">

                    <a href="{{ route('customers.index') }}" class="btn btn-light">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-primary" id="updateBtn">
                        Update Customer
                    </button>

                </div>

            </div>

        </form>

    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#updateForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('customers.update', $customer->id) }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,

                    beforeSend: function() {
                        $('#updateBtn').prop('disabled', true).text('Updating...');
                    },

                    success: function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res.message
                        }).then(() => {
                            window.location.href = "{{ route('customers.index') }}";
                        });

                    },

                    error: function(xhr) {

                        let errors = xhr.responseJSON.errors;
                        let msg = '';

                        $.each(errors, function(key, value) {
                            msg += value + "\n";
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });

                        $('#updateBtn').prop('disabled', false).text('Update Customer');
                    }

                });

            });

        });
    </script>
@endpush
