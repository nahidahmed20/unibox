@extends('backend.layouts.app')

@section('title', isset($purchase) ? 'Edit Purchase' : 'Create Purchase')

@section('content')

    @push('styles')
        <style>
            .shopify-card {
                background: #fff;
                border: 1px solid #e1e3e5;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .shopify-input {
                border: 1px solid #c9cccf;
                border-radius: 8px;
                padding: 10px;
            }

            .table-container {
                border: 1px solid #e1e3e5;
                border-radius: 12px;
            }

            .search-item {
                padding: 10px;
                cursor: pointer;
                border-bottom: 1px solid #f1f1f1;
            }

            .search-item:hover {
                background: #f8f9fa;
            }

            .btn-shopify {
                background: #000032;
                color: #fff;
                border-radius: 8px;
            }
        </style>
    @endpush

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between mb-3">
            <h3>{{ isset($purchase) ? 'Edit Purchase' : 'Create Purchase' }}</h3>
            <button class="btn btn-shopify" form="purchaseForm">
                {{ isset($purchase) ? 'Update Purchase' : 'Save Purchase' }}
            </button>
        </div>

        <form id="purchaseForm">
            @csrf
            @if (isset($purchase))
                @method('PUT')
            @endif

            <div class="row">

                {{-- LEFT --}}
                <div class="col-lg-8">

                    <div class="shopify-card p-3 mb-3 position-relative">
                        <label>Search Product</label>
                        <input type="text" id="productSearch" class="form-control shopify-input">
                        <div id="searchResults"></div>
                    </div>

                    <div class="table-container">
                        <table class="table mb-0" id="selectedProducts">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Variation</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if (isset($purchase))
                                    @foreach ($purchase->details as $i => $d)
                                        <tr>
                                            <td>
                                                <strong>{{ $d->product->name }}</strong>
                                                <input type="hidden" name="products[{{ $i }}][product_id]"
                                                    value="{{ $d->product_id }}" data-product="{{ $d->product_id }}">
                                            </td>

                                            <td>{{ $d->product->sku }}</td>

                                            <td>
                                                <select name="products[{{ $i }}][size_id]"
                                                    class="form-select form-select-sm">
                                                    <option value="">Size</option>
                                                    @foreach ($d->product->sizes as $s)
                                                        <option value="{{ $s->id }}" @selected($d->size_id == $s->id)>
                                                            {{ $s->size }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <select name="products[{{ $i }}][color_id]"
                                                    class="form-select form-select-sm mt-1">
                                                    <option value="">Color</option>
                                                    @foreach ($d->product->colors as $c)
                                                        <option value="{{ $c->color_id }}" @selected($d->color_id == $c->color_id)>
                                                            {{ $c->color->name ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" class="form-control price"
                                                    name="products[{{ $i }}][price]"
                                                    value="{{ $d->buying_price }}">
                                            </td>

                                            <td>
                                                <input type="number" class="form-control qty"
                                                    name="products[{{ $i }}][qty]" value="{{ $d->quantity }}">
                                            </td>

                                            <td class="row-total">
                                                {{ $d->buying_price * $d->quantity }}
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove">X</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="col-lg-4">

                    <div class="shopify-card p-3">

                        <label>Supplier</label>
                        <select name="supplier_id" class="form-select shopify-input">
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}" @if (isset($purchase) && $purchase->supplier_id == $s->id) selected @endif>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>

                        <label class="mt-2">Invoice</label>
                        <input type="text" name="invoice_no" value="{{ $purchase->invoice_no ?? $nextInvoiceNo }}"
                            class="form-control shopify-input">

                        <label class="mt-2">Date</label>
                        <input type="date" name="purchase_date" value="{{ $purchase->purchase_date ?? date('Y-m-d') }}"
                            class="form-control shopify-input">

                        <hr>

                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <h4>BDT <span id="grandTotal">
                                    {{ $purchase->total_amount ?? 0 }}
                                </span></h4>
                        </div>

                    </div>

                </div>

            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        let rowIndex = $("#selectedProducts tbody tr").length + 100;

        $(document).ready(function() {
            calculateTotal();
        });

        /* ================= SEARCH ================= */
        $("#productSearch").on("keyup", function() {

            let q = $(this).val();
            if (q.length < 2) {
                $("#searchResults").hide();
                return;
            }

            $.get("{{ route('products.search') }}", {
                q
            }, function(res) {

                let html = "";

                res.forEach(p => {
                    html += `
            <div class="search-item"
                data-product="${encodeURIComponent(JSON.stringify(p))}">
                <strong>${p.name}</strong><br>
                <small>${p.sku}</small>
            </div>`;
                });

                $("#searchResults").html(html).show();
            });
        });

        /* ================= ADD PRODUCT ================= */
        $(document).on("click", ".search-item", function() {

            let p = JSON.parse(decodeURIComponent($(this).attr("data-product")));

            if ($(`input[value="${p.id}"]`).length) {
                toastr.warning("Already added");
                return;
            }

            let sizes = p.sizes || [];
            let colors = p.colors || [];

            let row = `
    <tr>
        <td>
            <strong>${p.name}</strong>
            <input type="hidden" name="products[${rowIndex}][product_id]" value="${p.id}">
        </td>

        <td>${p.sku ?? '-'}</td>

        <td>
            <select name="products[${rowIndex}][size_id]" class="form-select form-select-sm">
                <option value="">Size</option>
                ${sizes.map(s=>`<option value="${s.id}">${s.size}</option>`).join('')}
            </select>

            <select name="products[${rowIndex}][color_id]" class="form-select form-select-sm mt-1">
                <option value="">Color</option>
                ${colors.map(c=>`<option value="${c.color_id}">${c.color?.name ?? ''}</option>`).join('')}
            </select>
        </td>

        <td>
            <input type="number" class="form-control price"
                name="products[${rowIndex}][price]"
                value="${p.purchase_price}">
        </td>

        <td>
            <input type="number" class="form-control qty"
                name="products[${rowIndex}][qty]" value="1">
        </td>

        <td class="row-total">${p.purchase_price}</td>

        <td>
            <button type="button" class="btn btn-danger btn-sm remove">X</button>
        </td>
    </tr>`;

            $("#selectedProducts tbody").append(row);

            rowIndex++;
            calculateTotal();

            $("#searchResults").hide();
            $("#productSearch").val('');
        });

        /* ================= CALC ================= */
        function calculateTotal() {

            let total = 0;

            $("#selectedProducts tbody tr").each(function() {

                let price = parseFloat($(this).find(".price").val()) || 0;
                let qty = parseFloat($(this).find(".qty").val()) || 0;

                let sub = price * qty;

                $(this).find(".row-total").text(sub.toFixed(2));

                total += sub;
            });

            $("#grandTotal").text(total.toFixed(2));
        }

        $(document).on("input", ".price,.qty", calculateTotal);

        /* ================= REMOVE ================= */
        $(document).on("click", ".remove", function() {
            $(this).closest("tr").remove();
            calculateTotal();
        });

        /* ================= CLOSE SEARCH ================= */
        $(document).click(function(e) {
            if (!$(e.target).closest("#productSearch,#searchResults").length) {
                $("#searchResults").hide();
            }
        });

        /* ================= SUBMIT ================= */
        $("#purchaseForm").submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ isset($purchase) ? route('purchases.update', $purchase->id) : route('purchases.store') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message);
                    window.location.href = "{{ route('purchases.index') }}";
                },
                error: function() {
                    toastr.error("Something went wrong");
                }
            });
        });
    </script>
@endpush
