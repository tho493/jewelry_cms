@extends('layouts.admin')

@section('page-title', 'Đội ngũ Thành viên')

@section('topbar-actions')
    <a href="{{ route('admin.team-members.create') }}" class="btn btn-primary btn-sm">+ Thêm thành viên mới</a>
@endsection

@section('content')
@if(session('success'))
<div style="background:var(--success);color:#fff;padding:12px;border-radius:8px;margin-bottom:20px;">
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div style="overflow-x:auto;">
        <table style="width:100%;text-align:left;border-collapse:collapse;">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th style="padding:16px;color:var(--muted)">Avatar</th>
                    <th style="padding:16px;color:var(--muted)">Họ Tên</th>
                    <th style="padding:16px;color:var(--muted)">Vai trò</th>
                    <th style="padding:16px;color:var(--muted)">Link liên kết</th>
                    <th style="padding:16px;color:var(--muted)">Sắp xếp</th>
                    <th style="padding:16px;color:var(--muted);text-align:right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $m)
                <tr style="border-bottom:1px solid var(--border)">
                    <td style="padding:16px">
                        @if($m->avatar_path)
                            <img src="{{ Storage::disk('public')->url($m->avatar_path) }}" style="width:48px;height:48px;object-fit:cover;border-radius:50%">
                        @else
                            <div style="width:48px;height:48px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;color:var(--muted)">N/A</div>
                        @endif
                    </td>
                    <td style="padding:16px;font-weight:600">{{ $m->name }}</td>
                    <td style="padding:16px">{{ $m->role }}</td>
                    <td style="padding:16px">
                        @if($m->custom_link)
                            <a href="{{ $m->custom_link }}" target="_blank" style="color:var(--gold)">Xem Link</a>
                        @else 
                            - 
                        @endif
                    </td>
                    <td style="padding:16px">{{ $m->sort_order }}</td>
                    <td style="padding:16px;text-align:right">
                        <a href="{{ route('admin.team-members.edit', $m) }}" class="btn btn-secondary btn-sm" style="margin-bottom:4px">Chỉnh sửa</a>
                        <form action="{{ route('admin.team-members.destroy', $m) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thành viên {{ $m->name }}?');">
                            @csrf @method('DELETE')
                            <button class="btn" style="background:var(--danger);color:#fff;border:none;padding:5px 10px;font-size:12px;border-radius:6px;cursor:pointer">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding:32px;text-align:center;color:var(--muted)">Chưa có thành viên nào trong danh sách. Hãy thêm mới!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
