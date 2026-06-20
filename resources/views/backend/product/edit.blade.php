@extends('backend.layouts.app')

@section('title', 'Edit Product')

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

    /* Pill Checkboxes for Sizes */
    .size-checkbox-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .size-check-input { display: none; }

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
            <div class="alert alert-danger shadow-sm border-0 rounded-3 mb-4" id="errorAlert">
                <strong class="mb-2 d-block"><i class="fa-solid fa-triangle-exclamation me-2"></i>Please review the errors below:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="row">
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
                            <input type="text" class="form-control form-control-lg" id="name" name="name" value="{{ $product->name }}">
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Product Slug</label>
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ $product->slug }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Short Description</label>
                                <textarea name="short_description" class="form-control" rows="1">{{ $product->short_description }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="form-label">Full Description</label>
                            <textarea name="description" class="form-control summernote">{{ $product->description }}</textarea>
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
                                @if ($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($product->image) }}" class="rounded-3 shadow-sm" height="80" style="object-fit: cover;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="image">
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Size Guide Image</label>
                                @if($product->size_guide)
                                    <div class="mb-2">
                                        <a href="{{ asset($product->size_guide) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fa fa-eye me-1"></i> View Current Size Guide</a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="size_guide">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Product Gallery</label>
                                @if(count($product->images) > 0)
                                    <div class="d-flex gap-2 flex-wrap mb-2">
                                        @foreach($product->images as $img)
                                            <img src="{{ asset($img->image) }}" class="rounded-3 shadow-sm border" height="60" width="60" style="object-fit: cover;">
                                        @endforeach
                                    </div>
                                @endif
                                <input type="file" class="form-control" name="images[]" multiple>
                                <small class="text-muted d-block mt-2"><i class="fa-solid fa-circle-info me-1"></i> Add new images to gallery</small>
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
                                    <option value="single" {{ $product->product_type=='single'?'selected':'' }}>Single Product</option>
                                    <option value="multiple" {{ $product->product_type=='multiple'?'selected':'' }}>Variable Product (Sizes, Colors)</option>
                                </select>
                            </div>

                            <div class="col-md-12 product-options" style="display: none;">
                                <hr class="text-muted mb-4">
                                
                                <div class="row">
                                    <div class="col-md-5 mb-4">
                                        <label class="form-label">Variation Group</label>
                                        <select id="variation_select" class="form-select" name="variation_id">
                                            <option value="">Select Variation</option>
                                            @foreach($variations as $variation)
                                                <option value="{{ $variation->id }}" 
                                                    data-values='@json($variation->values)'
                                                    {{ $product->variation_id==$variation->id?'selected':'' }}>
                                                    {{ $variation->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-7 mb-4" id="variationSizesWrapper">
                                        <label class="form-label">Available Options</label>
                                        <div id="variationSizes" class="size-checkbox-wrapper"></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label d-flex justify-content-between align-items-center mb-3">
                                        <span>Color Variants & Specific Images</span>
                                    </label>
                                    <button type="button" class="btn btn-dark addColorImage text-nowrap rounded-3 mb-2">
                                        <i class="fa fa-plus me-1"></i> Add 
                                    </button>
                                    <div id="colorImageWrapper">
                                        
                                        @foreach ($product->colors as $i => $color)
                                            <div class="color-image-group d-flex flex-column gap-2 mb-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <select name="color_image_ids[{{ $i }}]" class="form-select color-select" style="max-width:180px;">
                                                        <option value="">Select Color</option>
                                                        @foreach ($colors as $c)
                                                            <option value="{{ $c->id }}" {{ $color->color_id == $c->id ? 'selected' : '' }}>
                                                                {{ $c->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="file" name="color_images[{{ $i }}][]" class="form-control" multiple>
                                                    <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3" data-id="{{ $color->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    <input type="hidden" name="existing_color_ids[]" value="{{ $color->id }}">
                                                </div>
                                                
                                                @if(count($color->images) > 0)
                                                    <div class="d-flex gap-2 flex-wrap mt-1">
                                                        @foreach ($color->images as $img)
                                                            <img src="{{ asset($img->image) }}" class="rounded-2 shadow-sm border" height="40" width="40" style="object-fit: cover;">
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach

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
                            <input type="number" step="0.01" class="form-control fs-5 fw-bold text-success" name="selling_price" id="selling_price" value="{{ $product->selling_price }}">
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Cost Price</label>
                                <input type="number" step="0.01" class="form-control" name="purchase_price" value="{{ $product->purchase_price }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Discount Price</label>
                                <input type="number" step="0.01" class="form-control" name="main_price" value="{{ $product->main_price }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select">
                                <option value="">No Discount</option>
                                <option value="percent" {{ $product->discount_type=='percent'?'selected':'' }}>Percentage (%)</option>
                                <option value="fixed" {{ $product->discount_type=='fixed'?'selected':'' }}>Fixed Amount</option>
                            </select>
                        </div>

                        <hr class="text-muted my-4">

                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control font-monospace" id="sku" name="sku" value="{{ $product->sku }}" readonly>
                        </div>
                        
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" class="form-control" name="barcode" value="{{ $product->barcode }}">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Alert Qty</label>
                                <input type="number" class="form-control" name="alert_quantity" value="{{ $product->alert_quantity }}">
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
                            <select name="category_id" class="form-select">
                                <option value="">Search Category...</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subcategory</label>
                            <select name="subcategory_id" class="form-select">
                                <option value="">Select Subcategory</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}" {{ $product->subcategory_id == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Unit</label>
                            <select name="unit_id" class="form-select">
                                <option value="">Select Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
                                    </option>
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
                                    <option value="1" {{ $product->is_featured==1?'selected':'' }}>Yes</option>
                                    <option value="0" {{ $product->is_featured==0?'selected':'' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">New Arrival</label>
                                <select name="is_new" class="form-select">
                                    <option value="1" {{ $product->is_new==1?'selected':'' }}>Yes</option>
                                    <option value="0" {{ $product->is_new==0?'selected':'' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Best Seller</label>
                                <select name="is_bestseller" class="form-select">
                                    <option value="1" {{ $product->is_bestseller==1?'selected':'' }}>Yes</option>
                                    <option value="0" {{ $product->is_bestseller==0?'selected':'' }}>No</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Trending</label>
                                <select name="is_trending" class="form-select">
                                    <option value="1" {{ $product->is_trending==1?'selected':'' }}>Yes</option>
                                    <option value="0" {{ $product->is_trending==0?'selected':'' }}>No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select {{ $product->status == 1 ? 'bg-success text-white border-0' : 'bg-secondary text-white border-0' }}" id="statusSelect">
                                    <option value="1" {{ $product->status==1?'selected':'' }}>Active (Published)</option>
                                    <option value="0" {{ $product->status==0?'selected':'' }}>Inactive (Draft)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card modern-card bg-transparent shadow-none">
                    <div class="sticky-footer rounded-4 shadow-sm border">
                        <a href="{{ route('dashboard') }}" class="btn btn-light px-4 border rounded-3 fw-bold">Discard</a>
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
$(document).ready(function () {

    /* ================= SUMMERNOTE ================= */
    $('.summernote').summernote({ height: 250 });

    /* ================= SLUG + SKU ================= */
    $('#name').on('keyup', function () {
        let name = $(this).val().trim();
        $('#slug').val(name.toLowerCase().replace(/[^a-z0-9]+/g, '-'));

        if (name.length > 0) {
            let code = Math.random().toString(36).substring(2, 8).toUpperCase();
            $('#sku').val(name.substring(0, 3).toUpperCase() + '-' + code);
        } else {
            $('#sku').val('');
        }
    });

    /* ================= PRODUCT TYPE ================= */
    function toggleProductOptions() {
        if ($('#product_type').val() === 'single') {
            $('.product-options').slideUp(300);
        } else {
            $('.product-options').slideDown(300);
        }
    }
    toggleProductOptions();
    $('#product_type').on('change', toggleProductOptions);

    /* Status Select Color Toggle */
    $('#statusSelect').on('change', function(){
        if($(this).val() == '1'){
            $(this).removeClass('bg-secondary').addClass('bg-success text-white');
        } else {
            $(this).removeClass('bg-success').addClass('bg-secondary text-white');
        }
    });

    /* ================= IMAGE PREVIEWS ================= */
    $('input[name="image"]').on('change', function () {
        $('#mainImagePreviewNew').remove();
        let file = this.files[0];
        if (!file) return;
        let reader = new FileReader();
        reader.onload = function (e) {
            $('<img>', {
                src: e.target.result,
                id: 'mainImagePreviewNew',
                class: 'img-thumbnail mt-2 shadow-sm rounded-3',
                style: 'max-height:80px; object-fit: cover;'
            }).insertAfter('input[name="image"]');
        };
        reader.readAsDataURL(file);
    });

    $('input[name="images[]"]').on('change', function () {
        $('#imagesPreviewNew').remove();
        let wrapper = $('<div id="imagesPreviewNew" class="d-flex flex-wrap gap-2 mt-2"></div>');
        Array.from(this.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('<img>', {
                    src: e.target.result,
                    class: 'img-thumbnail shadow-sm rounded-3',
                    style: 'height:60px; width:60px; object-fit:cover;'
                }).appendTo(wrapper);
            };
            reader.readAsDataURL(file);
        });
        $(this).after(wrapper);
    });

    /* ================= VARIATION SIZES (PILL DESIGN) ================= */
    function loadVariation() {
        let option = $('#variation_select option:selected');
        let values = option.data('values');

        if (typeof values === "string") {
            try { values = JSON.parse(values); } catch (e) { values = []; }
        }

        let selectedSizes = @json($product->sizes->pluck('size'));
        let wrapper = $('#variationSizes');
        let mainWrapper = $('#variationSizesWrapper');

        wrapper.empty();

        if (Array.isArray(values) && values.length > 0) {
            mainWrapper.fadeIn();
            let html = ``;
            values.forEach(function (val, i) {
                let checked = selectedSizes.includes(val) ? 'checked' : '';
                html += `
                    <div>
                        <input class="size-check-input" type="checkbox" name="variation_sizes[]" value="${val}" id="size_${i}" ${checked}>
                        <label class="size-check-label" for="size_${i}">${val}</label>
                    </div>
                `;
            });
            wrapper.html(html);
        } else {
            mainWrapper.hide();
        }
    }

    $(document).on('change', '#variation_select', loadVariation);
    loadVariation();

    /* ================= COLOR SYSTEM ================= */
    let selectedColors = [];

    function refreshColors() {
        selectedColors = [];
        $('.color-select').each(function () {
            let val = $(this).val();
            if (val) selectedColors.push(val);
        });
    }
    refreshColors();

    /* duplicate prevention */
    $(document).on('change', '.color-select', function () {
        let val = $(this).val();
        let oldVal = $(this).data('old') || null;

        if (!val) return;
        if (oldVal) { selectedColors = selectedColors.filter(c => c !== oldVal); }

        if (selectedColors.includes(val)) {
            Swal.fire('Warning', 'Color already selected!', 'warning');
            $(this).val(oldVal || '');
            return;
        }

        selectedColors.push(val);
        $(this).data('old', val);
    });

    /* ================= ADD NEW COLOR ================= */
    $(document).on('click', '.addColorImage', function () {
        let index = $('.color-image-group').length + 99; // Random high index for new appends

        let options = `
            <option value="">Select Color</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->name }}</option>
            @endforeach
        `;

        let html = `
        <div class="color-image-group d-flex flex-column gap-2 mb-3">
            <div class="d-flex align-items-center gap-3">
                <select name="color_image_names[${index}]" class="form-select color-select" style="max-width:180px;">
                    ${options}
                </select>
                <input type="file" name="color_images[${index}][]" class="form-control" multiple>
                <button type="button" class="btn btn-outline-danger removeColorImage text-nowrap rounded-3"><i class="fa fa-trash"></i></button>
            </div>
        </div>
        `;
        $('#colorImageWrapper').append(html);
    });

    /* ================= DELETE COLOR (AJAX) ================= */
    $(document).on('click', '.removeColorImage', function () {
        let group = $(this).closest('.color-image-group');
        let colorId = $(this).data('id');

        Swal.fire({
            title: 'Delete this color?',
            text: 'This will permanently remove the color variant and its images.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (!result.isConfirmed) return;

            if (colorId) {
                $.ajax({
                    url: "/admin/product-color/delete/" + colorId,
                    type: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function (res) {
                        if (res.status === 'success') {
                            group.slideUp(300, function(){ $(this).remove(); refreshColors(); });
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Color variant has been removed.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            } else {
                group.slideUp(300, function(){ $(this).remove(); refreshColors(); });
            }
        });
    });

    // Auto Hide Error Alerts
    setTimeout(function () {
        $('#errorAlert').fadeOut('slow', function(){ $(this).remove(); });
    }, 5000); 

});
</script>
@endpush