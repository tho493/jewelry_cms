@extends('layouts.admin')

@section('page-title', 'Thêm danh mục')

@section('topbar-actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
@endsection

@section('content')
<div style="max-width:600px">
    <div class="card">
        <div class="card-header"><h3>Tạo danh mục mới</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tên danh mục <span class="req">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nhẫn, Dây chuyền..." required>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Mô tả danh mục...">{{ old('description') }}</textarea>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Tạo danh mục</button>
            </form>
        </div>
    </div>
</div>
@endsection
