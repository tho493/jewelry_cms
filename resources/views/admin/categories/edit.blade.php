@extends('layouts.admin')

@section('page-title', 'Sửa danh mục')

@section('topbar-actions')
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
@endsection

@section('content')
    <div style="max-width:600px">
        <div class="card">
            <div class="card-header">
                <h3>Sửa: {{ $category->name }}</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Tên danh mục <span class="req">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}"
                            required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Slug (tự động)</label>
                        <input type="text" class="form-control" value="{{ $category->slug }}" disabled style="opacity:.5">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control"
                            rows="4">{{ old('description', $category->description) }}</textarea>
                    </div>
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Lưu</button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                            onsubmit="return confirm('Xóa danh mục?')" style="flex:1">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                                Xóa</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection