@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('topbar-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
@endsection

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
        <div class="stat-label">Tổng sản phẩm</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['published']) }}</div>
        <div class="stat-label">Đã xuất bản</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['draft']) }}</div>
        <div class="stat-label">Bản nháp</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['total_categories']) }}</div>
        <div class="stat-label">Danh mục</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($stats['total_media']) }}</div>
        <div class="stat-label">Media files</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Sản phẩm mới nhất</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">Xem tất cả</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestProducts as $product)
                    <tr>
                        <td>
                            @if($product->coverImage())
                                <img src="{{ $product->coverImage()->thumbnail_url }}" class="thumb" alt="">
                            @else
                                <div class="thumb-placeholder">💎</div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:500">{{ $product->name }}</div>
                            <div style="font-size:12px;color:var(--muted)">{{ $product->short_description }}</div>
                        </td>
                        <td style="color:var(--muted)">{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ $product->price ? number_format($product->price) . 'đ' : '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $product->status }}">
                                {{ $product->status === 'published' ? 'Đã xuất bản' : 'Nháp' }}
                            </span>
                        </td>
                        <td style="color:var(--muted)">{{ $product->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Sửa</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" style="text-align:center;color:var(--muted);padding:40px">Chưa có sản phẩm nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
