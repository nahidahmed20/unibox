@extends('frontend.layouts.app')
@section('title', $product->name . ' | Unibox')

@section('content')
@push('css')
<style>
    .product-slider-wrap { display: flex; gap: 20px; align-items: flex-start; }
    .product-gallary-thumb { width: 90px !important; flex-shrink: 0; height: 450px; }
    .thumb-item { border-radius: 8px; overflow: hidden; border: 2px solid transparent; cursor: pointer; transition: all 0.3s ease; background: #fff; padding: 2px; }
    .swiper-slide-thumb-active .thumb-item { border-color: #008a7a; box-shadow: 0 4px 10px rgba(0, 138, 122, 0.2); }
    .thumb-item img { width: 100%; height: 100%; aspect-ratio: 1/1; object-fit: cover; border-radius: 6px; }
    .product-gallary { position: relative; flex: 1; border: 1px solid #f0f0f0; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    .gallary-item img { width: 100%; height: auto; aspect-ratio: 1/1; object-fit: cover; transition: transform 0.5s ease; }
    .gallary-item:hover img { transform: scale(1.03); }
    .product-gallary .swiper-nav-next, .product-gallary .swiper-nav-prev { position: absolute; top: 50%; transform: translateY(-50%); z-index: 10; cursor: pointer; width: 45px; height: 45px; background: rgba(255, 255, 255, 0.95); display: flex; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); transition: all 0.3s; }
    .product-gallary .swiper-nav-next:hover, .product-gallary .swiper-nav-prev:hover { background: #008a7a; color: #fff; transform: translateY(-50%) scale(1.1); }
    .product-gallary .swiper-nav-next { right: 15px; } .product-gallary .swiper-nav-prev { left: 15px; }

    .size-item { min-width: 55px; height: 44px; padding: 0 15px; display: inline-flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #008a7a; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px; transition: all 0.3s; white-space: nowrap; position: relative; }
    .size-item:hover:not(.disabled) { background: #008a7a; color: #fff; }
    .size-item.active { background: #008a7a; color: #fff; box-shadow: 0 4px 8px rgba(0, 138, 122, 0.3); }
    .size-item.disabled { border-color: #e2e8f0; color: #a0aec0; background: #f7fafc; cursor: not-allowed; padding-bottom: 10px; }
    .size-item.disabled::after { content: 'Out'; font-size: 9px; color: #ef4444; font-weight: 700; position: absolute; bottom: 2px; }

    .color-box { width: 36px; height: 36px; display: inline-block; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px #cbd5e0; cursor: pointer; transition: all 0.3s; }
    .color-box:hover { transform: scale(1.1); }
    .color-box.active { box-shadow: 0 0 0 2px #008a7a; transform: scale(1.15); }
    .color-box.stock-out { opacity: 0.4; cursor: not-allowed; }
    
    .stock-out-overlay { display: none; position: absolute; top: 20px; left: 20px; background: #ef4444; color: #fff; padding: 6px 14px; border-radius: 6px; font-weight: 700; letter-spacing: 0.5px; z-index: 20; }
    .stock-out-overlay.show { display: block; }

    .qty-box { width: 130px; height: 48px; background: #008a7a; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; overflow: hidden; }
    .qty-btn { width: 40px; height: 50px; border: none; background: transparent; color: #fff; font-size: 16px; cursor: pointer; transition: .3s; }
    .qty-btn:hover { background: rgba(255, 255, 255, .15); }
    .qty-box input { width: 50px; border: none; background: transparent; color: #fff; font-size: 18px; font-weight: 700; text-align: center; outline: none; }
    .qty-box input::-webkit-inner-spin-button, .qty-box input::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }

    .cart-btn { flex: 1; height: 50px; border: 2px solid #222; border-radius: 50px; background: #fff; color: #222; font-weight: 700; font-size: 16px; transition: .3s; }
    .cart-btn:hover { background: #008a7a; border-color: #008a7a; color: #fff; }
    .product-extra-note { background: #fff8e6; border: 1px solid #ffe4a1; color: #8a6d1a; padding: 10px 14px; border-radius: 8px; font-size: 16px; line-height: 1.5; }

    /* Specification Summary Custom Grid */
    .specs-grid-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px 20px;
        font-size: 14px;
    }
    @media (max-width: 575px) {
        .specs-grid-summary { grid-template-columns: 1fr; }
    }

    .product-rich-description { width: 100%; max-width: 100%; overflow-x: hidden; color: #374151; line-height: 1.8; font-size: 16px; word-wrap: break-word !important; overflow-wrap: break-word !important; }
    .product-rich-description * { max-width: 100% !important; box-sizing: border-box !important; }
    .product-rich-description p, .product-rich-description span, .product-rich-description div, .product-rich-description font, .product-rich-description b, .product-rich-description strong { white-space: normal !important; width: auto !important; }
    .product-rich-description img, .product-rich-description video, .product-rich-description iframe, .product-rich-description figure { max-width: 100% !important; width: auto !important; height: auto !important; object-fit: contain; border-radius: 8px; margin: 15px 0; }
    .product-rich-description iframe { width: 100% !important; aspect-ratio: 16 / 9; }
    .product-rich-description table { width: 100% !important; max-width: 100% !important; display: block !important; overflow-x: auto !important; -webkit-overflow-scrolling: touch; border-collapse: collapse; margin: 20px 0; background: #fff; }
    .product-rich-description table th, .product-rich-description table td { padding: 10px 12px; border: 1px solid #e5e7eb; min-width: 120px !important; white-space: normal !important; }

    .size-guide-wrapper { position: sticky; top: 20px; text-align: center; }
    .size-guide-wrapper img { max-width: 100%; height: auto; border-radius: 12px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #f3f4f6; }
    /* Custom Selectable Attributes (Screenshot_1 style) */
    .product-attributes-calculator { margin-top: 25px; margin-bottom: 25px; }
    .attributes-heading { font-size: 18px; font-weight: 700; color: #111; margin-bottom: 20px; }
    .attribute-step-wrapper { margin-bottom: 20px; }
    .attribute-step-title { font-weight: 600; font-size: 15px; margin-bottom: 12px; color: #000; }
    .attribute-options-container { display: flex; flex-wrap: wrap; gap: 10px; }
    
    .attribute-option-label {
        cursor: pointer;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        background: #fff;
        color: #333;
        transition: all 0.2s ease-in-out;
        user-select: none;
        margin-bottom: 0;
    }
    
    .attribute-option-label:hover { 
        border-color: #008a7a; 
    }
    
    /* When Radio button is checked, apply style to the label */
    .attribute-option-input:checked + .attribute-option-label {
        border-color: #008a7a;
        color: #008a7a;
        box-shadow: 0 0 0 1px #008a7a;
    }
    
    /* Hide the actual radio button */
    .attribute-option-input { 
        display: none; 
    }

    @media (max-width: 767px) {
        .product-slider-wrap { flex-direction: column; gap: 15px; }
        .product-gallary { width: 100% !important; }
        .product-gallary-thumb { width: 100% !important; height: auto !important; }
        .product-gallary-thumb .swiper-wrapper { flex-direction: row !important; }
        .thumb-item { width: 70px; height: 70px; }
        .qty-box input { width: 100% !important; text-align: center; }
        .product-btn { flex-wrap: wrap; gap: 10px !important; }
        .qty-box, .cart-btn-wrap-2, #addToCartBtn, #buyNowBtn { width: 100% !important; display: block; }
        .product-rich-description [style*="display: grid"], .product-rich-description [style*="grid-template-columns"], .product-rich-description [style*="display: flex"], .product-rich-description [style*="float"] { display: block !important; grid-template-columns: 1fr !important; width: 100% !important; float: none !important; margin-bottom: 20px !important; }
        .product-rich-description img, .product-rich-description figure { display: block !important; width: 100% !important; max-width: 100% !important; height: auto !important; margin: 20px auto !important; float: none !important; object-fit: contain; }
        .product-rich-description p, .product-rich-description h1, .product-rich-description h2, .product-rich-description h3, .product-rich-description h4 { word-break: break-word !important; text-align: left !important; }
    }
</style>
@endpush

@php
    $totalStock = $product->stock;
@endphp

<section class="shop-section single pt-100 pb-100">
    <div class="container">
        <div class="row g-4">

            <!-- Product Gallery Image Section -->
            <div class="col-lg-6 col-md-12 product-details-wrap">
                <div class="product-slider-wrap">
                    <div class="swiper product-gallary-thumb">
                        <div class="swiper-wrapper" id="thumbWrapper">
                            @if($product->image)
                            <div class="swiper-slide"><div class="thumb-item"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></div></div>
                            @endif
                            @foreach($product->images ?? [] as $img)
                            <div class="swiper-slide"><div class="thumb-item"><img src="{{ asset($img->image) }}" alt="{{ $product->name }}"></div></div>
                            @endforeach
                        </div>
                    </div>

                    <div class="swiper product-gallary">
                        <div class="swiper-wrapper" id="mainGalleryWrapper">
                            @if($product->image)
                            <div class="swiper-slide"><div class="gallary-item"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></div></div>
                            @endif
                            @foreach($product->images ?? [] as $img)
                            <div class="swiper-slide"><div class="gallary-item"><img src="{{ asset($img->image) }}" alt="{{ $product->name }}"></div></div>
                            @endforeach
                        </div>
                        <div class="swiper-nav-next"><i class="fa-solid fa-chevron-right"></i></div>
                        <div class="swiper-nav-prev"><i class="fa-solid fa-chevron-left"></i></div>
                        <div class="stock-out-overlay" id="stockOutOverlay">Stock Out</div>
                    </div>
                </div>
            </div>

            <!-- Product Details Section -->
            <div class="col-lg-6 col-md-12">
                <div class="product-details">
                    <div class="product-info">
                        <div class="product-inner">

                            <span class="category">{{ $product->brand->name ?? '' }}</span>
                            <h3 class="title">{{ $product->name }}</h3>
                            
                            <h4 class="price">
                                ৳{{ $product->selling_price }}
                                @if($product->max_price)
                                    - ৳{{ $product->max_price }}
                                @endif
                                
                                @if($product->main_price)
                                    <span style="text-decoration: line-through; color: #a0aec0; font-size: 16px; margin-left: 10px;">
                                        ৳{{ $product->main_price }}
                                    </span>
                                @endif
                            </h4>

                            @if($product->short_description)
                            <div class="product-desc-wrap">
                                <p class="desc">{{ $product->short_description ?? '' }}</p>
                            </div>
                            @endif

                            @if($product->product_type !== 'single' && $product->sizes->count())
                            <div class="product-size mt-3">
                                <strong>Size:</strong>
                                <div class="d-flex gap-2 mt-2 flex-wrap">
                                    @foreach($product->sizes as $size)
                                        @php
                                            $targetSizeId = $size->size_id ?? $size->id;
                                            $stock = $product->variants->where('size_id', $targetSizeId)->sum('stock');
                                            $sizeName = $size->size->name ?? $size->name ?? 'N/A';
                                        @endphp
                                        <span class="size-item {{ $stock <= 0 ? 'disabled' : '' }}"
                                            data-size-id="{{ $targetSizeId }}"
                                            data-size="{{ $sizeName }}"
                                            data-stock="{{ $stock }}">
                                            {{ $sizeName }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <input type="hidden" id="selectedSizeId">
                            <input type="hidden" id="selectedSize">

                            @if($product->product_type !== 'single' && $product->colors->count())
                            <div class="product-color mt-4">
                                <strong>Color: <span id="selectedColorName" class="text-muted fw-normal ms-1"></span></strong>
                                <div class="d-flex gap-2 mt-2 flex-wrap">
                                    @foreach($product->colors as $color)
                                        @php
                                            $targetColorId = $color->color_id; 
                                            $colorStock = $product->variants->where('color_id', $targetColorId)->sum('stock');
                                            $colorImages = $color->images ?? collect();
                                        @endphp
                                        <span class="color-box {{ $colorStock <= 0 ? 'stock-out' : '' }}"
                                            style="background: {{ $color->color->code ?? '#ccc' }}"
                                            data-color-id="{{ $targetColorId }}" 
                                            data-color-name="{{ $color->color->name ?? '' }}"
                                            data-color-stock="{{ $colorStock }}"
                                            data-color-images='@json($colorImages->pluck("image")->filter()->values())'
                                            title="{{ $color->color->name ?? '' }} {{ $colorStock <= 0 ? '(Stock Out)' : '' }}"
                                        ></span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <input type="hidden" id="selectedColorId">
                        </div>
                        
                        <!-- 🟢 Selectable Attributes Box 🟢 -->
                        @if($product->attributes && $product->attributes->count() > 0)
                        <div class="product-attributes-calculator">
                            <h4 class="attributes-heading">Choose your specifications to calculate the total cost.</h4>
                            
                            @foreach($product->attributes as $index => $attr)
                            <div class="attribute-step-wrapper">
                                <div class="attribute-step-title">
                                    Step {{ $index + 1 }}: Select {{ $attr->name }}
                                </div>
                                <div class="attribute-options-container">
                                    @foreach($attr->options as $optIndex => $opt)
                                        <!-- Hidden Radio Input -->
                                        <input type="radio" 
                                            name="custom_attributes[{{ $attr->id }}]" 
                                            id="attr_{{ $attr->id }}_opt_{{ $opt->id }}" 
                                            value="{{ $opt->value }}" 
                                            class="attribute-option-input"
                                            {{ $optIndex == 0 ? 'checked' : '' }}> <!-- by default first option selected -->
                                            
                                        <!-- Styled Label acting as Button -->
                                        <label for="attr_{{ $attr->id }}_opt_{{ $opt->id }}" class="attribute-option-label">
                                            {{ $opt->value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <!-- ============================================== -->

                        <!-- 🟢 Calculator Size Dimension (Conditional) 🟢 -->
                        @if($product->is_calculator == 1)
                        <div class="product-attributes-calculator mt-4">
                            <h4 class="attributes-heading">Step Select Size Dimension (Width x Height)</h4>
                            <div class="row g-4 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label text-muted" style="font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">WIDTH (FEET & INCHES)</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="number" id="width_ft" class="form-control text-center dim-input" value="0" min="0"> <span class="fw-bold text-muted">ft</span>
                                        <input type="number" id="width_in" class="form-control text-center dim-input" value="0" min="0" max="11"> <span class="fw-bold text-muted">in</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted" style="font-size: 12px; font-weight: 700; letter-spacing: 0.5px;">HEIGHT (FEET & INCHES)</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="number" id="height_ft" class="form-control text-center dim-input" value="0" min="0"> <span class="fw-bold text-muted">ft</span>
                                        <input type="number" id="height_in" class="form-control text-center dim-input" value="0" min="0" max="11"> <span class="fw-bold text-muted">in</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 p-3 bg-light border rounded text-dark" style="font-size: 16px; font-weight: 600;">
                                = <span id="total_sqft">0.00</span> sq. ft
                            </div>
                            <input type="hidden" id="calc_sqft" value="0">
                        </div>
                        @endif

                        <div class="product-btn mt-4 d-flex gap-2 align-items-center">
                            <div class="qty-box">
                                <button class="qty-btn qty-minus">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" id="qty" value="1" min="1">
                                <button class="qty-btn qty-plus">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <div class="cart-btn-wrap-2">
                                <button type="button" class="rr-primary-btn cart-btn" id="addToCartBtn" data-id="{{ $product->id }}" data-total-stock="{{ $totalStock }}">
                                    {{ $totalStock <= 0 ? 'Stock Out' : 'Add To Cart' }}
                                </button>
                            </div>
                        </div>
                        <a href="#" class="shop-details-btn rr-primary-btn mt-2 d-inline-block text-center" id="buyNowBtn" data-id="{{ $product->id }}">
                            Buy Now
                        </a>

                        @if($product->extra_note)
                        <div class="product-extra-note mt-3">
                            <i class="fa-solid fa-circle-info me-1"></i> {{ $product->extra_note }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Description & Reviews Tabs -->
<section class="product-description pb-100">
    <div class="container">
        <ul class="nav tab-navigation" id="product-tab-navigation" role="tablist">
            <li role="presentation">
                <button class="active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Description</button>
            </li>
            
            <!-- 🟢 Specifications Tab Button 🟢 -->
            @if($product->attributes && $product->attributes->count() > 0)
            <li role="presentation">
                <button id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" type="button" role="tab" aria-controls="specs" aria-selected="false">Specifications</button>
            </li>
            @endif

            <li role="presentation">
                <button id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Reviews ({{ $product->reviews->count() }})</button>
            </li>
        </ul>

        <div class="tab-content" id="product-tab-content">
            
            <!-- Description Tab -->
            <div class="tab-pane fade show active description" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row gy-4 align-items-start mt-2"> 
                    <div class="col-12 {{ $product->size_guide ? 'col-lg-8 order-2 order-lg-1' : '' }}">
                        <div class="product-rich-description">
                            {!! $product->description !!}
                        </div>
                    </div>
                    @if($product->size_guide)
                    <div class="col-12 col-lg-4 order-1 order-lg-2">
                        <div class="size-guide-wrapper">
                            <img src="{{ asset($product->size_guide) }}" alt="Size Guide for {{ $product->name }}" class="img-fluid">
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 🟢 Specifications Tab Content 🟢 -->
            @if($product->attributes && $product->attributes->count() > 0)
            <div class="tab-pane fade description" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <h4 class="mb-4">Product Specifications & Attributes</h4>
                        <div class="table-responsive border rounded-3">
                            <table class="table table-striped table-hover mb-0">
                                <tbody>
                                    @foreach($product->attributes as $attr)
                                    <tr>
                                        <th width="35%" class="text-muted fw-semibold py-3 px-4">{{ $attr->name }}</th>
                                        <td class="text-dark fw-medium py-3 px-4">
                                            @if($attr->options && $attr->options->count() > 0)
                                                @foreach($attr->options as $opt)
                                                    <span class="badge bg-light text-dark border px-2 py-1 me-1">{{ $opt->value }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Reviews Tab -->
            <div class="tab-pane fade review" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="row product-review gy-lg-0 gy-4">
                    <div class="col-lg-5 col-md-12">
                        <div class="reviewr-wrap">
                            <div class="review-list">
                                @forelse($product->reviews as $review)
                                <div class="review-item">
                                    <div class="review-thumb">
                                        @if($review->user && $review->user->image)
                                            <img src="{{ asset($review->user->image) }}" alt="{{ $review->name }}">
                                        @else
                                            <img src="{{ asset('frontend/assets/img/shop/default-user.png') }}" alt="user">
                                        @endif
                                    </div>
                                    <div class="content">
                                        <div class="content-top">
                                            <h4 class="name">{{ $review->user_name }} <span>{{ $review->created_at->format('d M, Y') }}</span></h4>
                                            <ul class="review">
                                                @for($i=1; $i<=5; $i++)
                                                    <li><i class="fa-sharp fa-solid fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i></li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                                @empty
                                <p>No reviews yet for this product.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-md-12">
                        <div class="review-form-wrap">
                            <h4 class="title">Review this product</h4>
                            <span class="publish">Your email address will not be published. Required fields are marked *</span>
                            
                            <div class="blog-contact-form form-2 review-form">
                                <div class="request-form">
                                    @auth('customer')
                                        <form action="{{ route('product.review.store', $product->id) }}" method="post" class="form-horizontal">
                                            @csrf
                                            <div class="review-box pb-2">
                                                <span>Your ratings :</span>
                                                <input type="hidden" name="rating" id="rating-value" value="5">
                                                
                                                <ul class="review" id="star-rating" style="cursor: pointer; list-style: none; padding: 0; display: flex;">
                                                    <li data-value="1"><i class="fa-solid fa-star" style="color: #ffc107;"></i></li>
                                                    <li data-value="2"><i class="fa-solid fa-star" style="color: #ffc107;"></i></li>
                                                    <li data-value="3"><i class="fa-solid fa-star" style="color: #ffc107;"></i></li>
                                                    <li data-value="4"><i class="fa-solid fa-star" style="color: #ffc107;"></i></li>
                                                    <li data-value="5"><i class="fa-solid fa-star" style="color: #ffc107;"></i></li>
                                                </ul>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="form-item">
                                                        <input type="text" name="name" class="form-control" 
                                                            value="{{ auth()->guard('customer')->user()->name }}" 
                                                            placeholder="Your Name" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-item">
                                                        <input type="email" name="email" class="form-control" 
                                                            value="{{ auth()->guard('customer')->user()->email }}" 
                                                            placeholder="Your Email" >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <div class="form-item message-item">
                                                        <textarea name="comment" cols="30" rows="5" class="form-control address" placeholder="Comment" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="submit-btn">
                                                <button class="rr-primary-btn" type="submit">Submit</button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            Please <a href="{{ route('user.login') }}">login</a> to write a review.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('javascript')
    <script>
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            event: "view_item",
            ecommerce: {
                currency: "BDT",
                value: {{ $product->selling_price }},
                items: [
                    {
                        item_id: "{{ $product->id }}",
                        item_name: {!! json_encode($product->name) !!},
                        item_category: {!! json_encode($product->category->name ?? '') !!},
                        item_brand: {!! json_encode($product->brand->name ?? '') !!},
                        price: {{ $product->selling_price }},
                        quantity: 1
                    }
                ]
            }
        });

        // FB ViewContent
        if (typeof fbq === 'function') {
            fbq('track', 'ViewContent', {
                content_name: {!! json_encode($product->name) !!},
                content_category: {!! json_encode($product->category->name ?? '') !!},
                content_ids: ["{{ $product->id }}"],
                content_type: 'product',
                value: {{ $product->selling_price }},
                currency: 'BDT'
            });
        }
    
        $(document).ready(function () {

            const productType = "{{ $product->product_type }}";
            const hasSizeData  = {{ $product->sizes->count() > 0 ? 'true' : 'false' }};
            const hasColorData = {{ $product->colors->count() > 0 ? 'true' : 'false' }};
            const totalStock   = {{ $product->stock }};

            const isCalculator = {{ $product->is_calculator ?? 0 }};
            const pricePerSqft = {{ $product->price_per_sqft ?? 0 }};

            const hasSize  = hasSizeData && productType !== 'single';
            const hasColor = hasColorData && productType !== 'single';

            const defaultImages = @json(
                collect([$product->image])
                    ->merge($product->images->pluck('image'))
                    ->filter()
                    ->values()
            );

            let swiperMain = null;
            let swiperThumbs = null;

            function initSwipers() {
                swiperThumbs = new Swiper(".product-gallary-thumb", {
                    spaceBetween: 10,
                    slidesPerView: 4,
                    freeMode: true,
                    watchSlidesProgress: true,
                    breakpoints: {
                        768: { direction: 'vertical' },
                        0: { direction: 'horizontal' }
                    }
                });

                swiperMain = new Swiper(".product-gallary", {
                    spaceBetween: 10,
                    navigation: {
                        nextEl: ".swiper-nav-next",
                        prevEl: ".swiper-nav-prev",
                    },
                    thumbs: {
                        swiper: swiperThumbs,
                    },
                });
            }
            initSwipers();

            if (totalStock <= 0) {
                $('#addToCartBtn').text('Stock Out').prop('disabled', true).css({background: '#ccc', cursor: 'not-allowed', borderColor: '#ccc'});
                $('#buyNowBtn').text('Stock Out').css({pointerEvents: 'none', opacity: 0.6});
                $('#stockOutOverlay').addClass('show');
            }

            window.updateGallery = function(images) {
                if (!Array.isArray(images) || images.length === 0) images = defaultImages;
                
                let mainHtml = '', thumbHtml = '';
                images.forEach(img => {
                    let src = img.startsWith('http') ? img : '/' + img.replace(/^\//, '');
                    mainHtml += `<div class="swiper-slide"><div class="gallary-item"><img src="${src}"></div></div>`;
                    thumbHtml += `<div class="swiper-slide"><div class="thumb-item"><img src="${src}"></div></div>`;
                });

                if (swiperMain) swiperMain.destroy(true, true);
                if (swiperThumbs) swiperThumbs.destroy(true, true);

                $('#mainGalleryWrapper').html(mainHtml);
                $('#thumbWrapper').html(thumbHtml);

                initSwipers();
            };

            if(isCalculator == 1) {
                $('.dim-input').on('input', function() {
                    let w_ft = parseFloat($('#width_ft').val()) || 0;
                    let w_in = parseFloat($('#width_in').val()) || 0;
                    let h_ft = parseFloat($('#height_ft').val()) || 0;
                    let h_in = parseFloat($('#height_in').val()) || 0;

                    let totalWidthFt = w_ft + (w_in / 12);
                    let totalHeightFt = h_ft + (h_in / 12);
                    let totalSqft = totalWidthFt * totalHeightFt;

                    $('#total_sqft').text(totalSqft.toFixed(2));
                    $('#calc_sqft').val(totalSqft.toFixed(2));

                    let newPrice = totalSqft * pricePerSqft;
                    if(newPrice > 0) {
                        $('.price').html('৳' + newPrice.toFixed(2));
                    }
                });
            }

            $(document).on('click', '.color-box:not(.stock-out)', function () {
                $('.color-box').removeClass('active');
                $(this).addClass('active');
                $('#selectedColorId').val($(this).data('color-id'));
                $('#selectedColorName').text($(this).data('color-name'));
                
                let images = $(this).data('color-images');
                if (typeof images === 'string') images = JSON.parse(images);
                
                updateGallery(images);
            });

            $(document).on('click', '.size-item:not(.disabled)', function () {
                $('.size-item').removeClass('active');
                $(this).addClass('active');
                $('#selectedSizeId').val($(this).data('size-id'));
                $('#selectedSize').val($(this).data('size'));
            });

            function validateSelection() {
                if (productType === 'single') return true;
                if (hasSize && !$('#selectedSizeId').val()) { Swal.fire('Select Size', 'Please select a size', 'warning'); return false; }
                if (hasColor && !$('#selectedColorId').val()) { Swal.fire('Select Color', 'Please select a color', 'warning'); return false; }
                return true;
            }

            $('.qty-plus').click(function() {
                let input = $('#qty');
                input.val(parseInt(input.val()) + 1);
            });

            $('.qty-minus').click(function() {
                let input = $('#qty');
                let val = parseInt(input.val());
                if (val > 1) {
                    input.val(val - 1);
                }
            });

            $('#addToCartBtn').on('click', function () {
                if (!validateSelection()) return;
                let btn = $(this);
                btn.prop('disabled', true).text('Adding...');

                let selectedAttributes = {};
                $('.attribute-option-input:checked').each(function() {
                    let attrId = $(this).attr('name').match(/\d+/)[0]; 
                    selectedAttributes[attrId] = $(this).val();
                });

                let payload = {
                    _token: "{{ csrf_token() }}",
                    product_id: $(this).data('id'),
                    qty: $('#qty').val(),
                    size_id: $('#selectedSizeId').val() || null,
                    color_id: $('#selectedColorId').val() || null,
                    custom_attributes: selectedAttributes
                };

                if (isCalculator == 1) {
                    let sqft = parseFloat($('#calc_sqft').val()) || 0;
                    if(sqft <= 0) {
                        Swal.fire('Dimension Required', 'Please enter valid width and height greater than 0.', 'warning');
                        btn.prop('disabled', false).text('Add To Cart');
                        return;
                    }
                    payload.dimensions = {
                        w_ft: $('#width_ft').val(), w_in: $('#width_in').val(),
                        h_ft: $('#height_ft').val(), h_in: $('#height_in').val(),
                        sqft: sqft
                    };
                }

                $.post("{{ url('/cart/add') }}", payload, function (res) {
                    if (res.success) {
                        if (res.html) {
                            $('#cart-section').html(res.html);
                        }
                        if (res.cart_count !== undefined) {
                            $('.cart-item-count-render').text(res.cart_count);
                            $('#cart-count').text(res.cart_count); 
                        }

                        if (res.cart_total) {
                            $('.total-value').text('৳' + res.cart_total);
                        }
                        $('#cart-overlay, #cart-drawer').addClass('active');
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Added to Cart', 
                            timer: 1200, 
                            showConfirmButton: false 
                        });
                    }
                }).always(function() {
                    btn.prop('disabled', false).text('Add To Cart');
                });
            });

            $('#buyNowBtn').on('click', function (e) {
                e.preventDefault();
                if (!validateSelection()) return;
                
                let selectedAttributes = {};
                $('.attribute-option-input:checked').each(function() {
                    let attrId = $(this).attr('name').match(/\d+/)[0]; 
                    selectedAttributes[attrId] = $(this).val();
                });

                let payload = {
                    _token: "{{ csrf_token() }}",
                    product_id: $(this).data('id'),
                    qty: $('#qty').val(),
                    size_id: $('#selectedSizeId').val() || null,
                    color_id: $('#selectedColorId').val() || null,
                    custom_attributes: selectedAttributes
                };

                if (isCalculator == 1) {
                    let sqft = parseFloat($('#calc_sqft').val()) || 0;
                    if(sqft <= 0) {
                        Swal.fire('Dimension Required', 'Please enter valid width and height greater than 0.', 'warning');
                        return;
                    }
                    payload.dimensions = {
                        w_ft: $('#width_ft').val(), w_in: $('#width_in').val(),
                        h_ft: $('#height_ft').val(), h_in: $('#height_in').val(),
                        sqft: sqft
                    };
                }

                $.post("{{ url('/cart/add') }}", payload, function (res) {
                    if (res.success) window.location.href = "{{ url('/cart') }}";
                });
            });
        });
   
        $(document).ready(function() {
            // Star Rating Logic
            $('#star-rating li').on('click', function() {
                let rating = $(this).data('value');
                $('#rating-value').val(rating);
                $('#star-rating li i').css('color', '#ccc');
                $('#star-rating li').each(function(index) {
                    if (index < rating) {
                        $(this).find('i').css('color', '#ffc107');
                    }
                });
            });

            $('#star-rating li').hover(
                function() {
                    let rating = $(this).data('value');
                    $('#star-rating li i').css('color', '#ccc');
                    $('#star-rating li').each(function(index) {
                        if (index < rating) {
                            $(this).find('i').css('color', '#ffc107');
                        }
                    });
                },
                function() {
                    let selected = $('#rating-value').val();
                    $('#star-rating li i').css('color', '#ccc');
                    $('#star-rating li').each(function(index) {
                        if (index < selected) {
                            $(this).find('i').css('color', '#ffc107');
                        }
                    });
                }
            );

            $('.product-rich-description').find('*').each(function() {
                var $this = $(this);
                $this.removeAttr('width').removeAttr('height');
                $this.css({
                    'width': '',
                    'min-width': '',
                    'max-width': '100%'
                });
            });
            $('.product-rich-description table').each(function() {
                if (!$(this).parent().hasClass('table-responsive')) {
                    $(this).wrap('<div class="table-responsive" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; border: 1px solid #e5e7eb; margin-bottom: 15px;"></div>');
                }
            });
        });
    </script>
@endpush