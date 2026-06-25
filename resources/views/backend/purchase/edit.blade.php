@extends('backend.layouts.app')
@section('title', 'Edit Purchase')
@section('content')
   @push('styles')
    <style>
        #searchResults {
            border: 1px solid #e2e8f0 !important;
            border-radius: 14px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.03) !important;
            overflow: hidden;
            margin-top: 8px;
            background: #ffffff;
        }
        
        .search-item {
            padding: 14px 20px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .search-item:last-child {
            border-bottom: none !important;
        }
        
        .search-item:hover {
            background-color: #f8fafc !important;
            transform: translateX(6px); 
        }

        .product-name-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
        }

        .product-sku-text {
            font-size: 0.8rem;
            color: #64748b;
        }

        .modal-content {
            border: 1px solid rgba(0, 0, 0, 0.03) !important;
            border-radius: 24px !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08) !important;
            background: #ffffff;
        }

        .modal-header {
            border-bottom: none !important;
            padding: 24px 32px !important;
            background: #0f172a !important; 
            position: relative;
        }

        .modal-header-icon-box {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff !important;
        }

        .modal-header .btn-close {
            background-color: rgba(255, 255, 255, 0.1) !important;
            filter: invert(1) grayscale(1) brightness(2); 
            padding: 10px !important;
            border-radius: 50% !important;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .modal-header .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.2) !important;
            transform: rotate(90deg) scale(1.05);
            opacity: 1;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 20px 32px !important;
            background: #f8fafc;
            border-bottom-left-radius: 24px !important;
            border-bottom-right-radius: 24px !important;
        }

        .table {
            border-color: #f1f5f9 !important;
        }

        .table thead th {
            font-weight: 700 !important;
            text-transform: uppercase;
            font-size: 0.75rem !important;
            letter-spacing: 0.8px;
            background-color: #f8fafc !important;
            color: #64748b !important;
            padding: 14px 20px !important;
            border-bottom: 1px solid #e2e8f0 !important;
        }

        .table tbody td {
            padding: 16px 20px !important;
            color: #334155;
            font-size: 0.9rem;
        }

        #variantMatrixTable tbody tr {
            transition: all 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        
        #variantMatrixTable tbody tr:hover {
            background-color: #f8fafc !important;
        }

        #variantMatrixTable tbody tr.row-active {
            background-color: rgba(15, 23, 42, 0.02) !important;
        }
        
        #variantMatrixTable tbody tr.row-active td {
            color: #0f172a !important;
        }

        .variant-title-text {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 600;
        }

        .modal-price, .modal-qty, .price, .qty, .premium-modal-input {
            border-radius: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 8px 14px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            font-size: 0.9rem !important;
            text-align: center;
            background-color: #ffffff;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .modal-price:focus, .modal-qty:focus, .price:focus, .qty:focus, .premium-modal-input:focus {
            border-color: #0f172a !important; 
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08) !important;
            background-color: #ffffff !important;
            outline: none;
        }

        .premium-modal-input.has-value {
            border-color: #3b82f6 !important;
            background-color: rgba(59, 130, 246, 0.02) !important;
            color: #3b82f6 !important;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        .badge {
            font-size: 0.78rem !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            letter-spacing: 0.3px;
        }
        
        .bg-light {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
            border: 1px solid #e2e8f0 !important;
        }

        .btn-sm {
            border-radius: 10px !important;
            padding: 8px 12px !important;
            font-weight: 600;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-danger:hover {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }

        #btnModalAddProducts:hover {
            background: #1e293b !important;
            border-color: #1e293b !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15) !important;
        }
        
        #btnModalAddProducts:active {
            transform: translateY(0px);
        }

        .btn-close {
            transition: all 0.2s;
        }
        .btn-close:hover {
            transform: rotate(90deg);
        }

        .premium-summary-card {
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.03), 0 8px 10px -6px rgba(15, 23, 42, 0.03) !important;
            position: sticky;
            top: 20px; 
        }

        .summary-icon-box {
            width: 38px;
            height: 38px;
            background-color: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-input-label {
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            color: #475569 !important;
            margin-bottom: 6px !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .modern-dashboard-input {
            border-radius: 10px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding: 10px 14px !important;
            font-size: 0.9rem !important;
            color: #334155 !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
            transition: all 0.2s ease-in-out !important;
        }

        .modern-dashboard-input:focus {
            border-color: #0f172a !important;
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08) !important;
            outline: none;
        }

        .btn-modern-action {
            background: #0f172a !important;
            color: #ffffff !important;
            border: 1px solid #0f172a !important;
            border-radius: 10px !important;
            padding: 0 16px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-modern-action:hover {
            background: #1e293b !important;
            border-color: #1e293b !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15) !important;
        }

        .btn-modern-action:active {
            transform: translateY(1px);
        }

        .dashed-separator {
            border-top: 1.5px dashed #e2e8f0;
            height: 0;
            width: 100%;
        }

        .total-display-box {
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 14px;
            padding: 16px 20px;
        }
    </style>
@endpush
    <div class="container-fluid py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Purchase</h2>
            <button type="submit" form="purchaseForm" class="btn btn-shopify" style="background:#0f172a; color:#fff;">Update Purchase</button>
        </div>

        <form id="purchaseForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopify-card p-4 mb-4" style="background:#fff; border-radius:16px; border:1px solid #e2e8f0;">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Search Products</label>
                            <div class="position-relative">
                                <input type="text" id="productSearch" class="form-control shopify-input modern-dashboard-input"
                                    placeholder="Search product to add more...">
                                <div id="searchResults"></div>
                            </div>
                        </div>

                        <div class="table-container">
                            <table class="table align-middle mb-0" id="selectedProducts">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Variation</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $rowIndex = 0; @endphp
                                    @foreach($purchase->details as $item)
                                        @php
                                            $variantLabel = 'Standard';
                                            if ($item->product_variant_id && $item->product && $item->product->variants) {
                                                $variant = $item->product->variants->where('id', $item->product_variant_id)->first();
                                                if($variant){
                                                    $parts = [];
                                                    if (isset($variant->color->name)) $parts[] = 'Color: ' . $variant->color->name;
                                                    if (isset($variant->size->name)) $parts[] = 'Size: ' . $variant->size->name;
                                                    if (count($parts) > 0) $variantLabel = implode(' | ', $parts);
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->product->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">SKU : {{ $item->product->sku ?? '-' }}</small>
                                                    <input type="hidden" name="products[{{ $rowIndex }}][id]" value="{{ $item->product_id }}">
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border px-2 py-1">{{ $variantLabel }}</span>
                                                <input type="hidden" name="products[{{ $rowIndex }}][variant_id]" value="{{ $item->product_variant_id ?? '' }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control price" name="products[{{ $rowIndex }}][price]" value="{{ $item->buying_price }}" step="0.01">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control qty" name="products[{{ $rowIndex }}][qty]" value="{{ $item->quantity }}" min="1">
                                            </td>
                                            <td class="row-total fw-bold">
                                                {{ number_format($item->total_price, 2, '.', '') }}
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @php $rowIndex++; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="premium-summary-card p-4">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div class="summary-icon-box">
                                <i class="fa fa-file-invoice text-dark fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1.1rem; color: #0f172a;">Purchase Details</h5>
                                <small class="text-muted" style="font-size: 0.78rem;">Document info & total summary</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label custom-input-label">Supplier</label>
                            <div class="input-group gap-2">
                                <select class="form-select modern-dashboard-input" name="supplier_id" id="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ $purchase->supplier_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-modern-action" data-bs-toggle="modal" data-bs-target="#supplierModal" title="Add New Supplier">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label custom-input-label">Invoice No</label>
                            <div class="position-relative">
                                <input type="text" name="invoice_no" value="{{ $purchase->invoice_no }}" class="form-control modern-dashboard-input fw-semibold text-dark">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label custom-input-label">Purchase Date</label>
                            <input type="date" name="purchase_date" value="{{ $purchase->purchase_date }}" class="form-control modern-dashboard-input" required>
                        </div>
                        
                        <div class="dashed-separator my-4"></div>
                        
                        <div class="total-display-box d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-uppercase tracking-wider text-muted d-block" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.8px;">Total Payable</span>
                                <span class="fw-bold" style="font-size: 0.95rem; color: #64748b;">Net Amount</span>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-extrabold mb-0" style="font-size: 1.75rem; color: #0f172a; letter-spacing: -0.5px;">
                                    <span style="font-size: 1rem; font-weight: 700; color: #64748b; margin-right: 2px;">BDT</span>
                                    <span id="grandTotal">{{ number_format($purchase->total_amount, 2, '.', '') }}</span>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @include('backend.purchase.add_supplier_modal')
    @include('backend.purchase.multiple_product_modal')
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // আগের ডাটার কাউন্ট দিয়ে rowIndex শুরু হবে, যাতে নতুন ডাটা আগেরগুলোকে রিপ্লেস না করে
            let rowIndex = {{ $purchase->details->count() }};
            
            // পেজ লোড হওয়ার সাথে সাথেই একবার টোটাল ক্যালকুলেট করে নিবে
            calculateTotal();

            $("#productSearch").on("keyup", function() {
                let q = $(this).val();
                if (q.length < 2) {
                    $("#searchResults").hide();
                    return;
                }
                
                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: { q: q },
                    success: function(res) {
                        let html = "";
                        if (res.length > 0) {
                            res.forEach(function(p) {
                                let jsonStr = JSON.stringify(p).replace(/'/g, "&apos;");
                                
                                html += `
                                <div class="search-item" data-product='${jsonStr}'>
                                    <div style="flex:1">
                                        <div class="product-name-text fw-bold">${p.name}</div>
                                        <div class="product-sku-text mt-1 text-muted">
                                            SKU : ${p.sku ?? '-'} | Type: <span class="badge bg-secondary">${p.product_type.toUpperCase()}</span>
                                        </div>
                                    </div>
                                    <i class="fa fa-arrow-circle-right text-primary fs-5"></i>
                                </div>`;
                            });
                        } else {
                            html = `<div class="p-3 text-center text-muted">No Product Found</div>`;
                        }
                        $("#searchResults").html(html).show();
                    }
                });
            });

            $(document).on("click", ".search-item", function() {
                let p = JSON.parse($(this).attr("data-product"));
                
                if (p.product_type === 'multiple' && p.variants && p.variants.length > 0) {
                    openVariantModal(p);
                } else {
                    let singleItem = {
                        id: p.id,
                        variant_id: "",
                        clean_name: p.name,
                        sku: p.sku ?? '-',
                        price: p.purchase_price || 0,
                        variant_label: 'Standard'
                    };
                    addProduct(singleItem, 1);
                    $("#searchResults").hide();
                    $("#productSearch").val("");
                }
            });

            function openVariantModal(p) {
                $("#variantModalLabel").html(`Select Variants for: <span class="">${p.name}</span>`);
                let html = "";
                
                p.variants.forEach(function(v) {
                    let variantNameParts = [];
                    if (v.color && v.color.name) variantNameParts.push(`Color: ${v.color.name}`);
                    if (v.size && v.size.name) variantNameParts.push(`Size: ${v.size.name}`);
                    let variantLabel = variantNameParts.length > 0 ? variantNameParts.join(' | ') : 'Standard';
                    
                    let vData = {
                        id: p.id,
                        variant_id: v.id,
                        clean_name: p.name,
                        sku: v.sku ?? p.sku ?? '-',
                        price: v.purchase_price || p.purchase_price || 0,
                        variant_label: variantLabel
                    };
                    let jsonStr = JSON.stringify(vData).replace(/'/g, "&apos;");

                    html += `
                    <tr>
                        <td class="fw-bold text-secondary">${variantLabel}</td>
                        <td><small class="text-muted">${vData.sku}</small></td>
                        <td><input type="number" class="form-control form-control-sm modal-price" value="${vData.price}" step="0.01" style="width: 100px;"></td>
                        <td>
                            <input type="number" class="form-control form-control-sm modal-qty fw-bold border-primary" data-variant='${jsonStr}' min="0" value="0" style="width: 90px;">
                        </td>
                    </tr>`;
                });
                
                $("#variantModalTableBody").html(html);
                $("#variantModal").modal("show");
                
                setTimeout(() => {
                    $("#variantModalTableBody input.modal-qty:first").focus().select();
                }, 500);
            }

            $("#btnModalAddProducts").click(function() {
                let anyAdded = false;
                
                $("#variantModalTableBody tr").each(function() {
                    let qtyInput = $(this).find(".modal-qty");
                    let qty = parseInt(qtyInput.val()) || 0;
                    
                    if (qty > 0) {
                        let pData = JSON.parse(qtyInput.attr("data-variant"));
                        let dynamicPrice = parseFloat($(this).find(".modal-price").val()) || pData.price;
                        
                        pData.price = dynamicPrice; 
                        addProduct(pData, qty);
                        anyAdded = true;
                    }
                });
                
                if (anyAdded) {
                    $("#variantModal").modal("hide");
                    $("#searchResults").hide();
                    $("#productSearch").val("");
                } else {
                    if(typeof toastr !== 'undefined') toastr.warning("Please enter quantity for at least one variant.");
                }
            });

            $(document).on("keypress", ".modal-qty", function(e) {
                if (e.which == 13) { 
                    e.preventDefault();
                    $("#btnModalAddProducts").click();
                }
            });

            function addProduct(p, qty = 1) {
                let total = p.price * qty;
                let row = `
                <tr>
                    <td>
                        <div>
                            <strong>${p.clean_name}</strong><br>
                            <small class="text-muted">SKU : ${p.sku}</small>
                            <input type="hidden" name="products[${rowIndex}][id]" value="${p.id}">
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1">${p.variant_label}</span>
                        <input type="hidden" name="products[${rowIndex}][variant_id]" value="${p.variant_id}">
                    </td>
                    <td>
                        <input type="number" class="form-control price" name="products[${rowIndex}][price]" value="${p.price}" step="0.01">
                    </td>
                    <td>
                        <input type="number" class="form-control qty" name="products[${rowIndex}][qty]" value="${qty}" min="1">
                    </td>
                    <td class="row-total fw-bold">
                        ${total.toFixed(2)}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                
                $("#selectedProducts tbody").append(row);
                rowIndex++;
                calculateTotal();
            }

            $(document).on("input", ".price, .qty", function() {
                calculateTotal();
            });

            function calculateTotal() {
                let grandTotal = 0;
                $("#selectedProducts tbody tr").each(function() {
                    let price = parseFloat($(this).find(".price").val()) || 0;
                    let qty = parseFloat($(this).find(".qty").val()) || 0;
                    let total = price * qty;
                    
                    $(this).find(".row-total").text(total.toFixed(2));
                    grandTotal += total;
                });
                $("#grandTotal").text(grandTotal.toFixed(2));
            }

            $(document).on("click", ".remove", function() {
                $(this).closest("tr").remove();
                calculateTotal();
            });

            $("#purchaseForm").submit(function(e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('purchases.update', $purchase->id) }}",
                    type: "POST", 
                    data: formData,
                    success: function(res) {
                        if(typeof toastr !== 'undefined') toastr.success(res.message || "Purchase updated successfully");
                        setTimeout(function() { 
                            window.location.href = "{{ route('purchases.index') }}"; 
                        }, 1000);
                    },
                    error: function(xhr) { 
                        if(typeof toastr !== 'undefined') toastr.error("Something went wrong"); 
                    }
                });
            });

            $(document).click(function(e) {
                if (!$(e.target).closest("#productSearch,#searchResults").length) {
                    $("#searchResults").hide();
                }
            });

            $("#supplierForm").submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('suppliers.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res){
                        if(res.success){
                            $("#supplier_id").append(
                                `<option value="${res.supplier.id}" selected>
                                    ${res.supplier.name}
                                </option>`
                            );
                            $("#supplierModal").modal("hide");
                            $("#supplierForm")[0].reset();
                            if(typeof toastr !== 'undefined') toastr.success(res.message);
                        }
                    },
                    error: function(xhr){
                        if(xhr.status == 422){
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value){
                                if(typeof toastr !== 'undefined') toastr.error(value[0]);
                            });
                        }else{
                            if(typeof toastr !== 'undefined') toastr.error("Something went wrong");
                        }
                    }
                });
            });
    
        });
    </script>
@endpush