<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO -->
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Trang sức cao cấp – Vàng, Bạc, Kim Cương')">
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Trang sức cao cấp')">
    <meta property="og:image" content="@yield('og_image', '')">
    <meta property="og:type" content="website">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold: #c9a84c;
            --gold-light: #e8c96a;
            --dark: #0d0d0d;
            --surface: #141414;
            --border: rgba(201, 168, 76, 0.2);
            --text: #f0ede8;
            --muted: #8a8278;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        /* ── Header ─────────────────────────────────── */
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(13, 13, 13, 0.9);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 600;
            color: var(--gold);
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav a {
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.5px;
            color: var(--muted);
            transition: color 0.2s;
            text-transform: uppercase;
        }

        .nav a:hover,
        .nav a.active {
            color: var(--gold);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            color: var(--gold);
            cursor: pointer;
            padding: 4px;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav {
            display: none;
        }

        /* ── Container ───────────────────────────────── */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section {
            padding: 80px 0;
        }

        .section-sm {
            padding: 48px 0;
        }

        /* ── Section heading ────────────────────────── */
        .section-heading {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-heading h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 38px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 15px;
        }

        .gold-line {
            width: 48px;
            height: 2px;
            background: var(--gold);
            margin: 14px auto 0;
        }

        /* ── Product Card ────────────────────────────── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: var(--surface);
            border: 1px solid rgba(201, 168, 76, 0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, border-color 0.3s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            border-color: var(--border);
        }

        .product-card-img {
            position: relative;
            overflow: hidden;
            aspect-ratio: 1;
        }

        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .product-card:hover .product-card-img img {
            transform: scale(1.05);
        }

        .product-card-img-placeholder {
            width: 100%;
            aspect-ratio: 1;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }

        .product-card-body {
            padding: 18px;
        }

        .product-card-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .product-card-cat {
            font-size: 11px;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .product-card-price {
            font-size: 16px;
            font-weight: 600;
            color: var(--gold);
        }

        /* ── Buttons ─────────────────────────────────── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 12px 28px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }

        .btn-gold {
            background: var(--gold);
            color: #000;
        }

        .btn-gold:hover {
            background: var(--gold-light);
        }

        .btn-outline {
            background: transparent;
            color: var(--gold);
            border: 1px solid var(--gold);
        }

        .btn-outline:hover {
            background: var(--gold);
            color: #000;
        }

        /* ── Footer ──────────────────────────────────── */
        .footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 48px 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 40px;
        }

        .footer-brand p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            margin-top: 12px;
        }

        .footer-col h4 {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--gold);
            margin-bottom: 16px;
            font-weight: 600;
        }

        .footer-col a {
            display: block;
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .footer-col a:hover {
            color: var(--text);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-bottom p {
            color: var(--muted);
            font-size: 12px;
        }

        /* ── Divider ─────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid rgba(201, 168, 76, 0.15);
        }

        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .mobile-nav {
                display: flex;
                flex-direction: column;
                background: var(--surface);
                border-top: 1px solid var(--border);
                border-bottom: 1px solid var(--border);
                padding: 8px 24px;
            }

            .mobile-nav a {
                padding: 14px 0;
                font-size: 13px;
                font-weight: 500;
                color: var(--text);
                text-decoration: none;
                text-transform: uppercase;
                letter-spacing: 1px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            .mobile-nav a:last-child {
                border-bottom: none;
            }

            .mobile-nav a.active {
                color: var(--gold);
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 14px;
            }
        }

        /* ── Splash Screen ────────────────────────── */
        #splash {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #080808;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0;
            pointer-events: all;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        #splash.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .splash-gem {
            position: relative;
            width: 80px;
            height: 80px;
            margin-bottom: 32px;
        }

        .splash-gem svg {
            width: 80px;
            height: 80px;
            animation: gemRotate 3s ease-in-out infinite;
            filter: drop-shadow(0 0 18px rgba(201, 168, 76, 0.6));
        }

        @keyframes gemRotate {

            0%,
            100% {
                transform: rotateY(0deg) scale(1);
            }

            25% {
                transform: rotateY(20deg) scale(1.05);
            }

            50% {
                transform: rotateY(0deg) scale(1.02);
            }

            75% {
                transform: rotateY(-20deg) scale(1.05);
            }
        }

        .splash-rings {
            position: absolute;
            inset: -20px;
        }

        .splash-ring {
            position: absolute;
            inset: 0;
            border: 1px solid rgba(201, 168, 76, 0.25);
            border-radius: 50%;
            animation: ringPulse 2s ease-in-out infinite;
        }

        .splash-ring:nth-child(2) {
            inset: -12px;
            animation-delay: 0.4s;
            border-color: rgba(201, 168, 76, 0.15);
        }

        .splash-ring:nth-child(3) {
            inset: -24px;
            animation-delay: 0.8s;
            border-color: rgba(201, 168, 76, 0.08);
        }

        @keyframes ringPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.06);
                opacity: 1;
            }
        }

        .splash-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36px;
            font-weight: 600;
            letter-spacing: 6px;
            color: var(--gold);
            text-transform: uppercase;
            animation: logoReveal 1s ease forwards;
            opacity: 0;
            animation-delay: 0.3s;
        }

        @keyframes logoReveal {
            from {
                opacity: 0;
                letter-spacing: 14px;
                filter: blur(4px);
            }

            to {
                opacity: 1;
                letter-spacing: 6px;
                filter: blur(0);
            }
        }

        .splash-tagline {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(201, 168, 76, 0.5);
            margin-top: 10px;
            animation: fadeUp 1s ease forwards;
            opacity: 0;
            animation-delay: 0.7s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .splash-bar {
            width: 160px;
            height: 1px;
            background: rgba(201, 168, 76, 0.15);
            margin-top: 36px;
            position: relative;
            overflow: hidden;
        }

        .splash-bar-fill {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            animation: barSweep 1.6s ease-in-out infinite;
        }

        @keyframes barSweep {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .splash-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
        }

        .splash-particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--gold);
            border-radius: 50%;
            animation: particleFall linear infinite;
            opacity: 0;
        }

        @keyframes particleFall {
            0% {
                transform: translateY(-10px) translateX(0);
                opacity: 0;
            }

            10% {
                opacity: 0.8;
            }

            90% {
                opacity: 0.4;
            }

            100% {
                transform: translateY(100vh) translateX(20px);
                opacity: 0;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Splash Screen -->
    <div id="splash">
        <!-- Particles -->
        <div class="splash-particles" id="splashParticles"></div>

        <!-- Diamond Icon -->
        <div class="splash-gem">
            <div class="splash-rings">
                <div class="splash-ring"></div>
                <div class="splash-ring"></div>
                <div class="splash-ring"></div>
            </div>
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="gemGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="#e8c96a" />
                        <stop offset="50%" stop-color="#c9a84c" />
                        <stop offset="100%" stop-color="#8a6f2e" />
                    </linearGradient>
                    <linearGradient id="shimmer" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="rgba(255,255,255,0)" />
                        <stop offset="50%" stop-color="rgba(255,255,255,0.3)" />
                        <stop offset="100%" stop-color="rgba(255,255,255,0)" />
                    </linearGradient>
                </defs>
                <!-- Diamond shape -->
                <polygon points="50,8 90,38 75,92 25,92 10,38" fill="url(#gemGrad)" stroke="#e8c96a" stroke-width="1" />
                <!-- Facets -->
                <polygon points="50,8 90,38 50,42" fill="rgba(255,255,255,0.15)" />
                <polygon points="50,8 10,38 50,42" fill="rgba(0,0,0,0.2)" />
                <polygon points="10,38 25,92 50,60" fill="rgba(255,255,255,0.08)" />
                <polygon points="90,38 75,92 50,60" fill="rgba(0,0,0,0.15)" />
                <polygon points="25,92 75,92 50,60" fill="rgba(255,255,255,0.05)" />
                <!-- Top line detail -->
                <line x1="50" y1="8" x2="50" y2="42" stroke="rgba(255,255,255,0.3)" stroke-width="0.5" />
                <line x1="10" y1="38" x2="90" y2="38" stroke="rgba(255,255,255,0.2)" stroke-width="0.5" />
                <!-- Shimmer overlay -->
                <polygon points="50,8 90,38 75,92 25,92 10,38" fill="url(#shimmer)" opacity="0.5" />
            </svg>
        </div>

        <!-- Brand -->
        <div class="splash-logo">{{ config('app.name') }}</div>
        <div class="splash-tagline">Bộ sưu tập trang sức &bull; Hán - Việt</div>

        <!-- Loading bar -->
        <div class="splash-bar">
            <div class="splash-bar-fill"></div>
        </div>
    </div>

    <!-- Header -->
    <header class="header" x-data="{ mobileMenuOpen: false }">
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">
                {{ config('app.name') }}
            </a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Giới thiệu</a>
                <a href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products*') ? 'active' : '' }}">Sản phẩm</a>
            </nav>
            <div class="header-actions">
                @auth
                    <a href="{{ route('admin.dashboard') }}" style="font-size:12px;color:var(--gold)">Admin</a>
                @endauth

                <button type="button" class="mobile-menu-btn" @click="mobileMenuOpen = !mobileMenuOpen"
                    aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24"
                        height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" style="display: none;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div class="mobile-nav" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" style="display: none;"
            x-transition>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Giới thiệu</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products*') ? 'active' : '' }}">Sản
                phẩm</a>
        </div>
    </header>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">{{ config('app.name') }}</div>
                    <p>Website này dùng để tổng hợp kết quả nghiên cứu sản phẩm dưới dạng trực quan. Phục vụ báo cáo,
                        trưng bày, thuyết trình và số hóa học dữ liệu.</p>
                </div>
                <div class="footer-col">
                    <h4>Hệ thống</h4>
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="{{ route('about') }}">Về chúng tôi</a>
                    <a href="{{ route('products.index') }}">Tất cả sản phẩm</a>
                    <a href="{{ route('products.index') }}?danh_muc=nhan">Nhẫn</a>
                </div>
                <div class="footer-col">
                    <h4>Liên hệ</h4>
                    <a href="#">chitho040903@gmail.com</a>
                    <!-- <a href="#">0896505169</a> -->
                    <a href="#">Hải Phòng, Việt Nam</a>
                </div>
            </div>
            <hr class="divider">
            <div class="footer-bottom">
                <p>© 2026 <a href="https://github.com/tho493" style="color: white">tho493</a>. Bảo lưu mọi
                    quyền.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Generate gold particles
        (function () {
            var container = document.getElementById('splashParticles');
            for (var i = 0; i < 28; i++) {
                var p = document.createElement('div');
                p.className = 'splash-particle';
                p.style.left = Math.random() * 100 + '%';
                p.style.width = (Math.random() * 2 + 1) + 'px';
                p.style.height = p.style.width;
                p.style.animationDuration = (Math.random() * 6 + 4) + 's';
                p.style.animationDelay = (Math.random() * 5) + 's';
                p.style.opacity = Math.random() * 0.6 + 0.2;
                container.appendChild(p);
            }
        })();

        // Hide splash after page is ready
        window.addEventListener('load', function () {
            setTimeout(function () {
                var splash = document.getElementById('splash');
                splash.classList.add('hidden');
                setTimeout(function () {
                    splash.style.display = 'none';
                }, 850);
            }, 1200);
        });
    </script>
</body>

</html>