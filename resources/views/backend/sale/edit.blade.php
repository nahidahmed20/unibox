@extends('backend.layouts.app')

@section('title', 'Edit Sale')


@push('styles')
    <style>
        .shopify-card {
            border: none;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .05);
        }

        .shopify-section {
            background: #fff;
            border: 1px solid #eef2f7;
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px 0;
        }

        .page-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }

        .page-header p {
            font-size: 13px;
            color: #6b7280;
            margin: 0;
        }

        .section-title {
            font-size: 14px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            min-height: 42px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #111827;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, .08);
        }

        .btn-shopify {
            background: #111827;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
            transition: .2s;
        }

        .btn-shopify:hover {
            background: #000;
            color: #fff;
        }

        .btn-success {
            border-radius: 12px;
            font-weight: 600;
        }

        .btn-danger {
            border-radius: 10px;
        }

        /* Add item button */
        .btn-add-item {
            border-radius: 12px;
            font-weight: 600;
            padding: 8px 14px;
        }

        #saleTable {
            margin: 0;
            background: #fff;
        }

        #saleTable thead th {
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            padding: 14px;
        }

        #saleTable tbody td {
            border-bottom: 1px solid #f1f5f9;
            padding: 12px;
            vertical-align: middle;
        }

        #saleTable tbody tr:hover {
            background: #fafafa;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .summary-card {
            position: sticky;
            top: 20px;
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .04);
        }

        .summary-card h5 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #111827;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
            color: #374151;
        }

        .summary-row strong {
            font-weight: 700;
            color: #111827;
        }

        .summary-row.danger strong {
            color: #ef4444;
        }

        /* Total highlight */
        .total-highlight {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .badge {
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        @media(max-width:991px) {
            .summary-card {
                position: relative;
                top: auto;
                margin-top: 20px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="page-header">
            <div>
                <h2>Edit Sale</h2>
                <p>Update order information</p>
            </div>
            <button type="submit" form="saleForm" class="btn btn-shopify">
                Save Changes
            </button>
        </div>

        <form id="saleForm">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <!-- LEFT SIDE -->
                <div class="col-lg-8">
                    <!-- CUSTOMER CARD -->
                    <div class="shopify-section">
                        <h5 class="section-title">Customer Information</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Customer</label>
                                <select name="customer_id" class="form-select customer-select">
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}"
                                            {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->phone }}-{{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sale Date</label>
                                <input type="date" name="sale_date" class="form-control" value="{{ $sale->sale_date }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Sales By</label>
                                <select name="user_id" class="form-select sales-select">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $sale->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUCTS CARD -->
                    <div class="shopify-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="section-title mb-0">Order Items</h5>
                            <button type="button" id="addRow" class="btn btn-success btn-add-item">
                                + Add Item
                            </button>
                        </div>

                        <table class="table" id="saleTable">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Color</th>
                                    <th>Size</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody id="saleBody">
                                @foreach ($sale->items as $item)
                                    <tr class="saleRow">
                                        <td>
                                            <select name="product_id[]" class="form-select product-select">
                                                @foreach ($products as $p)
                                                    <option value="{{ $p->id }}" data-price="{{ $p->selling_price }}"
                                                        {{ $item->product_id == $p->id ? 'selected' : '' }}>
                                                        {{ $p->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="color_id[]" class="form-select color-select">
                                                @foreach ($item->product->productcolors as $c)
                                                    <option value="{{ $c->id }}"
                                                        {{ $item->color_id == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="size_id[]" class="form-select size-select">
                                                @foreach ($item->product->sizes as $s)
                                                    <option value="{{ $s->id }}"
                                                        {{ $item->size_id == $s->id ? 'selected' : '' }}>
                                                        {{ $s->size }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control qty"
                                                value="{{ $item->quantity }}">
                                        </td>
                                        <td>
                                            <input type="number" name="selling_price[]" class="form-control price"
                                                value="{{ $item->selling_price }}">
                                        </td>
                                        <td>
                                            <input type="number" name="total_price[]" class="form-control total" readonly
                                                value="{{ $item->total_price }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeRow">
                                                X
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-lg-4">
                    <div class="summary-card">
                        <h5>Order Summary</h5>
                        <div class="summary-row">
                            <span>Grand Total</span>
                            <strong id="grandTotalText">৳0.00</strong>
                        </div>

                        <div class="summary-row danger">
                            <span>Due</span>
                            <strong id="dueAmountText">৳0.00</strong>
                        </div>
                    </div>

                    <div class="shopify-section mt-3">
                        <h5 class="section-title">Payment</h5>
                        <label class="form-label">Paid Amount</label>
                        <input type="number" name="paid_amount" id="paid_amount" class="form-control" value="{{ $sale->paid_amount }}">
                    </div>

                    <div class="shopify-section mt-3">
                        <h5 class="section-title">Delivery</h5>
                        <select id="delivery_type" class="form-select" name="delivery_type">
                            <option value="inside">Inside Dhaka</option>
                            <option value="outside">Outside Dhaka</option>
                            <option value="free">Free</option>
                        </select>
                        <input type="hidden" name="delivery_charge" id="delivery_charge">
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection


@push('scripts')
    <script>
        $(document).ready(function () {
            function initSelect2(row) {
                row.find('.product-select, .color-select, .size-select').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    width: '100%'
                });
            }

            $('.saleRow').each(function () {
                initSelect2($(this));
            });

            $('.customer-select, .sales-select, .branch-select').select2({
                placeholder: 'Select an option',
                allowClear: true,
                width: '100%'
            });

            $('#addRow').click(function () {
                let firstRow = $('.saleRow:first');
                firstRow.find('.product-select, .color-select, .size-select').select2('destroy');
                let newRow = firstRow.clone();
                newRow.find('input').val('');
                newRow.find('select').val(null);
                $('#saleBody').append(newRow);
                initSelect2(firstRow);
                initSelect2(newRow);
                calculateTotal();
            });

            $(document).on('click', '.removeRow', function () {
                if ($('.saleRow').length > 1) {
                    $(this).closest('tr').remove();
                    calculateTotal();
                }
            });

            $(document).on('change', '.product-select', function () {
                let row = $(this).closest('tr');
                let productId = $(this).val();
                let price = $(this).find(':selected').data('price') || 0;
                row.find('.price').val(price);
                calculateRow(row);
                if (productId) {
                    $.ajax({
                        url: "{{ route('get.product.details') }}",
                        type: "GET",
                        data: { product_id: productId },
                        success: function (res) {
                            let colorOptions = '<option value="">Select Color</option>';
                            $.each(res.colors, function (k, color) {
                                colorOptions += `<option value="${color.id}">${color.name}</option>`;
                            });

                            let sizeOptions = '<option value="">Select Size</option>';
                            $.each(res.sizes, function (k, s) {
                                sizeOptions += `<option value="${s.id}">${s.size}</option>`;
                            });

                            row.find('.color-select').html(colorOptions).trigger('change');
                            row.find('.size-select').html(sizeOptions).trigger('change');
                        }
                    });
                } else {
                    row.find('.color-select').html('<option value="">Select Color</option>').trigger('change');
                    row.find('.size-select').html('<option value="">Select Size</option>').trigger('change');
                }
            });

            $(document).on('input', '.qty, .price', function () {
                calculateRow($(this).closest('tr'));
            });

            function calculateRow(row) {
                let qty = parseFloat(row.find('.qty').val()) || 0;
                let price = parseFloat(row.find('.price').val()) || 0;
                let total = qty * price;
                row.find('.total').val(total.toFixed(2));
                calculateTotal();
            }

            function calculateTotal() {
                let grandTotal = 0;
                $('.saleRow').each(function () {
                    grandTotal += parseFloat($(this).find('.total').val()) || 0;
                });
                let discount = parseFloat($('#discount').val()) || 0;
                let discountType = $('#discount_type').val();
                let paid = parseFloat($('#paid_amount').val()) || 0;
                let discountAmount = 0;
                if (discountType === 'percent') {
                    discountAmount = (grandTotal * discount) / 100;
                } else {
                    discountAmount = discount;
                }
                let finalTotal = grandTotal - discountAmount;
                if (finalTotal < 0) finalTotal = 0;
                let due = finalTotal - paid;
                if (due < 0) due = 0;

                $('#grand_total').val(finalTotal.toFixed(2));
                $('#due_amount').val(due.toFixed(2));

                $('#grandTotalText').text('৳' + finalTotal.toFixed(2));
                $('#dueAmountText').text('৳' + due.toFixed(2));
            }

            $('#discount, #paid_amount, #discount_type').on('input change', function () {
                calculateTotal();
            });

            function setDeliveryCharge(type) {
                let charge = 0;
                if (type === 'inside') charge = 60;
                else if (type === 'outside') charge = 120;
                else charge = 0;
                $('#delivery_charge').val(charge);
            }

            $('#delivery_type').on('change', function () {
                setDeliveryCharge($(this).val());
            });

            setDeliveryCharge($('#delivery_type').val());

            $('#saleForm').submit(function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('sales.update', $sale->id) }}",
                    type: "POST",
                    data: formData,
                    beforeSend: function () {
                        $('#submitBtn').prop('disabled', true).text('Updating...');
                    },

                    success: function (res) {
                        $('#submitBtn').prop('disabled', false).text('Update Sale');
                        if (res.status === 'success') {
                            toastr.success(res.message);
                            setTimeout(() => {
                                window.location.href = "{{ route('sales.index') }}";
                            }, 800);
                        } else {
                            toastr.error(res.message || 'Something went wrong');
                        }
                    },

                    error: function (xhr) {
                        $('#submitBtn').prop('disabled', false).text('Update Sale');
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, msgs) {
                                msgs.forEach(msg => toastr.error(msg));
                            });
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Unexpected error!');
                        }
                    }
                });
            });

            calculateTotal();

        });
    </script>
@endpush
