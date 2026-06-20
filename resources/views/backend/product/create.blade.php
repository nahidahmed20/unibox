@extends('backend.layouts.app')

@section('title', 'Create Product')

@section('content')
@push('styles')
<style>
    /* Modern E-commerce Dashboard UI */
    .app-content {
        background-color: #f4f6f8; /* Soft background */
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
                            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="e.g. Premium Cotton T-Shirt">
                            @error('name')<small class="text-danger mt-1 d-block">{{ $message }}</small>@enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Product Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="auto-generated-slug" readonly>
                                @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="1" placeholder="Brief summary..."></textarea>
                                @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control summernote"></textarea>
                            @error('description')<small class="text-danger">{{ $message }}</small>@enderror
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
                                <small class="text-muted d-block mt-2"><i class="fa-solid fa-circle-info me-1"></i> You can select multiple images by holding CTRL.</small>
                                @error('images')<small class="text-danger">{{ $message }}</small>@enderror
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
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Product Type</label>
                                <select name="product_type" id="product_type" class="form-select">
                                    <option value="single" selected>Single Product</option>
                                    <option value="multiple">Variable Product (Sizes, Colors)</option>
                                </select>
                            </div>

                            <div class="col-md-12 product-options" style="display: none;">
                                <hr class="text-muted mb-4">
                                
                                <div class="row">
                                    <div class="col-md-5 mb-4">
                                        <label class="form-label">Variation Group (e.g., Sizes)</label>
                                        <select id="variation_select" class="form-select" name="variation_id">
                                            <option value="">Select Variation Base</option>
                                            @foreach($variations as $variation)
                                                <option value="{{ $variation->id }}" data-values='@json($variation->values)'>
                                                    {{ $variation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-7 mb-4" id="variationSizesWrapper" style="display: none;">
                                        <label class="form-label">Available Options</label>
                                        <div id="variationSizes" class="size-checkbox-wrapper"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label d-flex justify-content-between align-items-center mb-3">
                                        <span>Color Variants & Specific Images</span>
                                    </label>
                                    <div id="colorImageWrapper">
                                        <div class="color-image-group d-flex align-items-center gap-3 mb-3">
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
                        <div class="mb-3">
                            <label class="form-label">Selling Price (৳)</label>
                            <input type="number" step="0.01" class="form-control fs-5 fw-bold text-success" name="selling_price" id="selling_price" placeholder="0.00">
                            @error('selling_price')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Cost Price</label>
                                <input type="number" step="0.01" class="form-control" name="purchase_price" placeholder="0.00">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control" name="main_price" placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" id="discount_type" class="form-select">
                                <option value="">No Discount</option>
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="mb-3">
                            <label class="form-label">SKU (Stock Keeping Unit)</label>
                            <input type="text" class="form-control font-monospace" id="sku" name="sku" placeholder="Auto-generated" readonly>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" placeholder="Optional">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Alert Qty</label>
                                <input type="number" class="form-control" name="alert_quantity" placeholder="e.g. 5">
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
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<small class="text-danger">{{ $message }}</small>@enderror
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
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                <option value="">Select Unit (Piece, Kg, etc)</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
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
                            <div class="col-6 mb-3">
                                <label class="form-label">Featured</label>
                                <select name="is_featured" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>
                            
                            <div class="col-6 mb-3">
                                <label class="form-label">New Arrival</label>
                                <select name="is_new" class="form-select">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Best Seller</label>
                                <select name="is_bestseller" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Trending</label>
                                <select name="is_trending" class="form-select">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>

                            <div class="col-12 mt-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select bg-success text-white border-0" id="statusSelect">
                                    <option value="1" selected>Active (Published)</option>
                                    <option value="0">Inactive (Draft)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card modern-card bg-transparent shadow-none">
                    <div class="sticky-footer rounded-4 shadow-sm border">
                        <a href="{{ route('dashboard') }}" class="btn btn-light px-4 border rounded-3 fw-bold">Discard</a>
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
        $('.summernote').summernote({ height: 250 });

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
        });

        // Toggle Variable Products
            function toggleProductOptions() {
                if($('#product_type').val() === 'single'){
                    $('.product-options').slideUp(300);
                    $('#variation_select').prop('disabled', true).val('');
                    $('.color-select').prop('disabled', true).val('');
                    $('.size-check-input').prop('disabled', true).prop('checked', false);
                } else {
                    $('.product-options').slideDown(300);
                    $('#variation_select').prop('disabled', false);
                    $('.color-select').prop('disabled', false);
                    $('.size-check-input').prop('disabled', false);
                }
            }
            toggleProductOptions();
            $('#product_type').on('change', toggleProductOptions);

        // Status Select Color Toggle
        $('#statusSelect').on('change', function(){
            if($(this).val() == '1'){
                $(this).removeClass('bg-secondary').addClass('bg-success text-white');
            } else {
                $(this).removeClass('bg-success').addClass('bg-secondary text-white');
            }
        });

        // Image Previews (Main)
        $('input[name="image"]').on('change', function() {
            $('#mainImagePreview').remove();
            let file = this.files[0];
            if(file){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('<img id="mainImagePreview" class="img-thumbnail mt-3 shadow-sm" style="max-height:120px; border-radius:10px;">')
                        .attr('src', e.target.result)
                        .insertAfter('input[name="image"]');
                };
                reader.readAsDataURL(file);
            }
        });

        // Image Previews (Gallery)
        $('input[name="images[]"]').on('change', function() {
            $('#imagesPreview').remove();
            let wrapper = $('<div id="imagesPreview" class="d-flex flex-wrap gap-2 mt-3"></div>');
            Array.from(this.files).forEach(file => {
                let reader = new FileReader();
                reader.onload = function(e){
                    $('<img class="img-thumbnail shadow-sm" style="height:80px; width:80px; object-fit:cover; border-radius:10px;">').attr('src', e.target.result).appendTo(wrapper);
                };
                reader.readAsDataURL(file);
            });
            $(this).after(wrapper);
        });

        // Unique Color Selection Logic
        let selectedColors = [];
        $(document).on('change', '.color-select', function () {
            let value = $(this).val();
            let prevValue = $(this).data('prev');
            
            if (prevValue) {
                selectedColors = selectedColors.filter(c => c !== prevValue);
            }
            if (value !== '') {
                if (selectedColors.includes(value)) {
                    alert('This color is already selected!');
                    $(this).val('');
                    return;
                }
                selectedColors.push(value);
                $(this).data('prev', value);
            }
        });

        // Add New Color Row
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
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
            $('#colorImageWrapper').append(html);
        });

        // Remove Color Row
        $(document).on('click', '.removeColorImage', function () {
            let group = $(this).closest('.color-image-group');
            let select = group.find('.color-select');
            let value = select.val();
            if (value && selectedColors.includes(value)) {
                selectedColors = selectedColors.filter(c => c !== value);
            }
            group.slideUp(300, function(){ $(this).remove(); });
        });

        // Fetch Subcategories via AJAX
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
                    },
                    error: function() {
                        subDropdown.empty().append('<option value="">Select Subcategory</option>');
                    }
                });
            } else {
                subDropdown.empty().append('<option value="">Select Subcategory</option>');
            }
        });

        // Generate Variation Sizes (Pill Checkboxes)
        $(document).on('change', '#variation_select', function () {
            let option = $(this).find(':selected');
            let values = option.attr('data-values');
            let wrapper = $('#variationSizes');
            let mainWrapper = $('#variationSizesWrapper');
            
            wrapper.empty();

            if (!values || values === "") {
                mainWrapper.hide();
                return;
            }

            try {
                values = JSON.parse(values);
            } catch (e) {
                values = [];
            }

            if (Array.isArray(values) && values.length > 0) {
                mainWrapper.fadeIn();
                let html = ``;
                values.forEach(function (val, index) {
                    html += `
                        <div>
                            <input class="size-check-input" type="checkbox" name="variation_sizes[]" value="${val}" id="size_${index}">
                            <label class="size-check-label" for="size_${index}">${val}</label>
                        </div>
                    `;
                });
                wrapper.html(html);
            } else {
                mainWrapper.hide();
            }
        });

        // Auto Hide Error Alerts
        setTimeout(function () {
            $('#errorAlert').fadeOut('slow', function(){ $(this).remove(); });
        }, 5000); 
    });
</script>
@endpush