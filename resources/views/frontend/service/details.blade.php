@extends('frontend.layouts.app')
@section('title', $service->title . ' | Unibox')

@section('content')

@push('css')
<style>
    /* Service Details Custom Styling */
    .service-image-wrap img {
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    .service-content h1 {
        font-weight: 800;
        color: var(--rr-color-heading, #212b36);
    }
    .service-content .lead {
        font-size: 18px;
        line-height: 1.8;
    }
    .description-wrapper {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        border: 1px solid var(--rr-color-border-1, #eee);
    }
    .description-content {
        font-size: 16px;
        line-height: 1.9;
        color: var(--rr-color-text-body, #637381);
    }
</style>
@endpush

<section class="shop-section single pt-100 pb-100">
    <div class="container">
        <div class="row align-items-center">
            
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="service-image-wrap text-center">
                    @if($service->image)
                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
                    @elseif($service->icon)
                        <div class="py-5 bg-light rounded" style="border: 2px dashed #ddd;">
                            <i class="{{ $service->icon }}" style="font-size: 120px; color: var(--rr-color-theme-primary);"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="service-content ps-lg-5">
                    <span class="badge mb-3 px-3 py-2 text-uppercase" style="background: rgba(var(--rr-color-theme-primary-rgb, 103, 176, 46), 0.1); color: var(--rr-color-theme-primary);">
                        <i class="fa-solid fa-star me-1"></i> Premium Service
                    </span>
                    
                    <h1 class="mb-4">{{ $service->title }}</h1>
                    
                    <p class="lead text-muted mb-5">
                        {{ $service->short_description }}
                    </p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#full-description" class="btn btn-dark rounded-pill px-4 py-2 fw-bold">
                            Read Full Details <i class="fa-solid fa-arrow-down ms-2"></i>
                        </a>
                        <a href="{{ route('contact.us') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="product-description pb-100" id="full-description">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="description-wrapper p-4 p-md-5">
                    
                    <h3 class="mb-4 fw-bold border-bottom pb-3">Service Overview</h3>
                    
                    <div class="description-content">
                        @if($service->description)
                            {!! $service->description !!}
                        @else
                            <p class="text-center text-muted fst-italic">No detailed description available for this service yet.</p>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

@endsection