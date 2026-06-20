<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

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
            --rr-color-common-white: #ffffff;
            --rr-color-heading-primary: #141414;
            --rr-color-text-body: #74787C;
            --rr-color-theme-primary: #E53E3E;
            --rr-color-theme-secondary: #885B3A;
            --rr-color-bg-1: #11151C;
            --rr-color-border-1: #E8E8E8;
            --rr-fs-body: 16px;
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
            height: 480px;
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

        .left::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--rr-color-theme-secondary);
            top: -50px;
            right: -250px;
            border-radius: 50%;
        }

        .left h1 {
            font-family: var(--rr-ff-heading);
            font-size: 36px;
            font-weight: 700;
            z-index: 2;
            position: relative;
            letter-spacing: 1px;
        }

        .left p {
            font-size: 16px;
            margin-top: 10px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
        }

        .circle1 { width: 180px; height: 180px; bottom: -60px; left: 30px; }
        .circle2 { width: 120px; height: 120px; bottom: 40px; left: 150px; }

        /* RIGHT SIDE */
        .right {
            width: 50%;
            padding: 50px 40px;
            background: var(--rr-color-common-white);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .right h2 {
            font-size: 28px;
            font-weight: 600;
            color: var(--rr-color-heading-primary);
            margin-bottom: 10px;
        }

        .right .sub-text {
            color: var(--rr-color-text-body);
            font-size: 14px;
            margin-bottom: 20px;
        }

        .input-box {
            position: relative;
            margin-bottom: 15px;
        }

        .input-box input {
            width: 100%;
            padding: 12px 40px;
            border-radius: 6px;
            border: 1px solid var(--rr-color-border-1);
            font-size: var(--rr-fs-body);
            color: var(--rr-color-heading-primary);
            transition: border-color 0.3s;
        }

        .input-box input:focus {
            outline: none;
            border-color: var(--rr-color-theme-primary);
        }

        .input-box input::placeholder {
            color: #adb5bd;
        }

        .input-box i {
            position: absolute;
            top: 15px;
            left: 14px;
            color: var(--rr-color-text-body);
            font-size: 14px;
        }

        .input-box input[readonly] {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: var(--rr-color-theme-primary);
            color: var(--rr-color-common-white);
            font-size: var(--rr-fs-body);
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: var(--rr-color-theme-secondary);
        }

        /* Error Messages */
        .alert-error { 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            padding: 10px; 
            border-radius: 4px; 
            margin-bottom: 15px; 
            font-size: 13px;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <h1>NEW PASSWORD</h1>
        <p>Secure your account with a strong password.</p>

        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
    </div>

    <div class="right">
        <h2>Reset Password</h2>
        <p class="sub-text">Please enter your email and set a new password for your account.</p>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="input-box">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" value="{{ request()->email ?? old('email') }}" required readonly>
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" name="password" placeholder="New Password" required autofocus>
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" name="password_confirmation" placeholder="Confirm New Password" required>
            </div>

            <button type="submit" class="btn">Update Password</button>
        </form>
    </div>

</div>

</body>
</html>