@extends('layouts.admin')

@section('page-title', 'Cấu hình Thông tin Dự án (About Us)')

@section('content')
@if(session('success'))
<div style="background:var(--success);color:#fff;padding:12px;border-radius:8px;margin-bottom:20px;">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="card" style="margin-bottom:20px">
        <div class="card-header"><h3>Mô tả Dự án (About the Project)</h3></div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label">Tiêu đề dự án / Trang</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $about->title) }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Bài viết giới thiệu (Thông tin chi tiết)</label>
                <textarea name="content" id="editor" class="form-control" rows="20" placeholder="Viết giới thiệu về dự án ở đây...">{{ old('content', $about->content) }}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="padding: 12px 24px; font-size: 16px;">Lưu Thay Đổi</button>
</form>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key') }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor',
        plugins: 'anchor autolink lists link image table wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright justify | bullist numlist | link image | table',
        skin: 'oxide-dark',
        content_css: 'dark',
    });
</script>
@endpush
