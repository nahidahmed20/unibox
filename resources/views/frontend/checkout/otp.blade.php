@extends('frontend.layouts.app')

@section('title', 'OTP | Unibox')

@push('css')
    <style>
        .card {
            border-radius: 20px;
        }

        .form-control {
            border-radius: 12px;
            height: 55px;
        }

        .btn-submit {
            border-radius: 12px;
            transition: .3s;
            background: #E53E3E;
            color: #fff;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            color: #fff;
            background: #C53030;
        }

        #timer {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }

        #resendOtp.disabled {
            pointer-events: none;
            opacity: .5;
        }
    </style>
@endpush

@section('content')

    <div class="container pt-50 pb-50">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">

                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold mb-2">
                                OTP Verification
                            </h3>

                            <p class="text-muted mb-0">
                                Please enter the OTP sent to your mobile number
                                to complete your order.
                            </p>

                        </div>

                        <form action="{{ route('checkout.otp.verify') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Enter OTP
                                </label>
                                <input type="text" name="otp" class="form-control form-control-lg text-center"
                                    placeholder="Enter 6 Digit OTP" maxlength="6" autocomplete="off">
                                @error('otp')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
                            <button type="submit" id="verifyBtn" class="btn btn-submit w-100 py-3 fw-bold">
                                <i class="fa-solid fa-check me-2"></i>
                                Verify OTP
                            </button>
                        </form>
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Didn't receive the OTP?
                            </small>
                            <div class="mt-2">
                                <span id="timer">
                                    02:00
                                </span>
                            </div>

                            <div class="mt-2">
                                <a href="javascript:void(0)" id="resendOtp"
                                    class="text-decoration-none fw-semibold disabled">
                                    Resend OTP
                                </a>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('javascript')
<script>
    $(document).ready(function () {
        let timer;
        startTimer();
        function startTimer(expireTime = null) {
            clearInterval(timer);
            if (expireTime == null) {
                expireTime = {{ \Carbon\Carbon::parse(session('checkout_otp_expire'))->timestamp * 1000 }};
            }
            $('#verifyBtn')
                .prop('disabled', false)
                .html('<i class="fa-solid fa-check me-2"></i> Verify OTP');
            $('input[name="otp"]').prop('disabled', false);
            $('#resendOtp').addClass('disabled');
            timer = setInterval(function () {
                let now = new Date().getTime();
                let distance = expireTime - now;
                if (distance <= 0) {
                    clearInterval(timer);
                    $('#timer').text('OTP Expired');
                    $('#resendOtp').removeClass('disabled');
                    $('#verifyBtn')
                        .prop('disabled', true)
                        .html('OTP Expired');
                    $('input[name="otp"]')
                        .prop('disabled', true);
                    return;
                }
                let minutes = Math.floor(distance / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                $('#timer').text(
                    String(minutes).padStart(2, '0') +
                    ':' +
                    String(seconds).padStart(2, '0')
                );
            }, 1000);
        }

        $('#resendOtp').click(function (e) {
            e.preventDefault();
            if ($(this).hasClass('disabled')) {
                return false;
            }
            $.ajax({
                url: "{{ route('checkout.otp.resend') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function () {
                    $('#resendOtp').addClass('disabled');
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    startTimer(response.expire_time);
                },

                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong.'
                    });
                    $('#resendOtp').removeClass('disabled');
                }
            });
        });
    });
</script>
@endpush
