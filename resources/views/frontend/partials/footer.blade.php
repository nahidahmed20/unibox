<footer class="footer-section bg-light pt-80" style="border-top: 1px solid rgba(0,0,0,0.05);">
    <div class="container">
        
        <div class="row gy-4 mb-5 pb-4 border-bottom justify-content-center">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="footer-feature-item d-flex align-items-center gap-3">
                    <div class="icon-box">
                        <img src="{{ asset('frontend/assets/img/icon/footer-1.png') }}" alt="Free Shipping">
                    </div>
                    <div class="content">
                        <h4 class="title fw-bold mb-1 fs-6">Free Shipping</h4>
                        <span class="text-muted small">On orders over ৳1000</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="footer-feature-item d-flex align-items-center gap-3">
                    <div class="icon-box">
                        <img src="{{ asset('frontend/assets/img/icon/footer-2.png') }}" alt="Instant Return">
                    </div>
                    <div class="content">
                        <h4 class="title fw-bold mb-1 fs-6">Instant Return</h4>
                        <span class="text-muted small">Return on Delivery</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="footer-feature-item d-flex align-items-center gap-3">
                    <div class="icon-box">
                        <img src="{{ asset('frontend/assets/img/icon/footer-3.png') }}" alt="Secure Payment">
                    </div>
                    <div class="content">
                        <h4 class="title fw-bold mb-1 fs-6">Secure Payment</h4>
                        <span class="text-muted small">bKash, Nagad, Cards</span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="footer-feature-item d-flex align-items-center gap-3">
                    <div class="icon-box">
                        <img src="{{ asset('frontend/assets/img/icon/footer-4.png') }}" alt="24/7 Support">
                    </div>
                    <div class="content">
                        <h4 class="title fw-bold mb-1 fs-6">24/7 Support</h4>
                        <span class="text-muted small">Friendly customer support</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row footer-widget-wrap pb-60 gy-5">

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="widget-title fw-bold mb-4 fs-5">About Store</h3>
                    
                    <div class="footer-contact d-flex align-items-start gap-3 mb-3">
                        <div class="icon text-primary-theme mt-1">
                            <i class="fa-solid fa-headset fs-5"></i>
                        </div>
                        <div class="content">
                            <span class="d-block text-muted small mb-1">Have Question? Call Us 24/7</span>
                            <a href="tel:{{ setting('phone') }}" class="fw-bold text-dark text-decoration-none">
                                {{ setting('phone') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer-contact d-flex align-items-start gap-3 mb-3">
                        <div class="icon text-primary-theme mt-1">
                            <i class="fa-regular fa-envelope fs-5"></i>
                        </div>
                        <div class="content">
                            <span class="d-block text-muted small mb-1">Email Address</span>
                            <a href="mailto:{{ setting('email') }}" class="text-dark fw-medium text-decoration-none">
                                {{ setting('email') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer-contact d-flex align-items-start gap-3 mb-4">
                        <div class="icon text-primary-theme mt-1">
                            <i class="fa-solid fa-location-dot fs-5"></i>
                        </div>
                        <div class="content">
                            <span class="d-block text-muted small mb-1">Our Address</span>
                            <p class="mb-0 text-dark fw-medium">{{ setting('company_address') }}</p>
                        </div>
                    </div>
                    
                    <div class="footer-social d-flex gap-2">
                        @if(setting('facebook'))
                            <a href="{{ setting('facebook') }}" target="_blank" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                        @endif
                        @if(setting('instagram'))
                            <a href="{{ setting('instagram') }}" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                        @endif
                        @if(setting('youtube'))
                            <a href="{{ setting('youtube') }}" target="_blank" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
                        @endif
                        @if(setting('tiktok'))
                            <a href="{{ setting('tiktok') }}" target="_blank" class="social-btn"><i class="fa-brands fa-tiktok"></i></a>
                        @endif
                        @if(setting('linkedin'))
                            <a href="{{ setting('linkedin') }}" target="_blank" class="social-btn"><i class="fa-brands fa-linkedin-in"></i></a>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $categories = \App\Models\Category::where('status', 1)->latest()->take(5)->get();
            @endphp
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget ps-lg-4">
                    <h3 class="widget-title fw-bold mb-4 fs-5">Shop Categories</h3>
                    <ul class="footer-list list-unstyled mb-0">
                        @foreach($categories as $category)
                            <li class="mb-2"><a href="{{ route('category.show', $category->slug) }}" class="footer-link">{{ $category->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="widget-title fw-bold mb-4 fs-5">Useful Links</h3>
                    <ul class="footer-list list-unstyled mb-0">
                        <li class="mb-2"><a href="{{ route('privacy.policy') }}" class="footer-link">Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('terms.conditions') }}" class="footer-link">Terms & Conditions</a></li>
                        <li class="mb-2"><a href="{{ route('track.order') }}" class="footer-link">Track Order</a></li>
                        <li class="mb-2"><a href="{{ route('return.policy') }}" class="footer-link">Return & Refund</a></li>
                        <li class="mb-2"><a href="{{ route('shipping.policy') }}" class="footer-link">Shipping Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h3 class="widget-title fw-bold mb-4 fs-5">Our Newsletter</h3>
                    <p class="text-muted mb-4 small" style="line-height: 1.6;">
                        Subscribe to our newsletter and get the latest product updates, exclusive offers, and special discounts.
                    </p>
                    
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form position-relative mb-4">
                        @csrf
                        <input type="email" name="email" class="form-control rounded-pill border-0 shadow-sm ps-4 pe-5 py-3" placeholder="Enter your email..." required>
                        <button type="submit" class="btn btn-theme-primary rounded-circle position-absolute top-50 translate-middle-y end-0 me-1 shadow-sm" style="width: 44px; height: 44px; padding: 0;">
                            <i class="fa-regular fa-paper-plane"></i>
                        </button>
                    </form>

                    <ul class="list-unstyled text-muted small">
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Latest Products</li>
                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Exclusive Offers</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i> Special Coupons</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-area py-4 border-top">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="mb-0 text-muted small">
                        Copyright &copy; {{ date('Y') }}
                        <span class="fw-bold text-dark">
                            {{ setting('site_name', 'Unibox') }}
                        </span>.
                        All Rights Reserved.
                    </p>
                </div>

                <div class="col-md-6 text-center text-md-end">
                    <span class="me-2 text-muted small">Secure Payments:</span>

                    <img src="{{ asset('frontend/assets/img/payment/bkash.jpg') }}"
                        alt="bKash"
                        class="payment-icon">

                    <img src="{{ asset('frontend/assets/img/payment/nagad.jpg') }}"
                        alt="Nagad"
                        class="payment-icon">

                    <img src="{{ asset('frontend/assets/img/payment/rocket.jpg') }}"
                        alt="Rocket"
                        class="payment-icon">

                    <img src="{{ asset('frontend/assets/img/payment/visa.jpg') }}"
                        alt="Visa"
                        class="payment-icon">

                    <img src="{{ asset('frontend/assets/img/payment/mastercard.jpg') }}"
                        alt="MasterCard"
                        class="payment-icon">
                </div>

            </div>
        </div>
    </div>
</footer>
<style>
    .payment-icon{
        height: 28px;
        width: auto;
        margin-left: 8px;
        object-fit: contain;
        transition: 0.3s ease;
    }

    .payment-icon:hover{
        transform: translateY(-2px);
    }
    /* Theme Colors */
    .text-primary-theme {
        color: #008a7a !important; /* Your Brand Color */
    }
    .btn-theme-primary {
        background-color: #008a7a;
        color: #ffffff;
        transition: all 0.3s ease;
    }
    .btn-theme-primary:hover {
        background-color: #008a7a;
        color: #ffffff;
        transform: scale(1.05);
    }

    /* Footer Feature Items */
    .footer-feature-item {
        padding: 15px;
        border-radius: 12px;
        transition: background-color 0.3s ease;
    }
    .footer-feature-item:hover {
        background-color: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .footer-feature-item .icon-box img {
        width: 45px;
        height: 45px;
        object-fit: contain;
        transition: transform 0.3s ease;
    }
    .footer-feature-item:hover .icon-box img {
        transform: translateY(-3px);
    }

    /* Footer Links with Hover Slide Effect */
    .footer-link {
        color: #64748b;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
        position: relative;
    }
    .footer-link::before {
        content: '\f105'; /* FontAwesome angle-right */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 0;
        opacity: 0;
        transition: all 0.3s ease;
        color: #008a7a;
    }
    .footer-link:hover {
        color: #008a7a;
        transform: translateX(2px);
    }
    .footer-link:hover::before {
        opacity: 1;
        left: -2px;
    }

    /* Social Buttons */
    .social-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        background-color: #ffffff;
        color: #475569;
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .social-btn:hover {
        background-color: #008a7a;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(229, 62, 62, 0.3);
    }

    /* Newsletter Input Focus */
    .newsletter-form .form-control:focus {
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.15) !important;
    }
</style>