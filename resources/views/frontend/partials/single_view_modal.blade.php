<div class="row">
    {{-- LEFT : IMAGE SLIDER --}}
    <div class="col-lg-6 col-md-12 col-12">
        <div class="product-gallery product-gallery-vertical d-flex">

            {{-- LARGE IMAGE --}}
            <div class="product-img-large ">
                <div class="img-large-slider common-slider" data-slick='{
                    "slidesToShow": 1,
                    "slidesToScroll": 1,
                    "dots": false,
                    "arrows": false,
                    "asNavFor": ".img-thumb-slider"
                }'>
                    {{-- MAIN IMAGE --}}
                    <div class="img-large-wrapper">
                        <a href="{{ asset($product->image) }}" data-fancybox="gallery">
                            <img src="{{ asset($product->image) }}" alt="img">
                        </a>
                    </div>

                    {{-- OTHER IMAGES --}}
                    @foreach ($product->images as $subImage)
                        <div class="img-large-wrapper">
                            <a href="{{ asset($subImage->image) }}" data-fancybox="gallery">
                                <img src="{{ asset($subImage->image) }}" alt="img">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- THUMB IMAGE --}}
            <div class="product-img-thumb ms-2">
                <div class="img-thumb-slider common-slider" data-vertical-slider="true" data-slick='{
                    "slidesToShow": 5,
                    "slidesToScroll": 1,
                    "dots": false,
                    "arrows": true,
                    "infinite": false,
                    "speed": 300,
                    "cssEase": "ease",
                    "focusOnSelect": true,
                    "swipeToSlide": true,
                    "vertical": true,
                    "asNavFor": ".img-large-slider"
                }'>
                    {{-- MAIN IMAGE THUMB --}}
                    <div>
                        <div class="img-thumb-wrapper">
                            <img src="{{ asset($product->image) }}" alt="img">
                        </div>
                    </div>

                    {{-- OTHER IMAGE THUMBS --}}
                    @foreach ($product->images as $subImage)
                        <div>
                            <div class="img-thumb-wrapper">
                                <img src="{{ asset($subImage->image) }}" alt="img">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="activate-arrows show-arrows-always arrows-white d-none d-lg-flex justify-content-between mt-3"></div>
            </div>

        </div>
    </div>

    {{-- RIGHT : DETAILS --}}
    <div class="col-lg-6 col-md-12 col-12">
        <div class="product-details ps-lg-4">

            {{-- STOCK --}}
            <div class="mb-3">
                @if($product->stocks->sum('quantity') > 0)
                    <span class="product-availability">In Stock</span>
                @else
                    <span class="text-white bg-danger">Out of Stock</span>
                @endif
            </div>

            {{-- TITLE --}}
            <h2 class="product-title mb-3">{{ $product->name }}</h2>

            {{-- PRICE --}}
            <div class="product-price-wrapper mb-4">
                <span class="product-price regular-price">
                    ৳{{ number_format($product->selling_price, 2) }}
                </span>

                @if($product->main_price)
                    <del class="product-price compare-price ms-2">
                        ৳{{ number_format($product->main_price, 2) }}
                    </del>
                @endif
            </div>

            {{-- SKU --}}
            <div class="product-sku product-meta mb-1">
                <strong class="label">SKU:</strong> {{ $product->sku ?? 'N/A' }}
            </div>

            {{-- BRAND --}}
            @if($product->brand)
            <div class="product-vendor product-meta mb-3">
                <strong class="label">Brand:</strong> {{ $product->brand->name ?? 'N/A' }}
            </div>
            @endif

            {{-- COLORS --}}
            @if($product->colors->count())
            <div class="product-variant product-variant-color mb-3">
                <strong class="label mb-1 d-block">Color:</strong>
                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                    @foreach($product->colors as $color)
                        <li class="variant-item">
                            <input type="radio" id="color-{{ $color->id }}" name="color_id" value="{{ $color->id }}" required>
                            <label for="color-{{ $color->id }}" class="variant-label" style="background: {{ $color->name }}"></label>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- SIZES --}}
            @if($product->sizes->count())
            <div class="product-variant product-variant-other mb-3">
                <strong class="label mb-1 d-block">Size:</strong>
                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                    @foreach($product->sizes as $size)
                        <li class="variant-item">
                            <input type="radio" id="size-{{ $size->id }}" name="size_id" value="{{ $size->id }}" required>
                            <label for="size-{{ $size->id }}" class="variant-label">{{ $size->size }}</label>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

             <div class="misc d-flex align-items-end justify-content-between mt-4">
                <div class="quantity d-flex align-items-center justify-content-between">
                    <button class="qty-btn dec-qty">−</button>
                    <input class="qty-input" type="number" value="1" min="1">
                    <button class="qty-btn inc-qty">+</button>
                </div>
            </div>

            {{-- ACTIONS --}}
            <form class="product-form mt-4"data-stock='@json($stockData)'>

                <div class="product-form-buttons ">
                    <button type="button"
                            class="position-relative btn-atc btn-add-to-cart"
                            data-product="{{ $product->id }}">
                        ADD TO CART
                    </button>

                </div>

                <div class="buy-it-now-btn mt-2">
                    
                    <button type="button"
                            class="position-relative btn-atc btn-buyit-now"
                            data-product="{{ $product->id }}">
                        BUY IT NOW
                    </button>
                </div>
            </form>    
            {{-- WHATSAPP ORDER BOX --}}
            <div class="whatsapp-order-box mt-4">
                <a href="https://wa.me/8801627188836?text=আমি%20এই%20পণ্যটি%20সম্পর্কে%20জানতে%20চাই" 
                target="_blank"
                class="whatsapp-order-link">

                    <div class="whatsapp-left">
                        <i class="fab fa-whatsapp"></i>
                    </div>

                    <div class="whatsapp-right">
                        <h6>হোয়াটসঅ্যাপে অর্ডার করুন</h6>
                        <p>
                            📞 01627-188836 <br>
                            যেকোনো তথ্য বা অর্ডারের জন্য এখনই মেসেজ দিন
                        </p>
                    </div>

                    <div class="whatsapp-arrow">
                        <i class="fa fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
