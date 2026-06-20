<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset(setting('nav_icon')) }}" type="image/x-icon">

    <style>
        /* Theme Variables */
        :root {
            --rr-ff-body: 'Jost', sans-serif;
            --rr-ff-heading: 'Jost', sans-serif;
            --rr-ff-p: 'Jost', sans-serif;
            --rr-fw-normal: normal;
            --rr-fw-thin: 100;
            --rr-fw-elight: 200;
            --rr-fw-light: 300;
            --rr-fw-regular: 400;
            --rr-fw-medium: 500;
            --rr-fw-sbold: 600;
            --rr-fw-bold: 700;
            --rr-fw-ebold: 800;
            --rr-fw-black: 900;
            --rr-fs-body: 16px;
            --rr-fs-p: 16px;
            --rr-fs-h1: 60px;
            --rr-fs-h2: 36px;
            --rr-fs-h3: 20px;
            --rr-fs-h4: 20px;
            --rr-fs-h5: 16px;
            --rr-fs-h6: 14px;
            --rr-color-common-white: #ffffff;
            --rr-color-common-black: #000000;
            --rr-color-common-dark: #141414;
            --rr-color-heading-primary: #141414;
            --rr-color-text-body: #74787C;
            --rr-color-theme-primary: #E53E3E;
            --rr-color-theme-secondary: #885B3A;
            --rr-color-bg-1: #11151C;
            --rr-color-grey-1: #F6F6F7;
            --rr-color-grey-2: #E3E3E3;
            --rr-color-grey-3: #F3F0E8;
            --rr-color-border-1: #E8E8E8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--rr-ff-body);
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--rr-color-bg-1);
        }

        .container {
            width: 900px;
            height: 450px;
            background: var(--rr-color-common-white);
            border-radius: 15px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* LEFT SIDE */
        .left {
            width: 50%;
            position: relative;
            color: var(--rr-color-common-white);
            padding: 60px 40px;
            background: var(--rr-color-theme-primary);
            overflow: hidden;
        }

        /* CURVE SHAPE */
        .left::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--rr-color-theme-secondary);
            top: -50px;
            right: -250px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        /* TEXT */
        .left h1 {
            font-family: var(--rr-ff-heading);
            font-size: var(--rr-fs-h2);
            font-weight: var(--rr-fw-bold);
            z-index: 2;
            position: relative;
            letter-spacing: 1px;
        }

        .left p {
            font-family: var(--rr-ff-p);
            font-size: var(--rr-fs-p);
            font-weight: var(--rr-fw-regular);
            margin-top: 10px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        /* CIRCLES */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
        }

        .circle1 {
            width: 180px;
            height: 180px;
            bottom: -60px;
            left: 30px;
        }

        .circle2 {
            width: 120px;
            height: 120px;
            bottom: 40px;
            left: 150px;
        }

        /* RIGHT SIDE */
        .right {
            width: 50%;
            padding: 60px 40px;
            background: var(--rr-color-common-white);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-family: var(--rr-ff-heading);
            font-size: 28px;
            font-weight: var(--rr-fw-sbold);
            color: var(--rr-color-heading-primary);
            margin-bottom: 10px;
        }

        .right .sub-text {
            color: var(--rr-color-text-body);
            font-size: 15px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .input-box input {
            width: 100%;
            padding: 12px 40px;
            border-radius: 6px;
            border: 1px solid var(--rr-color-border-1);
            font-size: var(--rr-fs-body);
            color: var(--rr-color-heading-primary);
            background: var(--rr-color-common-white);
            transition: border-color 0.3s;
        }

        .input-box input:focus {
            outline: none;
            border-color: var(--rr-color-theme-primary);
        }

        .input-box input::placeholder {
            color: #adb5bd;
            font-weight: var(--rr-fw-light);
        }

        .input-box i {
            position: absolute;
            top: 15px;
            left: 14px;
            color: var(--rr-color-text-body);
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: var(--rr-color-theme-primary);
            color: var(--rr-color-common-white);
            font-size: var(--rr-fs-body);
            font-weight: var(--rr-fw-sbold);
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 15px;
            transition: background 0.3s;
            margin-top: 10px;
        }

        .btn:hover {
            background: var(--rr-color-theme-secondary);
        }

        .footer {
            text-align: center;
            font-size: var(--rr-fs-body);
            color: var(--rr-color-text-body);
            margin-top: 15px;
        }

        .footer a {
            color: var(--rr-color-theme-primary);
            font-weight: var(--rr-fw-sbold);
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Alert styling for success/error messages */
        .alert {
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
            text-align: left;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <h1>RECOVERY</h1>
        <p>Reset your admin password</p>

        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
    </div>

    <div class="right">
        <h2>Forgot Password?</h2>
        <p class="sub-text">Enter the email address associated with your account and we'll send you a link to reset your password.</p>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-box">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autofocus>
            </div>

            <button type="submit" class="btn">Send Reset Link</button>

            <div class="footer">
                Remember your password? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </form>
    </div>

</div>

</body>
</html>