@php
    $cart = session('cart', []);
    $cartCount = collect($cart)->pluck('product_id')->unique()->count();
@endphp

<style>
    .user-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .user-info:hover {
        background: #f5f5f5;
    }

    .user-name {
        font-weight: 500;
        font-size: 14px;
    }

    /* DROPDOWN */
    .user-dropdown-menu {
        position: absolute;
        top: 45px;
        right: 0;
        background: #fff;
        width: 190px;
        border-radius: 10px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        padding: 8px 0;
        display: none;
        z-index: 999;
    }

    .user-dropdown.active .user-dropdown-menu {
        display: block;
    }

    .user-dropdown-menu li {
        list-style: none;
    }

    .user-dropdown-menu li a {
        display: block;
        padding: 10px 15px;
        font-size: 14px;
        color: #333;
        transition: 0.2s;
    }

    .user-dropdown-menu li a:hover {
        background: #f3f3f3;
    }
    .logout-btn {
        width: 100%;
        text-align: left;
        padding: 10px 15px;
        background: none;
        border: none;
        font-size: 14px;
        color: #e74c3c;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .logout-btn:hover {
        background: #fbeaea;
    }
    .form-wrap{
        position: relative;
    }

    .category-form-wrap{
        position: relative;
    }

    #searchSuggestion, .mobile-search-suggestion {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        z-index: 99999;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,.08);
        max-height: 400px;
        overflow-y: auto;
    }
    .header-right{
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .header-right-item a{
        font-size: 22px;
        color: #222;
    }
    .mobile-cart a{
        position: relative;
        display: inline-block;
    }

    .cart-badge {
        position: absolute !important;
        top: -10px !important;  
        right: -12px !important;  
        width: 18px !important;
        height: 18px !important;
        background: #E53E3E !important; 
        color: #ffffff !important;       
        border-radius: 50% !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 99 !important;          
    }

    .mobile-search-wrap {
        display: none;
        padding: 10px 15px;
        background: #fff;
        border-bottom: 1px solid #eee;
    }

    @media (max-width: 991px) {
        .mobile-search-wrap {
            display: block; 
        }
    }
    
    #searchSuggestion, .searchSuggestion {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        width: 100% !important;
        background: #ffffff !important;
        z-index: 999999 !important; 
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0,0,0,.15);
        max-height: 350px;
        overflow-y: auto;
        display: none;
    }

    #cart-drawer {
        background-color: #fff;
        position: fixed;
        overflow-y: auto;
        top: 0;
        right: 0;
        width: 80%; 
        max-width: 380px; 
        height: 100%;
        display: flex;
        flex-direction: column;
        -webkit-transform: translateX(100%);
        -ms-transform: translateX(100%);
        transform: translateX(100%);
        z-index: 999999 !important; 
        -webkit-transition: transform 0.1s ease;
        -o-transition: transform 0.5s ease;
        transition: transform 0.5s ease;
        backface-visibility: hidden;
        box-shadow: -5px 0 25px rgba(0,0,0,0.15);
        border-left: 1px solid #eee;
    }

    /* যখন কার্ট ওপেন হবে */
    #cart-drawer.is-open {
        -webkit-transform: translateX(0);
        -ms-transform: translateX(0);
        transform: translateX(0) !important;
    }

    @media only screen and (max-width: 767px) {
        #cart-drawer {
            width: 100%;
            max-width: 320px; 
        }
    }

    #cart-overlay {
        background-color: rgba(0, 0, 0, 0.7);
        height: 100%;
        /* width: 0%; */
        position: fixed;
        top: 0;
        right: 0;
        opacity: 0;
        visibility: hidden;
        /* -webkit-transition: all 500ms ease; */
        -o-transition: all 500ms ease;
        transition: all 500ms ease;
        z-index: 999998; 
        display: block;
    }

    #cart-overlay.is-open {
        width: 100%;
        opacity: 0.5;
        visibility: visible;
    }

    .mobile-cart {
        position: relative !important;
        z-index: 9999 !important;
    }
    .header-form .form-control{
        border: 1px solid var(--rr-color-border-1);
        padding: 8px 150px 8px 20px;
        border-radius: 100px;
        box-shadow: none;
    }
    button.submit.rr-primary-btn {
        padding: 11px 20px;
        border-radius: 100px;
        align-items: center;
    }

    .unibox-floating-cart {
        position: fixed;
        bottom: 30px; 
        right: 30px;  
        background-color: #E53E3E; 
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 8px 20px rgba(229, 62, 62, 0.35); 
        z-index: 9; 
        cursor: pointer;
        transition: all 0.3s ease;
        }

    .unibox-floating-cart:hover {
        transform: translateY(-5px); 
        box-shadow: 0 12px 25px rgba(229, 62, 62, 0.5); 
        }

    .unibox-floating-cart svg {
        width: 28px;
        height: 28px;
        }

    .cart-badge-middle {
        position: absolute;
        top: -4px !important;
        right: -3px !important;
        background-color: #ffffff;
        color: #E53E3E !important;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 15px;
        font-weight: 700;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); 
    }
</style>

