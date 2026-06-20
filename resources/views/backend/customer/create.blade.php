@extends('backend.layouts.app')

@section('title', 'Create Customer')

@push('styles')
<style>
    .wrapper{
        padding:25px;
    }

    /* CARD */
    .customer-card{
        background:#fff;
        border:1px solid #e5e7eb;
        border-radius:16px;
        box-shadow:0 10px 25px rgba(0,0,0,0.05);
        overflow:hidden;
    }

    /* HEADER */
    .header{
        padding:25px 30px;
        border-bottom:1px solid #eef0f2;
    }

    .title{
        font-size:22px;
        font-weight:700;
        color:#111827;
    }

    .subtitle{
        font-size:13px;
        color:#6b7280;
    }

    /* SECTION */
    .section{
        padding:25px 30px;
        border-bottom:1px solid #f1f3f5;
    }

    .section-title{
        font-size:12px;
        font-weight:700;
        text-transform:uppercase;
        letter-spacing:.6px;
        color:#374151;
        margin-bottom:15px;
    }

    /* INPUTS */
    .form-control,
    .form-select{
        height:44px;
        border-radius:10px;
        border:1px solid #d1d5db;
        font-size:14px;
    }

    .form-control:focus,
    .form-select:focus{
        border-color:#4f46e5;
        box-shadow:0 0 0 3px rgba(79,70,229,0.15);
    }

    /* FOOTER */
    .footer{
        padding:20px 30px;
        display:flex;
        justify-content:flex-end;
        gap:10px;
        background:#fafafa;
    }

    .btn-primary{
        background:#111827;
        border:none;
        padding:10px 18px;
        border-radius:10px;
    }

    .btn-light{
        border:1px solid #d1d5db;
        background:#fff;
        border-radius:10px;
        padding:10px 18px;
    }
</style>
@endpush
@section('content')
<div class="wrapper">
    <form id="submitForm">
        @csrf
        <div class="customer-card">
            <!-- HEADER -->
            <div class="header">
                <div class="title">Create Customer</div>
                <div class="subtitle">Add complete customer information</div>
            </div>

            <!-- BASIC -->
            <div class="section">

                <div class="section-title">Basic Information</div>

                <div class="row g-3">

                    <div class="col-md-4">
                        <input type="text" name="customer_code" class="form-control" placeholder="Customer Code (optional)">
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="name" class="form-control" placeholder="Customer Name">
                    </div>

                    <div class="col-md-4">
                        <select name="type" class="form-select">
                            <option value="regular">Regular</option>
                            <option value="wholesale">Wholesale</option>
                            <option value="walk-in">Walk-in</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="alternate_phone" class="form-control" placeholder="Alternate Phone">
                    </div>

                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                </div>

            </div>

            <!-- ADDRESS -->
            <div class="section">

                <div class="section-title">Address Information</div>

                <div class="row g-3">

                    <div class="col-md-12">
                        <input type="text" name="address" class="form-control" placeholder="Full Address">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="city" class="form-control" placeholder="City">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="state" class="form-control" placeholder="State">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="postal_code" class="form-control" placeholder="Postal Code">
                    </div>

                    <div class="col-md-3">
                        <input type="text" name="country" class="form-control" placeholder="Country">
                    </div>

                </div>

            </div>

            <!-- FINANCIAL -->
            <div class="section">

                <div class="section-title">Financial Information</div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <input type="number" step="0.01" name="opening_balance" class="form-control" placeholder="Opening Balance" value="0">
                    </div>

                    <div class="col-md-6">
                        <input type="number" step="0.01" name="total_due" class="form-control" placeholder="Total Due" value="0">
                    </div>

                </div>

            </div>

            <!-- NOTE -->
            <div class="section" style="border-bottom:none;">

                <div class="section-title">Note</div>

                <textarea name="note" class="form-control" rows="3" placeholder="Write note..."></textarea>

            </div>

            <!-- FOOTER -->
            <div class="footer">

                <a href="{{ route('customers.index') }}" class="btn btn-light">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Save Customer
                </button>

            </div>

        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){

    $('#submitForm').on('submit', function(e){
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('customers.store') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            beforeSend: function(){
                $('#submitBtn').prop('disabled', true).text('Saving...');
            },

            success: function(res){

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message ?? 'Customer created successfully'
                }).then(() => {
                    window.location.href = "{{ route('customers.index') }}";
                });

            },

            error: function(xhr){

                let errors = xhr.responseJSON.errors;
                let msg = '';

                $.each(errors, function(key, value){
                    msg += value + "\n";
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: msg
                });

                $('#submitBtn').prop('disabled', false).text('Save Customer');
            }

        });

    });

});
</script>
@endpush