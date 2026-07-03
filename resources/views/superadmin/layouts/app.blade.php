<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Super Admin') — LMS SaaS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-border: rgba(255,255,255,.06);
            --accent: #6366f1;
            --accent-light: #818cf8;
            --accent-glow: rgba(99,102,241,.15);
            --topbar-h: 64px;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
            --border: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Inter', sans-serif; background: var(--body-bg); color: var(--text-primary); font-size: 14px; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; z-index: 200;
            display: flex; flex-direction: column;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar-brand {
            padding: 24px 22px 20px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sidebar-brand .brand-logo {
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff; font-weight: 800; flex-shrink: 0;
        }
        .sidebar-brand .brand-text { line-height: 1.2; }
        .sidebar-brand .brand-name { color: #fff; font-weight: 700; font-size: 15px; display: block; }
        .sidebar-brand .brand-sub { color: var(--text-muted); font-size: 11px; display: block; }

        .sidebar-nav { padding: 12px 0; flex: 1; }

        .nav-section {
            color: #475569; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .1em;
            padding: 16px 22px 6px;
        }

        .sidebar .nav-item { margin: 2px 10px; }
        .sidebar .nav-link {
            color: #94a3b8; padding: 10px 14px; border-radius: 8px;
            display: flex; align-items: center; gap: 10px;
            font-size: 13.5px; font-weight: 500; transition: all .15s;
            text-decoration: none;
        }
        .sidebar .nav-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar .nav-link:hover { color: #e2e8f0; background: rgba(255,255,255,.06); }
        .sidebar .nav-link.active {
            color: #fff;
            background: var(--accent-glow);
            box-shadow: inset 3px 0 0 var(--accent);
        }
        .sidebar .nav-link.active i { color: var(--accent-light); }

        .sidebar-footer {
            padding: 16px 10px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sidebar-footer .nav-link { color: #64748b; }
        .sidebar-footer .nav-link:hover { color: var(--danger); background: rgba(239,68,68,.08); }

        /* ── Main ── */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Topbar ── */
        .topbar {
            height: var(--topbar-h);
            background: var(--card-bg);
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 8px; }
        .topbar-breadcrumb { font-size: 13px; color: var(--text-secondary); }
        .topbar-breadcrumb .current { color: var(--text-primary); font-weight: 600; }
        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .topbar-btn {
            width: 36px; height: 36px; border-radius: 8px; border: 1px solid var(--border);
            background: transparent; color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all .15s; font-size: 16px; text-decoration: none;
        }
        .topbar-btn:hover { background: var(--body-bg); color: var(--text-primary); }

        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), #8b5cf6);
            color: #fff; font-weight: 700; font-size: 13px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: 2px solid var(--border);
        }
        .topbar-user-name { font-size: 13px; font-weight: 600; color: var(--text-primary); }
        .topbar-user-role { font-size: 11px; color: var(--text-muted); }

        /* ── Content ── */
        .content-area { padding: 28px; flex: 1; }

        /* ── Stat Cards ── */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px 24px;
            display: flex; align-items: center; gap: 16px;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); transform: translateY(-2px); }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.indigo { background: rgba(99,102,241,.12); color: #6366f1; }
        .stat-icon.green  { background: rgba(16,185,129,.12); color: #10b981; }
        .stat-icon.amber  { background: rgba(245,158,11,.12);  color: #f59e0b; }
        .stat-icon.blue   { background: rgba(59,130,246,.12);  color: #3b82f6; }
        .stat-icon.red    { background: rgba(239,68,68,.12);   color: #ef4444; }
        .stat-body { min-width: 0; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--text-primary); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-top: 4px; }
        .stat-trend { font-size: 11px; font-weight: 600; margin-top: 2px; }
        .stat-trend.up { color: var(--success); }

        /* ── Cards ── */
        .sa-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }
        .sa-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .sa-card-header .title { font-weight: 700; font-size: 14px; color: var(--text-primary); }

        /* ── Table ── */
        .sa-table { width: 100%; border-collapse: collapse; }
        .sa-table thead th {
            background: #f8fafc; color: var(--text-muted);
            font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
            padding: 12px 16px; border-bottom: 1px solid var(--border); white-space: nowrap;
        }
        .sa-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .1s; }
        .sa-table tbody tr:last-child { border-bottom: none; }
        .sa-table tbody tr:hover { background: #f8fafc; }
        .sa-table tbody td { padding: 14px 16px; vertical-align: middle; }

        /* ── Badges ── */
        .sa-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;
        }
        .sa-badge::before { content:''; width:6px; height:6px; border-radius:50%; display:inline-block; }
        .sa-badge.active  { background:#dcfce7; color:#15803d; } .sa-badge.active::before  { background:#16a34a; }
        .sa-badge.trial   { background:#fef9c3; color:#854d0e; } .sa-badge.trial::before   { background:#d97706; }
        .sa-badge.suspended { background:#fee2e2; color:#dc2626; } .sa-badge.suspended::before { background:#ef4444; }
        .sa-badge.pending { background:#e0f2fe; color:#0369a1; } .sa-badge.pending::before { background:#0ea5e9; }

        /* ── Buttons ── */
        .btn-sa {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
            border: none; cursor: pointer; transition: all .15s; text-decoration: none;
        }
        .btn-sa-primary { background: var(--accent); color: #fff; }
        .btn-sa-primary:hover { background: #4f46e5; color: #fff; }
        .btn-sa-outline { background: transparent; border: 1px solid var(--border); color: var(--text-secondary); }
        .btn-sa-outline:hover { background: var(--body-bg); color: var(--text-primary); border-color: #cbd5e1; }
        .btn-sa-danger { background: transparent; border: 1px solid #fecaca; color: var(--danger); }
        .btn-sa-danger:hover { background: #fee2e2; }
        .btn-sa-success { background: transparent; border: 1px solid #bbf7d0; color: var(--success); }
        .btn-sa-success:hover { background: #dcfce7; }
        .btn-sa-sm { padding: 5px 12px; font-size: 12px; }

        /* ── Alert ── */
        .sa-alert { border-radius: 10px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 500; margin-bottom: 20px; }
        .sa-alert.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .sa-alert.error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }

        /* ── Form Controls ── */
        .sa-form-label { font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; display: block; }
        .sa-form-control {
            width: 100%; padding: 9px 12px; border: 1px solid var(--border);
            border-radius: 8px; font-size: 13px; font-family: 'Inter', sans-serif;
            background: #fff; color: var(--text-primary); transition: border-color .15s, box-shadow .15s;
        }
        .sa-form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }

        /* ── Page header ── */
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 0 0 4px; }
        .page-header p { font-size: 13px; color: var(--text-muted); margin: 0; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">S</div>
            <div class="brand-text">
                <span class="brand-name">LMS SaaS</span>
                <span class="brand-sub">Super Admin</span>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <div class="nav-item">
            <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </div>

        <div class="nav-section">Management</div>
        <div class="nav-item">
            <a href="{{ route('superadmin.organizations.index') }}" class="nav-link {{ request()->routeIs('superadmin.organizations.*') ? 'active' : '' }}">
                <i class="bi bi-building-fill"></i> Organizations
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('superadmin.plans.index') }}" class="nav-link {{ request()->routeIs('superadmin.plans.*') && !request()->routeIs('superadmin.plans.pricing') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i> Subscription Plans
            </a>
        </div>

        <div class="nav-section">SaaS</div>
        <div class="nav-item">
            <a href="{{ route('superadmin.plans.pricing') }}" class="nav-link {{ request()->routeIs('superadmin.plans.pricing') ? 'active' : '' }}">
                <i class="bi bi-tag-fill"></i> Pricing Page
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ url('/') }}" target="_blank" class="nav-link" onclick="openSiteAsGuest(event, '{{ url('/') }}')">
                <i class="bi bi-globe2"></i> View Site
            </a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="nav-item">
            <a href="{{ route('superadmin.logout') }}" class="nav-link">
                <i class="bi bi-box-arrow-right"></i> Sign Out
            </a>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-content">

    {{-- Topbar --}}
    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-breadcrumb">
                <span class="text-muted">Super Admin</span>
                <span class="text-muted mx-1">/</span>
                <span class="current">@yield('page-title', 'Dashboard')</span>
            </span>
        </div>
        <div class="topbar-right">
            {{-- <a href="{{ url('/') }}" target="_blank" class="topbar-btn" title="View Site" onclick="openSiteAsGuest(event, '{{ url('/') }}')">
                <i class="bi bi-globe2"></i>
            </a> --}}
            <div class="d-flex align-items-center gap-2">
                <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->full_name ?? 'S', 0, 1)) }}</div>
                <div>
                    <div class="topbar-user-name">{{ auth()->user()->full_name ?? 'Super Admin' }}</div>
                    <div class="topbar-user-role">Super Administrator</div>
                </div>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <div class="content-area">
        @if(session('success'))
            <div class="sa-alert success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="sa-alert error"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openSiteAsGuest(e, url) {
    e.preventDefault();
    // Open a blank window first, then POST to the preview route to get a guest view
    window.open('{{ route("superadmin.preview.site") }}', '_blank');
}
</script>
@yield('scripts')
</body>
</html>
