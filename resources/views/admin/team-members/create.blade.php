@extends('layouts.admin')

@section('page-title', 'Tạo Thành viên mới')

@section('content')
<form action="{{ route('admin.team-members.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="card" style="max-width: 650px;">
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label">Họ tên <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Ví dụ: Nguyễn Văn A" required>
                    @error('name')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Vai trò (Role)</label>
                    <input type="text" name="role" class="form-control" value="{{ old('role') }}" placeholder="Ví dụ: Giám đốc sáng tạo">
                    @error('role')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link liên hệ</label>
                <input type="url" name="custom_link" class="form-control" value="{{ old('custom_link') }}" placeholder="Ví dụ: https://instagram.com/nguyenvana">
                @error('custom_link')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Ảnh đại diện (Avatar)</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
                @error('avatar')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
                <small style="color:var(--muted);display:block;margin-top:5px;">Chỉ hỗ trợ file ảnh (JPG, PNG). Dung lượng tối đa: 5MB. Tỉ lệ khuyên dùng: 1:1 vuông.</small>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tiểu sử ngắn gọn</label>
                <textarea name="bio" class="form-control" rows="4" placeholder="Một đoạn văn mô tả ngắn về thành tích hoặc triết lý của nhân sự này...">{{ old('bio') }}</textarea>
                @error('bio')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mức độ ưu tiên (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
                <small style="color:var(--muted)">Số càng nhỏ sẽ càng xuất hiện trên cùng.</small>
            </div>
            
            <div style="margin-top:24px;display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">Lưu Thành Viên</button>
                <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary">Hủy bỏ</a>
            </div>
        </div>
    </div>
</form>
@endsection
