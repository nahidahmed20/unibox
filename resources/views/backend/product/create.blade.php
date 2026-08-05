@extends('backend.layouts.app')

@section('title', 'Create Product')

@section('content')
@push('styles')
<style>
    /* Modern E-commerce Dashboard UI */
    .app-content {
        background-color: #f4f6f8; 
        padding-bottom: 50px;
    }

    .modern-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        background: #ffffff;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .modern-card-header {
        background: #ffffff;
        border-bottom: 1px solid #f0f2f5;
        padding: 18px 24px;
    }

    .modern-card-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #212b36;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-body {
        padding: 24px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #637381;
        margin-bottom: 6px;
    }

    /* Modern Input Fields */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid transparent;
        background-color: #f4f6f8;
        padding: 10px 16px;
        font-size: 14px;
        color: #212b36;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        background-color: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .form-control::placeholder {
        color: #919eab;
    }

    /* Pill Checkboxes for Sizes */
    .size-checkbox-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .size-check-input {
        display: none;
    }

    .size-check-label {
        padding: 8px 20px;
        background-color: #f4f6f8;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #637381;
    }

    .size-check-label:hover {
        background-color: #e2e8f0;
    }

    .size-check-input:checked + .size-check-label {
        background-color: #212b36;
        color: #ffffff;
        box-shadow: 0 4px 8px rgba(33, 43, 54, 0.2);
    }

    /* Color Group Styling */
    .color-image-group {
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        border: 1px dashed #ced4da;
        transition: all 0.3s;
    }
    
    .color-image-group:hover {
        border-color: #adb5bd;
        background: #f8f9fa;
    }

    /* Sticky Footer */
    .sticky-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 15px 24px;
        border-top: 1px solid #f0f2f5;
        border-radius: 0 0 12px 12px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    
    .btn-modern-primary {
        background-color: #212b36;
        color: #fff;
        border-radius: 8px;
        padding: 10px 24px;
        font-weight: 600;
        border: none;
    }
    
    .btn-modern-primary:hover {
        background-color: #000;
        color: #fff;
    }

    .premium-variation-wrapper {
        background: #fafafa;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #f0f0f0;
    }
    
    .premium-variation-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #878a99;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
        display: block;
    }

    .premium-size-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 50px;
        min-height: 42px;
        padding: 8px 18px;
        background-color: #ffffff;
        border: 2px solid #e9ebec;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px; 
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        user-select: none;
    }

    .premium-size-btn:hover {
        border-color: #ced4da;
        background-color: #f8f9fa;
    }

    .btn-check:checked + .premium-size-btn {
        background-color: #111827;
        border-color: #111827;
        color: #ffffff;
        box-shadow: 0 8px 16px rgba(17, 24, 39, 0.2);
        transform: scale(1.05); 
    }
</style>
@endpush

