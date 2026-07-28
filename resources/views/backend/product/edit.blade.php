@extends('backend.layouts.app')

@section('title', 'Edit Product')

@section('content')
@push('styles')
<style>
    /* Modern E-commerce Dashboard UI */
    .app-content { background-color: #f4f6f8; padding-bottom: 50px; }
    .modern-card { border: none; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04); border-radius: 12px; background: #ffffff; margin-bottom: 24px; overflow: hidden; }
    .modern-card-header { background: #ffffff; border-bottom: 1px solid #f0f2f5; padding: 18px 24px; }
    .modern-card-header h5 { margin: 0; font-size: 16px; font-weight: 600; color: #212b36; display: flex; align-items: center; gap: 8px; }
    .card-body { padding: 24px; }
    .form-label { font-size: 13px; font-weight: 600; color: #637381; margin-bottom: 6px; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid transparent; background-color: #f4f6f8; padding: 10px 16px; font-size: 14px; color: #212b36; transition: all 0.2s; }
    .form-control:focus, .form-select:focus { background-color: #ffffff; border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1); }
    .size-checkbox-wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
    .size-check-input { display: none; }
    .size-check-label { padding: 8px 20px; background-color: #f4f6f8; border: 1px solid transparent; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; color: #637381; }
    .size-check-label:hover { background-color: #e2e8f0; }
    .size-check-input:checked + .size-check-label { background-color: #212b36; color: #ffffff; box-shadow: 0 4px 8px rgba(33, 43, 54, 0.2); }
    .color-image-group { background: #ffffff; padding: 15px; border-radius: 10px; border: 1px dashed #ced4da; transition: all 0.3s; }
    .sticky-footer { position: sticky; bottom: 0; z-index: 10; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); padding: 15px 24px; border-top: 1px solid #f0f2f5; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end; gap: 12px; }
    .btn-modern-primary { background-color: #212b36; color: #fff; border-radius: 8px; padding: 10px 24px; font-weight: 600; border: none; }
    /* Premium Variation Wrapper */
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

    /* Premium Size Pill Styling */
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

    /* Hover Effect */
    .premium-size-btn:hover {
        border-color: #ced4da;
        background-color: #f8f9fa;
    }

    /* Active/Checked Effect */
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
                <h3 class="mb-0 fw-bold" style="color: #212b36;">Edit Product</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Edit Product</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4">
                <strong class="mb-2 d-block"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please review the errors below:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form id="productForm" enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}" method="POST" class="row">
            @csrf
            @method('PUT') 
            
            <div class="col-lg-8">
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-regular fa-file-lines text-muted"></i> General Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Product Title</label>
                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ old('name', $product->name) }}">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Product Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $product->slug) }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="1">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control summernote">{{ old('description', $product->description) }}</textarea>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Extra Note <span class="text-muted fw-normal">(Product Page - Optional)</span></label>
                            <textarea name="extra_note" class="form-control" rows="2" placeholder="বিশেষ নোট">{{ old('extra_note', $product->extra_note ?? '') }}</textarea>
                            <small class="text-muted d-block mt-1">
                                <i class="fa-solid fa-circle-info me-1"></i> এই মেসেজ শুধু এই প্রোডাক্টের পেজে "Add to Cart" বাটনের নিচে দেখাবে। খালি রাখলে কিছু দেখাবে না।
                            </small>
                            @error('extra_note')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
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
                                <label class="form-label">Main Thumbnail</label>
                                <input type="file" class="form-control" name="image">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="img-thumbnail mt-2" style="max-height:80px;">
                                @endif
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Size Guide Image (Optional)</label>
                                <input type="file" class="form-control" name="size_guide">
                                @if($product->size_guide)
                                    <img src="{{ asset($product->size_guide) }}" class="img-thumbnail mt-2" style="max-height:80px;">
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Product Gallery</label>
                                <input type="file" class="form-control" name="images[]" multiple>
                                <small class="text-muted d-block mt-2">Uploading new images will replace existing ones.</small>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($product->images as $img)
                                        <img src="{{ asset($img->image) }}" class="img-thumbnail shadow-sm" style="height:60px; width:60px; object-fit:cover;">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-layer-group text-muted"></i> Product Variations</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Product Type</label>
                                <select name="product_type" id="product_type" class="form-select">
                                    <option value="single" {{ old('product_type', $product->product_type) == 'single' ? 'selected' : '' }}>Single Product</option>
                                    <option value="multiple" {{ old('product_type', $product->product_type) == 'multiple' ? 'selected' : '' }}>Variable Product (Sizes, Colors)</option>
                                </select>
                            </div>

                            <div class="col-md-12 product-options" style="display: none;">
                                <hr class="text-muted mb-4">
                                
                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <label class="form-label">Variation Group (e.g., Sizes)</label>
                                        <select id="variation_select" class="form-select" name="variation_id">
                                            <option value="">Select Variation Base</option>
                                            @foreach($variations as $variation)
                                                <option value="{{ $variation->id }}" data-values="{{ json_encode($variation->sizes) }}" {{ old('variation_id', $product->variation_id) == $variation->id ? 'selected' : '' }}>
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
                                        <button type="button" class="btn btn-sm btn-dark addColorImage"><i class="fa fa-plus"></i> Add Color</button>
                                    </label>
                                    <div id="colorImageWrapper">
                                        @if($product->colors->count() > 0)
                                            @foreach($product->colors as $index => $pColor)
                                                <div class="color-image-group d-flex align-items-center gap-3 mb-3" data-index="{{ $index }}">
                                                    <select name="color_image_names[{{ $index }}]" class="form-select color-select" style="max-width:180px;">
                                                        <option value="">Select Color</option>
                                                        @foreach($colors as $color)
                                                            <option value="{{ $color->id }}" {{ $pColor->color_id == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="color_images[{{ $index }}][]" class="form-control" multiple>
                                                        @if($pColor->images->count() > 0)
                                                            <div class="d-flex gap-1 mt-1">
                                                                @foreach($pColor->images as $cImg)
                                                                    <img src="{{ asset($cImg->image) }}" class="rounded" style="height:30px; width:30px; object-fit:cover;">
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3"><i class="fa fa-trash"></i></button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="color-image-group d-flex align-items-center gap-3 mb-3" data-index="0">
                                                <select name="color_image_names[0]" class="form-select color-select" style="max-width:180px;">
                                                    <option value="">Select Color</option>
                                                    @foreach($colors as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                    @endforeach
                                                </select>
                                                <input type="file" name="color_images[0][]" class="form-control" multiple>
                                                <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3"><i class="fa fa-trash"></i></button>
                                            </div>
                                        @endif
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
                                            <tbody id="variantCombinationsTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-solid fa-tag text-muted"></i> Pricing & Inventory</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Selling Price (Min)</label>
                                <input type="number" step="0.01" class="form-control fs-5 fw-bold text-success" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Maximum Price</label>
                                <input type="number" step="0.01" class="form-control fs-5 fw-bold text-primary" name="max_price" value="{{ old('max_price', $product->max_price) }}">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Cost Price</label>
                                <input type="number" step="0.01" class="form-control" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control" name="main_price" value="{{ old('main_price', $product->main_price) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="" {{ old('discount_type', $product->discount_type) == '' ? 'selected' : '' }}>No Discount</option>
                                <option value="percent" {{ old('discount_type', $product->discount_type) == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control font-monospace" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" readonly>
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Alert Qty</label>
                                <input type="number" class="form-control" name="alert_quantity" value="{{ old('alert_quantity', $product->alert_quantity) }}">
                            </div>
                            <div class="col-12 mb-3" id="singleProductStockWrapper">
                                <label class="form-label">Current Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control fw-bold" name="stock" value="{{ old('stock', $product->stock) }}">
                                <small class="text-muted">For single product</small>
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
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Search Category...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subcategory</label>
                            <select name="subcategory_id" id="subcategory_id" class="form-select">
                                <option value="">Select Subcategory</option>
                                @foreach($subcategories as $subcat)
                                    <option value="{{ $subcat->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcat->id ? 'selected' : '' }}>{{ $subcat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                <option value="">Select Unit</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card modern-card">
                    <div class="modern-card-header">
                        <h5><i class="fa-regular fa-eye text-muted"></i> Visibility</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Featured --}}
                            <div class="col-6 mb-3">
                                <label class="form-label">Featured</label>
                                <select name="is_featured" class="form-select">
                                    <option value="1" {{ old('is_featured', $product->is_featured) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_featured', $product->is_featured) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            
                            {{-- New Arrival --}}
                            <div class="col-6 mb-3">
                                <label class="form-label">New Arrival</label>
                                <select name="is_new" class="form-select">
                                    <option value="1" {{ old('is_new', $product->is_new) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_new', $product->is_new) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Best Seller --}}
                            <div class="col-6 mb-3">
                                <label class="form-label">Best Seller</label>
                                <select name="is_bestseller" class="form-select">
                                    <option value="1" {{ old('is_bestseller', $product->is_bestseller) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_bestseller', $product->is_bestseller) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Trending --}}
                            <div class="col-6 mb-3">
                                <label class="form-label">Trending</label>
                                <select name="is_trending" class="form-select">
                                    <option value="1" {{ old('is_trending', $product->is_trending) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_trending', $product->is_trending) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            {{-- Status --}}
                            <div class="col-12 mt-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select {{ old('status', $product->status) == '1' ? 'bg-success text-white border-0' : 'bg-secondary text-white border-0' }}" id="statusSelect">
                                    <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Active (Published)</option>
                                    <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Inactive (Draft)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card modern-card bg-transparent shadow-none">
                    <div class="sticky-footer rounded-4 shadow-sm border">
                        <a href="{{ route('products.index') }}" class="btn btn-light px-4 border rounded-3 fw-bold">Cancel</a>
                        <button type="submit" class="btn btn-modern-primary">
                            <i class="fa-solid fa-check me-2"></i> Update Product
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

        let existingVariants = @json($product->variants ?? []);
        let existingSizes = @json($product->variants->pluck('size_id')->filter()->unique()->values() ?? []);
        let savedVariationId = "{{ $product->variation_id }}";

        // Toggle Product Options
        function toggleProductOptions() {
            if($('#product_type').val() === 'single') {
                $('.product-options').slideUp(300);
                $('#variantCombinationsWrapper').slideUp(300);
                $('#singleProductStockWrapper').slideDown(300);
                
                $('#variation_select').prop('disabled', true);
                $('.color-select').prop('disabled', true);
                $('.size-check-input').prop('disabled', true);
            } else {
                $('.product-options').slideDown(300);
                $('#singleProductStockWrapper').slideUp(300);
                
                $('#variation_select').prop('disabled', false);
                $('.color-select').prop('disabled', false);
                $('.size-check-input').prop('disabled', false);
            }
        }
        
        toggleProductOptions();
        $('#product_type').on('change', toggleProductOptions);

        // Color Handling
        let selectedColors = [];
        function refreshColorList() {
            selectedColors = [];
            $('.color-select').each(function() {
                let colorId = $(this).val();
                if(colorId && colorId !== "") {
                    selectedColors.push({ id: colorId, name: $(this).find('option:selected').text() });
                    $(this).data('prev', colorId);
                }
            });
        }
        refreshColorList();

        $(document).on('change', '.color-select', function () {
            let value = $(this).val();
            let prevValue = $(this).data('prev');
            let isDuplicate = false;

            $('.color-select').not(this).each(function() {
                if ($(this).val() === value && value !== "") isDuplicate = true;
            });

            if (isDuplicate) {
                alert('This color is already selected!');
                $(this).val(prevValue); 
                return;
            }

            $(this).data('prev', value);
            refreshColorList();
            generateVariantMatrix();
        });

        // Add/Remove Colors
        let colorImageIndex = {{ max(1, $product->colors->count()) }};
        $(document).on('click', '.addColorImage', function () {
            let index = colorImageIndex++;
            let options = `<option value="">Select Color</option>
                @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach`;

            let html = `
                <div class="color-image-group d-flex align-items-center gap-3 mb-3" data-index="${index}">
                    <select name="color_image_names[${index}]" class="form-select color-select" style="max-width:180px;">${options}</select>
                    <div class="flex-grow-1">
                        <input type="file" name="color_images[${index}][]" class="form-control" multiple>
                    </div>
                    <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3"><i class="fa fa-trash"></i></button>
                </div>
            `;
            $('#colorImageWrapper').append(html);
        });

        $(document).on('click', '.removeColorImage', function () {
            let group = $(this).closest('.color-image-group');
            group.slideUp(300, function(){ 
                $(this).remove(); 
                refreshColorList();
                generateVariantMatrix(); 
            });
        });

        // Category -> Subcategory AJAX
        $('select[name="category_id"]').on('change', function(){
            let category_id = $(this).val();
            let subDropdown = $('select[name="subcategory_id"]');
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
                    }
                });
            } else {
                subDropdown.empty().append('<option value="">Select Subcategory</option>');
            }
        });

        // Variation (Sizes) Select
        $(document).on('change', '#variation_select', function () {
            let option = $(this).find(':selected');
            let valuesStr = option.attr('data-values') || option.data('values');
            let wrapper = $('#variationSizes');
            let mainWrapper = $('#variationSizesWrapper');
            wrapper.empty();

            if (!valuesStr || valuesStr === "") {
                mainWrapper.hide();
                generateVariantMatrix();
                return;
            }

            let values = [];
            if (typeof valuesStr === 'string') {
                try { values = JSON.parse(valuesStr); } catch (e) { values = []; }
            } else {
                values = valuesStr;
            }

            if (Array.isArray(values) && values.length > 0) {
                mainWrapper.fadeIn();
                let html = ``;
                
                values.forEach(function (item, index) {
                    let displayValue = '';
                    let sizeId = '';
                    if (item !== null && typeof item === 'object') {
                        displayValue = item.value || item.name || item.text;
                        sizeId = item.id;
                    } else {
                        displayValue = item;
                        sizeId = item;
                    }

                    if (!displayValue || displayValue.toString().trim() === "") return;
                    
                    // existingSizes এর ভেতরে size_id আছে কিনা তা চেক করছে
                    let isChecked = existingSizes.some(id => String(id) === String(sizeId)) ? 'checked' : '';
                    
                    html += `
                        <div class="position-relative">
                            <input type="checkbox" class="btn-check size-check-input" name="variation_sizes[]" value="${sizeId}" data-name="${displayValue}" id="size_${index}" ${isChecked}>
                            <label class="premium-size-btn" for="size_${index}">
                                ${displayValue}
                            </label>
                        </div>
                    `;
                });
                wrapper.html(html);
            } else {
                mainWrapper.hide();
            }
            generateVariantMatrix();
        });

        $(document).on('change', '.size-check-input', function() {
            generateVariantMatrix();
        });

        // Generate Matrix Table
        function generateVariantMatrix() {
            let selectedSizes = [];
            $('.size-check-input:checked').each(function() { 
                selectedSizes.push({
                    id: $(this).val(),
                    name: $(this).data('name')
                }); 
            });

            refreshColorList();

            let tbody = $('#variantCombinationsTableBody');
            tbody.empty();

            if (selectedSizes.length === 0 && selectedColors.length === 0) {
                $('#variantCombinationsWrapper').hide();
                return;
            }

            let combinations = [];
            if (selectedColors.length > 0 && selectedSizes.length > 0) {
                selectedColors.forEach(c => { selectedSizes.forEach(s => { combinations.push({ color: c, size: s }); }); });
            } else if (selectedColors.length > 0) {
                selectedColors.forEach(c => { combinations.push({ color: c, size: null }); });
            } else if (selectedSizes.length > 0) {
                selectedSizes.forEach(s => { combinations.push({ color: null, size: s }); });
            }

            if (combinations.length > 0) {
                $('#variantCombinationsWrapper').fadeIn();
                let baseSku = $('#sku').val() || 'PROD';
                let defaultSellingPrice = $('#selling_price').val() || '';

                combinations.forEach((variant, index) => {
                    let colorVal = variant.color ? variant.color.id : null;
                    let sizeVal = variant.size ? variant.size.id : null; // size_id string out

                    let dbMatch = existingVariants.find(v => {
                        let matchColor = (v.color_id == colorVal) || (!v.color_id && !colorVal);
                        let matchSize = (v.size_id == sizeVal) || (!v.size_id && !sizeVal); // size_id check
                        return matchColor && matchSize;
                    });

                    let variantName = '';
                    if (variant.color) variantName += variant.color.name;
                    if (variant.color && variant.size) variantName += ' - ';
                    if (variant.size) variantName += variant.size.name;

                    let colorCode = variant.color ? variant.color.name.substring(0,3).toUpperCase() : '';
                    let sizeCode = variant.size ? variant.size.name.toString().replace(/[^a-zA-Z0-9]/g, '').toUpperCase() : '';
                    
                    let finalSku = dbMatch ? dbMatch.sku : (baseSku + (colorCode ? '-'+colorCode : '') + (sizeCode ? '-'+sizeCode : ''));
                    let finalPurchase = dbMatch ? dbMatch.purchase_price : '';
                    let finalSelling = dbMatch ? dbMatch.selling_price : defaultSellingPrice;
                    let finalStock = dbMatch ? dbMatch.stock : 0;

                    let row = `
                        <tr>
                            <td class="fw-bold text-secondary">
                                ${variantName}
                                <input type="hidden" name="variants[${index}][color_id]" value="${colorVal || ''}">
                                <input type="hidden" name="variants[${index}][size_id]" value="${sizeVal || ''}">
                            </td>
                            <td><input type="text" name="variants[${index}][sku]" class="form-control font-monospace form-control-sm" value="${finalSku}" required></td>
                            <td><input type="number" step="0.01" name="variants[${index}][purchase_price]" class="form-control form-control-sm" value="${finalPurchase}"></td>
                            <td><input type="number" step="0.01" name="variants[${index}][selling_price]" class="form-control font-monospace form-control-sm text-success fw-bold" value="${finalSelling}" required></td>
                            <td><input type="number" name="variants[${index}][stock]" class="form-control form-control-sm" value="${finalStock}" required></td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            } else {
                $('#variantCombinationsWrapper').hide();
            }
        }
        // Initialize Data on Page Load
        if (savedVariationId && $('#product_type').val() === 'multiple') {
            $('#variation_select').val(savedVariationId).trigger('change');
        } else if ($('#product_type').val() === 'multiple') {
            generateVariantMatrix();
        }
    });
</script>
@endpush