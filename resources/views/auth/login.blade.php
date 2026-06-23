<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset(setting('nav_icon')) }}" type="image/x-icon">

    <style>
        /* Theme Variables */
        :root {
            --rr-ff-body: 'Jost', sans-serif;
            --rr-ff-heading: 'Jost', sans-serif; /* Changed from serif to sans-serif as Jost is a sans font */
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
            --rr-color-theme-primary: #008a7a;
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
            background: var(--rr-color-bg-1); /* Theme Dark Background */
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
            background: var(--rr-color-theme-primary); /* Theme Primary Red */
            overflow: hidden;
        }

        /* CURVE SHAPE */
        .left::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: var(--rr-color-theme-secondary); /* Theme Secondary Brown */
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
            padding: 50px 40px;
            background: var(--rr-color-common-white);
        }

        .right h2 {
            font-family: var(--rr-ff-heading);
            font-size: var(--rr-fs-h2);
            font-weight: var(--rr-fw-sbold);
            color: var(--rr-color-heading-primary);
            margin-bottom: 25px;
        }

        .input-box {
            position: relative;
            margin-bottom: 18px;
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
            color: var(--rr-color-text-body);
            font-weight: var(--rr-fw-light);
        }

        .input-box i {
            position: absolute;
            top: 15px;
            left: 14px;
            color: var(--rr-color-text-body);
            font-size: 14px;
        }

        .show-pass {
            position: absolute;
            right: 14px;
            top: 15px;
            font-size: 12px;
            font-weight: var(--rr-fw-sbold);
            color: var(--rr-color-theme-primary);
            cursor: pointer;
            user-select: none;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: var(--rr-fs-h6);
            color: var(--rr-color-text-body);
            margin-bottom: 20px;
        }

        .options a {
            color: var(--rr-color-theme-primary);
            text-decoration: none;
            font-weight: var(--rr-fw-medium);
        }

        .options a:hover {
            text-decoration: underline;
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
            margin-bottom: 12px;
            transition: background 0.3s;
        }

        .btn:hover {
            background: var(--rr-color-theme-secondary);
        }

        .btn-outline {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--rr-color-border-1);
            color: var(--rr-color-heading-primary);
            font-size: var(--rr-fs-body);
            font-weight: var(--rr-fw-medium);
            background: var(--rr-color-common-white);
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-outline:hover {
            border-color: var(--rr-color-heading-primary);
            background: var(--rr-color-grey-1);
        }

        .footer {
            text-align: center;
            font-size: var(--rr-fs-h6);
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
    </style>
</head>

<body>

<div class="container">

    <div class="left">
        <h1>WELCOME</h1>
        <p>Ecommerce Admin Panel</p>

        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
    </div>

    <div class="right">
        <h2>Sign in</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-box">
                <i class="fa fa-user"></i>
                <input type="text" name="email" placeholder="Email Address" required>
            </div>

            <div class="input-box">
                <i class="fa fa-lock"></i>
                <input type="password" id="pass" name="password" placeholder="Password" required>
                <span class="show-pass" onclick="togglePass()">SHOW</span>
            </div>

            <div class="options">
                <label style="cursor: pointer;"><input type="checkbox" name="remember" style="margin-right: 5px;"> Remember me</label>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>

            <button type="submit" class="btn">Sign in</button>
            <button type="button" class="btn-outline">Sign in with Google</button>

            <div class="footer">
                {{-- Don't have an account? <a href="#">Sign up</a> --}}
            </div>
        </form>
    </div>

</div>

<script>
    function togglePass() {
        let p = document.getElementById("pass");
        let btn = document.querySelector(".show-pass");
        
        if (p.type === "password") {
            p.type = "text";
            btn.textContent = "HIDE";
        } else {
            p.type = "password";
            btn.textContent = "SHOW";
        }
    }
</script>

</body>
</html>