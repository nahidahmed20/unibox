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

        @include('frontend.partials.style')
    </head>

    <body>

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

        <div class="mobile-side-menu" style="--rr-color-theme-primary: #E53E3E">
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

        {{-- <div id="preloader" style="--rr-color-theme-primary: #E53E3E">
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

        <div id="scroll-percentage" style="--rr-color-theme-primary: #E53E3E"><span id="scroll-percentage-value"></span></div>
        <!--scrollup-->

        <!-- JS here -->
        @include('frontend.partials.script')
    </body>

</html>

