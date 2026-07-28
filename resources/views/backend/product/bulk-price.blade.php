@extends('backend.layouts.app')
@section('title', 'Bulk Price Update')

@section('content')

    <div class="app-content-header mb-4 mt-3">
        <div class="container-fluid">
            <div class="row align-items-center">

                <div class="col-sm-6">
                    <h3 class="mb-0 fw-bold" style="color:#212b36;">
                        Bulk Price Update
                    </h3>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">
                                Dashboard
                            </a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('products.index') }}" class="text-decoration-none text-muted">
                                Products
                            </a>
                        </li>

                        <li class="breadcrumb-item active fw-bold text-dark">
                            Bulk Price Update
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            <div class="card modern-card">

                <div class="modern-card-header">

                    <h4 class="card-title">
                        <i class="fa-solid fa-tags text-muted me-2"></i>
                        Update Prices in Bulk
                    </h4>

                </div>

                <div class="card-body p-4">

                    <div class="alert alert-info border-0" style="background:#eef4ff;">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Category, Brand, Vendor ফাঁকা রাখলে সেই ফিল্টার প্রযোজ্য হবে না। কোনো ফিল্টার না দিলে
                        <strong>সব প্রোডাক্টের</strong> দাম আপডেট হবে — সাবধানে ব্যবহার করুন।
                    </div>

                    <form id="bulkPriceForm">
                        @csrf

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="fw-bold mb-1">Category</label>
                                <select id="category_id" class="form-select">
                                    <option value="">-- সব Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-bold mb-1">Brand</label>
                                <select id="brand_id" class="form-select">
                                    <option value="">-- সব Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="row align-items-end">

                            <div class="col-md-4 mb-3">
                                <label class="fw-bold mb-1">Update Type</label>
                                <select id="type" class="form-select">
                                    <option value="percent">Percent (%)</option>
                                    <option value="fixed">Fixed Amount (৳)</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="fw-bold mb-1">Value</label>
                                <input type="number" id="value" step="0.01" class="form-control"
                                    placeholder="যেমন: 10 অথবা -10">
                                <small class="text-muted">
                                    দাম বাড়াতে পজিটিভ সংখ্যা, কমাতে নেগেটিভ সংখ্যা (যেমন: -10) দিন।
                                </small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm w-100">
                                    <i class="fa-solid fa-rotate me-1"></i>
                                    Update Prices
                                </button>
                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#bulkPriceForm').submit(function(e) {

                e.preventDefault();

                let value = $('#value').val();

                if (value === '' || isNaN(value)) {
                    toastr.error('একটি সঠিক value দিন।');
                    return;
                }

                let confirmMsg = 'আপনি কি নিশ্চিত? নির্বাচিত ফিল্টার অনুযায়ী প্রোডাক্টের দাম আপডেট হয়ে যাবে।';

                if (!confirm(confirmMsg)) {
                    return;
                }

                let formData = {
                    category_id: $('#category_id').val(),
                    brand_id: $('#brand_id').val(),
                    type: $('#type').val(),
                    value: value,
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({

                    url: "{{ route('products.bulkPrice.update') }}",
                    type: 'POST',
                    data: formData,

                    success: function(res) {
                        toastr.success(res.message);
                        $('#bulkPriceForm')[0].reset();
                    },
                    error: function(xhr) {
                        showErrors(xhr);
                    }
                });

            });

            function showErrors(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errors = '';
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errors += value + '<br>';
                    });
                    toastr.error(errors);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    </script>
@endpush