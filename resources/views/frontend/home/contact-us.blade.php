@extends('frontend.layouts.app')
@section('title', 'Contact')

@section('content')

    <section class="contact-section pt-50 pb-100">
        <div class="container">
            <div class="row contact-wrap">
                
                {{-- Contact Form --}}
                <div class="col-lg-8 col-md-12">
                    <div class="blog-contact-form form-2">
                        <div class="request-form">
                            <h2 class="form-title">Get in Touch</h2>
                            
                            <form action="{{ route('contact.store') }}" name="contact-us-form" class="needs-validation" novalidate method="POST">
                                @csrf
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-item">
                                            <h4 class="form-header">Your name <span class="text-danger">*</span></h4>
                                            <input type="text" id="fullname" name="name" class="form-control" placeholder="Enter your full name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-item">
                                            <h4 class="form-header">Email address <span class="text-danger">*</span></h4>
                                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-item">
                                            <h4 class="form-header">Subject <span class="text-danger">*</span></h4>
                                            <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter the subject" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-item message-item">
                                            <h4 class="form-header">Write Your Message <span class="text-danger">*</span></h4>
                                            <textarea id="message" name="message" cols="30" rows="5" class="form-control address" placeholder="How can we help you?" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="submit-btn mt-3">
                                    <button id="submit" class="rr-primary-btn" type="submit">Submit Message</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>

                {{-- Contact Information --}}
                <div class="col-lg-4 col-md-12 mt-5 mt-lg-0">
                    <div class="contact-content">
                        <div class="contact-img">
                            {{-- Image path updated with Laravel asset() --}}
                            <img src="{{ asset('frontend/assets/img/images/contact-img.png') }}" alt="Contact Us" class="img-fluid rounded">
                        </div>
                        
                        <div class="contact-info-box mt-4">
                            <h3 class="title">Unibox BD</h3>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i> 
                                    278/3/A, Sardar Villa, 5th Floor <br> Kataban Dhal, Kataban
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-phone-alt text-primary me-2"></i> 
                                    Phone: <a href="tel:+8801700000000" class="text-decoration-none text-dark">+880 17XX XXXXXX</a>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-envelope text-primary me-2"></i> 
                                    Email: <a href="mailto:uniboxbd4u@gmail.com" class="text-decoration-none text-dark">uniboxbd4u@gmail.com</a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="contact-info-box mt-4">
                            <h3 class="title">Opening Hours</h3>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-clock text-primary me-2"></i> Saturday - Thursday : 9:00 AM - 8:00 PM <br> 
                                    <span class="ms-4 text-danger">Friday Closed</span>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <!-- ./ contact-section -->

    <div class="map-wrapper pb-130">
        <div class="container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d7304.655103904136!2d90.38197834264871!3d23.735696164721446!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1skatabon%20dhal!5e0!3m2!1sen!2sbd!4v1782890174364!5m2!1sen!2sbd" 
                width="100%" 
                height="600" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="strict-origin-when-cross-origin">
            </iframe>
        </div>
    </div>
    <!-- ./ map-wrapper -->
@endsection
