@extends('backend.layouts.app')
@section('title', 'Create Purchase')
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

            .product-info {
                display: flex;
                flex-direction: column;
            }

            .product-title {
                font-size: 15px;
                font-weight: 700;
                color: #222;
            }

            .product-meta {
                font-size: 12px;
                color: #777;
            }

            .product-add {
                font-size: 22px;
                color: #000032;
            }
        </style>
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
                    <div class="shopify-card p-4">
                        <h5 class="fw-bold mb-3">Purchase Details</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Supplier</label>
                            <div class="input-group">
                                <select class="form-select shopify-input" name="supplier_id" id="supplier_id" required>
                                    <option value="">Select Supplier</option>
                                    @foreach ($suppliers as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" class="btn btn-shopify" data-bs-toggle="modal"
                                    data-bs-target="#supplierModal">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Invoice No</label>
                            <input type="text" name="invoice_no" value="{{ $nextInvoiceNo }}"
                                class="form-control shopify-input">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">
                                Date
                            </label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="form-control shopify-input" required>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fs-5 text-muted">Total</span>
                            <h3 class="fw-bold mb-0">BDT <span id="grandTotal">0.00</span></h3>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="supplierModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="supplierForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Add New Supplier
                        </h5>
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
                            <label class="form-check-label" for="supplierStatus">
                                Active
                            </label>
                        </div>
                    </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-shopify" >
                            Save Supplier
                        </button>
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
            $("#productSearch").on("keyup", function() {
                let q = $(this).val();

                if (q.length < 2) {
                    $("#searchResults").hide();
                    return;
                }
                $.ajax({
                    url: "{{ route('products.search') }}",
                    type: "GET",
                    data: {
                        q: q
                    },
                    success: function(res) {
                        let html = "";
                        if (res.length > 0) {
                            res.forEach(function(p) {
                                html += `
                                <div class="search-item"
                                    data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
                                    <div style="flex:1">
                                        <div class="product-name-text">
                                            ${p.name}
                                        </div>
                                        <div class="product-sku-text">
                                            SKU : ${p.sku}
                                            Price : ${p.purchase_price}
                                        </div>
                                    </div>
                                    <i class="fa fa-plus-circle text-success"></i>
                                </div>
                            `;
                            });
                        } else {
                            html = `
                            <div class="p-3 text-center text-muted">
                                No Product Found
                            </div>
                        `;
                        }
                        $("#searchResults").html(html).show();
                    }
                });
            });

            $(document).on("click", ".search-item", function() {
                let product = JSON.parse($(this).attr("data-product"));
                addProduct(product);
            });

            function addProduct(p) {
                $("#searchResults").hide();
                $("#productSearch").val("");
                let sizes = p.sizes || [];
                let colors = p.colors || [];
                let sizeHtml = "";
                if (sizes.length > 0) {
                    sizeHtml = `
                    <select
                        name="products[${rowIndex}][size_id]" class="form-select form-select-sm mb-1">
                        ${sizes.map(function (s) {
                            return `
                                    <option value="${s.id}">
                                        ${s.size}
                                    </option>
                                `;
                        }).join("")}
                    </select>
                `;
                }
                let colorHtml = "";
                    if (colors.length > 0) {
                        colorHtml = `
                            <select
                                name="products[${rowIndex}][color_id]"
                                class="form-select form-select-sm">
                                ${colors.map(function(c){
                                    let colorName = "";
                                    if(c.color){
                                        colorName = c.color.name;
                                    }
                                    return `
                                        <option value="${c.color_id}">
                                            ${colorName}
                                        </option>
                                    `;

                                }).join("")}

                            </select>
                        `;

                    }
                let row = `
                <tr>
                    <td>
                        <div>
                            <strong>${p.name}</strong><br>
                            <small class="text-muted">
                                SKU : ${p.sku ?? '-'}
                            </small>
                            <input type="hidden" name="products[${rowIndex}][id]" value="${p.id}">
                        </div>
                    </td>
                    <td>
                        ${sizeHtml}
                        ${colorHtml}
                    </td>
                    <td>
                        <input type="number" class="form-control price" name="products[${rowIndex}][price]"  value="${p.purchase_price}" step="0.01">
                    </td>
                    <td>
                        <input  type="number" class="form-control qty"  name="products[${rowIndex}][qty]" value="1" min="1">
                    </td>
                    <td class="row-total">
                        ${parseFloat(p.purchase_price).toFixed(2)}
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


            $(document).on("input", ".price,.qty", function() {
                calculateTotal();
            });

            function calculateTotal() {
                let grandTotal = 0;
                $("#selectedProducts tbody tr").each(function() {
                    let price = parseFloat(
                        $(this).find(".price").val()
                    ) || 0;
                    let qty = parseFloat(
                        $(this).find(".qty").val()
                    ) || 0;
                    let total = price * qty;
                    $(this).find(".row-total").text(total.toFixed(2));
                    grandTotal += total;
                });
                $("#grandTotal").text(
                    grandTotal.toFixed(2)
                );
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
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function() {
                        toastr.error("Something went wrong");
                    }
                });
            });

            $(document).click(function(e) {
                if (!$(e.target).closest("#productSearch,#searchResults").length) {
                    $("#searchResults").hide();
                }
            });
        });

        //==========================
        // SUPPLIER AJAX STORE
        //==========================

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
    
    </script>
@endpush
