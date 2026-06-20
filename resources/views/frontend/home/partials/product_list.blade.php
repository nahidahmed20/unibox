{{-- TAB CONTENT --}}
<div class="tab-content" id="nav-tabContent">

    {{-- GRID VIEW --}}
    <div class="tab-pane fade show active" id="nav-home" role="tabpanel">

        <div class="row gy-4">

            @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">

                    <div class="shop-item">

                        <div class="shop-thumb">
                            <div class="overlay"></div>

                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">

                            @if ($product->discount_value > 0)
                                <span class="sale">
                                    {{ $product->discount_type == 'percent' ? $product->discount_value . '% OFF' : 'Sale' }}
                                </span>
                            @elseif($product->is_new)
                                <span class="sale">New</span>
                            @endif

                            <ul class="shop-list">
                                <li>
                                    <a href="javascript:void(0);" class="openCartModal" data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}" data-price="{{ $product->selling_price }}"
                                        data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}">
                                        <i class="fa-regular fa-cart-shopping"></i>
                                    </a>
                                </li>

                                {{-- <li><a href="#"><i class="fa-light fa-heart"></i></a></li> --}}
                                <li><a href="{{ route('product.show', $product->slug) }}"><i
                                            class="fa-light fa-eye"></i></a></li>
                            </ul>
                        </div>

                        <div class="shop-content">
                            <span class="category">{{ $product->category_name }}</span>

                            <h3 class="title">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>


                            <span class="price">
                                ৳{{ $product->selling_price }}
                                @if ($product->main_price)
                                    <span class="offer">৳{{ $product->main_price }}</span>
                                @endif
                            </span>

                        </div>

                    </div>

                </div>

            @empty
                <div class="col-12">
                    <div class="alert alert-warning rounded-4">
                        No products found.
                    </div>
                </div>
            @endforelse

        </div>

    </div>

    {{-- LIST VIEW --}}
    <div class="tab-pane fade" id="nav-profile" role="tabpanel">

        <div class="grid-shop-items">

            @forelse($products as $product)
                <div class="shop-item grid-shop">

                    <div class="shop-thumb">
                        <div class="overlay"></div>
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">

                        @if ($product->discount_value > 0)
                            <span class="sale">
                                {{ $product->discount_type == 'percent'
                                    ? $product->discount_value . '% OFF'
                                    : '৳' . $product->discount_value . ' OFF' }}
                            </span>
                        @endif

                        @if ($product->is_new)
                            <span class="sale new-badge"
                                style="{{ $product->discount_value > 0 ? 'top: 45px;' : '' }}">New</span>
                        @endif

                        <ul class="shop-list">
                            <li>
                                <a href="javascript:void(0);" class="openCartModal" data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}" data-price="{{ $product->selling_price }}"
                                    data-image="{{ $product->image ? asset($product->image) : asset('frontend/assets/img/product/default.png') }}">
                                    <i class="fa-regular fa-cart-shopping"></i>
                                </a>
                            </li>
                            <li><a href="#"><i class="fa-light fa-heart"></i></a></li>
                            <li><a href="{{ route('product.show', $product->slug) }}"><i
                                        class="fa-light fa-eye"></i></a></li>
                        </ul>
                    </div>

                    <div class="shop-content">
                        <span class="category">{{ $product->category_name }}</span>

                        <h3 class="title">
                            <a href="{{ route('product.show', $product->slug) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <p>
                            {{ \Illuminate\Support\Str::limit(strip_tags($product->short_description), 120) }}
                        </p>

                        {{-- প্রাইস লজিক Grid View এর মতো ঠিক করা হলো --}}
                        <span class="price">
                            ৳{{ $product->selling_price }}
                            @if ($product->main_price)
                                <span class="offer">৳{{ $product->main_price }}</span>
                            @endif
                        </span>
                    </div>

                </div>

            @empty
                <div class="alert alert-warning rounded-4">
                    No products found.
                </div>
            @endforelse

        </div>

    </div>

</div>
