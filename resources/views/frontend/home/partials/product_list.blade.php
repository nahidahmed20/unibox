{{-- TAB CONTENT --}}
<div class="tab-content" id="nav-tabContent">

    {{-- GRID VIEW --}}
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel">
        <div class="row gy-4 justify-content-center">
            @forelse($products as $product)
                <div class="col-xl-20 col-lg-3 col-md-4 col-6 single-item p-1 product-box">
                    <div class="product-item product-item-2">
                        
                        <div class="product-thumb">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <img src="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                                     alt="{{ $product->name }}">
                            </a>
                        </div>

                        <div class="product-content">
                            <span class="category">
                                {{ $product->brand_name ?? '' }}
                            </span>

                            <h3 class="title">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>

                            <h4 class="quantity mb-2">
                                @if($product->total_quantity > 0)
                                    <span style="color:#16a34a; font-size: 14px; font-weight: 500;">
                                        <i class="fas fa-check-circle"></i> In Stock ({{ $product->total_quantity }})
                                    </span>
                                @else
                                    <span style="color:#dc2626; font-size: 14px; font-weight: 500;">
                                        <i class="fas fa-times-circle"></i> Out of Stock
                                    </span>
                                @endif
                            </h4>

                            <span class="price">
                                ৳{{ number_format($product->selling_price, 2) }}
                                @if($product->main_price)
                                    <span class="offer">
                                        ৳{{ number_format($product->main_price, 2) }}
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="product-bottom">
                            <button
                                type="button"
                                class="rr-primary-btn openCartModal {{ $product->total_quantity <= 0 ? 'disabled' : '' }}"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->selling_price }}"
                                data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                                {{ $product->total_quantity <= 0 ? 'disabled' : '' }}>
                                Add To Cart
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning rounded-4 text-center">
                        No products found.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- LIST VIEW --}}
    <div class="tab-pane fade" id="nav-profile" role="tabpanel">
        <div class="list-view-wrapper">
            @forelse($products as $product)
                <div class="list-product-card">
                    
                    <div class="list-product-image">
                        <a href="{{ route('product.show', $product->slug) }}">
                            <img src="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                                 alt="{{ $product->name }}">
                        </a>
                    </div>

                    <div class="list-product-content">
                        <span class="list-category">
                            {{ $product->brand_name ?? '' }}
                        </span>

                        <h3 class="list-title">
                            <a href="{{ route('product.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="list-stock mb-2">
                            @if($product->total_quantity > 0)
                                <span class="in-stock" style="color:#16a34a; font-weight: 500;">
                                    In Stock ({{ $product->total_quantity }})
                                </span>
                            @else
                                <span class="out-stock" style="color:#dc2626; font-weight: 500;">
                                    Out of Stock
                                </span>
                            @endif
                        </div>

                        <p class="list-description">
                            {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 140) }}
                        </p>
                    </div>

                    <div class="list-product-action">
                        <span class="list-price">
                            ৳{{ number_format($product->selling_price, 2) }}
                            @if($product->main_price)
                                <span class="old-price">
                                    ৳{{ number_format($product->main_price, 2) }}
                                </span>
                            @endif
                        </span>

                        <button
                            type="button"
                            class="rr-primary-btn openCartModal {{ $product->total_quantity <= 0 ? 'disabled' : '' }}"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}"
                            data-price="{{ $product->selling_price }}"
                            data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}"
                            {{ $product->total_quantity <= 0 ? 'disabled' : '' }}>
                            Add To Cart
                        </button>
                    </div>

                </div>
            @empty
                <div class="alert alert-warning text-center">
                    No products found.
                </div>
            @endforelse
        </div>
    </div>

</div>