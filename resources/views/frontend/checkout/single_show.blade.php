@extends('frontend.layouts.app')
@section('title', 'Checkout')


@section('content')
@push('css')
    <style>
        
        .whatsapp-order-box {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .whatsapp-order-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            background: #25d36b;
            color: #fff;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .whatsapp-order-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.35);
            color: #fff;
        }

        /* Left Icon */
        .whatsapp-left {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.18);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        /* Text */
        .whatsapp-right h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
        }

        .whatsapp-right p {
            font-size: 20px;
            line-height: 1.4;
            opacity: 0.95;
            margin: 0 auto;
        }

        .whatsapp-phone {
            font-size: 16px;
            font-weight: 700;
        }


        /* Arrow */
        .whatsapp-arrow {
            margin-left: auto;
            font-size: 16px;
            opacity: 0.8;
        }
    </style>
@endpush
<main id="MainContent" class="content-for-layout">
    <div class="product-page mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-gallery product-gallery-vertical d-flex">

                        {{-- LARGE IMAGE --}}
                        <div class="product-img-large">
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
                        <div class="product-img-thumb">
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

                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-details ps-lg-4">
                        <div class="mb-3">
                            @if($product->stocks->sum('quantity') > 0)
                                <span class="product-availability">In Stock</span>
                            @else
                                <span class=" text-white bg-danger">Out of Stock</span>
                            @endif
                        </div>
                        <h2 class="product-title mb-3">{{$product->name}}</h2>
                        
                        <div class="product-price-wrapper mb-4">
                            <span class="product-price regular-price">৳{{ number_format($product->selling_price, 2) }}</span>
                            <del class="product-price compare-price ms-2">৳{{ number_format($product->main_price, 2) }}</del>
                        </div>
                        <div class="product-sku product-meta mb-1">
                            <strong class="label">SKU:</strong> {{ $product->sku ?? 'N/A' }}
                        </div>
                        @if($product->brond)
                        <div class="product-vendor product-meta mb-3">
                            <strong class="label">Brand:</strong> {{ $product->brand->name ?? 'N/A' }}
                        </div>
                        @endif

                            
                        <div class="product-variant-wrapper">
                            @if($product->colors->count())
                            <div class="product-variant product-variant-color">
                                <strong class="label mb-1 d-block">Color:</strong>

                                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($product->colors as $color)
                                    <li class="variant-item">
                                        <input type="radio"
                                            id="color-{{ $color->id }}"
                                            name="color_id"
                                            value="{{ $color->id }}"
                                            required>

                                        <label for="color-{{ $color->id }}"
                                            class="variant-label"
                                            style="background: {{ $color->name }}">
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if($product->sizes->count())
                            <div class="product-variant product-variant-other">
                                <strong class="label mb-1 d-block">Size:</strong>

                                <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                    @foreach($product->sizes as $size)
                                    <li class="variant-item">
                                        <input type="radio"
                                            id="size-{{ $size->id }}"
                                            name="size_id"
                                            value="{{ $size->id }}"
                                            required>

                                        <label for="size-{{ $size->id }}"
                                            class="variant-label">
                                            {{ $size->size }}
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                        
                        <div class="misc d-flex align-items-end justify-content-between mt-4">
                            <div class="quantity d-flex align-items-center justify-content-between">
                                <button class="qty-btn dec-qty">−</button>
                                <input class="qty-input" type="number" value="1" min="1">
                                <button class="qty-btn inc-qty">+</button>
                            </div>
                        </div>

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
                        <div class="whatsapp-order-box mt-4">
                            <a href="https://wa.me/8801627188836?text=আমি%20এই%20পণ্যটি%20সম্পর্কে%20জানতে%20চাই" 
                            target="_blank"
                            class="whatsapp-order-link">

                                <div class="whatsapp-left">
                                    <i class="fab fa-whatsapp"></i>
                                </div>

                                <div class="whatsapp-right">
                                    <h6>যে কোন প্রয়োজনে হোয়াটসঅ্যাপে করুন</h6>
                                    <p>
                                        📞 01627-188836 <br>
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
        </div>
    </div>

    <!-- product tab start -->
    <div class="product-tab-section mt-100" data-aos="fade-up" data-aos-duration="700">
        <div class="container">
            <div class="tab-list product-tab-list">
                <nav class="nav product-tab-nav">
                    <a class="product-tab-link tab-link active" href="#pdescription" data-bs-toggle="tab">Description</a>
                    <a class="product-tab-link tab-link" href="#pshipping" data-bs-toggle="tab">Shipping & Returns</a>
                </nav>
            </div>
            <div class="tab-content product-tab-content">
                <div id="pdescription" class="tab-pane fade show active">
                    <div class="row">
                        <div class="col-lg-7 col-md-12 col-12">
                            <div class="desc-content">
                                <h4 class="heading_18 mb-3">Product Description</h4>
                                <p class="text_16 mb-4">{!!$product->description !!}</p> 
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-12 col-12">
                            <div class="desc-img">
                                <img src="{{ asset($product->size_guide) }}" alt="img">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="desc-content mt-4">
                                <p class="text_16">{{ $product->additional_info ?? 'No additional information available.' }}</p> 
                            </div>
                        </div>
                    </div>
                </div>
                <div id="pshipping" class="tab-pane fade">
                    <div class="desc-content">
                        <h4 class="heading_18 mb-3">শিপিং ও রিটার্ন নীতিমালা</h4>
                        <p class="text_16 mb-4">
                            Unibox-এ আমরা আপনাদের জন্য একটি সহজ ও নির্ভরযোগ্য কেনাকাটার অভিজ্ঞতা নিশ্চিত করতে কাজ করি। 
                            আমাদের শিপিং ও রিটার্ন নীতিমালা এমনভাবে তৈরি করা হয়েছে যাতে আপনি নিশ্চিন্তে আমাদের থেকে পণ্য কিনতে পারেন।
                        </p>

                        <h4 class="heading_18 mb-3">শিপিং সংক্রান্ত তথ্য</h4>
                        <p class="text_16 mb-4">
                            আমরা দ্রুত ও নির্ভরযোগ্য শিপিং সেবা প্রদান করি, যাতে আপনার অর্ডার সময়মতো আপনার কাছে পৌঁছে যায়।
                            অর্ডার কনফার্ম হওয়ার পর সাধারণত ১–৩ কার্যদিবসের মধ্যে পণ্য প্রসেস ও পাঠানো হয়।
                            শিপমেন্ট পাঠানোর পর আপনাকে একটি ট্র্যাকিং নম্বর প্রদান করা হবে, যার মাধ্যমে আপনি আপনার অর্ডারের অবস্থান জানতে পারবেন।
                        </p>

                        <h4 class="heading_18 mb-3">রিটার্ন নীতিমালা</h4>
                        <p class="text_16 mb-4">
                            আপনি যদি আপনার কেনা পণ্যে সম্পূর্ণ সন্তুষ্ট না হন, তাহলে সেটি রিটার্ন করতে পারবেন।
                            তবে পণ্যটি অবশ্যই এর মূল অবস্থায় এবং মূল প্যাকেজিংসহ ফেরত দিতে হবে।
                            পণ্য ডেলিভারি পাওয়ার পরপরই রিটার্ন করার সুযোগ রয়েছে।
                        </p>

                        <p class="text_16 mb-4">
                            রিটার্ন প্রক্রিয়া শুরু করতে অনুগ্রহ করে আমাদের কাস্টমার সার্ভিস টিমের সাথে যোগাযোগ করুন।
                            রিটার্ন শিপিং খরচ সাধারণত গ্রাহককে বহন করতে হবে,
                            তবে যদি আমাদের পক্ষ থেকে কোনো ভুল হয় (যেমন ভুল পণ্য পাঠানো), তাহলে সেই ক্ষেত্রে শিপিং খরচ আমরা বহন করব।
                        </p>

                        <p class="text_16">
                            বিশেষ দ্রষ্টব্য: আপনার অর্ডারের সাথে মিলিয়ে পণ্যটি পরীক্ষা করার পর যদি এটি আপনার প্রয়োজন অনুযায়ী উপযুক্ত না হয়,
                            তাহলেও সেটি রিটার্নের জন্য গ্রহণযোগ্য হবে। এতে আপনি আরও আত্মবিশ্বাসের সাথে আমাদের থেকে কেনাকাটা করতে পারবেন।
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- product tab end -->
    
    <!-- you may also like start -->
    <div class="featured-collection-section mt-100 home-section overflow-hidden">
        <div class="container">
            <div class="section-header">
                <h2 class="section-heading">You may also like</h2>
            </div>

            <div class="product-container position-relative">
                <div class="common-slider" data-slick='{
                    "slidesToShow": 4,
                    "slidesToScroll": 1,
                    "dots": false,
                    "arrows": true,
                    "autoplay": true,
                    "autoplaySpeed": 3000,
                    "responsive": [
                        {
                            "breakpoint": 1281,
                            "settings": {
                                "slidesToShow": 3
                            }
                        },
                        {
                            "breakpoint": 768,
                            "settings": {
                                "slidesToShow": 2
                            }
                        }]}'>
                        @foreach ($relatedProducts as $relatedP)
                            @php
                                    $discount = 0;
                                    if ($relatedP->main_price > 0) {
                                        $discount = round((($relatedP->main_price - $relatedP->selling_price) / $relatedP->main_price) * 100);
                                    }
                                @endphp
                            <div class="new-item" data-aos="fade-up" data-aos-duration="300">
                                <div class="product-card">
                                    <div class="product-card-img">
                                        <a class="hover-switch" href="collection-left-sidebar.html">
                                            <img class="secondary-img" src="{{asset($relatedP->image)}}"
                                                alt="product-img">
                                            <img class="primary-img" src="{{asset($relatedP->image)}}"
                                                alt="product-img">
                                        </a>
                                        
                                        @if($discount > 0)
                                        <div class="product-badge">
                                            <span class="badge-label badge-percentage rounded">
                                                -{{ $discount }}%
                                            </span>
                                        </div>
                                        @endif

                                        <div class="product-card-action product-card-action-2">
                                            <a href="javascript:void(0)" class="quickview-btn btn-primary" data-id="{{ $product->id }}">QUICKVIEW</a>
                                            <a href="{{ route('add.to.cart', $product->slug) }}" class="addtocart-btn btn-primary">ADD TO CART</a>
                                        </div>
                                    </div>
                                    <div class="product-card-details text-center">
                                        <h3 class="product-card-title">
                                            <a href="{{ route('add.to.cart', $product->slug) }}">{{ $product->name }}</a>
                                        </h3>
                                        <div class="product-card-price">
                                            <span class="card-price-regular">
                                                ৳{{ number_format($product->selling_price) }}
                                            </span>
                                            <span class="card-price-compare text-decoration-line-through">
                                                ৳{{ number_format($product->main_price) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                    </div>
                <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
            </div>
        </div>
    </div>
    <!-- you may also lik end -->
</main>

@endsection

@push('js')

<script>
    $(document).ready(function () {

        // LARGE IMAGE SLIDER
        $('.img-large-slider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: false,
            fade: true,
            asNavFor: '.img-thumb-slider'
        });

        // THUMB SLIDER
        $('.img-thumb-slider').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.img-large-slider',
            dots: false,
            arrows: true,
            focusOnSelect: true,
            vertical: true,
            infinite: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        vertical: false,
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        vertical: false,
                        slidesToShow: 3
                    }
                }
            ]
        });

    });
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', '.btn-add-to-cart, .btn-buyit-now', function () {
            let btn = $(this);
            let form = btn.closest('.product-form');

            let product_id = btn.data('product');
            let color_id   = $('.variant-item input[name="color_id"]:checked').val() || null;
            let size_id    = $('.variant-item input[name="size_id"]:checked').val() || null;
            let quantity   = parseInt($('#quickview-modal .qty-input').val()) || 1;

            let stockData  = form.data('stock') || [];

            console.log(stockData); 

            let hasColorStock = stockData.some(s => s.color_id && s.color_id > 0);
            let hasSizeStock  = stockData.some(s => s.size_id && s.size_id > 0);

            if (hasColorStock && !color_id) {
                toastr.warning('Please select a color');
                return;
            }

            if (hasSizeStock && !size_id) {
                toastr.warning('Please select a size');
                return;
            }

            let matchedStock = stockData.find(s =>
                (hasColorStock ? Number(s.color_id) === Number(color_id) : true) &&
                (hasSizeStock  ? Number(s.size_id)  === Number(size_id)  : true)
            );

            if (!matchedStock || matchedStock.qty <= 0) {
                toastr.error('Selected variation is out of stock');
                return;
            }

            if (quantity > matchedStock.qty) {
                toastr.error('Only ' + matchedStock.qty + ' items available');
                return;
            }

            // AJAX Add to Cart
            $.post("{{ route('cart.add') }}", {
                _token: "{{ csrf_token() }}",
                product_id: product_id,
                color_id: color_id,
                size_id: size_id,
                qty: quantity
            }, function(res) {
                if(res.success){
                    toastr.success(res.message);

                    // Buy It Now redirect
                    if(btn.hasClass('btn-buyit-now')){
                        window.location.href = "{{ route('checkout') }}";
                        return;
                    }

                    // Reload to update cart drawer
                    setTimeout(function () {
                        window.location.reload();
                    }, 500);

                } else {
                    toastr.error(res.message);
                }
            });
        });

        // ---------------------------
        // REMOVE ITEM
        // ---------------------------
        $(document).on('click', '.product-remove', function(e){
            e.preventDefault();
            let item = $(this).closest('.minicart-item');
            let id = item.data('id');

            $.post("{{ route('cart.remove') }}", {
                _token: "{{ csrf_token() }}",
                product_id: id
            }, function(res){
                if(res.success){
                    toastr.success(res.message);
                    updateDrawerCart();
                } else {
                    toastr.error(res.message);
                }
            });
        });

        // ---------------------------
        // UPDATE DRAWER CART VIEW
        // ---------------------------
        function updateDrawerCart() {
            $.get("{{ route('cart.view') }}", function(res) {
                let cartContainer = $('#drawer-cart-items');
                let cartCountElem = $('#drawer-cart-count');
                let cartSubtotalElem = $('#drawer-cart-subtotal');

                cartContainer.empty();

                if(res.cartCount === 0){
                    cartContainer.html(`
                        <div class="cart-empty-area text-center py-5">
                            <p class="cart-empty">You have no items in your cart</p>
                        </div>
                    `);
                    cartCountElem.text(0);
                    cartSubtotalElem.text('0.00');
                    return;
                }

                $.each(res.cartItems, function(key, item){
                    cartContainer.append(`
                        <div class="minicart-item d-flex" data-id="${key}">
                            <div class="mini-img-wrapper">
                                <img class="mini-img" src="${item.image}" alt="${item.name}">
                            </div>
                            <div class="product-info">
                                <h2 class="product-title"><a href="#">${item.name}</a></h2>
                                <p class="product-vendor">
                                    ${item.size ? `<span>Size: ${item.size}</span>` : ''}
                                    ${item.color ? ` | <span>Color: ${item.color}</span>` : ''}
                                </p>
                                <div class="misc d-flex align-items-end justify-content-between">
                                    <div class="product-quantity">
                                        Qty: ${item.quantity}
                                    </div>
                                    <div class="product-remove-area text-end">
                                        <div class="product-price">
                                            ৳${(item.price * item.quantity).toFixed(2)}
                                        </div>
                                        <a href="#" class="product-remove text-danger" data-key="${key}">Remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });

                cartCountElem.text(res.cartCount);
                cartSubtotalElem.text(res.cartSubtotal.toFixed(2));
            });
        }

    });
</script>

<script>
    $(document).on('click', '.quickview-btn', function () {
        let productId = $(this).data('id');

        // show loading first
        $('#quickview-modal-body').html('<div class="text-center py-5">Loading...</div>');
        $('#quickview-modal').modal('show');

        $.ajax({
            url: "{{ url('/quickview') }}/" + productId,
            type: "GET",
            success: function (response) {

                // inject content
                $('#quickview-modal-body').html(response);

                // wait until modal fully shown
                $('#quickview-modal').on('shown.bs.modal', function () {

                    let slider = $('#quickview-modal-body .common-slider');

                    if (slider.hasClass('slick-initialized')) {
                        slider.slick('unslick');
                    }

                    slider.slick({
                        slidesToShow: 1,
                        arrows: true,
                        dots: true,
                        autoplay: false,
                        adaptiveHeight: true
                    });
                });
            }
        });
    });
</script>
@endpush