<div class="app-content-header mb-4 mt-3">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #212b36;">Add New Product</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Create Product</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4" id="errorAlert">
                <strong class="mb-2 d-block"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please review the errors below:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="productForm" enctype="multipart/form-data" action="{{route('products.store')}}" method="POST" class="row">
            @csrf
            
            <div class="col-lg-8">
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-regular fa-file-lines text-muted"></i> General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Product Title</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Acrylic Nameplate">
                            @error('name')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Product Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" placeholder="auto-generated-slug" readonly>
                                @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="1" placeholder="Brief summary...">{{ old('short_description') }}</textarea>
                                @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control summernote">{{ old('description') }}</textarea>
                            @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Extra Note <span class="text-muted fw-normal">(Product Page - Optional)</span></label>
                            <textarea name="extra_note" class="form-control" rows="2" placeholder="বিশেষ নোট">{{ old('extra_note') }}</textarea>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-circle-info me-1"></i> এই মেসেজ শুধু এই প্রোডাক্টের পেজে "Add to Cart" বাটনের নিচে দেখাবে।
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Custom Calculator Setup Card -->
                <div class="card modern-card border-warning">
                    <div class="modern-card-header bg-light">
                        <h5 class="text-dark"><i class="fa-solid fa-calculator text-warning me-2"></i> Custom Product Calculator Setup</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_calculator" name="is_calculator" value="1" {{ old('is_calculator', $product->is_calculator ?? 0) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_calculator">Enable Dynamic Price Calculator (Square Feet / Step Selection)</label>
                        </div>

                        <div class="mb-3 calculator-price-box" style="display: none;">
                            <label class="form-label">Price Per Square Feet (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control fw-bold text-success" name="price_per_sqft" value="{{ old('price_per_sqft', $product->price_per_sqft ?? 0) }}" placeholder="e.g. 150">
                        </div>
                    </div>
                </div>

                <!-- Assign Attributes for Calculator -->
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-list-check text-muted me-2"></i> Assign Attributes for Calculator</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php
                                $allAttributes = \App\Models\Attribute::where('status', 1)->get();
                                $assignedAttr = isset($selectedAttributes) ? $selectedAttributes : [];
                            @endphp
                            @if($allAttributes->count() > 0)
                                @foreach($allAttributes as $attr)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="attribute_ids[]" value="{{ $attr->id }}" id="attr_{{ $attr->id }}" {{ in_array($attr->id, old('attribute_ids', $assignedAttr)) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="attr_{{ $attr->id }}">
                                                {{ $attr->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-muted text-center py-2">
                                    No attributes found. Please create attributes first.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-regular fa-image text-muted"></i> Media & Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Main Thumbnail <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="image">
                                @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Size Guide Image (Optional)</label>
                                <input type="file" class="form-control" name="size_guide">
                                @error('size_guide')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Product Gallery</label>
                                <input type="file" class="form-control" name="images[]" multiple>
                                <small class="text-muted d-block mt-2"><i class="fa-solid fa-circle-info me-1"></i> You can select multiple images.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variations Section -->
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-layer-group text-muted"></i> Product Variations</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Product Type</label>
                                <select name="product_type" id="product_type" class="form-select">
                                    <option value="single" {{ old('product_type') == 'single' ? 'selected' : '' }}>Single Product</option>
                                    <option value="multiple" {{ old('product_type') == 'multiple' ? 'selected' : '' }}>Variable Product (Sizes, Colors)</option>
                                </select>
                            </div>

                            <div class="col-md-12 product-options" style="display: none;">
                                <hr class="text-muted mb-4">
                                
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label">Variation Group (e.g., Sizes)</label>
                                        <select name="variation_id" id="variation_select" class="form-select rounded-3">
                                            <option value="">Select Variation Group</option>
                                            @foreach($variations as $variation)
                                                <option value="{{ $variation->id }}" data-values="{{ json_encode($variation->sizes) }}" {{ old('variation_id') == $variation->id ? 'selected' : '' }}>
                                                    {{ $variation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-8 mb-4" id="variationSizesWrapper" style="display: none;">
                                        <div class="premium-variation-wrapper shadow-sm">
                                            <label class="premium-variation-label">
                                                <i class="fa-solid fa-gem text-dark me-1"></i> Available Sizes
                                            </label>
                                            <div id="variationSizes" class="d-flex flex-wrap gap-3 align-items-center"></div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label d-flex justify-content-between align-items-center mb-3">
                                        <span>Color Variants & Specific Images</span>
                                    </label>
                                    <div id="colorImageWrapper">
                                        <div class="color-image-group d-flex align-items-center gap-3 mb-3" data-index="0">
                                            <select name="color_image_names[0]" class="form-select color-select" style="max-width:180px;">
                                                <option value="">Select Color</option>
                                                @foreach($colors as $color)
                                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="file" name="color_images[0][]" class="form-control" multiple>
                                            <button type="button" class="btn btn-dark addColorImage text-nowrap rounded-3">
                                                <i class="fa fa-plus me-1"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4" id="variantCombinationsWrapper" style="display: none;">
                                    <label class="form-label text-dark fw-bold mb-3">Variant Price & Stock Setup</label>
                                    <div class="table-responsive border rounded-3">
                                        <table class="table table-hover align-middle mb-0 bg-white">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Variant (Color - Size)</th>
                                                    <th>SKU <span class="text-danger">*</span></th>
                                                    <th>Purchase Price</th>
                                                    <th>Selling Price <span class="text-danger">*</span></th>
                                                    <th>Stock <span class="text-danger">*</span></th>
                                                </tr>
                                            </thead>
                                            <tbody id="variantCombinationsTableBody"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-lg-4">
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-tag text-muted"></i> Pricing & Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Selling Price (৳) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control fs-5 fw-bold text-success" name="selling_price" id="selling_price" value="{{ old('selling_price', 0) }}">
                                @error('selling_price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Maximum Price</label>
                                <input type="number" step="0.01" class="form-control fs-5 fw-bold text-primary" name="max_price" id="max_price" value="{{ old('max_price', 0) }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Cost Price</label>
                                <input type="number" step="0.01" class="form-control" name="purchase_price" id="base_purchase_price" value="{{ old('purchase_price', 0) }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control" name="main_price" value="{{ old('main_price', 0) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="">No Discount</option>
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control font-monospace" id="sku" name="sku" value="{{ old('sku') }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Alert Qty</label>
                                <input type="number" class="form-control" name="alert_quantity" value="{{ old('alert_quantity', 5) }}">
                            </div>
                            <div class="col-6 mb-3" id="singleProductStockWrapper">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control fw-bold" name="stock" value="{{ old('stock', 100) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-folder-tree text-muted"></i> Organization</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subcategory</label>
                            <select name="subcategory_id" id="subcategory_id" class="form-select">
                                <option value="">Select Subcategory</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card modern-card bg-transparent shadow-none">
                    <div class="sticky-footer rounded-4 shadow-sm border">
                        <a href="{{ route('products.index') }}" class="btn btn-light px-4 border rounded-3 fw-bold">Cancel</a>
                        <button type="submit" id="submitBtn" class="btn btn-modern-primary">
                            <i class="fa-solid fa-check me-2"></i> Save Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('.summernote').length > 0) {
            $('.summernote').summernote({ height: 250 });
        }

        // Calculator Box Toggle Logic
        function toggleCalculatorBox() {
            if ($('#is_calculator').is(':checked')) {
                $('.calculator-price-box').slideDown();
            } else {
                $('.calculator-price-box').slideUp();
            }
        }
        toggleCalculatorBox();
        $('#is_calculator').on('change', toggleCalculatorBox);

        // Auto Generate Slug & SKU
        $('#name').on('keyup', function() {
            let name = $(this).val().trim();
            let slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            $('#slug').val(slug);
            if (name.length > 0) {
                let randomCode = Math.random().toString(36).substring(2, 8).toUpperCase();
                $('#sku').val(name.substring(0,3).toUpperCase() + '-' + randomCode);
            } else {
                $('#sku').val('');
            }
            if($('#product_type').val() === 'multiple') {
                generateVariantMatrix();
            }
        });

        // Toggle Single vs Variable Products view
        function toggleProductOptions() {
            if($('#product_type').val() === 'single') {
                $('.product-options').slideUp(300);
                $('#variantCombinationsWrapper').slideUp(300);
                $('#singleProductStockWrapper').slideDown(300);
                
                $('#variation_select').prop('disabled', true).val('');
                $('.color-select').prop('disabled', true).val('');
                $('.size-check-input').prop('disabled', true).prop('checked', false);
            } else {
                $('.product-options').slideDown(300);
                $('#singleProductStockWrapper').slideUp(300);
                
                $('#variation_select').prop('disabled', false);
                $('.color-select').prop('disabled', false);
                $('.size-check-input').prop('disabled', false);
                generateVariantMatrix();
            }
        }
        toggleProductOptions();
        $('#product_type').on('change', toggleProductOptions);

        // Dynamic Color Row Addition
        let colorImageIndex = 1;
        $(document).on('click', '.addColorImage', function () {
            let index = colorImageIndex++;
            let options = `
                <option value="">Select Color</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
            `;

            let html = `
                <div class="color-image-group d-flex align-items-center gap-3 mb-3" data-index="${index}">
                    <select name="color_image_names[${index}]" class="form-select color-select" style="max-width:180px;">
                        ${options}
                    </select>
                    <input type="file" name="color_images[${index}][]" class="form-control" multiple>
                    <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3">
                        <i class="fa fa-trash"></i> Remove
                    </button>
                </div>
            `;
            $('#colorImageWrapper').append(html);
        });

        $(document).on('click', '.removeColorImage', function () {
            $(this).closest('.color-image-group').slideUp(300, function(){ 
                $(this).remove(); 
                generateVariantMatrix(); 
            });
        });

        // Category to Subcategory AJAX
        $('#category_id').on('change', function(){
            let category_id = $(this).val();
            let subDropdown = $('#subcategory_id');
            subDropdown.empty().append('<option value="">Loading...</option>');

            if(category_id){
                $.ajax({
                    url: '/admin/get-subcategories/' + category_id, 
                    type: "GET",
                    dataType: "json",
                    success:function(data){
                        subDropdown.empty().append('<option value="">Select Subcategory</option>');
                        $.each(data, function(key, value){
                            subDropdown.append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    },
                    error: function() {
                        subDropdown.empty().append('<option value="">Select Subcategory</option>');
                    }
                });
            } else {
                subDropdown.empty().append('<option value="">Select Subcategory</option>');
            }
        });

        // Variation Sizes Generator
        $(document).on('change', '#variation_select', function () {
            let option = $(this).find(':selected');
            let values = option.data('values') || option.attr('data-values');
            let wrapper = $('#variationSizes');
            let mainWrapper = $('#variationSizesWrapper');
            
            wrapper.empty();

            if (!values || values === "") {
                mainWrapper.hide();
                generateVariantMatrix();
                return;
            }

            if (typeof values === 'string') {
                try { values = JSON.parse(values); } catch (e) { values = []; }
            }

            if (Array.isArray(values) && values.length > 0) {
                mainWrapper.fadeIn();
                let html = ``;
                
                values.forEach(function (item, index) {
                    let displayValue = item.value || item.name || item.text || item;
                    let sizeId = item.id || item;

                    html += `
                        <div class="position-relative">
                            <input type="checkbox" class="btn-check size-check-input" name="variation_sizes[]" value="${sizeId}" data-name="${displayValue}" id="size_${index}" autocomplete="off">
                            <label class="premium-size-btn" for="size_${index}">${displayValue}</label>
                        </div>
                    `;
                });
                wrapper.html(html);
            } else {
                mainWrapper.hide();
            }
            generateVariantMatrix();
        });

        $(document).on('change', '.size-check-input, .color-select', function() {
            generateVariantMatrix();
        });

        // Variant Matrix Generator
        function generateVariantMatrix() {
            if($('#product_type').val() === 'single') {
                $('#variantCombinationsWrapper').hide();
                return;
            }

            let selectedSizes = [];
            $('.size-check-input:checked').each(function() {
                selectedSizes.push({ id: $(this).val(), name: $(this).data('name') });
            });

            let selectedColors = [];
            $('.color-select').each(function() {
                let colorId = $(this).val();
                let colorName = $(this).find('option:selected').text().trim();
                if (colorId && colorId !== "" && colorName !== "Select Color") {
                    selectedColors.push({ id: colorId, name: colorName });
                }
            });

            let tbody = $('#variantCombinationsTableBody');
            tbody.empty();

            if (selectedSizes.length === 0 && selectedColors.length === 0) {
                $('#variantCombinationsWrapper').hide();
                return;
            }

            let combinations = [];
            if (selectedColors.length > 0 && selectedSizes.length > 0) {
                selectedColors.forEach(color => {
                    selectedSizes.forEach(size => { combinations.push({ color: color, size: size }); });
                });
            } else if (selectedColors.length > 0) {
                selectedColors.forEach(color => { combinations.push({ color: color, size: null }); });
            } else if (selectedSizes.length > 0) {
                selectedSizes.forEach(size => { combinations.push({ color: null, size: size }); });
            }

            if (combinations.length > 0) {
                $('#variantCombinationsWrapper').fadeIn();
                let baseSku = $('#sku').val() || 'PROD';
                let defaultSellingPrice = $('#selling_price').val() || '';
                let defaultPurchasePrice = $('#base_purchase_price').val() || '';

                combinations.forEach((variant, index) => {
                    let variantName = '';
                    let colorVal = variant.color ? variant.color.id : '';
                    let sizeVal = variant.size ? variant.size.id : '';
                    
                    if (variant.color) variantName += variant.color.name;
                    if (variant.color && variant.size) variantName += ' - ';
                    if (variant.size) variantName += variant.size.name;

                    let colorCode = variant.color ? variant.color.name.substring(0,3).toUpperCase() : '';
                    let sizeCode = variant.size ? variant.size.name.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() : '';
                    let variantSku = baseSku + (colorCode ? '-' + colorCode : '') + (sizeCode ? '-' + sizeCode : '');

                    let row = `
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-2 fw-bold">${variantName}</span>
                                <input type="hidden" name="variants[${index}][color_id]" value="${colorVal}">
                                <input type="hidden" name="variants[${index}][size_id]" value="${sizeVal}">
                            </td>
                            <td><input type="text" name="variants[${index}][sku]" class="form-control font-monospace form-control-sm" value="${variantSku}" required></td>
                            <td><input type="number" step="0.01" name="variants[${index}][purchase_price]" class="form-control form-control-sm" value="${defaultPurchasePrice}"></td>
                            <td><input type="number" step="0.01" name="variants[${index}][selling_price]" class="form-control form-control-sm text-success fw-bold" value="${defaultSellingPrice}" required></td>
                            <td><input type="number" name="variants[${index}][stock]" class="form-control form-control-sm" value="1" required></td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            } else {
                $('#variantCombinationsWrapper').hide();
            }
        }

        $('#productForm').on('submit', function() {
            let $btn = $('#submitBtn');
            $btn.html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');
            $btn.prop('disabled', true); 
        });
    });
</script>
@endpush