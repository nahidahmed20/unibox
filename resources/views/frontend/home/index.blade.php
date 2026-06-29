@extends('frontend.layouts.app')
@section('title', 'Home')
@section('content')
    @include('frontend.home.home-css')
   <section class="hero-section-2 pt-60">
        <div class="container">
            <div class="row gy-lg-0 gy-4 justify-content-center">
                <div class="col-md-3 d-none d-md-block">
                    <div class="hero-list-wrap">
                        <ul class="hero-list">
                            @foreach ($categories as $category)
                                @php
                                    $hasSubcategories = $category->subcategories && $category->subcategories->count() > 0;
                                @endphp
                                <li>
                                    <div class="category-item-wrap">
                                        @if($hasSubcategories)
                                            <a href="javascript:void(0)" class="category-link" data-bs-toggle="collapse" data-bs-target="#collapseCat{{ $category->id }}" aria-expanded="false" aria-controls="collapseCat{{ $category->id }}">
                                                <div class="cat-name">
                                                    <i class="fa-solid fa-folder-plus"></i>
                                                    {{ $category->name }}
                                                </div>
                                                <div class="toggle-indicator">
                                                    <span>({{ $category->products_count }})</span>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ route('category.show', $category->slug) }}" class="category-link">
                                                <div class="cat-name">
                                                    <i class="fa-solid fa-list"></i>
                                                    {{ $category->name }}
                                                </div>
                                                <div class="toggle-indicator">
                                                    <span>({{ $category->products_count }})</span>
                                                </div>
                                            </a>
                                        @endif
                                    </div>

                                    <!-- Collapsible Subcategory List -->
                                    @if($hasSubcategories)
                                        <div class="collapse" id="collapseCat{{ $category->id }}">
                                            <ul class="sub-category-list">
                                                @foreach($category->subcategories as $subcategory)
                                                    <li>
                                                        <a href="{{ route('category.show', $subcategory->slug) }}">
                                                            <div class="sub-cat-name">
                                                                <i class="fa-solid fa-chevron-right"></i>
                                                                {{ $subcategory->name }}
                                                            </div>
                                                            <span class="badge bg-light text-dark border">
                                                                {{ $subcategory->products_count }}
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Slider Section Keeps Unchanged -->
                <div class="col-md-9">
                    <div id="heroSlider" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($sliders as $key => $slider)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <div class="hero-item" style="background-image: url('{{ asset($slider->image) }}'); border-radius: 8px;">
                                        <div class="product-overlay"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ hero-section -->

    <section class="category-section pt-100 pb-100">
        <div class="container">
            <div class="category-top heading-space space-border">
                <div class="section-heading mb-0">
                    <h2 class="section-title">categories</h2>
                </div>
                <!-- Carousel Arrows -->
                <div class="swiper-arrow">
                    <div class="swiper-nav swiper-next"><i class="fa-regular fa-arrow-left"></i></div>
                    <div class="swiper-nav swiper-prev"><i class="fa-regular fa-arrow-right"></i></div>
                </div>
            </div>
            <div class="category-carousel swiper">
                <div class="swiper-wrapper">
                    @foreach ($categories as $category)
                        <div class="swiper-slide">
                            <div class="category-item">

                                <a href="{{ route('category.show', $category->slug) }}">
                                    <div class="category-img">
                                        <img src="{{ asset($category->image ?? 'frontend/assets/img/images/default.png') }}"
                                            alt="{{ $category->name }}">
                                    </div>
                                </a>

                                <h3 class="title">
                                    <a href="{{ route('category.show', $category->slug) }}">
                                        {{ $category->name }}
                                    </a>
                                </h3>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- ./ food-section -->

    @php
        $bestsellers = $products->where('is_bestseller', 1)->take(6);
    @endphp

    @if($bestsellers->count() > 0)
    <section class="bestseller-product bg-grey pt-60 pb-30">
        <div class="container">
            <div class="product-top-content mb-25 text-center">
                <div class="section-heading mb-0">
                    <h2 class="section-title">Best Selling Products</h2>
                </div>
            </div>
            
            <div class="row gy-4 justify-content-center">
                @foreach ($bestsellers as $product)
                    <div class="col-xl-2 col-lg-3 col-6 single-item p-1 product-box">
                        <div class="product-item product-item-2">
                            
                            <div class="product-thumb">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                                        alt="product">
                                </a>
                            </div>

                            <div class="product-content">
                                <span class="category">
                                    {{ $product->brand_name ?? '' }}
                                </span>

                                <h3 class="title">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        {{ $product->name ?? '' }}
                                    </a>
                                </h3>

                                <h4 class="quantity">
                                    @if($product->total_quantity > 0)
                                        <span style="color:#16a34a">
                                            In Stock ({{ $product->total_quantity }})
                                        </span>
                                    @else
                                        <span style="color:#dc2626">
                                            Out of Stock
                                        </span>
                                    @endif
                                </h4>

                                <span class="price">
                                    ৳{{ $product->selling_price }}
                                    @if ($product->main_price)
                                        <span class="offer">৳{{ $product->main_price }}</span>
                                    @endif
                                </span>
                            </div>

                            <div class="product-bottom">
                                <button type="button" class="rr-primary-btn openCartModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}">
                                    Add To Cart
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4" id="loadMoreWrapper">
                <div class="d-inline-block">
                    <button id="loadMore" class="rr-primary-btn">
                        Show More
                    </button>
                </div>
            </div>
        </div>
    </section>
    @endif


    <section class="popular-product bg-grey pb-60 pt-60">
        <div class="container">
            <div class="product-top-content heading-space mb-25">
                <div class="section-heading mb-0 heading-2">
                    <h2 class="section-title"> Products</h2>
                </div>
                <!-- FILTER -->
                <ul class="project-filter text-center">
                    <li class="active" data-filter="*">Show All</li>
                    @foreach ($filteredCategories as $fcategory)
                        <li data-filter=".cat{{ $fcategory->id }}">
                            {{ $fcategory->name }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- PRODUCTS -->
            <div class="filter-items row gy-4">
                @foreach ($products as $product)
                    <div class="col-xl-2 col-lg-3 col-6 single-item p-1 cat{{ $product->category_id }} product-box">

                        <div class="product-item product-item-2">

                            <div class="time">
                                {{-- <span>{{ $product->brand_name }}</span> --}}
                            </div>

                            <div class="product-thumb">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                                        alt="product">
                                </a>
                            </div>

                            <div class="product-content">
                                <span class="category">
                                    {{ $product->brand_name ?? '' }}
                                </span>

                                <h3 class="title">
                                    <a href="{{ route('product.show', $product->slug) }}">
                                        {{ $product->name ?? '' }}
                                    </a>
                                </h3>

                                <h4 class="quantity">
                                    @if($product->total_quantity > 0)
                                        <span style="color:#16a34a">
                                            In Stock ({{ $product->total_quantity }})
                                        </span>
                                    @else
                                        <span style="color:#dc2626">
                                            Out of Stock
                                        </span>
                                    @endif
                                </h4>

                                <span class="price">
                                    ৳{{ $product->selling_price }}
                                    @if ($product->main_price)
                                        <span class="offer">৳{{ $product->main_price }}</span>
                                    @endif
                                </span>
                            </div>

                            <div class="product-bottom">
                                <button type="button" class="rr-primary-btn openCartModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-price="{{ $product->selling_price }}"
                                    data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}">
                                    Add To Cart
                                </button>
                            </div>

                        </div>

                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4" id="loadMoreWrapper">
                <div class="d-inline-block">
                    <button id="loadMore" class="rr-primary-btn">
                        Show More
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- ./ popular-product -->

   @if(count($services) > 0)
    <section class="service-section pb-60">
        <div class="container">
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="section-heading justify-content-center">
                        <h2 class="section-title">Our Services</h2>
                    </div>
                </div>
            </div>
            
            <div class="swiper service-carousel">
                <div class="swiper-wrapper py-2"> 
                    @foreach($services as $service)
                <div class="swiper-slide pt-2 pb-4">
                    <div class="product-item h-100 p-0" style="overflow: hidden; border-radius: 10px; display: flex; flex-direction: column;">
                        
                        <div class="product-thumb" style="padding: 0 !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; border-bottom: 1px solid var(--rr-color-border-1);">
                            <a href="{{ route('service.details', $service->slug) }}" style="display: block;">
                                @if($service->image)
                                    <img src="{{ asset($service->image) }}" alt="img" style="width: 100% !important; max-width: 100% !important; height: 220px !important; object-fit: cover !important; display: block; margin: 0; border-radius: 10px 10px 0 0;">
                                @elseif($service->icon)
                                    <div class="text-center" style="width: 100%; padding: 50px 0; background: #f8f9fa;">
                                        <i class="{{ $service->icon }}" style="font-size: 60px; color: var(--rr-color-theme-primary);"></i>
                                    </div>
                                @endif
                            </a>
                        </div>
                        
                        <div class="product-content text-center p-4">
                            <span class="category d-inline-block mb-2 text-uppercase" style="font-size: 12px; font-weight: 600; color: var(--rr-color-theme-primary); background: #f4f6f8; padding: 4px 12px; border-radius: 4px;">Premium Service</span>
                            
                            <h3 class="title m-0">
                                <a href="{{ route('service.details', $service->slug) }}">{{ $service->title }}</a>
                            </h3>
                            <p class="quantity text-muted mt-2 mb-0" style="font-size: 14px;">{{ Str::limit($service->short_description, 60) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach

                </div>
                
                <div class="swiper-pagination" style="position: relative; margin-top: 20px;"></div>
            </div>

        </div>
    </section>
    @endif

   @if(count($materials) > 0)
        <section class="material-section pb-60">
            <div class="container">
                
                <div class="row ">
                    <div class="col-12 text-center">
                        <div class="section-heading justify-content-center">
                            <h2 class="section-title">Quality Materials</h2>
                        </div>
                    </div>
                </div>
                
                <div class="swiper material-carousel">
                    <div class="swiper-wrapper py-2">
                        
                        @foreach($materials as $material)
                        <div class="swiper-slide">
                            <div class="product-item p-3 h-100" style="border-radius: 12px; border: 1px solid var(--rr-color-border-1); box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                                
                                <div class="row align-items-center h-100">
                                    
                                    <div class="col-md-5 mb-3 mb-md-0">
                                        <div class="overflow-hidden" style="border-radius: 8px;">
                                            <img src="{{ asset($material->image) }}" alt="{{ $material->name }}" class="w-100" style="height: 180px; object-fit: cover; display: block; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-7">
                                        <div class="product-content p-0 text-start">
                                            <h3 class="title mb-2" style="font-size: 20px; font-weight: 700; color: var(--rr-color-heading);">
                                                {{ $material->name }}
                                            </h3>
                                            
                                            <p class="text-muted mb-0" style="font-size: 15px; line-height: 1.6;">
                                                {{ $material->description ?? 'We use premium quality materials to ensure the best durability and finishing for our clients.' }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                </div>

                            </div>
                        </div>
                        @endforeach

                    </div>
                    
                    <div class="swiper-pagination mt-4" style="position: relative;"></div>
                </div>

            </div>
        </section>
    @endif

    @if($about_us)
    <section class="about-section pt-60 pb-60">
        <div class="container">
            <div class="row gy-5 align-items-center">
                
                <div class="col-lg-6">
                    <div class="about-image-wrapper">
                        @if($about_us->image1)
                            <img src="{{ asset($about_us->image1) }}" alt="About {{ $about_us->title }}" class="img-fluid about-main-img">
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-6 ps-lg-5">
                    <div class="about-content">
                        
                        @if($about_us->subtitle)
                            <span class="about-subtitle">{{ $about_us->subtitle }}</span>
                        @endif
                        
                        <h2 class="about-title mb-4">{{ $about_us->title }}</h2>
                        
                        <p class="about-description mb-4">
                            {{ $about_us->description }}
                        </p>

                        <div class="about-actions d-flex flex-wrap align-items-center gap-4 mt-4">
                            
                            @if($about_us->btn_text)
                                <a href="{{ $about_us->btn_url ?? '#' }}" class="modern-btn-primary">
                                    {{ $about_us->btn_text }} <i class="fa-solid fa-arrow-right ms-2"></i>
                                </a>
                            @endif
                            
                            @if($about_us->experience_years)
                                <div class="experience-badge d-flex align-items-center gap-3">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-award text-warning fs-3"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bolder fs-5 text-dark">{{ $about_us->experience_years }}+ Years</span>
                                        <span class="text-muted small fw-medium">Of Experience</span>
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    @endif

    <section class="our-process-section py-5" style="background-color: #ffffff;">
        <div class="container">
            
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <h2 class="fw-bold mb-3" style="font-size: 36px; color: #111;">Our Process</h2>
                    <p class="text-muted mx-auto" style="font-size: 16px; max-width: 600px;">
                        Turning Your Ideas into Reality—Simple, Seamless, and Stress-Free.
                    </p>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="process-timeline">
                        
                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-regular fa-paper-plane"></i>
                            </div>
                            <h4 class="step-title">Send Order Request</h4>
                            <p class="step-desc">Share your requirements and place your order.</p>
                        </div>

                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-regular fa-comments"></i>
                            </div>
                            <h4 class="step-title">Consultation</h4>
                            <p class="step-desc">Discuss ideas and finalize the details with our team.</p>
                        </div>

                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-solid fa-wallet"></i>
                            </div>
                            <h4 class="step-title">Make Initial Payment</h4>
                            <p class="step-desc">Secure your order by making an advance payment (25-50%).</p>
                        </div>

                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-solid fa-pen-nib"></i>
                            </div>
                            <h4 class="step-title">Design & Approval</h4>
                            <p class="step-desc">Our experts create a digital mockup for your approval.</p>
                        </div>

                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-solid fa-hammer"></i>
                            </div>
                            <h4 class="step-title">Production</h4>
                            <p class="step-desc">We craft your product with precision and quality materials.</p>
                        </div>

                        <div class="process-step">
                            <div class="process-icon">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <h4 class="step-title">Installation & Delivery</h4>
                            <p class="step-desc">Delivered or installed, ready to impress.</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    @if(count($features) > 0)
    <section class="feature-section pb-80 pt-60" style="background-color: #fcfcfc;">
        <div class="container">
            
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <div class="section-heading justify-content-center mb-2">
                        <h2 class="section-title fw-bold" style="color: var(--rr-color-heading); font-size: 36px;">Why Choose Us</h2>
                    </div>
                    <p class="text-muted" style="font-size: 16px;">Discover the reasons why we are the best choice for you.</p>
                </div>
            </div>
            
            <div class="row gy-4 justify-content-center">
                
                @foreach($features as $feature)
                <div class="col-lg-4 col-md-6">
                    <div class="modern-feature-card h-100 bg-white">
                        
                        <div class="modern-icon-box mb-4">
                            @if($feature->image)
                                <img src="{{ asset($feature->image) }}" alt="feature-icon" class="feature-img">
                            @elseif($feature->icon)
                                <i class="{{ $feature->icon }} feature-icon-font"></i>
                            @endif
                        </div>
                        
                        <h4 class="title mb-3" style="font-size: 22px; font-weight: 700; color: var(--rr-color-heading);">
                            {{ $feature->title }}
                        </h4>
                        
                        <p class="description mb-0 text-muted" style="font-size: 15px; line-height: 1.7;">
                            {{ $feature->description }}
                        </p>
                        
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif


   @if(count($counters) > 0)
    <section class="counter-section pb-80 pt-80" style="background-color: #fcfcfc;">
        <div class="container">
            
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <div class="section-heading justify-content-center mb-2">
                        <h2 class="section-title fw-bold" style="font-size: 38px; color: var(--rr-color-heading, #111);">
                            Our Success in Numbers
                        </h2>
                    </div>
                    <p class="text-muted" style="font-size: 16px;">
                        Building Trust Through Excellence, Quality, and Innovation
                    </p>
                </div>
            </div>
            
            <div class="row gy-4 justify-content-center">
                
                @foreach($counters as $counter)
                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="modern-counter-card text-center h-100">
                        
                        @if($counter->icon)
                            <div class="counter-icon-wrap">
                                <i class="{{ $counter->icon }}"></i>
                            </div>
                        @endif
                        
                        <h2 class="counter-number">
                            {{ $counter->number }}<span class="counter-suffix">{{ $counter->suffix }}</span>
                        </h2>
                        
                        <p class="counter-title">{{ $counter->title }}</p>
                        
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
    @endif

    @if(count($clients) > 0)
    <section class="client-section pb-80 pt-40" style="background-color: #ffffff;">
        <div class="container">
            
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <h2 class="section-title fw-bold" style="font-size: 32px; color: var(--rr-color-heading, #111);">
                        Our Satisfied Clients
                    </h2>
                    <p class="text-muted" style="font-size: 16px;">Trusted by amazing companies worldwide</p>
                </div>
            </div>
            
            <div class="swiper client-carousel">
                <div class="swiper-wrapper">
                    
                    @foreach($clients as $client)
                    <div class="swiper-slide">
                        <div class="client-brand-box">
                            <a href="{{ $client->url ?? 'javascript:void(0)' }}" target="{{ $client->url ? '_blank' : '_self' }}">
                                <img src="{{ asset($client->logo) }}" alt="client-logo" class="client-logo-img img-fluid">
                            </a>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
            </div>

        </div>
    </section>
    @endif

    <div class="modal fade" id="cartModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 overflow-hidden">
                <button type="button" class="btn-close position-absolute end-0 top-0 m-3 z-3"
                    data-bs-dismiss="modal"></button>
                <div class="row g-0">
                    <!-- IMAGE -->
                    <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">
                        <img id="modalProductImage" src="" class="img-fluid rounded-3"
                            style="max-height:350px;object-fit:contain;">
                    </div>
                    <!-- CONTENT -->
                    <div class="col-md-7">
                        <div class="p-4 p-lg-5">
                            <h3 id="modalProductName" class="fw-bold mb-2">
                            </h3>
                            <div class="mb-4">
                                <span class="fs-3 fw-bold text-danger">
                                    ৳<span id="modalProductPrice"></span>
                                </span>
                            </div>
                            <!-- SIZE -->
                            <div class="mb-4" id="sizeSection">
                                <label class="fw-semibold mb-2 d-block">Size</label>
                                <div id="modalSizeWrap" class="d-flex flex-wrap gap-2"></div>
                            </div>
                            <!-- COLOR -->
                            <div class="mb-4" id="colorSection">
                                <label class="fw-semibold mb-2 d-block">Color</label>
                                <div id="modalColorWrap" class="d-flex flex-wrap gap-2"></div>
                            </div>
                            <div class="product-btn">
                                <!-- Quantity -->
                                <div class="qty-modern">
                                    <button type="button" class="qty-btn" id="qtyMinus">−</button>
                                    <input type="text" id="modalQty" value="1" readonly>
                                    <button type="button" class="qty-btn" id="qtyPlus">+</button>
                                </div>
                                <!-- Add To Cart -->
                                <button class="rr-primary-btn cart-btn" id="finalAddToCart">
                                    Add To Cart
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="unibox-floating-cart cart-toggle">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
        </svg>
        
        <span id="cart-count" class="cart-badge-middle cart-item-count-render">{{ $cartCount ?? 0 }}</span>
    </div>
    
@endsection

@push('javascript')
@include('frontend.home.home-js')
    
@endpush
