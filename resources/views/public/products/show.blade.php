@extends('layouts.public')

@section('title', ($product->seo_title ?: $product->name) . ' – ' . config('app.name'))
@section('meta_description', $product->seo_description ?: $product->short_description)
@section('og_title', $product->seo_title ?: $product->name)
@section('og_description', $product->seo_description ?: $product->short_description)
@section('og_image', $product->coverImage()?->url ?? '')

@push('styles')
    <style>
        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px;
            align-items: start;
        }

        /* Gallery */
        .gallery {
            position: sticky;
            top: 90px;
            z-index: 10;
        }

        @media (max-width: 991px) {
            .product-detail {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .gallery {
                position: static !important;
                z-index: 1;
            }

            .product-title {
                font-size: 28px !important;
            }

            .product-title span {
                font-size: 20px !important;
            }

            .gallery-thumb {
                width: 60px !important;
                height: 60px !important;
            }
        }

        .gallery-main {
            width: 100%;
            aspect-ratio: 1/1;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .gallery-main img,
        .gallery-main video {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .video-indicator {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 12px;
        }

        .gallery-thumbs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .gallery-thumb {
            width: 72px;
            height: 72px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .gallery-thumb.active {
            border-color: var(--gold);
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Media Caption */
        .gallery-caption {
            margin-top: 10px;
            min-height: 22px;
            font-size: 13px;
            color: var(--muted);
            text-align: center;
            font-style: italic;
            transition: opacity 0.2s;
            padding: 0 8px;
        }

        .audio-container {
            margin: 20px 0;
            width: 100%;
        }

        /* Tùy chỉnh thanh audio */
        audio.custom-audio {
            width: 100%;
            height: 40px;
            filter: sepia(20%) saturate(70%) grayscale(1) contrast(90%) invert(85%);
            /* Filter này giúp biến thanh audio màu trắng sang tông xám/vàng nhạt để hợp với màu Gold */
        }

        /* Cách 2: Nếu bạn muốn can thiệp sâu hơn vào màu sắc (Chrome/Edge/Safari) */
        audio.custom-audio::-webkit-media-controls-panel {
            background-color: #1a1a1a;
            /* Nền tối giống background web */
            border: 1px solid #c5a059;
            /* Viền màu vàng gold */
        }

        audio.custom-audio::-webkit-media-controls-play-button,
        audio.custom-audio::-webkit-media-controls-mute-button {
            background-color: #c5a059;
            /* Nút play màu vàng */
            border-radius: 50%;
        }

        audio.custom-audio::-webkit-media-controls-current-time-display,
        audio.custom-audio::-webkit-media-controls-time-remaining-display {
            color: #c5a059;
            /* Màu chữ thời gian */
        }

        /* Lightbox */
        .lightbox-modal {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(8, 8, 8, 0.95);
            backdrop-filter: blur(10px);
        }

        .lightbox-close {
            position: absolute;
            top: 24px;
            right: 24px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .lightbox-close:hover {
            background: var(--gold);
            color: black;
            border-color: var(--gold);
        }

        .lightbox-content {
            position: relative;
            z-index: 1;
            width: 90vw;
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            user-select: none;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: var(--muted);
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: var(--gold);
        }

        .breadcrumb-sep {
            color: rgba(255, 255, 255, 0.2);
        }

        /* Product info */
        .product-label {
            font-size: 11px;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .product-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 38px;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .product-price {
            font-size: 28px;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 24px;
        }

        .product-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 20px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            margin-bottom: 24px;
        }

        .product-meta-row {
            display: flex;
            gap: 12px;
            font-size: 14px;
        }

        .product-meta-label {
            color: var(--muted);
            min-width: 100px;
        }

        .product-meta-value {
            font-weight: 500;
        }

        .product-desc {
            font-size: 15px;
            line-height: 1.8;
            color: #c8c4bb;
        }

        .product-desc h2,
        .product-desc h3 {
            font-family: 'Cormorant Garamond', serif;
            color: var(--text);
            margin: 20px 0 10px;
        }

        .product-desc img {
            max-width: 100%;
            border-radius: 8px;
            margin: 12px 0;
        }

        .product-desc p {
            margin-bottom: 12px;
        }

        /* Related */
        .related-section {
            padding: 72px 0;
            border-top: 1px solid rgba(201, 168, 76, 0.1);
        }
    </style>
@endpush

@section('content')

    <div style="padding: 48px 0 0">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('home') }}">Trang chủ</a>
                <span class="breadcrumb-sep">/</span>
                <a href="{{ route('products.index') }}">Sản phẩm</a>
                @if($product->category)
                    <span class="breadcrumb-sep">/</span>
                    <a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a>
                @endif
                <span class="breadcrumb-sep">/</span>
                <span style="color:var(--text)">{{ $product->name }}</span>
            </nav>

            <div class="product-detail">
                <!-- Gallery -->
                <div class="gallery" x-data="gallery()">
                    <div class="gallery-main">
                        @if(($product->images->count() + $product->videos->count()) > 0)
                            <template x-if="currentType === 'image'">
                                <div style="width:100%;height:100%;position:relative;cursor:zoom-in" @click="isZoomed = true"
                                    title="Phóng to ảnh">
                                    <img :src="current" id="main-img" alt="{{ $product->name }}">
                                    <!-- Zoom Icon Overlay -->
                                    <div
                                        style="position:absolute;bottom:16px;right:16px;background:rgba(0,0,0,0.6);border-radius:50%;padding:10px;display:flex;pointer-events:none;">
                                        <svg width="24" height="24" fill="none" stroke="var(--gold)" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                            </template>

                            <template x-if="currentType === 'video'">
                                <video :src="current" controls autoplay muted class="main-video" id="main-video"></video>
                            </template>
                        @else
                            <div
                                style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:80px">
                                💎</div>
                        @endif
                    </div>{{-- /gallery-main --}}

                    <!-- Caption dưới ảnh/video chính -->
                    <div class="gallery-caption" x-show="currentCaption" x-text="currentCaption"></div>

                    @if(($product->images->count() + $product->videos->count()) > 1)
                        <div class="gallery-thumbs">
                            {{-- Thumbs cho Ảnh --}}
                            @foreach($product->images as $index => $img)
                                <div class="gallery-thumb {{ $index === 0 ? 'active' : '' }}"
                                    @click="select('{{ $img->url }}', 'image', $el, '{{ addslashes($img->caption) }}')"
                                    title="{{ $img->caption }}">
                                    <img src="{{ $img->thumbnail_url }}" alt="{{ $img->alt_text }}">
                                </div>
                            @endforeach

                            {{-- Thumbs cho Video --}}
                            @foreach($product->videos as $video)
                                <div class="gallery-thumb" @click="select('{{ $video->url }}', 'video', $el, '{{ addslashes($video->caption) }}')"
                                    title="{{ $video->caption }}">
                                    <div class="video-indicator">
                                        <video src="{{ $video->url }}#t=0.1"
                                            style="width:100%; height:100%; object-fit: cover;"></video>
                                        <span class="play-icon">▶</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Fullscreen Lightbox (Teleport to body end) -->
                    <template x-teleport="body">
                        <div x-show="isZoomed" style="display:none;" x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" class="lightbox-modal"
                            @keydown.escape.window="isZoomed = false">

                            <div class="lightbox-backdrop" @click="isZoomed = false"></div>

                            <button class="lightbox-close" @click="isZoomed = false" title="Đóng (ESC)">✕</button>

                            <div class="lightbox-content">
                                <img :src="current" alt="Zoomed image">
                            </div>
                        </div>
                    </template>

                </div>

                <!-- Info -->
                <div>
                    <div class="product-label">{{ $product->category?->name ?? 'Trang sức' }}</div>
                    <h1 class="product-title">
                        {{ $product->name }}
                        @if($product->name_hantu)
                            <span
                                style="font-size: 26px; color: var(--gold); font-weight: 400; margin-left: 6px;">({{ $product->name_hantu }})</span>
                        @endif
                    </h1>

                    @if($product->price)
                        <div class="product-price">
                            {{ $product->price ? number_format($product->price) . 'đ' : '' }}
                        </div>
                    @endif

                    @if($product->short_description)
                        <p style="color:var(--muted);font-size:15px;line-height:1.7;margin-bottom:24px">
                            {{ $product->short_description }}
                        </p>
                    @endif

                    <div class="product-meta">
                        @if($product->product_code)
                            <div class="product-meta-row">
                                <span class="product-meta-label">Mã sản phẩm</span>
                                <span class="product-meta-value">{{ $product->product_code }}</span>
                            </div>
                        @endif

                        @if($product->main_character)
                            <div class="product-meta-row">
                                <span class="product-meta-label">Chữ chủ đạo</span>
                                <span class="product-meta-value">{{ $product->main_character }}</span>
                            </div>
                        @endif

                        @if($product->material)
                            <div class="product-meta-row">
                                <span class="product-meta-label">Chất liệu - Kỹ thuật</span>
                                <span class="product-meta-value">{{ $product->material }}</span>
                            </div>
                        @endif
                        @if($product->category)
                            <div class="product-meta-row">
                                <span class="product-meta-label">Danh mục</span>
                                <a href="{{ route('categories.show', $product->category->slug) }}" class="product-meta-value"
                                    style="color:var(--gold)">{{ $product->category->name }}</a>
                            </div>
                        @endif
                    </div>

                    @if($product->audios->count() > 0)
                        <div class="audio-container">
                            <p style="color: #c5a059; font-size: 0.9rem; margin-bottom: 8px;">
                                <i class="fas fa-volume-up"></i> Thuyết minh sản phẩm:
                            </p>
                            @foreach($product->audios as $audio)
                                <div style="margin-bottom: 12px;">
                                    @if($audio->caption)
                                        <div style="font-size: 14px; font-weight: 500; color: var(--gold); margin-bottom: 6px;">♪
                                            {{ $audio->caption }}</div>
                                    @endif
                                    <audio controls class="custom-audio" preload="metadata">
                                        <source src="{{ $audio->url }}" type="audio/mpeg">
                                        Trình duyệt không hỗ trợ audio.
                                    </audio>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- <a href="tel:0901234567" class="btn btn-gold"
                                        style="width:100%;justify-content:center;margin-bottom:10px;font-size:14px">
                                        📞 Liên hệ tư vấn
                                    </a> -->
                    <a href="{{ route('products.index') }}" class="btn btn-outline"
                        style="width:100%;justify-content:center;font-size:14px">
                        ← Xem thêm sản phẩm
                    </a>
                </div>
            </div>

            <!-- Description -->
            @if($product->description || $product->form_characteristics || $product->cultural_meaning)
                <div
                    style="max-width:800px; margin: 64px auto 0; padding-top: 48px; border-top: 1px solid rgba(255,255,255,0.06)">

                    @if($product->form_characteristics)
                        <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;margin-bottom:24px">Đặc điểm tạo hình</h2>
                        <div class="product-desc" style="margin-bottom:48px">{!! $product->form_characteristics !!}</div>
                    @endif

                    @if($product->cultural_meaning)
                        <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;margin-bottom:24px">Ý nghĩa văn hóa</h2>
                        <div class="product-desc" style="margin-bottom:48px">{!! $product->cultural_meaning !!}</div>
                    @endif

                    @if($product->description)
                        <h2 style="font-family:'Cormorant Garamond',serif;font-size:28px;margin-bottom:24px">Mô tả sản phẩm</h2>
                        <div class="product-desc">{!! $product->description !!}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Related Products -->
    @if($related->count() > 0)
        <section class="related-section">
            <div class="container">
                <div class="section-heading">
                    <h2>Sản phẩm liên quan</h2>
                    <div class="gold-line"></div>
                </div>
                <div class="product-grid">
                    @foreach($related as $rel)
                        <a href="{{ route('products.show', $rel->slug) }}" class="product-card">
                            <div class="product-card-img">
                                @if($rel->coverImage())
                                    <img src="{{ $rel->coverImage()->thumbnail_url }}" alt="{{ $rel->name }}" loading="lazy">
                                @else
                                    <div class="product-card-img-placeholder">💎</div>
                                @endif
                            </div>
                            <div class="product-card-body">
                                <div class="product-card-cat">{{ $rel->category?->name }}</div>
                                <div class="product-card-name">{{ $rel->name }}</div>
                                <div style="font-size: 13px; color: var(--muted); margin-bottom: 4px;">
                                    {{ $rel->product_code ? 'Mã SP: ' . $rel->product_code : ($rel->material ? 'Chất liệu: ' . $rel->material : '') }}
                                </div>
                                @if($rel->price)
                                <div class="product-card-price">
                                    {{ number_format($rel->price) . 'đ' }}
                                </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('scripts')
    <script>
        function gallery() {
            return {
                // Khởi tạo giá trị mặc định từ ảnh đầu tiên (nếu có)
                current: '{{ $product->images->first()?->url ?? ($product->videos->first()?->url ?? "") }}',
                currentType: '{{ $product->images->first() ? "image" : ($product->videos->first() ? "video" : "") }}',
                currentCaption: '{{ addslashes($product->images->first()?->caption ?? $product->videos->first()?->caption ?? "") }}',
                isZoomed: false,

                select(url, type, el, caption = '') {
                    this.current = url;
                    this.currentType = type;
                    this.currentCaption = caption;

                    // Xử lý active class
                    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
                        thumb.classList.remove('active');
                    });
                    el.classList.add('active');
                }
            }
        }
    </script>
@endpush