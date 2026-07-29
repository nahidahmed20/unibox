<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset(setting('nav_icon')) }}" type="image/x-icon">

    <style>
        :root {
            --ff-body: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --ff-display: 'Space Grotesk', 'Inter', sans-serif;

            /* Dark showcase side */
            --dark-1: #071711;
            --dark-2: #0C2A21;
            --dark-3: #123D2F;
            --lime: #A6EE7C;
            --gold: #FFC168;
            --glass-bg: rgba(255, 255, 255, 0.07);
            --glass-bg-strong: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.14);
            --on-dark: #F4F8F6;
            --on-dark-muted: rgba(244, 248, 246, 0.62);

            /* Light auth side */
            --bg-light: #FFFFFF;
            --text-primary: #14171A;
            --text-secondary: #6B7177;
            --text-muted: #9297A0;
            --border: #E4E5E7;
            --border-strong: #CBCDD1;

            --ink: #14171A;
            --ink-hover: #2B2E31;
            --brand-grad: linear-gradient(135deg, #14C08C 0%, #04794F 100%);
            --brand-glow: rgba(16, 185, 129, 0.18);

            --radius-lg: 18px;
            --radius-md: 12px;
            --radius-sm: 9px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--ff-body);
        }

        html, body { height: 100%; }

        body {
            min-height: 100vh;
            color: var(--text-primary);
            background: var(--bg-light);
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ============ LEFT: SHOWCASE ============ */
        .showcase {
            position: relative;
            flex: 1.15;
            overflow: hidden;
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(160deg, var(--dark-1) 0%, var(--dark-2) 52%, var(--dark-3) 100%);
            color: var(--on-dark);
        }

        .showcase-grid {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.07) 1px, transparent 1px);
            background-size: 24px 24px;
            mask-image: radial-gradient(ellipse 80% 70% at 30% 20%, black 40%, transparent 90%);
            opacity: 0.7;
        }

        .glow {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            pointer-events: none;
        }

        .glow-lime {
            width: 420px; height: 420px;
            background: rgba(166, 238, 124, 0.22);
            top: -120px; left: -80px;
        }

        .glow-gold {
            width: 380px; height: 380px;
            background: rgba(255, 193, 104, 0.16);
            bottom: -140px; right: -100px;
        }

        .showcase-top,
        .showcase-mid,
        .dashboard-stack {
            position: relative;
            z-index: 1;
        }

        .brand-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mini-mark {
            position: relative;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(155deg, #1FD494 0%, #0B7A54 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 128, 96, 0.35);
            flex-shrink: 0;
        }

        .mini-mark svg { width: 17px; height: 17px; }

        .mini-mark-ring {
            position: absolute;
            inset: -6px;
            border-radius: 13px;
            border: 1.5px solid var(--lime);
            opacity: 0;
            animation: ring-pulse 1.3s ease-out 0.4s 1;
        }

        @keyframes ring-pulse {
            0%   { opacity: 0.6; transform: scale(0.9); }
            100% { opacity: 0; transform: scale(1.4); }
        }

        .brand-name {
            font-family: var(--ff-display);
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.01em;
        }

        .showcase-mid { max-width: 460px; }

        .headline {
            font-family: var(--ff-display);
            font-weight: 700;
            font-size: clamp(30px, 3.4vw, 42px);
            line-height: 1.12;
            letter-spacing: -0.01em;
            margin-top: 18px;
        }

        .headline-accent {
            background: linear-gradient(100deg, var(--lime), #6FE3B4 60%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .showcase-sub {
            margin-top: 16px;
            font-size: 15.5px;
            line-height: 1.55;
            color: var(--on-dark-muted);
            max-width: 380px;
        }

        /* ---- Floating glass dashboard cards ---- */
        .dashboard-stack {
            height: 210px;
            margin-top: 12px;
        }

        .float-card {
            position: absolute;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.28);
            opacity: 0;
            transform: translateY(22px);
            animation: card-in 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes card-in {
            to { opacity: 1; transform: translateY(0); }
        }

        .card-stat {
            top: 0;
            left: 0;
            width: 190px;
            padding: 16px 18px;
            transform: translateY(22px) rotate(-2.5deg);
            animation-delay: 0.15s;
        }
        .card-stat.settled { transform: rotate(-2.5deg); }

        .stat-label {
            display: block;
            font-size: 12px;
            color: var(--on-dark-muted);
            margin-bottom: 6px;
        }

        .stat-value {
            display: block;
            font-family: var(--ff-display);
            font-weight: 700;
            font-size: 24px;
            letter-spacing: -0.01em;
        }

        .stat-delta {
            display: inline-block;
            margin-top: 8px;
            font-size: 12px;
            font-weight: 600;
            color: var(--dark-1);
            background: var(--lime);
            padding: 3px 8px;
            border-radius: 20px;
        }

        .card-chart {
            top: 28px;
            right: 0;
            width: 178px;
            padding: 14px 16px 10px;
            animation-delay: 0.32s;
        }

        .chart-label {
            display: block;
            font-size: 11.5px;
            color: var(--on-dark-muted);
            margin-bottom: 6px;
        }

        .sparkline { width: 100%; height: 46px; overflow: visible; }

        .spark-fill {
            fill: url(#sparkGradient);
            opacity: 0.9;
        }

        .spark-path {
            fill: none;
            stroke: var(--lime);
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .card-order {
            bottom: 0;
            left: 46px;
            width: 254px;
            padding: 13px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation-delay: 0.52s;
            background: var(--glass-bg-strong);
        }

        .order-icon {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(255, 193, 104, 0.18);
            border: 1px solid rgba(255, 193, 104, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .order-icon svg { width: 16px; height: 16px; }

        .order-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
            flex: 1;
            min-width: 0;
        }

        .order-title { font-size: 13px; font-weight: 600; }
        .order-sub { font-size: 12px; color: var(--on-dark-muted); }
        .order-amount { font-family: var(--ff-display); font-weight: 700; font-size: 14px; color: var(--gold); }

        /* ============ RIGHT: AUTH ============ */
        .auth {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--bg-light);
        }

        .mobile-banner { display: none; }

        .auth-card { width: 100%; max-width: 360px; }

        .heading {
            font-family: var(--ff-display);
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.01em;
            color: var(--text-primary);
        }

        .subheading {
            margin-top: 6px;
            font-size: 14.5px;
            color: var(--text-secondary);
            margin-bottom: 30px;
        }

        .field { width: 100%; margin-bottom: 16px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 6px;
        }

        .field-control { position: relative; }

        .field-control input {
            width: 100%;
            padding: 12px 14px;
            font-size: 15px;
            color: var(--text-primary);
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .field-control input::placeholder { color: var(--text-muted); }
        .field-control input:hover { border-color: var(--border-strong); }

        .field-control input:focus {
            border-color: #0B9A6C;
            box-shadow: 0 0 0 3.5px var(--brand-glow);
        }

        .field-control.has-toggle input { padding-right: 60px; }

        .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #0B9A6C;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 2px;
        }

        .toggle-pass:hover { color: var(--ink); }

        .row-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 4px 0 22px;
            font-size: 13.5px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            user-select: none;
        }

        .remember input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: var(--ink);
            cursor: pointer;
        }

        .row-options a {
            color: var(--text-secondary);
            font-weight: 500;
            text-decoration: none;
            border-bottom: 1px solid transparent;
        }
        .row-options a:hover { color: var(--text-primary); border-bottom-color: var(--text-primary); }

        button.btn-primary {
            width: 100%;
            padding: 13px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background: var(--brand-grad);
            border: none;
            border-radius: var(--radius-sm);
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(4, 121, 79, 0.28);
            transition: filter 0.15s ease, transform 0.05s ease;
        }
        button.btn-primary:hover { filter: brightness(1.06); }
        button.btn-primary:active { transform: translateY(1px); }

        .divider {
            display: flex; align-items: center; gap: 12px;
            margin: 20px 0;
            color: var(--text-muted);
            font-size: 12.5px; font-weight: 500;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: var(--border); }

        button.btn-google {
            width: 100%;
            padding: 11.5px;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--text-primary);
            background: var(--bg-light);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        button.btn-google:hover { background: #FAFAFA; border-color: var(--border-strong); }

        .page-footer {
            margin-top: 26px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }
        .page-footer a { color: var(--text-secondary); font-weight: 500; text-decoration: none; border-bottom: 1px solid transparent; }
        .page-footer a:hover { color: var(--text-primary); border-bottom-color: var(--text-primary); }

        button:focus-visible, a:focus-visible, input:focus-visible {
            outline: 2px solid #0B9A6C;
            outline-offset: 2px;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 980px) {
            .showcase { display: none; }
            .mobile-banner {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 34px;
            }
            .mobile-banner .brand-name { color: var(--text-primary); font-size: 15px; }
            .auth { padding: 40px 24px; }
        }

        @media (max-width: 400px) {
            .auth { padding: 32px 18px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .mini-mark-ring { display: none; }
            .float-card { animation: none; opacity: 1; transform: none !important; }
            .spark-path { stroke-dashoffset: 0 !important; transition: none !important; }
        }
    </style>
</head>

<body>

<div class="layout">

    <!-- ============ LEFT: LIVE DASHBOARD SHOWCASE ============ -->
    <aside class="showcase">
        <div class="showcase-grid"></div>
        <div class="glow glow-lime"></div>
        <div class="glow glow-gold"></div>

        <div class="showcase-top">
            <div class="brand-row">
                <div class="mini-mark">
                    <div class="mini-mark-ring"></div>
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 8H18L17.2 19.5C17.14 20.33 16.43 21 15.6 21H8.4C7.57 21 6.86 20.33 6.8 19.5L6 8Z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9 8V6.5C9 4.567 10.567 3 12.5 3C14.433 3 16 4.567 16 6.5V8" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="brand-name">Storefront Admin</span>
            </div>
        </div>

        <div class="showcase-mid">
            <h2 class="headline">Every order,<br>every insight,<br><span class="headline-accent">one dashboard.</span></h2>
            <p class="showcase-sub">Track sales, fulfill orders, and grow your store — all from a single command center.</p>
        </div>

        <div class="dashboard-stack">
            <div class="float-card card-stat">
                <span class="stat-label">Total revenue</span>
                <span class="stat-value" id="statValue">$0</span>
                <span class="stat-delta">▲ 18.2%</span>
            </div>

            <div class="float-card card-chart">
                <span class="chart-label">Last 7 days</span>
                <svg class="sparkline" viewBox="0 0 160 50" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="sparkGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#A6EE7C" stop-opacity="0.45"/>
                            <stop offset="100%" stop-color="#A6EE7C" stop-opacity="0"/>
                        </linearGradient>
                    </defs>
                    <path class="spark-fill" d="M2,38 C18,34 26,16 40,20 C54,24 62,8 78,12 C94,16 100,30 118,20 C134,12 144,16 158,6 L158,50 L2,50 Z"/>
                    <path class="spark-path" id="sparkPath" d="M2,38 C18,34 26,16 40,20 C54,24 62,8 78,12 C94,16 100,30 118,20 C134,12 144,16 158,6"/>
                </svg>
            </div>

            <div class="float-card card-order">
                <div class="order-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 8H18L17.2 19.5C17.14 20.33 16.43 21 15.6 21H8.4C7.57 21 6.86 20.33 6.8 19.5L6 8Z" stroke="#FFC168" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M9 8V6.5C9 4.567 10.567 3 12.5 3C14.433 3 16 4.567 16 6.5V8" stroke="#FFC168" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="order-text">
                    <span class="order-title">New order received</span>
                    <span class="order-sub">#4821 · 2 items</span>
                </div>
                <span class="order-amount">$86.00</span>
            </div>
        </div>
    </aside>

    <!-- ============ RIGHT: LOGIN FORM ============ -->
    <main class="auth">
        <div class="mobile-banner">
            <div class="mini-mark">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 8H18L17.2 19.5C17.14 20.33 16.43 21 15.6 21H8.4C7.57 21 6.86 20.33 6.8 19.5L6 8Z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M9 8V6.5C9 4.567 10.567 3 12.5 3C14.433 3 16 4.567 16 6.5V8" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="brand-name">Storefront Admin</span>
        </div>

        <div class="auth-card">
            <h1 class="heading">Welcome back</h1>
            <p class="subheading">Log in to continue to your store admin</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="field">
                    <label for="email">Email address</label>
                    <div class="field-control">
                        <input type="text" id="email" name="email" placeholder="you@yourstore.com" required autocomplete="username">
                    </div>
                </div>

                <div class="field">
                    <label for="pass">Password</label>
                    <div class="field-control has-toggle">
                        <input type="password" id="pass" name="password" placeholder="Enter your password" required autocomplete="current-password">
                        <button type="button" class="toggle-pass" onclick="togglePass()">SHOW</button>
                    </div>
                </div>

                <div class="row-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary">Log in</button>

                <div class="divider">or</div>

                <button type="button" class="btn-google">
                    <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 0 1-1.8 2.72v2.26h2.9c1.7-1.57 2.7-3.88 2.7-6.62z" fill="#4285F4"/>
                        <path d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.9-2.26c-.8.54-1.83.86-3.06.86-2.35 0-4.34-1.59-5.05-3.72H.96v2.33A9 9 0 0 0 9 18z" fill="#34A853"/>
                        <path d="M3.95 10.7A5.4 5.4 0 0 1 3.67 9c0-.59.1-1.17.28-1.7V4.97H.96A9 9 0 0 0 0 9c0 1.45.35 2.83.96 4.03l2.99-2.33z" fill="#FBBC05"/>
                        <path d="M9 3.58c1.32 0 2.51.46 3.44 1.35l2.58-2.58C13.46.89 11.43 0 9 0A9 9 0 0 0 .96 4.97l2.99 2.33C4.66 5.17 6.65 3.58 9 3.58z" fill="#EA4335"/>
                    </svg>
                    Continue with Google
                </button>
            </form>
        </div>

        <p class="page-footer">
            Having trouble signing in? <a href="#">Contact your store owner</a>
        </p>
    </main>

</div>

<script>
    function togglePass() {
        const input = document.getElementById("pass");
        const btn = document.querySelector(".toggle-pass");
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        btn.textContent = isHidden ? "HIDE" : "SHOW";
    }

    // Animate the revenue counter and sparkline draw-in once the page loads
    window.addEventListener("load", function () {
        const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        const statEl = document.getElementById("statValue");
        const target = 128492;

        if (reduceMotion) {
            statEl.textContent = "$" + target.toLocaleString();
        } else {
            const duration = 1400;
            const start = performance.now();
            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                statEl.textContent = "$" + Math.floor(eased * target).toLocaleString();
                if (progress < 1) requestAnimationFrame(tick);
            }
            requestAnimationFrame(tick);
        }

        const path = document.getElementById("sparkPath");
        if (path && !reduceMotion) {
            const length = path.getTotalLength();
            path.style.strokeDasharray = length;
            path.style.strokeDashoffset = length;
            path.getBoundingClientRect(); // force reflow
            path.style.transition = "stroke-dashoffset 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.3s";
            path.style.strokeDashoffset = "0";
        }
    });
</script>

</body>
</html>
