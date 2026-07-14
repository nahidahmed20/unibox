<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Unibox | @yield('title')</title>
        <link rel="shortcut icon" href="{{ asset(setting('nav_icon')) }}" type="image/x-icon">
        <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1017622233379795'); 
    fbq('track', 'PageView'); 
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1017622233379795&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-W47XZ899');</script>
    <!-- End Google Tag Manager -->

        @include('frontend.partials.style')
    </head>

    <body>
        <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-W47XZ899"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

        @include('frontend.partials.header')
        <!-- /.Main Header -->

        <div id="popup-search-box">
            <div class="box-inner-wrap d-flex align-items-center">
                <form id="form" action="#" method="get" role="search">
                    <input id="popup-search" type="text" name="s" placeholder="Type keywords here...">
                </form>
                <div class="search-close"><i class="fa-sharp fa-regular fa-xmark"></i></div>
            </div>
        </div>
        <!-- /#popup-search-box -->

        <div class="mobile-side-menu" style="--rr-color-theme-primary: #008a7a">
            <div class="side-menu-content">
                <div class="side-menu-head">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset(setting(key: 'header_logo')) }}" alt="Logo" class="">
                    </a>
                    <button class="mobile-side-menu-close"><i class="fa-regular fa-xmark"></i></button>
                </div>
                <div class="side-menu-wrap"></div>
                <ul class="side-menu-list">
                    <li><i class="fa-light fa-location-dot"></i>Address : <span>Kataban, Dhaka-1205</span></li>
                    <li><i class="fa-light fa-phone"></i>Phone : <a href="tel:+01569896654">+8801627188836</a></li>
                    <li><i class="fa-light fa-envelope"></i>Email : <a href="mailto:info@example.com">unibox4u@gmail.com</a></li>
                </ul>
            </div>
        </div>
        <!-- /.mobile-side-menu -->

        {{-- <div id="preloader" style="--rr-color-theme-primary: #008a7a">
            <div class="preloader-close">X</div>
            <div class="sk-three-bounce">
                <div class="sk-child sk-bounce1"></div>
                <div class="sk-child sk-bounce2"></div>
                <div class="sk-child sk-bounce3"></div>
            </div>
        </div> --}}
        <!-- ./ preloader -->

        @yield('content')

        @include('frontend.partials.footer')
        <!-- ./ footer-section -->

        <div id="scroll-percentage" style="--rr-color-theme-primary: #008a7a"><span id="scroll-percentage-value"></span></div>
        <!--scrollup-->

        <!-- JS here -->
        @include('frontend.partials.script')
    </body>

</html>

