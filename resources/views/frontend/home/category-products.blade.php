@extends('frontend.layouts.app')
@section('title', 'Home')
@section('content')
@push('css')
    <style>
        .size-box,
        .color-box-modal {
            width: 45px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #008a7a;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            position: relative;
        }

        .size-box.active {
            background: #008a7a;
            color: #fff;
            border-color: #008a7a;
        }

        .color-box-modal {
            width: 30px;
            height: 30px;
            display: inline-block;
            border-radius: 50%;
            border: 2px solid #ddd;
            cursor: pointer;
            position: relative;
            transition: transform 0.2s;
        }

        .color-box-modal.active {
            border: 3px solid #000;
        }

        .qty-modern {
            display: flex;
            align-items: center;
            width: fit-content;
            border: 1px solid #ddd;
            border-radius: 50px;
            overflow: hidden;
        }

        .qty-modern input {
            width: 60px;
            text-align: center;
            border: none;
            font-weight: 600;
        }

        .qty-btn {
            width: 45px;
            height: 45px;
            border: none;
            background: #f5f5f5;
            font-size: 20px;
            font-weight: bold;
        }

        .qty-btn:hover {
            background: #ff6600;
            color: #fff;
        }

        .size-box.disabled {
            opacity: .5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .color-box-modal.stock-out {
            opacity: .4;
            cursor: not-allowed;
            position: relative;
        }

        .color-box-modal.stock-out::after {
            /* content:'✕'; */
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
        }




        .product-btn {
            display: flex;
            align-items: center;
            gap: 20px;
            width: 100%;
        }

        /* Quantity Box */
        .qty-modern {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 130px;
            height: 52px;
            background: #008a7a;
            /* screenshot color */
            border-radius: 0;
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 52px;
            border: none;
            background: transparent;
            color: #fff;
            font-size: 22px;
            font-weight: 600;
            cursor: pointer;
        }

        .qty-modern input {
            width: 50px;
            border: none;
            background: transparent;
            text-align: center;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
        }

        .qty-modern input:focus {
            outline: none;
        }

        /* Cart Button */
        .cart-btn {
            flex: 1;
            height: 52px;
            border: 2px solid #222;
            border-radius: 50px;
            background: #fff;
            color: #222;
            font-weight: 600;
            font-size: 16px;
            transition: .3s;
            display: flex;
            justify-content: center;
            /* horizontal */
            align-items: center;
        }

        .cart-btn:hover {
            background: #141414;
            color: #fff;
        }

        /* Responsive */
        @media (max-width:576px) {

            .product-btn {
                gap: 12px;
            }

            .qty-modern {
                width: 100px;
                height: 48px;
            }

            .qty-btn {
                width: 30px;
                height: 48px;
                font-size: 18px;
            }

            .qty-modern input {
                width: 40px;
                font-size: 16px;
            }

            .cart-btn {
                height: 48px;
                font-size: 15px;
            }
        }


        /* ====================================
        PRODUCT CARD - HOME PAGE STYLE
        ==================================== */

        .product-box {
            display: flex;
        }

        .product-item.product-item-2 {
            width: 100%;
            background: #fff;
            border: 1px solid #edf0f2;
            border-radius: 12px;
            overflow: hidden;
            transition: all .3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
        }

        .product-item.product-item-2:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            border-color: #008a7a;
        }

        /* ====================================
        DESKTOP 5 PRODUCTS PER ROW
        ==================================== */

        @media (min-width:1200px){

            .col-xl-20{
                flex: 0 0 20%;
                max-width: 20%;
            }

        }

        /* ====================================
        PRODUCT IMAGE
        ==================================== */

        .product-thumb {
            height: 220px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
            overflow: hidden;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: .4s;
        }

        .product-item:hover .product-thumb img {
            transform: scale(1.05);
        }

        /* ====================================
        CONTENT
        ==================================== */

        .product-content {
            flex: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }

        .product-content .category {
            font-size: 12px;
            font-weight: 600;
            color: #008a7a;
            text-transform: uppercase;
            margin-bottom: 6px;
            display: block;
        }

        .product-content .title {
            min-height: 48px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-content .title a {
            font-size: 14px;
            font-weight: 600;
            color: #222;
            text-decoration: none;

            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;

            overflow: hidden;
        }

        .product-content .title a:hover {
            color: #008a7a;
        }

        .product-content .quantity {
            font-size: 13px;
            margin-bottom: 10px;
            min-height: 20px;
        }

        .product-content .price {
            font-size: 20px;
            font-weight: 700;
            color: #008a7a;
            display: block;
            margin-top: auto;
        }

        .product-content .price .offer {
            font-size: 14px;
            color: #9ca3af;
            text-decoration: line-through;
            margin-left: 5px;
            font-weight: 500;
        }

        /* ====================================
        BUTTON
        ==================================== */

        .product-bottom {
            padding: 0 15px 15px;
        }

        .product-bottom .rr-primary-btn {
            width: 100%;
            height: 45px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: .3s;
        }

        /* ====================================
        MOBILE
        ==================================== */

        @media (max-width: 767px) {

            .product-thumb {
                height: 150px;
                padding: 10px;
            }

            .product-content {
                padding: 10px;
            }

            .product-content .title {
                min-height: 42px;
            }

            .product-content .title a {
                font-size: 13px;
            }

            .product-content .price {
                font-size: 18px;
            }

            .product-bottom {
                padding: 0 10px 10px;
            }

            .product-bottom .rr-primary-btn {
                height: 40px;
                font-size: 13px;
            }
        }

        /* ===================================
        MODERN LIST VIEW
        =================================== */

        .list-view-wrapper{
            display:flex;
            flex-direction:column;
            gap:20px;
        }

        .list-product-card{
            background:#fff;
            border:1px solid #edf0f2;
            border-radius:12px;
            padding:20px;
            display:flex;
            align-items:center;
            gap:25px;
            transition:.3s;
        }

        .list-product-card:hover{
            border-color:#008a7a;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .list-product-image{
            width:180px;
            min-width:180px;
            height:180px;
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
        }

        .list-product-image img{
            width:100%;
            height:100%;
            object-fit:contain;
            transition:.4s;
        }

        .list-product-card:hover .list-product-image img{
            transform:scale(1.05);
        }

        /* Content */

        .list-product-content{
            flex:1;
        }

        .list-category{
            display:block;
            font-size:13px;
            font-weight:600;
            color:#008a7a;
            text-transform:uppercase;
            margin-bottom:8px;
        }

        .list-title{
            margin-bottom:10px;
        }

        .list-title a{
            font-size:22px;
            font-weight:700;
            color:#222;
            text-decoration:none;
        }

        .list-title a:hover{
            color:#008a7a;
        }

        .list-stock{
            margin-bottom:10px;
        }

        .in-stock{
            color:#16a34a;
            font-weight:600;
        }

        .out-stock{
            color:#dc2626;
            font-weight:600;
        }

        .list-description{
            color:#666;
            line-height:1.7;
            margin:0;
        }

        /* Right Side */

        .list-product-action{
            min-width:220px;
            display:flex;
            flex-direction:column;
            align-items:flex-end;
            gap:15px;
        }

        .list-price{
            font-size:30px;
            font-weight:700;
            color:#008a7a;
        }

        .old-price{
            display:block;
            font-size:16px;
            text-decoration:line-through;
            color:#9ca3af;
            margin-top:4px;
        }

        .list-product-action .rr-primary-btn{
            width:180px;
            height:48px;
        }

        /* ===================================
        TABLET
        =================================== */

        @media(max-width:991px){

            .list-product-card{
                flex-wrap:wrap;
            }

            .list-product-action{
                width:100%;
                align-items:flex-start;
            }

        }

        /* ===================================
        MOBILE
        =================================== */

        @media(max-width:767px){

            .list-product-card{
                flex-direction:column;
                text-align:center;
                padding:15px;
            }

            .list-product-image{
                width:140px;
                min-width:140px;
                height:140px;
            }

            .list-title a{
                font-size:18px;
            }

            .list-price{
                font-size:24px;
            }

            .list-product-action{
                width:100%;
                align-items:center;
            }

            .list-product-action .rr-primary-btn{
                width:100%;
            }

        }
    </style>
@endpush

    <section class="page-header">
            <div class="container">
                <div class="page-header-content">
                    <h1 class="title">Shop Grid</h1>
                    <h4 class="sub-title">
                        <span class="home">
                            <a href="#">
                                <span>Home</span>
                            </a>
                        </span>
                        <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                        <span class="inner">
                            <span>Shop Grid</span>
                        </span>
                    </h4>
                </div>
            </div>
        </section>
        <!-- ./ page-header -->

        <section class="shop-grid pt-100 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 ">
                        <div class="shop-grid-left">

                            {{-- TOP BAR --}}
                            <div class="top-grid-content">
                                <div class="shop-tab-nav">

                                    <nav>
                                        <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">

                                            {{-- GRID --}}
                                            <button class="nav-link active"
                                                    id="nav-home-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#nav-home"
                                                    type="button"
                                                    role="tab">

                                                <svg width="20" height="17" viewBox="0 0 20 17">
                                                    <rect x="15" width="5" height="3" fill="currentColor"/>
                                                    <rect x="15" y="7" width="5" height="3" fill="currentColor"/>
                                                    <rect x="15" y="14" width="5" height="3" fill="currentColor"/>
                                                    <rect x="7.71875" width="5" height="3" fill="currentColor"/>
                                                    <rect x="7.71875" y="7" width="5" height="3" fill="currentColor"/>
                                                    <rect x="7.71875" y="14" width="5" height="3" fill="currentColor"/>
                                                    <rect width="5" height="3" fill="currentColor"/>
                                                    <rect y="7" width="5" height="3" fill="currentColor"/>
                                                    <rect y="14" width="5" height="3" fill="currentColor"/>
                                                </svg>

                                            </button>

                                            {{-- LIST --}}
                                            <button class="nav-link"
                                                    id="nav-profile-tab"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#nav-profile"
                                                    type="button"
                                                    role="tab">

                                                <svg width="20" height="17" viewBox="0 0 20 17">
                                                    <rect x="5.71875" width="14.2857" height="3" fill="currentColor"/>
                                                    <rect x="5.71875" y="7" width="14.2857" height="3" fill="currentColor"/>
                                                    <rect x="5.71875" y="14" width="14.2857" height="3" fill="currentColor"/>
                                                    <rect width="3.80952" height="3" fill="currentColor"/>
                                                    <rect y="7" width="3.80952" height="3" fill="currentColor"/>
                                                    <rect y="14" width="3.80952" height="3" fill="currentColor"/>
                                                </svg>

                                            </button>

                                        </div>
                                    </nav>

                                    <span>
                                        Showing {{ $products->firstItem() }}–{{ $products->lastItem() }}
                                        of {{ $products->total() }} results
                                    </span>

                                </div>
                                <form method="GET">
                                    <select name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="">Default Sorting</option>
                                        <option value="latest"
                                            {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                            Latest Products
                                        </option>
                                        <option value="price_low"
                                            {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                            Price Low to High
                                        </option>
                                        <option value="price_high"
                                            {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                            Price High to Low
                                        </option>
                                    </select>
                                </form>
                            </div>

                             <div id="productArea">
                                @include('frontend.home.partials.product_list', ['products' => $products])
                            </div>
                            

                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-md-12">
                        <form id="filterForm">
                            <div class="shop-sidebar">
                                <h3 class="sidebar-header">Categories</h3>
                                <ul class="sidebar-list">
                                    @foreach($categories as $category)
                                        <li>
                                            <input type="checkbox"
                                                class="filter-input"
                                                name="categories[]"
                                                value="{{ $category->id }}"
                                                id="cat{{ $category->id }}">

                                            <label for="cat{{ $category->id }}">
                                                {{ $category->name }} ({{ $category->products_count }})
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="shop-sidebar">
                                <h3 class="sidebar-header">Item Size</h3>

                                <div class="radion-btn-area">
                                    @foreach($sizes as $size)
                                        <div class="radio-item">
                                            <label>
                                                <input type="checkbox"
                                                    class="filter-input"
                                                    name="size[]"
                                                    value="{{ $size->id }}">
                                                <span class="size">{{ $size->name }}</span>
                                            </label>

                                            <span class="number">
                                                ({{ $size->purchase_details_count }})
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shop-sidebar">
                                <h3 class="sidebar-header">Brands</h3>

                                <ul class="sidebar-list list-2">
                                    @foreach($brands as $brand)
                                        <li>
                                            <input type="checkbox"
                                                class="filter-input"
                                                name="brand[]"
                                                value="{{ $brand->id }}"
                                                id="brand{{ $brand->id }}">

                                            <label for="brand{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </form>
                    </div> --}}
                </div>
                @if ($products->hasPages())
                    <ul class="pagination-wrap mt-50">
                        {{-- Previous --}}
                        @if ($products->onFirstPage())
                            <li> <span class="disabled"> <i class="fa-regular fa-chevrons-left"></i> </span> </li>
                        @else
                            <li> <a href="{{ $products->previousPageUrl() }}"> <i class="fa-regular fa-chevrons-left"></i> </a> </li>
                        @endif
                        {{-- Page Numbers --}}
                        @foreach ($products->links()->elements[0] ?? [] as $page => $url)
                            @if ($page == $products->currentPage())
                                <li> <a href="{{ $url }}" class="active"> {{ $page }} </a> </li>
                            @else
                                <li><a href="{{ $url }}">{{ $page }} </a></li>
                            @endif
                        @endforeach
                        {{-- Next --}}
                        @if ($products->hasMorePages())
                            <li> <a href="{{ $products->nextPageUrl() }}"><i class="fa-regular fa-chevrons-right"></i> </a> </li>
                        @else
                            <li> <span class="disabled"> <i class="fa-regular fa-chevrons-right"></i> </span> </li>
                        @endif
                    </ul>
                @endif
            </div>
        </section>

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

@endsection
@push('javascript')
    <script>
        $(document).on('change', '.filter-input', function (e) {
            e.preventDefault();
            fetchProducts();
        });

        function fetchProducts() {
            let data = $('#filterForm').serialize();

            $.ajax({
                url: "{{ $filterUrl }}",
                type: "GET",
                data: data,
                success: function (res) {
                    $('#productArea').html(res.html);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            let modalSizesCount = 0;
            let modalColorsCount = 0;
            let modalTotalStock = 0;
            let productStocks = [];
            let basePrice = 0; 

            // Open Product Modal
            $(document).on('click', '.openCartModal', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                basePrice = $(this).data('price'); 
                let image = $(this).data('image');

                // Reset Modal
                $('#modalProductName').text(name);
                $('#modalProductPrice').text(basePrice);
                $('#modalProductImage').attr('src', image);
                $('#modalQty').val(1);
                $('#modalSizeWrap').html('');
                $('#modalColorWrap').html('');
                $('#sizeSection').hide();
                $('#colorSection').hide();

                $('#finalAddToCart')
                    .data('id', id)
                    .prop('disabled', false)
                    .html('Add To Cart');

                $.ajax({
                    url: '/product/modal-data/' + id,
                    type: 'GET',
                    beforeSend: function() {
                        $('.openCartModal').prop('disabled', true);
                    },
                    success: function(res) {
                        modalSizesCount = $('#modalSizeWrap .size-box:not(.disabled)').length;
                        modalColorsCount = $('#modalColorWrap .color-box-modal:not(.stock-out)').length;
                        modalTotalStock = res.total_stock || 0;
                        productStocks = res.variants || res.stocks || []; 

                        if (res.sizes && res.sizes.length > 0) {
                            let sizeHtml = '';
                            res.sizes.forEach(size => {
                                let stock = size.stock ?? 0;
                                let sizeName = size.name || size.size || 'N/A'; 
                                if (sizeName.trim() !== '') {
                                    sizeHtml += `
                                        <div class="size-box ${stock <= 0 ? 'disabled' : ''}"
                                            data-id="${size.id}"
                                            data-stock="${stock}">
                                            ${sizeName}
                                        </div>
                                    `;
                                }
                            });
                            if (sizeHtml) {
                                $('#modalSizeWrap').html(sizeHtml);
                                $('#sizeSection').show();
                            }
                        }
                        
                        if (res.colors && res.colors.length > 0) {
                            let colorHtml = '';
                            res.colors.forEach(color => {
                                let code = color.code || '#ccc';
                                colorHtml += `
                                <div class="color-box-modal ${color.stock <= 0 ? 'stock-out' : ''}"
                                    style="background:${code}"
                                    data-id="${color.id}"
                                    data-stock="${color.stock ?? 0}"
                                    title="${color.name || ''}">
                                </div>`;
                            });
                            if (colorHtml) {
                                $('#modalColorWrap').html(colorHtml);
                                $('#colorSection').show();
                            }
                        }

                        if (modalTotalStock <= 0) {
                            $('#finalAddToCart').prop('disabled', true).html('Stock Out');
                        }

                        const modal = new bootstrap.Modal(document.getElementById('cartModal'));
                        modal.show();
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load product data.' });
                    },
                    complete: function() {
                        $('.openCartModal').prop('disabled', false);
                    }
                });
            });

            function updateModalPriceAndStock() {
                let sizeId = $('#cartModal .size-box.active').data('id') || null;
                let colorId = $('#cartModal .color-box-modal.active').data('id') || null;

                if (sizeId || colorId) {
                    let variant = productStocks.find(v => 
                        (v.size_id == sizeId || (!sizeId)) && 
                        (v.color_id == colorId || (!colorId))
                    );

                    if (variant) {
                        if (variant.stock <= 0) {
                            $('#finalAddToCart').prop('disabled', true).html('Stock Out');
                        } else {
                            $('#finalAddToCart').prop('disabled', false).html('Add To Cart');
                        }

                        if (variant.selling_price && variant.selling_price > 0) {
                            $('#modalProductPrice').text(parseFloat(variant.selling_price).toFixed(2));
                        } else {
                            $('#modalProductPrice').text(parseFloat(basePrice).toFixed(2));
                        }
                        return variant.stock;
                    }
                }
                
                $('#modalProductPrice').text(parseFloat(basePrice).toFixed(2));
                return modalTotalStock;
            }

            $(document).on('click', '#cartModal .size-box:not(.disabled)', function() {
                $('#cartModal .size-box').removeClass('active');
                $(this).addClass('active');
                updateModalPriceAndStock();
            });

            $(document).on('click', '#cartModal .color-box-modal:not(.stock-out)', function() {
                $('#cartModal .color-box-modal').removeClass('active');
                $(this).addClass('active');
                updateModalPriceAndStock();
            });

            function validateModalStock() {
                let hasSelectableSizes = $('#modalSizeWrap .size-box:not(.disabled)').length > 0;
                if (hasSelectableSizes && $('#cartModal .size-box.active').length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Select Size', text: 'Please select a size' });
                    return false;
                }

                let hasSelectableColors = $('#modalColorWrap .color-box-modal:not(.stock-out)').length > 0;
                if (hasSelectableColors && $('#cartModal .color-box-modal.active').length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Select Color', text: 'Please select a color' });
                    return false;
                }

                let qty = parseInt($('#modalQty').val()) || 1;
                let stock = updateModalPriceAndStock(); 
                
                if (qty > stock) {
                    Swal.fire({ icon: 'error', title: 'স্টক সীমা অতিক্রম করেছে', text: 'আপনার চাহিদাকৃত পরিমাণ স্টকে নেই' });
                    return false;
                }
                return true;
            }

            $('#finalAddToCart').on('click', function() {
                let btn = $(this);
                if (btn.prop('disabled')) return;
                if (!validateModalStock()) return;

                let product_id = btn.data('id');
                let size_id = $('#cartModal .size-box.active').data('id') || null;
                let color_id = $('#cartModal .color-box-modal.active').data('id') || null;
                let qty = parseInt($('#modalQty').val()) || 1;

                btn.prop('disabled', true).html('Adding...');

                $.ajax({
                    url: '/cart/add',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        product_id: product_id,
                        qty: qty,
                        size_id: size_id,
                        color_id: color_id
                    },
                    success: function(res) {
                        if (res.success) {
                            if (res.html) $('#cart-section').html(res.html);
                            
                            // UI Count Update
                            if (res.cart_count !== undefined) {
                                $('.cart-item-count-render').not('.cart-badge').text(res.cart_count);
                                $('.cart-badge').text(res.cart_count);
                                $('#cart-count').text(res.cart_count); // Floating cart update
                            }

                            if (res.cart_total) $('.total-value').text('৳' + parseFloat(res.cart_total).toFixed(2));
                            if (res.shipping) $('.shipping-value').text('৳' + res.shipping);

                            $('#cart-overlay, #cart-drawer').addClass('active');
                            bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
                            
                            Swal.fire({ icon: 'success', title: 'Added To Cart', timer: 1200, showConfirmButton: false });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong' });
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('Add To Cart');
                    }
                });
            });
        });
    </script>
@endpush
