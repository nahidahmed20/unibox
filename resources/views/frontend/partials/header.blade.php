@php
    $cart = session('cart', []);
    $cartCount = count($cart);
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
        color: #008a7a;
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
        background: #008a7a !important; 
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
            padding: 5px 15px;
        }
        .pt-100 {
            padding-top: 15px;
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
        bottom: 90px;
        right: 15px;
        background-color: #008a7a;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 8px 20px rgba(0, 138, 122, 0.35);
        z-index: 9;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .unibox-floating-cart svg {
        width: 28px;
        height: 28px;
        }

    .unibox-whatsapp-float {
        position: fixed;
        bottom: 20px;      
        right: 15px;
        background-color: #25D366;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 8px 20px rgba(10, 107, 45, 0.35);
        z-index: 9;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .unibox-whatsapp-float:hover {
        transform: scale(1.05);
    }

    .cart-badge-middle {
        position: absolute;
        top: -4px !important;
        right: -3px !important;
        background-color: #ffffff;
        color: #008a7a !important;
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

    .header-2 .header-middle-inner .header-middle-right .contact-item-list .header-cart-btn {
        background-color: var(--rr-color-theme-green);
        display: flex;
        align-items: center;
        padding: 0 5px 0 5px;
        border-radius: 100px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .header-cart-btn .icon {
        font-size: 20px;
        text-decoration: none;
    }

    .header-cart-btn .cart-item-count-render {
        background-color: #ffffff; 
        color: var(--rr-color-theme-green); 
        font-size: 13px;
        font-weight: 700;
        height: 24px;
        min-width: 24px;
        border-radius: 50%; 
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    @media (min-width: 992px) {
        .header-menu-wrap ul .sub-menu {
            overflow: visible !important;
        }

        .header-menu-wrap ul .sub-menu li {
            position: relative !important;
            width: 100% !important;
            display: block !important;
        }

        .header-menu-wrap ul .sub-menu li a {
            color: #333333 !important; 
            display: block !important;
            padding: 10px 20px !important;
            white-space: nowrap !important; 
            font-size: 14px;
        }

        .header-menu-wrap ul li .sub-menu li .sub-menu {
            position: absolute !important;
            top: 0 !important;
            left: 100% !important; 
            min-width: 220px !important; 
            background-color: #ffffff !important; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.15) !important; 
            z-index: 9999 !important;
            
            display: block !important;
            opacity: 0 !important;
            visibility: hidden !important;
            transition: all 0.2s ease-in-out !important;
        }

        .header-menu-wrap ul li .sub-menu li:hover > .sub-menu {
            opacity: 1 !important;
            visibility: visible !important;
        }

        .header-menu-wrap ul li .sub-menu li .sub-menu li a:hover {
            color: #008a7a !important; 
            background-color: #f8f9fa !important;
        }
    }
</style>

<header class="header header-2 sticky-active" style="--rr-color-theme-primary: #008a7a">
    <div class="top-bar ">
        <div class="container d-none d-md-block">
            <div class="top-bar-inner ">
                <div class="top-bar-left">
                    <ul class="top-left-list">
                        <li><a href="{{ route('about.us') }}">About Us</a></li>
                        <li><a href="{{ route('contact.us') }}">Contact</a></li>
                        <li><a href="{{ route('track.order') }}">Track Order</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
                <div class="top-bar-right ">
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
                            <form class="header-form" id="searchForm" action="{{ route('search') }}" method="GET" autocomplete="off">
                                <input class="form-control" type="text" name="search" placeholder="Search for products, categories or brands" autocomplete="off">
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
                                <a href="{{ route('user.login') }}" class="login-btn">Login</a>
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
                            
                            <li class="menu-item-has-children">
                                <a href="javascript:void(0)">Categories</a>
                                <ul class="sub-menu">
                                    
                                    @foreach($categories as $category)
                                        @php
                                            $hasSubcategories = $category->subcategories && $category->subcategories->count() > 0;
                                        @endphp
                                        
                                        <li class="{{ $hasSubcategories ? 'menu-item-has-children' : '' }}">
                                            
                                            <a href="{{ $hasSubcategories ? 'javascript:void(0)' : route('category.show', $category->slug ?? $category->id) }}">
                                                {{ $category->name }}
                                            </a>
                                            
                                            @if($hasSubcategories)
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a href="{{ route('category.show', $category->slug ?? $category->id) }}">
                                                            View All {{ $category->name }}
                                                        </a>
                                                    </li>
                                                    
                                                    @foreach($category->subcategories as $subcategory)
                                                        <li>
                                                            <a href="{{ route('category.show', $subcategory->slug ?? $subcategory->id) }}">
                                                                {{ $subcategory->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            
                                        </li>
                                    @endforeach
                                    
                                </ul>
                            </li>

                            <li><a href="{{ route('our-blogs') }}">Blog</a></li>
                            <li><a href="{{ route('about.us') }}">About Us</a></li>
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
            <button class="checkout-button" onclick="goToCheckout()" style="width:100%; padding: 12px; background: #008a7a; color: #fff; border: none; border-radius: 5px; font-weight: 600; cursor: pointer;">Checkout</button>
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

    <a href="https://wa.me/8801632242724"
        target="_blank"
        rel="noopener noreferrer"
        class="unibox-whatsapp-float">
            <svg viewBox="0 0 24 24" fill="white" width="28" height="28">
                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.29-1.39a9.9 9.9 0 0 0 4.75 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.02h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.18 8.18 0 0 1-1.26-4.34c0-4.53 3.69-8.21 8.24-8.21 2.2 0 4.27.86 5.83 2.42a8.15 8.15 0 0 1 2.41 5.8c0 4.53-3.69 8.19-8.23 8.19zm4.51-6.13c-.25-.12-1.47-.72-1.7-.81-.23-.08-.39-.12-.56.13-.17.24-.64.81-.78.97-.15.17-.29.19-.54.06-.25-.12-1.04-.38-1.99-1.22-.74-.66-1.23-1.47-1.38-1.72-.15-.24-.02-.38.11-.5.11-.11.25-.29.37-.43.13-.15.17-.25.25-.42.08-.17.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.41-.42-.56-.42-.14-.01-.31-.01-.48-.01-.17 0-.44.06-.67.31-.23.24-.87.85-.87 2.08 0 1.22.89 2.4 1.01 2.57.13.17 1.76 2.69 4.26 3.77.6.26 1.06.41 1.42.53.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.28z"/>
            </svg>
    </a>
        
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