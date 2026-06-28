@extends('frontend.layouts.app')

@section('title', 'Login or Sign Up')

@section('content')

<style>

    /* Custom CSS */
    .login-section {
        background-color: #f4f7fb;
        padding: 100px 0;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
    }
    
    .login-box {
        background: #ffffff;
        padding: 50px 40px;
        border-radius: 20px; /* Modern rounded corners */
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05); /* Softer, wider shadow */
        max-width: 480px;
        margin: 0 auto;
        animation: slideUpFade 0.6s ease-out; /* Entry animation */
    }

    @keyframes slideUpFade {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .login-title {
        text-align: center;
        font-size: 22px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 30px;
        letter-spacing: 0.5px;
    }

    /* Social Buttons */
    .social-btn-group {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 30px;
    }
    .btn-social {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        text-align: center;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-social:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        color: #fff;
    }
    .btn-facebook { background-color: #1877F2; }
    .btn-google { background-color: #EA4335; }
    
    /* Divider */
    .divider-or {
        display: flex;
        align-items: center;
        text-align: center;
        color: #888;
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 30px;
    }
    .divider-or::before, .divider-or::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e5e7eb;
    }
    .divider-or:not(:empty)::before { margin-right: 15px; }
    .divider-or:not(:empty)::after { margin-left: 15px; }

    /* Input Groups */
    .custom-input-group {
        display: flex;
        align-items: center;
        background: #f9fafb;
        border: 1.5px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px 20px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    .custom-input-group:focus-within {
        border-color: #008a7a;
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(0, 138, 122, 0.1); /* Modern focus ring */
    }
    .custom-input-group i {
        color: #9ca3af;
        font-size: 18px;
        margin-right: 15px;
        transition: color 0.3s;
    }
    .custom-input-group:focus-within i {
        color: #008a7a;
    }
    .custom-input-group input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 15px;
        color: #1f2937;
        background: transparent;
        font-family: inherit;
    }
    .custom-input-group input::placeholder {
        color: #9ca3af;
    }
    
    /* Main Button */
    .btn-next {
        background: linear-gradient(135deg, #008a7a 0%, #006b5e 100%);
        color: white;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 138, 122, 0.2);
    }
    .btn-next:hover {
        box-shadow: 0 6px 20px rgba(0, 138, 122, 0.3);
    }

    /* OTP Specific CSS */
    .otp-input {
        letter-spacing: 8px !important;
        font-weight: 700 !important;
        text-align: center !important;
        font-size: 20px !important;
        color: #008a7a !important;
    }
    .timer-section {
        font-size: 14px;
        margin-bottom: 25px;
        color: #6b7280;
        background: #f3f4f6;
        padding: 10px;
        border-radius: 8px;
    }
    #time {
        color: #ef4444;
        font-weight: 600;
        font-size: 15px;
        margin-left: 5px;
    }
    .resend-link {
        display: none;
        color: #008a7a;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.3s;
    }
    .resend-link:hover {
        color: #006b5e;
        text-decoration: underline;
    }
    .change-number {
        display: inline-block;
        text-align: center;
        width: 100%;
        margin-top: 20px;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s;
    }
    .change-number:hover {
        color: #1f2937;
        text-decoration: underline;
    }
    .custom-input-group .otp-input {
        font-size: 28px !important; 
        letter-spacing: 20px !important; 
        height: 35px;
    }
</style>

<section class="login-section">
    <div class="container">
        <div class="login-box">
            
            @if(session()->has('otp_login'))
                <h3 class="login-title">VERIFY OTP</h3>
                <p class="text-center mb-4" style="color: #6b7280; font-size: 14px; line-height: 1.6;">
                    We've sent a 4-digit code to <br>
                    <strong style="color:#111827; font-size: 15px;">{{ session('otp_login') }}</strong>
                </p>

                <form method="POST" action="{{ route('user.login.verify_otp') }}">
                    @csrf
                    @if(session('error'))
                        <div class="alert alert-danger" style="color: #ef4444; text-align: center; margin-bottom: 20px; font-weight: 500;">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif
                    <div class="custom-input-group" style="padding: 10px 20px;">
                        <input type="number" name="otp" class="otp-input" placeholder="••••" required maxlength="4" inputmode="numeric" pattern="[0-9]*"  autocomplete="one-time-code" autofocus>
                    </div>
                    @error('otp')
                        <div class="text-danger mb-3" style="font-size: 13px; margin-top:-15px; text-align:center; font-weight: 500;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="text-center timer-section">
                        <span id="timer-text">Code expires in: <span id="time">02:00</span></span>
                        <a href="#" class="resend-link" id="resend-btn">Resend Code</a>
                    </div>

                    <button type="submit" class="btn-next">Verify & Login</button>
                    
                    <a href="{{ route('user.login.cancel_otp') }}" class="change-number">
                        <i class="fas fa-edit"></i> Change Phone / Email
                    </a>
                </form>

                <form id="resend-form" method="POST" action="{{ route('user.login.send_otp') }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="name" value="{{ session('otp_login') }}">
                </form>

            @else
                <h3 class="login-title">Welcome Back</h3>
                
                <div class="social-btn-group">
                    <a href="{{ route('login.facebook') }}" class="btn-social btn-facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="{{ route('login.google') }}" class="btn-social btn-google">
                        <i class="fab fa-google"></i> Google
                    </a>
                </div>

                <div class="divider-or">or continue with</div>

                <form method="POST" action="{{ route('user.login.send_otp') }}">
                    @csrf
                    <div class="custom-input-group">
                        <i class="fas fa-envelope"></i> 
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Phone Number or Email" required autocomplete="off">
                    </div>
                    @error('name')
                        <div class="text-danger mb-3" style="font-size: 13px; margin-top:-15px; font-weight: 500;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <button type="submit" class="btn-next">Continue <i class="fas fa-arrow-right ml-2"></i></button>
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
                        $('.timer-section').css('background', 'transparent'); // Removes background when timer is done
                    }
                }, 1000);
            } else {
                $('#timer-text').hide();
                $('#resend-btn').show();
                $('.timer-section').css('background', 'transparent');
            }

            $('#resend-btn').on('click', function(e) {
                e.preventDefault();
                $(this).css('pointer-events', 'none').html('<i class="fas fa-spinner fa-spin"></i> Sending...');
                $('#resend-form').submit();
            });

        @endif
    });

    $('input[name="otp"]').on('input', function() {
        if ($(this).val().length === 4) {
            $(this).closest('form').submit();
        }
    });
</script>
@endpush