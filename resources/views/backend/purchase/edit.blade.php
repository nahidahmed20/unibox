@extends('backend.layouts.app')
@section('title', 'Edit Purchase')
@section('content')
    @push('styles')
        <style>
            .shopify-card {
                background: #fff;
                border: 1px solid #e1e3e5;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                overflow: hidden;
            }

            .shopify-input {
                border: 1px solid #c9cccf;
                border-radius: 8px;
                padding: 12px;
                transition: all 0.2s;
            }

            .shopify-input:focus {
                border-color: #000032;
                box-shadow: 0 0 0 3px rgba(0, 128, 96, 0.15);
                outline: none;
            }

            .table-container {
                border: 1px solid #e1e3e5;
                border-radius: 12px;
            }

            .table thead {
                background: #fbfbfb;
                border-bottom: 1px solid #e1e3e5;
            }

            .table thead th {
                color: #5c5f62;
                font-size: 13px;
                font-weight: 600;
                text-transform: uppercase;
                padding: 16px;
            }

            .btn-shopify {
                background: #000032;
                color: #fff;
                border-radius: 8px;
                padding: 10px 24px;
                font-weight: 500;
            }

            .btn-shopify:hover {
                background: #000032;
                color: #fff;
            }

            .search-dropdown {
                border-radius: 12px;
                border: 1px solid #e1e3e5;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            #searchResults {
                position: absolute;
                width: 100%;
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 10px;
                max-height: 300px;
                overflow-y: auto;
                z-index: 999;
                display: none;
            }

            .search-item {
                padding: 12px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                cursor: pointer;
                border-bottom: 1px solid #f1f1f1;
                transition: .3s;
            }

            .search-item:hover {
                background: #f8f9fa;
            }

            .product-name-text {
                font-size: 15px;
                font-weight: 700;
                color: #222;
            }

            .product-sku-text {
                font-size: 12px;
                color: #777;
            }
        </style>
    @endpush

    <div class="container-fluid py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Edit Purchase</h2>
            <button type="submit" form="purchaseForm" class="btn btn-shopify">
                <i class="fa-solid fa-check me-2"></i> Update Purchase
            </button>
        </div>

        <form id="purchaseForm">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-lg-8">
                    <div class="shopify-card p-4 mb-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Search Products to Add</label>
                            <div class="position-relative">
                                <input type="text" id="productSearch" class="form-control shopify-input" placeholder="Search product by name or SKU...">
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
                    <div class="shopify-card p-4">
                        <h5 class="fw-bold mb-3">Purchase Details</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Supplier</label>
                            <div class="input-group">
                                <select class="form-select shopify-input" name="supplier_id" id="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}" {{ $purchase->supplier_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-shopify" data-bs-toggle="modal" data-bs-target="#supplierModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Invoice No</label>
                            <input type="text" name="invoice_no" value="{{ $purchase->invoice_no }}" class="form-control shopify-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Date</label>
                            <input type="date" name="purchase_date" value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d') }}" class="form-control shopify-input" required>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 text-muted">Total</span>
                            <h3 class="fw-bold mb-0">BDT <span id="grandTotal">{{ number_format($purchase->total_amount, 2) }}</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Supplier Modal -->
    <div class="modal fade" id="supplierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="supplierForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <textarea class="form-control" name="address" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="supplierStatus" name="status" value="1" checked>
                                <label class="form-check-label" for="supplierStatus">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-shopify">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowIndex = 0;

            // ==========================================
            // LOAD EXISTING PURCHASE DATA ON PAGE LOAD
            // ==========================================
            let existingDetails = @json($purchase->details);
            
            if (existingDetails.length > 0) {
                existingDetails.forEach(function(detail) {
                    if (detail.product) {
                        renderRow(detail.product, detail);
                    }
                });
            }

            // ==========================================
            // AJAX SEARCH
            // ==========================================
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
                                html += `
                                <div class="search-item" data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
                                    <div style="flex:1">
                                        <div class="product-name-text">${p.name}</div>
                                        <div class="product-sku-text">
                                            SKU : ${p.sku ?? 'N/A'} | Price : ${p.purchase_price ?? 0}
                                        </div>
                                    </div>
                                    <i class="fa fa-plus-circle text-success"></i>
                                </div>
                                `;
                            });
                        } else {
                            html = `<div class="p-3 text-center text-muted">No Product Found</div>`;
                        }
                        $("#searchResults").html(html).show();
                    }
                });
            });

            $(document).on("click", ".search-item", function() {
                let product = JSON.parse($(this).attr("data-product"));
                $("#searchResults").hide();
                $("#productSearch").val("");
                renderRow(product, null); // Null means it's a new item, not from DB
            });

            // ==========================================
            // RENDER ROW FUNCTION (Handles both Existing & New)
            // ==========================================
            function renderRow(p, existingDetail) {
                // If existingDetail is provided, use its values. Otherwise, use defaults.
                let price = existingDetail ? existingDetail.buying_price : (p.purchase_price ?? 0);
                let qty = existingDetail ? existingDetail.quantity : 1;
                let selectedVariantId = existingDetail ? existingDetail.product_variant_id : "";

                let variantHtml = "";

                if (p.product_type === 'multiple' && p.variants && p.variants.length > 0) {
                    variantHtml = `
                        <select name="products[${rowIndex}][variant_id]" class="form-select form-select-sm mb-1" required>
                            <option value="">Select Variant</option>
                            ${p.variants.map(function(v) {
                                let variantNameParts = [];
                                
                                if (v.color && v.color.name) {
                                    variantNameParts.push(`C: ${v.color.name}`);
                                }
                                
                                if (v.size) {
                                    let sizeName = v.size.name || v.size.size || v.size_id;
                                    variantNameParts.push(`S: ${sizeName}`);
                                } else if (v.size_id && isNaN(v.size_id)) {
                                    variantNameParts.push(`S: ${v.size_id}`);
                                }

                                let label = variantNameParts.length > 0 ? variantNameParts.join(' | ') : 'Standard';
                                let isSelected = (selectedVariantId == v.id) ? 'selected' : '';

                                return `<option value="${v.id}" ${isSelected}>${label} (Stock: ${v.stock})</option>`;
                            }).join("")}
                        </select>
                    `;
                } else {
                    variantHtml = `
                        <span class="badge bg-light text-dark border px-2 py-1">Standard</span>
                        <input type="hidden" name="products[${rowIndex}][variant_id]" value="">
                    `;
                }

                let row = `
                <tr>
                    <td>
                        <div>
                            <strong>${p.name}</strong><br>
                            <small class="text-muted">SKU : ${p.sku ?? '-'}</small>
                            <input type="hidden" name="products[${rowIndex}][id]" value="${p.id}">
                        </div>
                    </td>
                    <td>
                        ${variantHtml}
                    </td>
                    <td>
                        <input type="number" class="form-control price" name="products[${rowIndex}][price]" value="${price}" step="0.01" required>
                    </td>
                    <td>
                        <input type="number" class="form-control qty" name="products[${rowIndex}][qty]" value="${qty}" min="1" required>
                    </td>
                    <td class="row-total">
                        ${parseFloat(price * qty).toFixed(2)}
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                `;
                
                $("#selectedProducts tbody").append(row);
                rowIndex++;
                calculateTotal();
            }

            // ==========================================
            // CALCULATE TOTALS
            // ==========================================
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

            // ==========================================
            // FORM SUBMISSION (UPDATE)
            // ==========================================
            $("#purchaseForm").submit(function(e) {
                e.preventDefault();
                
                let btn = $(this).find('button[type="submit"]');
                let originalText = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> Updating...').prop('disabled', true);

                $.ajax({
                    url: "{{ route('purchases.update', $purchase->id) }}",
                    type: "POST", // Method Spoofing @method('PUT')
                    data: $(this).serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        setTimeout(function() {
                            window.location.href = "{{ route('purchases.index') }}";
                        }, 1000);
                    },
                    error: function(xhr) {
                        btn.html(originalText).prop('disabled', false);
                        toastr.error("Something went wrong or required fields are missing.");
                    }
                });
            });

            // Hide search dropdown on outside click
            $(document).click(function(e) {
                if (!$(e.target).closest("#productSearch, #searchResults").length) {
                    $("#searchResults").hide();
                }
            });

            // ==========================================
            // SUPPLIER AJAX STORE
            // ==========================================
            $("#supplierForm").submit(function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{ route('suppliers.store') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(res){
                        if(res.success){
                            $("#supplier_id").append(
                                `<option value="${res.supplier.id}" selected>${res.supplier.name}</option>`
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