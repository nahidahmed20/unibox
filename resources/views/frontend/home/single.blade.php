@extends('frontend.layouts.app')
@section('title', $product->name . ' | Unibox')

@section('content')
@push('css')
<style>
    .product-slider-wrap {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    /* Thumbnail Section */
    .product-gallary-thumb {
        width: 90px !important;
        flex-shrink: 0;
        height: 450px;
    }

    .thumb-item {
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #fff;
        padding: 2px;
    }

    .swiper-slide-thumb-active .thumb-item {
        border-color: #008a7a;
        box-shadow: 0 4px 10px rgba(0, 138, 122, 0.2);
    }

    .thumb-item img {
        width: 100%;
        height: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
        border-radius: 6px;
    }

    /* Main Gallery Section */
    .product-gallary {
        position: relative;
        flex: 1;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .gallary-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .gallary-item:hover img {
        transform: scale(1.03);
    }

    /* Slider Navigation UI */
    .product-gallary .swiper-nav-next,
    .product-gallary .swiper-nav-prev {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        cursor: pointer;
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }

    .product-gallary .swiper-nav-next:hover,
    .product-gallary .swiper-nav-prev:hover {
        background: #008a7a;
        color: #fff;
        transform: translateY(-50%) scale(1.1);
    }

    .product-gallary .swiper-nav-next { right: 15px; }
    .product-gallary .swiper-nav-prev { left: 15px; }

    /* Size Selector Modern & Responsive Design */
    .size-item {
        min-width: 55px;
        height: 44px;
        padding: 0 15px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid #008a7a;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        white-space: nowrap;
        position: relative;
    }

    .size-item:hover:not(.disabled) {
        background: #008a7a;
        color: #fff;
    }

    .size-item.active {
        background: #008a7a;
        color: #fff;
        box-shadow: 0 4px 8px rgba(0, 138, 122, 0.3);
    }

    .size-item.disabled {
        border-color: #e2e8f0;
        color: #a0aec0;
        background: #f7fafc;
        cursor: not-allowed;
        padding-bottom: 10px;
    }

    .size-item.disabled::after {
        content: 'Out';
        font-size: 9px;
        color: #ef4444;
        font-weight: 700;
        position: absolute;
        bottom: 2px;
    }

    /* Color Selector */
    .color-box {
        width: 36px;
        height: 36px;
        display: inline-block;
        border-radius: 50%;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #cbd5e0;
        cursor: pointer;
        transition: all 0.3s;
    }

    .color-box:hover { transform: scale(1.1); }

    .color-box.active {
        box-shadow: 0 0 0 2px #008a7a;
        transform: scale(1.15);
    }

    .color-box.stock-out { opacity: 0.4; cursor: not-allowed; }

    /* Stock Out Overlay */
    .stock-out-overlay {
        display: none;
        position: absolute;
        top: 20px;
        left: 20px;
        background: #ef4444;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 700;
        letter-spacing: 0.5px;
        z-index: 20;
    }

    .stock-out-overlay.show { display: block; }

    /* Layout Content */
    .desc-wrap {
        display: flex;
        gap: 30px;
    }

    .left-content {
        flex: 1;
        min-width: 0; 
    }

    .right-content img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
    }

    .left-content * {
        max-width: 100% !important; 
        box-sizing: border-box !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
    }

    .left-content table {
        width: 100% !important;
        display: block !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        border-collapse: collapse;
    }

    /* Responsive Design */
    @media (max-width: 767px) {
        .product-slider-wrap { flex-direction: column; gap: 15px; }
        .product-gallary { width: 100% !important; }
        .product-gallary-thumb { width: 100% !important; height: auto !important; }
        .product-gallary-thumb .swiper-wrapper { flex-direction: row !important; }
        .thumb-item { width: 70px; height: 70px; }
        .qty-box input { width: 100% !important; text-align: center; }
        .product-btn { flex-wrap: wrap; gap: 10px !important; }
        .qty-box, .cart-btn-wrap-2, #addToCartBtn, #buyNowBtn {
            width: 100% !important;
            display: block;
        }
        .desc-wrap {
            flex-direction: column-reverse; 
            gap: 20px;
        }
        .right-content {
            width: 100% !important; 
        }
    }

    /* Quantity */
    .qty-box{
        width: 130px;
        height: 48px;
        background: #008a7a;
        border-radius: 2px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
    }

    .qty-btn{
        width:40px;
        height:50px;
        border:none;
        background:transparent;
        color:#fff;
        font-size:16px;
        cursor:pointer;
        transition:.3s;
    }

    .qty-btn:hover{
        background:rgba(255,255,255,.15);
    }

    .qty-box input{
        width:50px;
        border:none;
        background:transparent;
        color:#fff;
        font-size:18px;
        font-weight:700;
        text-align:center;
        outline:none;
    }

    .qty-box input::-webkit-inner-spin-button,
    .qty-box input::-webkit-outer-spin-button{
        -webkit-appearance:none;
        margin:0;
    }

    /* Cart Button */
    .cart-btn{
        flex:1;
        height:50px;
        border:2px solid #222;
        border-radius:50px;
        background:#fff;
        color:#222;
        font-weight:700;
        font-size:16px;
        transition:.3s;
    }

    .cart-btn:hover{
        background:#008a7a;
        border-color:#008a7a;
        color:#fff;
    }

    .product-rich-description {
        overflow-x: hidden; 
        word-wrap: break-word;
    }

    .product-rich-description img {
        max-width: 100% !important;
        height: auto !important;
    }

    .product-rich-description table {
        width: 100% !important;
        max-width: 100% !important;
        display: block;
        overflow-x: auto; 
        white-space: nowrap;
    }
    .product-extra-note {
        background: #fff8e6;
        border: 1px solid #ffe4a1;
        color: #8a6d1a;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 16px;
        line-height: 1.5;
    }
