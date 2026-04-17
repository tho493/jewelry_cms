@extends('layouts.admin')

@section('page-title', 'Tài khoản của tôi')

@section('content')
    <div class="grid-2">
        <!-- Cập nhật thông tin profile -->
        <div class="card">
            <div class="card-header">
                <h3>Thông tin cá nhân</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <!-- Tên -->
                    <div class="form-group">
                        <label class="form-label" for="name">Họ và Tên <span class="req">*</span></label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            required autofocus autocomplete="name" />
                        @if($errors->has('name'))
                            <div class="form-error">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label class="form-label" for="username">Tên đăng nhập (Username)</label>
                        <input class="form-control" id="username" name="username" type="text"
                            value="{{ old('username', $user->username) }}" autocomplete="username" />
                        <small style="color: var(--muted); font-size: 11px;">Viết liền, không dấu (vd: admin,
                            tho493)</small>
                        @if($errors->has('username'))
                            <div class="form-error">{{ $errors->first('username') }}</div>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">Email <span class="req">*</span></label>
                        <input class="form-control" id="email" name="email" type="email"
                            value="{{ old('email', $user->email) }}" required autocomplete="email" />
                        @if($errors->has('email'))
                            <div class="form-error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4" style="display: flex; align-items: center; gap: 16px;">
                        <button class="btn btn-primary" type="submit">Lưu thông tin</button>
                        @if (session('status') === 'profile-updated')
                            <span style="color: var(--success); font-size: 13px;">Đã lưu thành công.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Đổi mật khẩu -->
        <div class="card">
            <div class="card-header">
                <h3>Đổi mật khẩu</h3>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                        <input class="form-control" id="current_password" name="current_password" type="password"
                            autocomplete="current-password" />
                        @if($errors->updatePassword->has('current_password'))
                            <div class="form-error">{{ $errors->updatePassword->first('current_password') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật khẩu mới</label>
                        <input class="form-control" id="password" name="password" type="password"
                            autocomplete="new-password" />
                        @if($errors->updatePassword->has('password'))
                            <div class="form-error">{{ $errors->updatePassword->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Xác nhận mật khẩu mới</label>
                        <input class="form-control" id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" />
                        @if($errors->updatePassword->has('password_confirmation'))
                            <div class="form-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                        @endif
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <button class="btn btn-primary" type="submit">Đổi mật khẩu</button>
                        @if (session('status') === 'password-updated')
                            <span style="color: var(--success); font-size: 13px;">Mật khẩu đã được đổi.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection