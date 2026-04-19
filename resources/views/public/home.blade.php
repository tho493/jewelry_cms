@extends('layouts.public')

@section('title', 'Bộ sưu tập trang sức – ' . config('app.name'))
@section('meta_description', 'Khám phá bộ sưu tập trang sức văn hóa Việt - Trung.')

@push('styles')
    <style>
        /* ── Hero ────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: #0a0a0a;
            overflow: hidden;
            color: #fff;
        }

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

        /* Chữ cái đầu tiên lớn */
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
            position: relative;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .hero-visual {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .main-img-wrapper {
            position: relative;
            width: min(100%, 450px, 55vh);
            aspect-ratio: 4 / 5;
            border: 1px solid rgba(201, 168, 76, 0.3);
            background-color: transparent !important;
            animation: morphFrame 15s infinite ease-in-out;
        }

        .hero-img {
            width: 100% !important;
            height: 100% !important;
            position: absolute !important;
            top: 0;
            left: 0;
            object-fit: cover !important;
            filter: contrast(1.1) brightness(0.9);
        }

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

        .hero-mobile-bg {
            display: none;
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
                display: flex;
            }

            .main-img-wrapper {
                display: none;
            }

            .hero-dots {
                margin-top: 10px;
            }

            .hero-mobile-bg {
                display: block;
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .hero-mobile-bg .mobile-slide {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .hero-mobile-bg img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                filter: brightness(0.3) contrast(1.1);
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

        /* ── Hero slider dots ─────────────────────────── */
        .hero-dots {
            width: 100%;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 10;
            margin-top: 30px;
        }

        .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Dot active */
        .dot.active {
            width: 18px;
            border-radius: 10px;
            background: rgba(201, 168, 76, 0.3);
            box-shadow: 0 0 10px rgba(201, 168, 76, 0.6);
        }

        .dot.active {
            animation: dotPulse 0.4s ease;
        }

        @keyframes dotPulse {
            0% {
                transform: scale(0.8);
            }

            100% {
                transform: scale(1);
            }
        }

        .dot {
            position: relative;
            overflow: hidden;
        }

        .dot-progress {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 100%;
            background: var(--gold);
            transform-origin: left;
            animation: dotProgress 4s linear forwards;
            z-index: 2;
        }

        @keyframes dotProgress {
            from {
                transform: scaleX(0);
            }

            to {
                transform: scaleX(1);
            }
        }

        /* ── Gold Dust Particles ─────────────────────────── */
        #gold-dust-container {
            position: absolute;
            inset: 0;
            z-index: 1; /* Below content but above background */
            pointer-events: none;
            overflow: hidden;
        }

        .gold-dust {
            position: absolute;
            background: radial-gradient(circle, rgba(255,215,0,0.8) 0%, rgba(201,168,76,0.2) 100%);
            border-radius: 50%;
            pointer-events: none;
            animation: float-dust linear infinite;
            filter: blur(1px);
        }

        @keyframes float-dust {
            0% {
                transform: translateY(110vh) translateX(0) scale(0);
                opacity: 0;
            }
            10% { opacity: 1; transform: translateY(90vh) scale(1); }
            90% { opacity: 0.8; }
            100% {
                transform: translateY(-20vh) translateX(50px) scale(0.5);
                opacity: 0;
            }
        }
    </style>
@endpush

@section('content')

    <section class="hero" x-data x-init="initHeroSlider({{ max(1, $slides->count()) }})">

        <!-- Gold Dust -->
        <div id="gold-dust-container"></div>

        <div class="hero-mobile-bg">
            @if($slides->count() > 0)
                @foreach($slides as $index => $slide)
                    <div class="mobile-slide" :style="{ transform: $store.slider.current === {{ $index }} ? 'scale(1)' : 'scale(1.05)', opacity: $store.slider.current === {{ $index }} ? 1 : 0, filter: $store.slider.current === {{ $index }} ? 'blur(0px)' : 'blur(10px)', zIndex: $store.slider.current === {{ $index }} ? 2 : 1 }">
                        <img src="{{ $slide->image_url }}" alt="Mobile Background">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="container">
            <div class="hero-wrapper">

                <div class="hero-content">
                    <div class="hero-label">
                        <span>{{ $setting->hero_label }}</span>
                    </div>
                    <h1 class="hero-title">
                        {{ $setting->hero_title_line1 }} <br>
                        <span>{{ $setting->hero_title_line2 }}</span>
                    </h1>
                    <p class="hero-desc">
                        {{ $setting->hero_description }}
                    </p>
                    <div class="hero-actions">
                        @if($setting->hero_btn_primary_text)
                            <a href="{{ route('products.index') }}"
                                class="btn btn-gold">{{ $setting->hero_btn_primary_text }}</a>
                        @endif
                        @if($setting->hero_btn_secondary_text)
                            <a href="#featured" class="btn btn-outline"
                                style="color: #fff; border-color: rgba(255,255,255,0.3);">
                                {{ $setting->hero_btn_secondary_text }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="hero-visual" @mouseenter="$store.slider.pause = true" @mouseleave="$store.slider.pause = false">
                    <div class="main-img-wrapper" style="overflow: hidden; position: relative;">
                        <!-- Slider Items -->
                        @if($slides->count() > 0)
                            @foreach($slides as $index => $slide)
                                <div style="position: absolute; inset: 0; width: 100%; height: 100%; transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);"
                                    :style="{ transform: $store.slider.current === {{ $index }} ? 'scale(1)' : 'scale(1.05)', opacity: $store.slider.current === {{ $index }} ? 1 : 0, filter: $store.slider.current === {{ $index }} ? 'blur(0px)' : 'blur(10px)', zIndex: $store.slider.current === {{ $index }} ? 2 : 1 }">
                                    <img src="{{ $slide->image_url }}" alt="Slide" class="hero-img">

                                    @if($slide->caption)
                                        <div
                                            style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 12px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); text-align: center; color: var(--gold); font-size: 13px; font-style: italic; z-index: 5;">
                                            {{ $slide->caption }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div
                                style="width: 100%; height: 100%; background: #222; display: flex; align-items: center; justify-content: center; color: #555;">
                                Chưa có ảnh (hãy truy cập Admin để tải lên)
                            </div>
                        @endif

                        <div
                            style="position: absolute; bottom: -20px; left: -20px; width: 100px; height: 100px; border-left: 2px solid var(--gold); border-bottom: 2px solid var(--gold); z-index: 10;">
                        </div>

                    </div>

                    @if($slides->count() > 1)
                        <div class="hero-dots">
                            <template x-for="(s, index) in {{ $slides->count() }}" :key="index">
                                <div class="dot" @click="$store.slider.current = index"
                                    :class="{ 'active': $store.slider.current === index }"
                                    @mouseenter="$store.slider.pause = true" @mouseleave="$store.slider.pause = false">
                                    <template x-if="$store.slider.current === index">
                                        <span class="dot-progress"
                                            @animationend="$store.slider.current = ($store.slider.current + 1) % $store.slider.total"
                                            :style="$store.slider.pause ? 'animation-play-state: paused' : 'animation-play-state: running'"></span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    @endif
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
                <h2>{{ $setting->featured_title }}</h2>
                <p>{{ $setting->featured_subtitle }}</p>
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
                                <div style="font-size: 13px; color: var(--muted); margin-bottom: 4px;">
                                    {{ $product->product_code ? 'Mã SP: ' . $product->product_code : ($product->material ? 'Chất liệu: ' . $product->material : '') }}
                                </div>
                                @if($product->price)
                                    <div class="product-card-price">
                                        {{ number_format($product->price) . 'đ' }}
                                    </div>
                                @endif
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

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initGoldDust();
        });

        function initGoldDust() {
            const container = document.getElementById('gold-dust-container');
            if(!container) return;
            const particleCount = window.innerWidth < 768 ? 25 : 50;
            
            for(let i=0; i<particleCount; i++) {
                let dust = document.createElement('div');
                dust.className = 'gold-dust';
                dust.style.left = Math.random() * 100 + 'vw';
                
                let size = Math.random() * 3 + 1;
                dust.style.width = size + 'px';
                dust.style.height = size + 'px';
                
                dust.style.animationDuration = (Math.random() * 15 + 10) + 's';
                dust.style.animationDelay = (Math.random() * -20) + 's';
                
                container.appendChild(dust);
            }
        }

        function initHeroSlider(total) {
            Alpine.store('slider', {
                current: 0,
                pause: false,
                total: total,
            });
        }
    </script>
@endpush