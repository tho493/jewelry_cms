@extends('layouts.public')

@section('title', 'Bộ sưu tập trang sức – ' . config('app.name'))
@section('meta_description', 'Khám phá bộ sưu tập trang sức văn hóa Việt - Trung.')

@push('styles')
    <style>
        /* ── Hero ────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            /* Tăng chiều cao để phủ kín màn hình ban đầu */
            display: flex;
            align-items: center;
            background: #0a0a0a;
            overflow: hidden;
            color: #fff;
        }

        /* Hiệu ứng ánh sáng nền di chuyển */
        .hero::before {
            content: '';
            position: absolute;
            top: -10%;
            right: -10%;
            width: 60%;
            height: 80%;
            background: radial-gradient(circle, rgba(201, 168, 76, 0.15) 0%, transparent 70%);
            filter: blur(80px);
            animation: floatLight 10s infinite alternate;
            z-index: 0;
        }

        @keyframes floatLight {
            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(-10%, 10%);
            }
        }

        /* Layout chia đôi: Text và Hình ảnh */
        .hero-wrapper {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 40px;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            opacity: 0;
            transform: translateX(-30px);
            animation: fadeInUp 1s forwards 0.5s;
        }

        /* Chữ cái đầu tiên lớn (Drop Cap) hoặc Line trang trí */
        .hero-label {
            font-size: 13px;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(48px, 8vw, 84px);
            /* Responsive font size */
            font-weight: 500;
            line-height: 1;
            margin-bottom: 25px;
            letter-spacing: -1px;
        }

        .hero-title span {
            display: block;
            font-style: italic;
            color: var(--gold);
            margin-left: 60px;
            /* Tạo độ lệch nghệ thuật */
            position: relative;
        }

        /* Hiệu ứng trang trí cho hình ảnh bên phải */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-img-wrapper {
            position: relative;
            width: 100%;
            max-width: 450px;
            aspect-ratio: 4/5;
            border: 1px solid rgba(201, 168, 76, 0.3);
            padding: 15px;
            animation: morphFrame 15s infinite ease-in-out;
        }

        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: contrast(1.1) brightness(0.9);
        }

        /* Nút bấm sang chảnh hơn */
        .btn-gold {
            background: var(--gold);
            color: #000;
            padding: 16px 35px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-gold:hover {
            background: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(201, 168, 76, 0.2);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive cho Mobile */
        @media (max-width: 991px) {
            .hero-wrapper {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-label {
                justify-content: center;
            }

            .hero-title span {
                margin-left: 0;
            }

            .hero-desc {
                margin: 0 auto 30px;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-visual {
                display: none;
            }

        }

        /* ── Category chips ─────────────────────────── */
        .category-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
        }

        .cat-chip {
            padding: 10px 22px;
            border-radius: 99px;
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .cat-chip:hover {
            border-color: var(--gold);
            color: var(--gold);
            background: rgba(201, 168, 76, 0.06);
        }

        .cat-chip strong {
            color: var(--gold);
            font-size: 12px;
        }
    </style>
@endpush

@section('content')

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero-wrapper">

                <div class="hero-content">
                    <div class="hero-label">
                        <span>Kiệt tác di sản</span>
                    </div>
                    <h1 class="hero-title">
                        Tinh Hoa <br>
                        <span>Trang Sức</span>
                    </h1>
                    <p class="hero-desc">
                        Tuyển tập những món bảo vật được hồi sinh từ dòng chảy Hán - Việt,
                        chế tác thủ công với độ tinh xảo tuyệt đối dành riêng cho giới mộ điệu.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('products.index') }}" class="btn btn-gold">Khám phá tuyệt tác</a>
                        <a href="#featured" class="btn btn-outline"
                            style="color: #fff; border-color: rgba(255,255,255,0.3);">
                            Xem bộ sưu tập
                        </a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="main-img-wrapper">
                        <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?q=80&w=2070&auto=format&fit=crop"
                            alt="Luxury Jewelry" class="hero-img">

                        <div
                            style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; border-left: 2px solid var(--gold); border-bottom: 2px solid var(--gold);">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="scroll-indicator"
            style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); opacity: 0.6;">
            <div style="width: 1px; height: 60px; background: linear-gradient(to bottom, var(--gold), transparent);"></div>
        </div>
    </section>

    <!-- Categories -->
    @if($categories->count() > 0)
        <section class="section-sm" style="border-bottom: 1px solid rgba(201,168,76,0.1)">
            <div class="container">
                <div class="category-grid">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="cat-chip">
                            {{ $cat->name }}
                            @if($cat->products_count > 0)
                                <strong>{{ $cat->products_count }}</strong>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products -->
    <section class="section" id="featured">
        <div class="container">
            <div class="section-heading">
                <h2>Sản phẩm nổi bật</h2>
                <p>Những thiết kế được yêu thích nhất</p>
                <div class="gold-line"></div>
            </div>

            @if($featuredProducts->count() > 0)
                <div class="product-grid">
                    @foreach($featuredProducts as $product)
                        <a href="{{ route('products.show', $product->slug) }}" class="product-card">
                            <div class="product-card-img">
                                @if($product->coverImage())
                                    <img src="{{ $product->coverImage()->thumbnail_url }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="product-card-img-placeholder">💎</div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <div class="product-card-cat">{{ $product->category?->name ?? 'Trang sức' }}</div>
                                <div class="product-card-name">{{ $product->name }}</div>
                                <div class="product-card-price">
                                    {{ $product->price ? number_format($product->price) . 'đ' : 'Liên hệ' }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div style="text-align:center;margin-top:40px">
                    <a href="{{ route('products.index') }}" class="btn btn-outline">Xem tất cả sản phẩm</a>
                </div>
            @else
                <div style="text-align:center;padding:80px 0;color:var(--muted)">
                    <p>Sản phẩm sắp ra mắt. Hãy quay lại sau!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- About strip -->
    <!-- <section style="background: linear-gradient(135deg, rgba(201,168,76,0.08), rgba(201,168,76,0.03)); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); padding: 56px 0;">
                                                        <div class="container">
                                                            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px,1fr)); gap:40px; text-align:center">
                                                                @foreach([['💎','Chất liệu cao cấp','Vàng 18K, Bạch kim, Kim cương thiên nhiên'], ['✋','Chế tác thủ công','Mỗi sản phẩm đều được chế tác tỉ mỉ bởi nghệ nhân'], ['📦','Giao hàng toàn quốc','Đóng gói sang trọng, giao hàng an toàn'], ['🛡','Bảo hành 12 tháng','Đổi trả miễn phí trong vòng 30 ngày']] as [$icon, $title, $desc])
                                                                <div>
                                                                    <div style="font-size:36px;margin-bottom:12px">{{ $icon }}</div>
                                                                    <div style="font-family:'Cormorant Garamond',serif;font-size:18px;font-weight:600;margin-bottom:6px">{{ $title }}</div>
                                                                    <div style="color:var(--muted);font-size:13px;line-height:1.6">{{ $desc }}</div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </section> -->

@endsection