@extends('frontend.layouts.app')
@section('title', $blog->title . ' | Unibox')

@section('content')
    <section class="page-header">
        <div class="shape"><img src="{{ asset('frontend/assets/img/shapes/page-header-shape.png') }}" alt="shape"></div>
        <div class="container">
            <div class="page-header-content text-center">
                <h1 class="title">Blog Details</h1>
                <h4 class="sub-title justify-content-center">
                    <span class="home"><a href="{{ route('home') }}"><span>Home</span></a></span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                    <span class="inner"><a href="{{ route('our-blogs') }}"><span>Blogs</span></a></span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                    <span class="inner"><span>{{ \Illuminate\Support\Str::limit($blog->title, 20) }}</span></span>
                </h4>
            </div>
        </div>
    </section>
    <section class="blog-details-section pt-100 pb-100" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row gy-5">
                
                <div class="col-lg-8">
                    <div class="blog-details-wrap bg-white p-4 p-md-5 rounded-4 shadow-sm">
                        
                        <div class="post-thumb mb-4 rounded-3 overflow-hidden">
                            <img src="{{ $blog->image ? asset($blog->image) : asset('frontend/assets/img/blog/default.jpg') }}" alt="{{ $blog->title }}" class="img-fluid w-100" style="max-height: 450px; object-fit: cover;">
                        </div>
                        
                        <div class="post-content">
                            <ul class="post-meta list-unstyled d-flex flex-wrap gap-3 mb-3 text-muted small">
                                <li>
                                    <i class="fa-solid fa-user text-primary-theme me-1"></i>
                                    By {{ $blog->user->name ?? 'Admin' }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-calendar-days text-primary-theme me-1"></i>
                                    {{ \Carbon\Carbon::parse($blog->date ?? $blog->created_at)->format('d F, Y') }}
                                </li>
                                <li>
                                    <i class="fa-solid fa-folder-open text-primary-theme me-1"></i>
                                    {{ $blog->category->name ?? 'Uncategorized' }}
                                </li>
                            </ul>
                            
                            <h2 class="fw-bold mb-4" style="color: #2b3445; line-height: 1.4;">{{ $blog->title }}</h2>
                            
                            <div class="blog-description text-muted" style="line-height: 1.8; font-size: 16px;">
                                {!! $blog->description !!}
                            </div>
                            
                            <div class="post-share d-flex align-items-center gap-3 mt-5 pt-4 border-top">
                                <h6 class="mb-0 fw-bold">Share This Post:</h6>
                                <div class="social-links d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" target="_blank" class="share-btn bg-light text-dark px-3 py-2 rounded-circle"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="share-btn bg-light text-dark px-3 py-2 rounded-circle"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(Request::fullUrl()) }}&title={{ urlencode($blog->title) }}" target="_blank" class="share-btn bg-light text-dark px-3 py-2 rounded-circle"><i class="fa-brands fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="sidebar-widget-wrap">
                        
                        <div class="widget-box bg-white p-4 rounded-4 shadow-sm mb-4">
                            <h4 class="widget-title fw-bold mb-3 fs-5">Search</h4>
                            <form action="{{ route('our-blogs') }}" method="GET" class="position-relative">
                                <input type="text" name="search" class="form-control bg-light border-0 py-3 ps-3 pe-5 rounded-3" placeholder="Search here...">
                                <button type="submit" class="position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-primary-theme pe-3">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>

                        @if($categories->count() > 0)
                        <div class="widget-box bg-white p-4 rounded-4 shadow-sm mb-4">
                            <h4 class="widget-title fw-bold mb-3 fs-5">Categories</h4>
                            <ul class="list-unstyled mb-0">
                                @foreach($categories as $category)
                                <li class="mb-2 pb-2 border-bottom border-light">
                                    <a href="{{ route('our-blogs', ['category' => $category->slug]) }}" class="d-flex justify-content-between align-items-center text-decoration-none text-muted cat-link">
                                        <span>{{ $category->name }}</span>
                                        <span class="badge bg-light text-dark rounded-pill">{{ $category->blogs_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if($recent_blogs->count() > 0)
                        <div class="widget-box bg-white p-4 rounded-4 shadow-sm">
                            <h4 class="widget-title fw-bold mb-4 fs-5">Recent Posts</h4>
                            <div class="recent-posts-list">
                                @foreach($recent_blogs as $recent)
                                <div class="recent-post-item d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-light">
                                    <div class="thumb" style="width: 80px; height: 80px; flex-shrink: 0;">
                                        <a href="{{ route('blog.details', $recent->slug) }}">
                                            <img src="{{ $recent->image ? asset($recent->image) : asset('frontend/assets/img/blog/default.jpg') }}" alt="{{ $recent->title }}" class="img-fluid rounded-3 w-100 h-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="content">
                                        <span class="date d-block text-muted small mb-1"><i class="fa-regular fa-calendar-days me-1"></i> {{ \Carbon\Carbon::parse($recent->date ?? $recent->created_at)->format('M d, Y') }}</span>
                                        <h6 class="mb-0 fw-bold" style="font-size: 14px; line-height: 1.4;">
                                            <a href="{{ route('blog.details', $recent->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                {{ \Illuminate\Support\Str::limit($recent->title, 40) }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </section>

    <style>
        .text-primary-theme { color: #E53E3E !important; }
        .hover-primary:hover { color: #E53E3E !important; transition: 0.3s; }
        .cat-link:hover { color: #E53E3E !important; padding-left: 5px; transition: 0.3s; }
        .share-btn { transition: all 0.3s ease; }
        .share-btn:hover { background-color: #E53E3E !important; color: white !important; transform: translateY(-3px); }
        .blog-description img { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
    </style>
@endsection