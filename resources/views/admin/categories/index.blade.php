@extends('layouts.admin')

@section('page-title', 'Danh mục')

@section('topbar-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Thêm danh mục</a>
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3>Tất cả danh mục ({{ $categories->total() }})</h3>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tên danh mục</th>
                    <th>Slug</th>
                    <th>Mô tả</th>
                    <th>Sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><div style="font-weight:500">{{ $category->name }}</div></td>
                        <td><code style="font-size:12px;color:var(--gold)">{{ $category->slug }}</code></td>
                        <td style="color:var(--muted)">{{ Str::limit($category->description, 60) }}</td>
                        <td>
                            <span class="badge" style="background:rgba(201,168,76,0.1);color:var(--gold)">
                                {{ $category->products_count }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-secondary btn-sm">Sửa</a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:40px">Chưa có danh mục nào.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--border)">
            {{ $categories->links() }}
        </div>
    @endif
</div>

@endsection
