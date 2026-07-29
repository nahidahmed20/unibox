@extends('backend.layouts.app')
@section('title', 'Create Purchase')
@section('content')
@push('styles')
    @include('backend.purchase.purchase_css')
@endpush
    <div class="container-fluid py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Create Purchase</h2>
            <button type="submit" form="purchaseForm" class="btn btn-shopify">Save Purchase</button>
        </div>

        <form id="purchaseForm">
            @csrf
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopify-card p-4 mb-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Search Products</label>
                            <div class="position-relative">
                                <input type="text" id="productSearch" class="form-control shopify-input"
                                    placeholder="Search...">
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
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="premium-summary-card p-4">
                        <!-- Header -->
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <div class="summary-icon-box">
                                <i class="fa fa-file-invoice text-dark fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-slate-900 mb-0" style="font-size: 1.1rem; color: #0f172a;">Purchase Details</h5>
                                <small class="text-muted" style="font-size: 0.78rem;">Document info & total summary</small>
                            </div>
                        </div>

                        <!-- Supplier Field -->
                        <div class="mb-3">
                            <label class="form-label custom-input-label">Supplier</label>
                            <div class="input-group gap-2">
                                <select class="form-select modern-dashboard-input" name="supplier_id" id="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-modern-action" data-bs-toggle="modal" data-bs-target="#supplierModal" title="Add New Supplier">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Invoice Field -->
                        <div class="mb-3">
                            <label class="form-label custom-input-label">Invoice No</label>
                            <div class="position-relative">
                                <input type="text" name="invoice_no" value="{{ $nextInvoiceNo }}" class="form-control modern-dashboard-input fw-semibold text-dark">
                            </div>
                        </div>

                        <!-- Date Field -->
                        <div class="mb-4">
                            <label class="form-label custom-input-label">Purchase Date</label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="form-control modern-dashboard-input" required>
                        </div>
                        
                        <!-- Separator -->
                        <div class="dashed-separator my-4"></div>
                        
                        <!-- Grand Total Premium Section -->
                        <div class="total-display-box d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-uppercase tracking-wider text-muted d-block" style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.8px;">Total Payable</span>
                                <span class="fw-bold" style="font-size: 0.95rem; color: #64748b;">Net Amount</span>
                            </div>
                            <div class="text-end">
                                <h3 class="fw-extrabold mb-0" style="font-size: 1.75rem; color: #0f172a; letter-spacing: -0.5px;">
                                    <span style="font-size: 1rem; font-weight: 700; color: #64748b; margin-right: 2px;">BDT</span>
                                    <span id="grandTotal">0.00</span>
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
            let rowIndex = 0;

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
                        selling_price: v.selling_price || p.selling_price || 0, 
                        variant_label: variantLabel
                    };
                    let jsonStr = JSON.stringify(vData).replace(/'/g, "&apos;");

                    html += `
                    <tr>
                        <td class="fw-bold text-secondary">${variantLabel}</td>
                        <td><small class="text-muted">${vData.sku}</small></td>
                        <td><input type="number" class="form-control form-control-sm modal-price" value="${vData.price}" step="0.01" style="width: 100px;"></td>
                        <td><input type="number" class="form-control form-control-sm modal-selling-price" value="${vData.selling_price}" step="0.01" style="width: 100px;"></td>
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
                        pData.price = parseFloat($(this).find(".modal-price").val()) || pData.price;
                        pData.selling_price = parseFloat($(this).find(".modal-selling-price").val()) || pData.selling_price; // ✅ Selling price ধরা হলো
                        
                        addProduct(pData, qty);
                        anyAdded = true;
                    }
                });
                
                if (anyAdded) {
                    $("#variantModal").modal("hide");
                    $("#searchResults").hide();
                    $("#productSearch").val("");
                } else {
                    toastr.warning("Please enter quantity for at least one variant.");
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
                        <label class="d-block" style="font-size:10px;">Buy Price</label>
                        <input type="number" class="form-control price" name="products[${rowIndex}][price]" value="${p.price}" step="0.01">
                    </td>
                    <td>
                        <label class="d-block text-success" style="font-size:10px;">Sell Price</label>
                        <input type="number" class="form-control price" name="products[${rowIndex}][selling_price]" value="${p.selling_price}" step="0.01">
                    </td>
                    <td>
                        <label class="d-block" style="font-size:10px;">Qty</label>
                        <input type="number" class="form-control qty" name="products[${rowIndex}][qty]" value="${qty}" min="1">
                    </td>
                    <td class="row-total fw-bold text-end" style="vertical-align: bottom;">
                        ${total.toFixed(2)}
                    </td>
                    <td class="text-center" style="vertical-align: bottom;">
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
                $.ajax({
                    url: "{{ route('purchases.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        setTimeout(function() { location.reload(); }, 1000);
                    },
                    error: function() { toastr.error("Something went wrong"); }
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
                        toastr.success(res.message);
                    }
                },
                error: function(xhr){
                    if(xhr.status == 422){
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value){
                            toastr.error(value[0]);
                        });
                    }else{
                        toastr.error("Something went wrong");
                    }
                }
            });
        });
    
        });
    </script>
@endpush