<header class="header header-2 sticky-active" style="--rr-color-theme-primary: #E53E3E">
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="top-bar-left">
                    <ul class="top-left-list">
                        <li><a href="{{ route('about.us') }}">About Us</a></li>
                        <li><a href="{{ route('contact.us') }}">Contact</a></li>
                        <li><a href="{{ route('track.order') }}">Track Order</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="top-bar-right">
                    <span>Need Help? Call Us: <a href="tel:{{ setting(key: 'phone') }}">{{ setting(key: 'phone') }}</a></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="header-middle">
        <div class="container">
            <div class="header-middle-inner">
                <div class="header-middle-left">
                    <div class="header-logo d-lg-block">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset(setting(key: 'header_logo')) }}" alt="Logo" class="">
                        </a>
                    </div>
                    <div class="form-wrap">
                        <div class="nice-select select-control country" tabindex="0">
                            <span class="current">Categories</span>
                            <ul class="list">
                                <li data-value="" class="option selected focus">Categories</li>
                                @foreach($categories as $category)
                                    <li class="option" data-value="{{ $category->id }}">{{ $category->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="category-form-wrap">
                            <form class="header-form" id="searchForm" action="{{ route('search') }}" method="GET">
                                <input class="form-control" type="text" name="search" placeholder="Search for products, categories or brands">
                                <input type="hidden" name="category_id" id="category_id">
                                <button class="submit rr-primary-btn">
                                    Search <i class="fa-light fa-magnifying-glass"></i>
                                </button>
                            </form>
                            <div id="searchSuggestion"></div>
                        </div>
                    </div>
                </div>
                
                <div class="header-middle-right">
                    <ul class="contact-item-list">
                        @auth('customer')
                            <li class="user-dropdown">
                                <div class="user-info">
                                    <span class="user-name">{{ auth('customer')->user()->name }}</span>
                                    <i class="fa-solid fa-angle-down"></i>
                                </div>
                                <ul class="user-dropdown-menu">
                                    <li>
                                        <a href="{{ route('customer.dashboard') }}" class="menu-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                                            <i class="fa-solid fa-gauge"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('customer.logout') }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="logout-btn">
                                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('user.login') }}" class="login-btn">Login / Register</a>
                            </li>
                        @endauth
                        
                        <li>
                            <div class="header-cart-btn">
                                <a href="javascript:void(0)" class="icon cart-toggle">
                                    <i class="fa-light fa-bag-shopping"></i>
                                </a>
                                <span id="cart-count" class="cart-item-count-render">
                                    {{ $cartCount ?? 0 }} 
                                </span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="primary-header">
        <div class="container">
            <div class="primary-header-inner">
                <div class="header-logo mobile-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset(setting(key: 'header_logo')) }}" alt="Logo" class="">
                    </a>
                </div>
                <div class="header-menu-wrap">
                    <div class="mobile-menu-items">
                        <ul>
                            <li class="menu-item-has-children active"><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('our-blogs') }}">Blog</a></li>
                            <li><a href="{{ route('about.us') }}">About</a></li>
                            <li><a href="{{ route('contact.us') }}">Contact</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="header-right-wrap d-block d-md-none">
                    <div class="header-right">
                        <div class="header-right-item">
                            <a href="{{ auth('customer')->check() ? route('customer.dashboard') : route('user.login') }}">
                                <i class="fa-solid fa-user"></i>
                            </a>
                        </div>
                        <div class="header-right-item mobile-cart">
                            <a href="javascript:void(0)" class="cart-toggle">
                                <i class="fa-light fa-bag-shopping"></i>
                                <span id="cart-count" class="cart-badge cart-item-count-render">{{ $cartCount ?? 0 }}</span>
                            </a>
                        </div>
                        <div class="header-right-item">
                            <a href="javascript:void(0)" class="mobile-side-menu-toggle">
                                <i class="fa-sharp fa-solid fa-bars"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-search-wrap">
        <div class="container">
            <div class="category-form-wrap" style="position: relative;"> 
                <form class="header-form" action="{{ route('search') }}" method="GET">
                    <input class="form-control" type="text" name="search" placeholder="Search for products..." autocomplete="off">
                    <button class="submit rr-primary-btn" style="position: absolute; right: 4px; top: 3px;">
                        <i class="fa-light fa-magnifying-glass"></i>
                    </button>
                </form>
                <div id="searchSuggestion" class="searchSuggestion"></div>
            </div>
        </div>
    </div>

    <div id="cart-overlay"></div>

    <div id="cart-drawer">
        <div class="cart-header">
            <h4>
                <i class="fa-solid fa-cart-shopping"></i>
                My Shopping Cart
            </h4>
            <button id="cart-close">&times;</button>
        </div>
        <div id="cart-section" class="cart-body">
            @include('frontend.cart.partials.header-cart')
        </div>
        <div class="cart-footer" style="padding: 15px; border-top: 1px solid #eee;">
            <button class="checkout-button" onclick="goToCheckout()" style="width:100%; padding: 12px; background: #E53E3E; color: #fff; border: none; border-radius: 5px; font-weight: 600; cursor: pointer;">Checkout</button>
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
        
</header>

@push('javascript')
<script>
    $(document).ready(function () {
        // toggle dropdown
        $('.user-info').on('click', function (e) {
            e.stopPropagation();
            $(this).closest('.user-dropdown').toggleClass('active');
        });

        // close when clicking outside
        $(document).on('click', function () {
            $('.user-dropdown').removeClass('active');
        });

        $(document).on("click", ".cart-toggle", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $("#cart-drawer").addClass("is-open");
            $("#cart-overlay").addClass("is-open");
        });

        $(document).on("click", "#cart-close, #cart-overlay", function (e) {
            e.preventDefault();
            $("#cart-drawer").removeClass("is-open");
            $("#cart-overlay").removeClass("is-open");
        });
    });
</script>
@endpush