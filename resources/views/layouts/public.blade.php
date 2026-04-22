<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

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

    <!-- Hreflang SEO -->
    @foreach($activeLanguages ?? [] as $lang)
        <link rel="alternate" hreflang="{{ $lang->code }}"
            href="{{ LaravelLocalization::getLocalizedURL($lang->code, null, [], true) }}">
    @endforeach
    <link rel="alternate" hreflang="x-default"
        href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getDefaultLocale(), null, [], true) }}">

    <!-- Favicon -->
    <!-- <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}"> -->

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
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        .nav a:hover,
        .nav a.active {
            color: var(--gold);
            text-shadow: 0 0 12px rgba(201, 168, 76, 0.4);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* ── Language Switcher ───────────────────────── */
        .lang-switcher {
            position: relative;
        }

        .lang-switcher-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            background: transparent;
            border: 1px solid rgba(201, 168, 76, 0.3);
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .lang-switcher-btn:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        .lang-switcher-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #1a1a1a;
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 140px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            z-index: 200;
        }

        .lang-switcher-dropdown a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--muted);
            transition: background 0.15s, color 0.15s;
        }

        .lang-switcher-dropdown a:hover {
            background: rgba(201, 168, 76, 0.08);
            color: var(--text);
        }

        .lang-switcher-dropdown a.current {
            color: var(--gold);
            font-weight: 600;
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
            transition: all 0.4s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            border-color: var(--gold);
            box-shadow: 0 12px 30px rgba(201, 168, 76, 0.15);
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
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(201, 168, 76, 0.25);
        }

        .btn-outline {
            background: transparent;
            color: var(--gold);
            border: 1px solid var(--gold);
        }

        .btn-outline:hover {
            background: var(--gold);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(201, 168, 76, 0.25);
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

        .footer-col a,
        .footer-col span {
            display: block;
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 10px;
            transition: color 0.2s;
        }

        .footer-col a:hover,
        .footer-col span:hover {
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
            width: 100px;
            height: 100px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 800px;
        }

        .v-logo-wrapper {
            width: 90px;
            height: 90px;
            position: relative;
            z-index: 2;
        }

        .v-logo {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            filter: drop-shadow(0 0 16px rgba(201, 168, 76, 0.6));
        }

        .v-path {
            stroke-dasharray: 100px;
            stroke-dashoffset: 100px;
            animation: drawErase 5s ease-in-out infinite;
        }

        @keyframes drawErase {
            0%, 5% {
                stroke-dashoffset: 100px;
            }
            35%, 75% {
                stroke-dashoffset: 0px;
            }
            95%, 100% {
                stroke-dashoffset: 100px;
            }
        }

        .shine-layer {
            stroke: #fff;
            -webkit-mask-image: linear-gradient(90deg, transparent 0%, white 50%, transparent 100%);
            mask-image: linear-gradient(90deg, transparent 0%, white 50%, transparent 100%);
            -webkit-mask-size: 100px 100%;
            mask-size: 100px 100%;
            -webkit-mask-repeat: no-repeat;
            mask-repeat: no-repeat;
            animation: sweepMaskCss 5s ease-in-out infinite;
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.8));
        }

        @keyframes sweepMaskCss {
            0%, 34% {
                opacity: 0;
                -webkit-mask-position: -150px 0;
                mask-position: -150px 0;
            }
            35% {
                opacity: 1;
                -webkit-mask-position: -150px 0;
                mask-position: -150px 0;
            }
            75% {
                opacity: 1;
                -webkit-mask-position: 150px 0;
                mask-position: 150px 0;
            }
            76%, 100% {
                opacity: 0;
                -webkit-mask-position: 150px 0;
                mask-position: 150px 0;
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
            <div class="v-logo-wrapper">
                <!-- Base drawn paths -->
                <svg viewBox="0 0 100 100" fill="none" class="v-logo v-logo-base">
                    <defs>
                        <linearGradient id="goldGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#e8c96a" />
                            <stop offset="50%" stop-color="#c9a84c" />
                            <stop offset="100%" stop-color="#8a6f2e" />
                        </linearGradient>
                    </defs>
                    <g stroke="url(#goldGrad)">
                        <path class="v-path" pathLength="100" d="M 20 40 C 5 30, 10 15, 25 20 C 40 25, 40 60, 45 85 C 48 90, 52 90, 55 85 C 65 50, 75 25, 85 25 C 100 25, 90 45, 80 40" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="v-path" pathLength="100" d="M 52 65 Q 65 50, 62 30" stroke-width="2" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 62 30 C 50 25, 50 10, 55 5 C 58 15, 60 20, 62 30 M 62 30 C 58 20, 60 5, 65 0 C 70 5, 66 20, 62 30" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="v-path" pathLength="100" d="M 35 70 Q 20 65, 15 75 Q 10 85, 25 90 Q 40 95, 48 85" stroke-width="2" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 32 30 A 28 28 0 0 0 32 85 M 34 35 A 22 22 0 0 0 34 80" stroke-width="1.5" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 20 57 L 15 57 M 23 45 L 18 42 M 23 70 L 18 73 M 28 35 L 24 30 M 28 80 L 24 85" stroke-width="1.5" stroke-linecap="round" />
                    </g>
                </svg>
                
                <!-- Shining sweeping paths -->
                <svg viewBox="0 0 100 100" fill="none" class="v-logo shine-layer">
                    <g stroke="#fff">
                        <path class="v-path" pathLength="100" d="M 20 40 C 5 30, 10 15, 25 20 C 40 25, 40 60, 45 85 C 48 90, 52 90, 55 85 C 65 50, 75 25, 85 25 C 100 25, 90 45, 80 40" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="v-path" pathLength="100" d="M 52 65 Q 65 50, 62 30" stroke-width="2" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 62 30 C 50 25, 50 10, 55 5 C 58 15, 60 20, 62 30 M 62 30 C 58 20, 60 5, 65 0 C 70 5, 66 20, 62 30" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path class="v-path" pathLength="100" d="M 35 70 Q 20 65, 15 75 Q 10 85, 25 90 Q 40 95, 48 85" stroke-width="2" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 32 30 A 28 28 0 0 0 32 85 M 34 35 A 22 22 0 0 0 34 80" stroke-width="1.5" stroke-linecap="round" />
                        <path class="v-path" pathLength="100" d="M 20 57 L 15 57 M 23 45 L 18 42 M 23 70 L 18 73 M 28 35 L 24 30 M 28 80 L 24 85" stroke-width="1.5" stroke-linecap="round" />
                    </g>
                </svg>
            </div>
        </div>

        <!-- Brand -->
        <div class="splash-logo">{{ config('app.name') }}</div>
        <div class="splash-tagline">{{ __('splash.tagline') }}</div>

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
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('nav.home') }}</a>
                <a href="{{ route('about') }}"
                    class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('nav.about') }}</a>
                <a href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products*') ? 'active' : '' }}">{{ __('nav.products') }}</a>
            </nav>
            <div class="header-actions">
                @auth
                    <a href="{{ route('admin.dashboard') }}" style="font-size:12px;color:var(--gold)">Admin</a>
                @endauth

                {{-- Language Switcher --}}
                @php
                    $languages = LaravelLocalization::getSupportedLocales();
                    $current = LaravelLocalization::getCurrentLocale();
                @endphp

                <div class="lang-switcher" x-data="{ open: false }">

                    <button class="lang-switcher-btn" @click="open = !open" @click.away="open = false">

                        <span>
                            {{ strtoupper($current) }}
                        </span>
                    </button>

                    <div class="lang-switcher-dropdown" x-show="open" style="display:none">

                        @foreach($languages as $code => $lang)
                            <a href="{{ LaravelLocalization::getLocalizedURL($code) }}"
                                class="{{ $current === $code ? 'current' : '' }}">

                                {{ $lang['native'] ?? $lang['name'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

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
            <a href="{{ route('home') }}"
                class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('nav.home') }}</a>
            <a href="{{ route('about') }}"
                class="{{ request()->routeIs('about') ? 'active' : '' }}">{{ __('nav.about') }}</a>
            <a href="{{ route('products.index') }}"
                class="{{ request()->routeIs('products*') ? 'active' : '' }}">{{ __('nav.products') }}</a>
            @if(isset($activeLanguages) && $activeLanguages->count() > 1)
                <div
                    style="padding: 14px 0; border-top: 1px solid rgba(255,255,255,0.05); display:flex; gap:10px; flex-wrap:wrap;">
                    @foreach($activeLanguages as $lang)
                        <a href="{{ LaravelLocalization::getLocalizedURL($lang->code) }}"
                            style="padding:0; border:none; {{ app()->getLocale() === $lang->code ? 'color:var(--gold)' : '' }}">
                            {{ $lang->flag_emoji }} {{ $lang->native_name }}
                        </a>
                    @endforeach
                </div>
            @endif
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
                    <p>{{ __('footer.description') }}</p>
                </div>
                <div class="footer-col">
                    <h4>{{ __('footer.system') }}</h4>
                    <a href="{{ route('home') }}">{{ __('nav.home') }}</a>
                    <a href="{{ route('about') }}">{{ __('footer.about_us') }}</a>
                    <a href="{{ route('products.index') }}">{{ __('footer.all_products') }}</a>
                </div>
                <div class="footer-col">
                    <h4>{{ __('footer.contact') }}</h4>
                    <a href="mailto:chitho040903@gmail.com">chitho040903@gmail.com</a>
                    <span style="">{{ __('footer.location') }}</span>
                </div>
            </div>
            <hr class="divider">
            <div class="footer-bottom">
                <p>© 2026 <a href="https://github.com/tho493" style="color: white">tho493</a>. {{ __('footer.rights') }}
                </p>
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