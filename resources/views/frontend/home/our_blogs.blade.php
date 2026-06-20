@extends('frontend.layouts.app')
@section('title', 'Our Blogs | Unibox')

@section('content')
    <section class="page-header">
        <div class="shape"><img src="{{ asset('frontend/assets/img/shapes/page-header-shape.png') }}" alt="shape"></div>
        <div class="container">
            <div class="page-header-content">
                <h1 class="title">Our Blogs</h1>
                <h4 class="sub-title">
                    <span class="home">
                        <a href="{{ route('home') }}">
                            <span>Home</span>
                        </a>
                    </span>
                    <span class="icon"><i class="fa-solid fa-angle-right"></i></span>
                    <span class="inner">
                        <span>Blogs</span>
                    </span>
                </h4>
            </div>
        </div>
    </section>
    <section class="blog-section pt-130 pb-130">
        <div class="container">
            <div class="row gy-4">
                
                @forelse($blogs as $blog)
                <div class="col-lg-4 col-md-6">
                    <div class="post-card">
                        <div class="post-thumb">
                            <a href="{{ route('blog.details', $blog->slug ?? '#') }}">
                                <img src="{{ $blog->image ? asset($blog->image) : asset('frontend/assets/img/blog/default.jpg') }}" alt="{{ $blog->title }}" style="width: 100%; height: 250px; object-fit: cover;">
                            </a>
                        </div>
                        <div class="post-content-wrap">
                            <div class="post-content">
                                <ul class="post-meta">
                                    <li>
                                        <i class="fa-sharp fa-solid fa-calendar-days"></i>
                                        {{ \Carbon\Carbon::parse($blog->date ?? $blog->created_at)->format('d M, Y') }}
                                    </li>
                                    <li>
                                        <i class="fa-regular fa-folder-open"></i>
                                        {{ $blog->category->name ?? 'Uncategorized' }}
                                    </li>
                                </ul>
                                <h3 class="title">
                                    <a href="{{ route('blog.details', $blog->slug ?? '#') }}">
                                        {{ \Illuminate\Support\Str::limit($blog->title, 50) }}
                                    </a>
                                </h3>
                            </div>
                            <div class="post-bottom">
                                <a href="{{ route('blog.details', $blog->slug ?? '#') }}" class="read-more">Read More<i class="fa-regular fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">No blogs found!</h3>
                </div>
                @endforelse

            </div>

            @if($blogs->hasPages())
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $blogs->links('pagination::bootstrap-5') }}
                </div>
            </div>
            @endif

        </div>
    </section>
@endsection