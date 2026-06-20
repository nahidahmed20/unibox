@extends('frontend.layouts.app')

@section('title', 'Register')

@section('content')

<style>
    .form-control::placeholder {
        color: #adb5bd !important; 
        opacity: 1 !important; 
        font-weight: 400 !important; 
        font-size: 15px; 
    }
    
    .form-control:focus::placeholder {
        color: #ced4da !important; 
    }
</style>

<section class="login-area pt-50 pb-100">
    <div class="container">

        <div class="login-wrap text-center">
            <h3 class="title">Create Your Account</h3>

            <form class="login-form" method="POST" action="{{ route('user.register.store') }}">
                @csrf

                {{-- FULL NAME --}}
                <div class="form-item">
                    <h4 class="form-header">Full Name</h4>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Full Name">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="form-item">
                    <h4 class="form-header">Email Address</h4>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="Email Address">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PHONE --}}
                <div class="form-item">
                    <h4 class="form-header">Phone Number</h4>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Phone Number">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="form-item">
                    <h4 class="form-header">Password</h4>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- CONFIRM PASSWORD --}}
                <div class="form-item">
                    <h4 class="form-header">Confirm Password</h4>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                </div>

                {{-- SUBMIT --}}
                <div class="submit-btn mt-3">
                    <button class="rr-primary-btn">Create Account</button>
                </div>

                <div class="login-btn-wrap mt-3">
                    <span>Already have an account?</span>
                    <a class="log-in" href="{{ route('user.login') }}">Login here</a>
                </div>

            </form>
        </div>

    </div>
</section>
@endsection