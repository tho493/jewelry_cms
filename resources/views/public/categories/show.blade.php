@extends('layouts.public')

@section('title', $category->name . ' – ' . config('app.name'))
@section('meta_description', $category->description ?: 'Sản phẩm ' . $category->name . ' cao cấp tại ' . config('app.name'))

@section('content')

<div style="padding: 56px 0;">
    <div class="container">
        <div style="margin-bottom:40px">
            <a href="{{ route('home') }}" style="color:var(--muted);font-size:13px">Trang chủ</a>
            <span style="color:rgba(255,255,255,0.2);margin:0 6px">/</span>
            <span style="color:var(--gold);font-size:13px">{{ $category->name }}</span>
        </div>

        <div class="section-heading" style="text-align:left;margin-bottom:36px">
            <h1 style="font-family:'Cormorant Garamond',serif;font-size:40px;font-weight:600">{{ $category->name }}</h1>
            @if($category->description)
                <p style="color:var(--muted);margin-top:8px">{{ $category->description }}</p>
            @endif
            <p style="color:var(--muted);font-size:13px;margin-top:6px">{{ $products->total() }} sản phẩm</p>
        </div>

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
                            <div class="product-card-name">{{ $product->name }}</div>
                            <div class="product-card-price">{{ $product->price ? number_format($product->price) . 'đ' : 'Liên hệ' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($products->hasPages())
                <div style="margin-top:48px;display:flex;justify-content:center">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div style="text-align:center;padding:80px 0;color:var(--muted)">
                <div style="font-size:48px;margin-bottom:16px">💎</div>
                <p>Danh mục này chưa có sản phẩm.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline" style="margin-top:20px">Xem tất cả sản phẩm</a>
            </div>
        @endif
    </div>
</div>

@endsection
