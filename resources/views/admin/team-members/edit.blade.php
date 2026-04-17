@extends('layouts.admin')

@section('page-title', 'Chỉnh sửa Thành viên')

@section('content')
<form action="{{ route('admin.team-members.update', $teamMember) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    
    <div class="card" style="max-width: 650px;">
        <div class="card-body">
            @if($teamMember->avatar_path)
            <div style="margin-bottom: 24px; text-align: center;">
                <img src="{{ Storage::disk('public')->url($teamMember->avatar_path) }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 2px solid var(--gold); padding: 4px; background: var(--surface);">
            </div>
            @endif

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label">Họ tên <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $teamMember->name) }}" required>
                    @error('name')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Vai trò (Role)</label>
                    <input type="text" name="role" class="form-control" value="{{ old('role', $teamMember->role) }}">
                    @error('role')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Link liên hệ</label>
                <input type="url" name="custom_link" class="form-control" value="{{ old('custom_link', $teamMember->custom_link) }}">
                @error('custom_link')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Ảnh đại diện mới (Bỏ trống nếu không muốn đổi)</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
                @error('avatar')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Tiểu sử ngắn (Bio)</label>
                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $teamMember->bio) }}</textarea>
                @error('bio')<div style="color:var(--danger);font-size:13px;margin-top:4px">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mức độ ưu tiên (Sort Order)</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $teamMember->sort_order) }}">
                <small style="color:var(--muted)">Số càng nhỏ sẽ càng xuất hiện trên cùng.</small>
            </div>
            
            <div style="margin-top:24px;display:flex;gap:12px;">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ route('admin.team-members.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</form>
@endsection
