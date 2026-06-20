@extends('frontend.layouts.app')

@section('title', 'Login')

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
            <h3 class="title">Login Into Your Account</h3>
            <form class="login-form" method="POST" action="{{ route('user.login.store') }}">
                @csrf
                <div class="form-item">
                    <h4 class="form-header">Phone or email address</h4>
                    <input type="text" id="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Phone or email address" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-item">
                    <h4 class="form-header">Password*</h4>
                    <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}" placeholder="Password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-item">
                    <div class="checkbox-wrap">
                        <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike">
                        <label for="vehicle1"> Remember me</label><br>
                    </div>
                </div>
                <div class="submit-btn">
                    <button class="rr-primary-btn">Login Account</button>
                </div>
                <a href="#" class="forgot">Lost your password?</a>
                <div class="login-btn-wrap">
                    <a href="#" class="forgot">Don't have an account?</a>
                    <a class="log-in" href="{{ route('user.register') }}">Register here</a>
                </div>
            </form>
        </div>
        
    </div>
</section>
@endsection
