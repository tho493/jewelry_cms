<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} – {{ config('app.name') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Admin CSS -->
    <style>
        :root {
            --sidebar-w: 260px;
            --gold: #c9a84c;
            --gold-dark: #a8832e;
            --bg: #0f1117;
            --surface: #1a1d27;
            --surface2: #222535;
            --border: rgba(255,255,255,0.08);
            --text: #e8e9ef;
            --muted: #8b8fa8;
            --danger: #e05252;
            --success: #4caf7d;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            z-index: 100; overflow-y: auto;
        }
        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo .gem { font-size: 22px; }
        .sidebar-logo span { font-size: 16px; font-weight: 700; color: var(--gold); letter-spacing: 0.5px; }
        .sidebar-logo small { display: block; font-size: 10px; color: var(--muted); letter-spacing: 1px; text-transform: uppercase; }

        .nav-section { padding: 12px 12px 4px; font-size: 10px; color: var(--muted); letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600; }
        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px; border-radius: 8px; margin: 2px 8px;
            color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.2s;
        }
        .nav-link:hover { background: var(--surface2); color: var(--text); }
        .nav-link.active { background: rgba(201,168,76,0.15); color: var(--gold); }
        .nav-link svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: var(--gold); color: #000; font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 99px; }

        .sidebar-footer {
            margin-top: auto; padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-card { display: flex; align-items: center; gap: 10px; }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: #000;
        }
        .user-name { font-size: 13px; font-weight: 600; }
        .user-role { font-size: 11px; color: var(--gold); }
        .btn-logout {
            margin-left: auto;
            background: none; border: none; cursor: pointer;
            color: var(--muted); transition: color 0.2s;
        }
        .btn-logout:hover { color: var(--danger); }

        /* ── Main ────────────────────────────────────── */
        .main { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        .topbar {
            position: sticky; top: 0; z-index: 50;
            background: rgba(15,17,23,0.85); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: 0 28px; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .page-title { font-size: 17px; font-weight: 600; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .content { padding: 28px; flex: 1; }

        /* ── Buttons ─────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px; border-radius: 8px; font-size: 14px; font-weight: 500;
            cursor: pointer; border: none; transition: all 0.2s; text-decoration: none;
        }
        .btn-primary { background: var(--gold); color: #000; }
        .btn-primary:hover { background: var(--gold-dark); }
        .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { border-color: var(--gold); color: var(--gold); }
        .btn-danger { background: rgba(224,82,82,0.15); color: var(--danger); border: 1px solid rgba(224,82,82,0.3); }
        .btn-danger:hover { background: var(--danger); color: #fff; }
        .btn-sm { padding: 5px 12px; font-size: 13px; }

        /* ── Cards ───────────────────────────────────── */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; }
        .card-header { padding: 18px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-header h3 { font-size: 15px; font-weight: 600; }
        .card-body { padding: 22px; }

        /* ── Stats ───────────────────────────────────── */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 12px;
            padding: 20px; position: relative; overflow: hidden;
        }
        .stat-card::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: var(--gold); }
        .stat-value { font-size: 28px; font-weight: 700; color: var(--gold); }
        .stat-label { font-size: 13px; color: var(--muted); margin-top: 4px; }

        /* ── Table ───────────────────────────────────── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 16px; text-align: left; font-size: 11px; color: var(--muted); text-transform: uppercase; letter-spacing: 0.8px; font-weight: 600; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── Badges ──────────────────────────────────── */
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
        .badge-published { background: rgba(76,175,125,0.15); color: var(--success); }
        .badge-draft { background: rgba(139,143,168,0.15); color: var(--muted); }

        /* ── Forms ───────────────────────────────────── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 500; color: var(--muted); margin-bottom: 6px; }
        .form-label span.req { color: var(--danger); }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 8px; color: var(--text); font-size: 14px; font-family: inherit;
            transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: var(--gold); }
        .form-control::placeholder { color: var(--muted); }
        select.form-control option { background: var(--surface2); }
        .form-error { font-size: 12px; color: var(--danger); margin-top: 4px; }

        /* ── Alerts ──────────────────────────────────── */
        .alert { padding: 13px 18px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: rgba(76,175,125,0.12); border: 1px solid rgba(76,175,125,0.3); color: var(--success); }
        .alert-error { background: rgba(224,82,82,0.12); border: 1px solid rgba(224,82,82,0.3); color: var(--danger); }

        /* ── Grid layout for create/edit ─────────────── */
        .grid-2 { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }
        @media (max-width: 900px) { .grid-2 { grid-template-columns: 1fr; } }

        /* ── Product thumbnails ───────────────────────── */
        .thumb { width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border); }
        .thumb-placeholder { width: 52px; height: 52px; border-radius: 8px; border: 1px solid var(--border); background: var(--surface2); display: flex; align-items: center; justify-content: center; color: var(--muted); font-size: 18px; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<nav class="sidebar">
    <div class="sidebar-logo">
        <div class="gem">💎</div>
        <div>
            <span>{{ config('app.name') }}</span>
            <small>Admin Panel</small>
        </div>
    </div>

    <div class="nav-section">Tổng quan</div>
    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
        Dashboard
    </a>

    <div class="nav-section">Quản lý</div>
    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        Sản phẩm
    </a>
    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        Danh mục
    </a>

    <div class="nav-section">Website</div>
    <a href="{{ route('home') }}" target="_blank" class="nav-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        Xem website
    </a>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Admin</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="btn-logout">
                @csrf
                <button type="submit" class="btn-logout" title="Đăng xuất">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main">
    <div class="topbar">
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠ {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
