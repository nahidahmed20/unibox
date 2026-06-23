@extends('frontend.layouts.app')

@section('title', 'Login or Sign Up')

@section('content')

<style>
    /* Custom CSS */
    .login-section {
        background-color: #f8f9fc;
        padding: 80px 0;
    }
    .login-box {
        background: #ffffff;
        padding: 50px 40px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        max-width: 500px;
        margin: 0 auto;
    }
    .login-title {
        text-align: center;
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 30px;
        text-transform: uppercase;
    }
    .social-btn-group {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 30px;
    }
    .btn-social {
        flex: 1;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        color: #fff;
        font-size: 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: opacity 0.3s ease;
    }
    .btn-social:hover {
        color: #fff;
        opacity: 0.8;
    }
    .btn-facebook { background-color: #4267B2; }
    .btn-google { background-color: #DB4437; }
    
    .divider-or {
        display: flex;
        align-items: center;
        text-align: center;
        color: #111;
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .divider-or::before, .divider-or::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }
    .divider-or:not(:empty)::before { margin-right: 1em; }
    .divider-or:not(:empty)::after { margin-left: 1em; }

    .custom-input-group {
        display: flex;
        align-items: center;
        border-bottom: 2px solid #8ab4f8; 
        margin-bottom: 30px;
        padding-bottom: 8px;
    }
    .custom-input-group i {
        color: #ccc;
        font-size: 18px;
        margin-right: 15px;
    }
    .custom-input-group input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 16px;
        color: #333;
        background: transparent;
        letter-spacing: 1px;
    }
    .custom-input-group input::placeholder {
        color: #b0b0b0;
        letter-spacing: normal;
    }
    
    .btn-next {
        background-color: #008a7a; 
        color: white;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .btn-next:hover {
        background-color: #2b8b45;
    }

    /* OTP Specific CSS */
    .timer-section {
        font-size: 14px;
        margin-bottom: 20px;
        color: #555;
    }
    #time {
        color: #e74c3c;
        font-weight: bold;
        font-size: 16px;
    }
    .resend-link {
        display: none;
        color: #008a7a;
        font-weight: 600;
        text-decoration: underline;
        cursor: pointer;
    }
    .change-number {
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #888;
        font-size: 13px;
        text-decoration: underline;
    }
</style>

<section class="login-section">
    <div class="container">
        <div class="login-box">
            
            @if(session()->has('otp_login'))
                <h3 class="login-title">VERIFY OTP</h3>
                <p class="text-center mb-4" style="color: #666; font-size: 14px;">
                    An OTP has been sent to <br><strong style="color:#333;">{{ session('otp_login') }}</strong>
                </p>

                <form method="POST" action="{{ route('user.login.verify_otp') }}">
                    @csrf
                    <div class="custom-input-group">
                        <i class="fas fa-unlock-alt"></i>
                        <input type="text" name="otp" placeholder="Enter 4-digit OTP" required maxlength="4" autocomplete="off" autofocus style="letter-spacing: 5px; font-weight: bold; text-align: center;">
                    </div>
                    @error('otp')
                        <div class="text-danger mb-3" style="font-size: 14px; margin-top:-20px; text-align:center;">{{ $message }}</div>
                    @enderror

                    <div class="text-center timer-section">
                        <span id="timer-text">Time remaining: <span id="time">02:00</span></span>
                        
                        <a href="#" class="resend-link" id="resend-btn">Resend OTP</a>
                    </div>

                    <button type="submit" class="btn-next">Verify & Login</button>
                    
                    <a href="{{ route('user.login.cancel_otp') }}" class="change-number">Change Phone / Email</a>
                </form>

                <form id="resend-form" method="POST" action="{{ route('user.login.send_otp') }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="name" value="{{ session('otp_login') }}">
                </form>

            @else
                <h3 class="login-title">LOGIN / SIGN UP</h3>
                
                <div class="social-btn-group">
                    <a href="{{ route('login.facebook') }}" class="btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="{{ route('login.google') }}" class="btn-social btn-google">
                        <i class="fab fa-google"></i> Google
                    </a>
                </div>

                <div class="divider-or">OR</div>

                <form method="POST" action="{{ route('user.login.send_otp') }}">
                    @csrf
                    <div class="custom-input-group">
                        <i class="fas fa-phone-alt"></i> 
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Phone or Email" required autocomplete="off">
                    </div>
                    @error('name')
                        <div class="text-danger mb-3" style="font-size: 14px; margin-top:-20px;">{{ $message }}</div>
                    @enderror

                    <button type="submit" class="btn-next">Next</button>
                </form>
            @endif

        </div>
    </div>
</section>

@endsection
@push('javascript')
<script>
    $(document).ready(function() {
        @if(session()->has('otp_login') && session()->has('otp_expires_at'))
            
            let duration = {{ max(0, session('otp_expires_at') - now()->timestamp) }};
            
            function formatTime(totalSeconds) {
                let minutes = Math.floor(totalSeconds / 60);
                let seconds = totalSeconds % 60;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                return minutes + ":" + seconds;
            }

            if (duration > 0) {
                $('#time').text(formatTime(duration));

                let timerInterval = setInterval(function() {
                    duration--; 

                    if (duration > 0) {
                        $('#time').text(formatTime(duration));
                    } else {
                        clearInterval(timerInterval);
                        $('#timer-text').hide();
                        $('#resend-btn').fadeIn(); 
                    }
                }, 1000);
            } else {
                $('#timer-text').hide();
                $('#resend-btn').show();
            }

            $('#resend-btn').on('click', function(e) {
                e.preventDefault();
                $(this).css('pointer-events', 'none').text('Sending...');
                $('#resend-form').submit();
            });

        @endif
    });
</script>
@endpush