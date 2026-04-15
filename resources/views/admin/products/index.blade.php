@extends('layouts.admin')

@section('page-title', 'Sản phẩm')

@section('topbar-actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Tất cả sản phẩm ({{ $products->total() }})</h3>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Media</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
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
                            <div style="font-size:12px;color:var(--muted)">{{ Str::limit($product->short_description, 60) }}</div>
                        </td>
                        <td style="color:var(--muted)">{{ $product->category?->name ?? '—' }}</td>
                        <td>{{ $product->price ? number_format($product->price) . 'đ' : '—' }}</td>
                        <td style="color:var(--muted)">{{ $product->media_count ?? 0 }} files</td>
                        <td>
                            <span class="badge badge-{{ $product->status }}">
                                {{ $product->status === 'published' ? 'Xuất bản' : 'Nháp' }}
                            </span>
                        </td>
                        <td style="color:var(--muted)">{{ $product->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Sửa</a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Xóa sản phẩm này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;color:var(--muted);padding:40px">Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}" style="color:var(--gold)">Tạo ngay</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