</style>
@endpush

@php
    $totalStock = $product->stock;
@endphp

<section class="shop-section single pt-100 pb-100">
    <div class="container">
        <div class="row g-4">

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
                                        <span
                                            class="size-item {{ $stock <= 0 ? 'disabled' : '' }}"
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
                                        <span
                                            class="color-box {{ $colorStock <= 0 ? 'stock-out' : '' }}"
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
                                <button type="button"
                                    class="rr-primary-btn cart-btn"
                                    id="addToCartBtn"
                                    data-id="{{ $product->id }}"
                                    data-total-stock="{{ $totalStock }}"
                                >
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

<section class="product-description pb-100">
    <div class="container">
        <ul class="nav tab-navigation" id="product-tab-navigation" role="tablist">
            <li role="presentation">
                <button class="active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button"
                    role="tab" aria-controls="home" aria-selected="true">Description</button>
            </li>
            {{-- <li role="presentation">
                <button id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab"
                    aria-controls="profile" aria-selected="false">Additional information</button>
            </li> --}}
            <li role="presentation">
                <button id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab"
                    aria-controls="contact" aria-selected="false">Reviews ({{ $product->reviews->count() }})</button>
            </li>
        </ul>

        <div class="tab-content" id="product-tab-content">
            <div class="tab-pane fade show active description" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="desc-wrap row gy-4"> 
                    <div class="col-12 {{ $product->size_guide ? 'col-lg-8' : '' }} left-content product-rich-description">
                        {!! $product->description !!}
                    </div>
                    
                    @if($product->size_guide)
                    <div class="col-12 col-lg-4 right-content text-center">
                        <img src="{{ asset($product->size_guide) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                    @endif

                </div>
            </div>

            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <table class="table product-table">
                    <tbody>
                        @foreach($product->sizes as $size)
                        <tr>
                            <td>{{ $size->size->name ?? $size->name ?? 'N/A' }}</td>
                            <td>{{ $size->bust ?? 'N/A' }}</td>
                            <td>{{ $size->waist ?? 'N/A' }}</td>
                            <td>{{ $size->hip ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

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
    </script>
    <script>
        $(document).ready(function () {

        const productType = "{{ $product->product_type }}";
        const hasSizeData  = {{ $product->sizes->count() > 0 ? 'true' : 'false' }};
        const hasColorData = {{ $product->colors->count() > 0 ? 'true' : 'false' }};
        const totalStock   = {{ $product->stock }};

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

         $(document).ready(function() {
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
        });

        $('#addToCartBtn').on('click', function () {
            if (!validateSelection()) return;
            let btn = $(this);
            btn.prop('disabled', true).text('Adding...');

            $.post("{{ url('/cart/add') }}", {
                _token: "{{ csrf_token() }}",
                product_id: $(this).data('id'),
                qty: $('#qty').val(),
                size_id: $('#selectedSizeId').val() || null,
                color_id: $('#selectedColorId').val() || null,
            }, function (res) {
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
            $.post("{{ url('/cart/add') }}", {
                _token: "{{ csrf_token() }}",
                product_id: $(this).data('id'),
                qty: $('#qty').val(),
                size_id: $('#selectedSizeId').val() || null,
                color_id: $('#selectedColorId').val() || null,
            }, function (res) {
                if (res.success) window.location.href = "{{ url('/cart') }}";
            });
        });

    });
    </script>
    <script>
        $(document).ready(function() {
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
        });
    </script>
@endpush