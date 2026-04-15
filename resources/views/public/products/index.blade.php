@extends('layouts.public')

@section('title', 'Sản phẩm trang sức – ' . config('app.name'))
@section('meta_description', 'Khám phá toàn bộ bộ sưu tập trang sức: nhẫn, dây chuyền, vòng tay, bông tai cao cấp.')

@section('content')

<div style="padding: 56px 0;">
    <div class="container">
        <!-- Page heading -->
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:36px; flex-wrap:wrap; gap:16px">
            <div>
                <h1 style="font-family:'Cormorant Garamond',serif; font-size:36px; font-weight:600">Sản phẩm</h1>
                <p style="color:var(--muted); font-size:14px; margin-top:4px">{{ $products->total() }} sản phẩm</p>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('products.index') }}" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center">
                <select name="danh_muc" onchange="this.form.submit()" style="background:var(--surface);border:1px solid var(--border);color:var(--text);padding:9px 14px;border-radius:8px;font-size:13px;cursor:pointer">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('danh_muc') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="sap_xep" onchange="this.form.submit()" style="background:var(--surface);border:1px solid var(--border);color:var(--text);padding:9px 14px;border-radius:8px;font-size:13px;cursor:pointer">
                    <option value="">Mới nhất</option>
                    <option value="gia_tang" {{ request('sap_xep') === 'gia_tang' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="gia_giam" {{ request('sap_xep') === 'gia_giam' ? 'selected' : '' }}>Giá giảm dần</option>
                </select>
                <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}" placeholder="Tìm kiếm..." style="background:var(--surface);border:1px solid var(--border);color:var(--text);padding:9px 14px;border-radius:8px;font-size:13px;width:200px">
                <button type="submit" class="btn btn-gold" style="padding:9px 18px">Tìm</button>
                @if(request()->hasAny(['danh_muc','sap_xep','tim_kiem']))
                    <a href="{{ route('products.index') }}" style="color:var(--muted);font-size:13px">Xóa bộ lọc</a>
                @endif
            </form>
        </div>

        <!-- Products grid -->
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
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
                            @if($product->short_description)
                                <p style="font-size:13px;color:var(--muted);margin:6px 0 10px;line-height:1.5">{{ Str::limit($product->short_description, 80) }}</p>
                            @endif
                            <div class="product-card-price">
                                {{ $product->price ? number_format($product->price) . 'đ' : 'Liên hệ' }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div style="margin-top:48px; display:flex; justify-content:center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div style="text-align:center; padding:100px 0; color:var(--muted)">
                <div style="font-size:56px; margin-bottom:20px">🔍</div>
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:28px; margin-bottom:10px">Không tìm thấy sản phẩm</h2>
                <p>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline" style="margin-top:24px">Xem tất cả</a>
            </div>
        @endif
    </div>
</div>

@endsection
