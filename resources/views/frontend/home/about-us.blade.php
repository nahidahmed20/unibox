@extends('frontend.layouts.app')
@section('title', 'About Us')

@section('content')
    <section class="page-header">
        <div class="shape"><img src="{{ asset('frontend/assets/img/shapes/page-header-shape.png') }}" alt="shape"></div>
        <div class="container">
            <div class="page-header-content">
                <h1 class="title">About Us</h1>
                <h4 class="sub-title">
                    <span class="home">
                        <a href="{{ route('home') }}">
                            <span>Home</span>
                        </a>
                    </span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                    <span class="inner">
                        <span>About Us</span>
                    </span>
                </h4>
            </div>
        </div>
    </section>
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
    @if(isset($teams) && count($teams) > 0)
    <section class="team-section pt-100 pb-100">
        <div class="container">
            <div class="section-heading text-center">
                <h2 class="section-title">Meet With Team</h2>
            </div>
            <div class="row gy-lg-0 gy-4">
                @foreach($teams as $team)
                <div class="col-lg-3 col-md-6">
                    <div class="team-item">
                        <div class="team-thumb">
                            <img src="{{ asset($team->image) }}" alt="{{ $team->name }}">
                        </div>
                        <div class="team-content text-center">
                            <span>{{ $team->designation }}</span>
                            <h3 class="title">{{ $team->name }}</h3>
                            <ul class="team-social">
                                @if($team->facebook) <li><a href="{{ $team->facebook }}" target="_blank">FB</a></li> @endif
                                @if($team->twitter) <li><a href="{{ $team->twitter }}" target="_blank">TW</a></li> @endif
                                @if($team->instagram) <li><a href="{{ $team->instagram }}" target="_blank">IG</a></li> @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @if(isset($services) && count($services) > 0)
    <section class="service-section pt-100 pb-100">
        <div class="container">
            <div class="row gy-lg-0 gy-4">
                @foreach($services as $key => $service)
                <div class="col-xl-4 col-lg-6 col-md-12">
                    <div class="service-item {{ $loop->even ? 'item-2' : '' }}">
                        
                        @if($loop->even)
                            <div class="service-img top-area">
                                <img src="{{ asset($service->image) }}" alt="{{ $service->title }}">
                            </div>
                            <div class="service-content text-center">
                                <span class="number">0{{ $key + 1 }}</span>
                                <h3 class="title">{{ $service->title }}</h3>
                                <p>{{ $service->short_description ?? $service->description }}</p>
                            </div>
                        @else
                            <div class="service-content top-area text-center">
                                <span class="number">0{{ $key + 1 }}</span>
                                <h3 class="title">{{ $service->title }}</h3>
                                <p>{{ $service->short_description ?? $service->description }}</p>
                            </div>
                            <div class="service-img">
                                <img src="{{ asset($service->image) }}" alt="{{ $service->title }}">
                            </div>
                        @endif

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @if(isset($testimonials) && count($testimonials) > 0)
    <section class="testimonial-section about-testi pt-100 pb-100">
        <div class="container">
            <div class="section-heading white-content text-center">
                <h2 class="section-title">Happy Customers</h2>
            </div>
            <div class="testimonial-carousel swiper">
                <div class="swiper-wrapper">
                    
                    @foreach($testimonials as $testimonial)
                    <div class="swiper-slide">
                        <div class="testi-item-wrap">
                            <div class="testi-item testi-item-2">
                                <div class="testi-top-content">
                                    <h4 class="title">{{ $testimonial->title ?? 'Product Quality' }}</h4>
                                    <ul class="review">
                                        @for($i = 1; $i <= 5; $i++)
                                            <li><i class="fa-solid fa-star" style="{{ $i <= $testimonial->rating ? 'color: #ffc107;' : 'color: #ddd;' }}"></i></li>
                                        @endfor
                                    </ul>
                                </div>
                                <p>“{{ $testimonial->review }}“</p>
                                <div class="shape"><img src="{{ asset('frontend/assets/img/shapes/testi-shape.png') }}" alt="shape"></div>
                            </div>
                            <div class="testi-author">
                                <img src="{{ asset($testimonial->image) }}" alt="{{ $testimonial->name }}">
                                <h4 class="name">{{ $testimonial->name }} <span>{{ $testimonial->designation }}</span></h4>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </section>
    @endif
    @if(isset($clients) && count($clients) > 0)
    <div class="sponsor-section pt-100 pb-100">
        <div class="container">
            <div class="row sponsor-wrap">
                @foreach($clients as $client)
                <div class="sponsor-item">
                    <a href="{{ $client->url ?? 'javascript:void(0)' }}" target="{{ $client->url ? '_blank' : '_self' }}">
                        <img src="{{ asset($client->logo) }}" alt="sponsor">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @endsection

    @push('javascript')
        <script>
            $(document).ready(function () {
                new Swiper(".testimonial-carousel", {
                    slidesPerView: 1,      
                    spaceBetween: 20,     
                    loop: true,           
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1200: {
                            slidesPerView: 3, 
                            spaceBetween: 30,
                        }
                    }
                });
            });
        </script>
    @endpush