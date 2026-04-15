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

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .product-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 14px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Header -->
    <header class="header">
        <div class="header-inner">
            <a href="{{ route('home') }}" class="logo">
                💎 {{ config('app.name') }}
            </a>
            <nav class="nav">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                <a href="{{ route('products.index') }}"
                    class="{{ request()->routeIs('products*') ? 'active' : '' }}">Sản phẩm</a>
            </nav>
            <div class="header-actions">
                @auth
                    <a href="{{ route('admin.dashboard') }}" style="font-size:12px;color:var(--gold)">Admin</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">💎 {{ config('app.name') }}</div>
                    <p>Website này dùng để tổng hợp kết quả nghiên cứu sản phẩm dưới dạng trực quan. Phục vụ báo cáo,
                        trưng bày, thuyết trình và số hóa học dữ liệu.</p>
                </div>
                <div class="footer-col">
                    <h4>Danh mục</h4>
                    <a href="{{ route('products.index') }}">Tất cả sản phẩm</a>
                    <a href="{{ route('products.index') }}?danh_muc=nhan">Nhẫn</a>
                    <a href="{{ route('products.index') }}?danh_muc=day-chuyen">Dây chuyền</a>
                    <a href="{{ route('products.index') }}?danh_muc=vong-tay">Vòng tay</a>
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
                <p>© {{ date('Y') }} tho493. Bảo lưu mọi quyền.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>