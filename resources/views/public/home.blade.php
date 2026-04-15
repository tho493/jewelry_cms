@extends('layouts.public')

@section('title', 'Trang sức cao cấp – ' . config('app.name'))
@section('meta_description', 'Khám phá bộ sưu tập trang sức cao cấp: nhẫn vàng, dây chuyền kim cương, vòng tay bạc.')

@push('styles')
    <style>
        /* ── Hero ────────────────────────────────────── */
        .hero {
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
            background: radial-gradient(ellipse at 60% 50%, rgba(201, 168, 76, 0.08) 0%, transparent 60%),
                linear-gradient(135deg, #0d0d0d 0%, #1a1410 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 1px 1px, rgba(201, 168, 76, 0.07) 1px, transparent 0);
            background-size: 40px 40px;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 600px;
        }

        .hero-label {
            font-size: 11px;
            color: var(--gold);
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .hero-label::before {
            content: '';
            width: 32px;
            height: 1px;
            background: var(--gold);
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 62px;
            font-weight: 600;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero-title em {
            font-style: italic;
            color: var(--gold);
        }

        .hero-desc {
            color: var(--muted);
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 36px;
            max-width: 480px;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
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
            <div class="hero-content">
                <div class="hero-label">Bộ sưu tập mới 2026</div>
                <h1 class="hero-title">
                    Trang sức<br>
                    <em>Cao Cấp</em><br>
                    Việt Nam
                </h1>
                <p class="hero-desc">
                    Mỗi sản phẩm được chế tác thủ công từ vàng 18K, bạch kim và kim cương thiên nhiên — mang vẻ đẹp vượt
                    thời gian.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('products.index') }}" class="btn btn-gold">Khám phá ngay</a>
                    <a href="#featured" class="btn btn-outline">Xem bộ sưu tập</a>
                </div>
            </div>
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
                    <div style="font-size:48px;margin-bottom:16px">💎</div>
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