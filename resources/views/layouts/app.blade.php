<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HR Sistem') - {{ \App\Models\CompanySetting::get('company_name', 'HR Sistem') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1e2a3a;
            --sidebar-color: #a8b8cc;
            --sidebar-hover: #2d4159;
            --sidebar-active: #3b5998;
            --header-bg: #ffffff;
            --primary: #3b5998;
        }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s;
        }
        #sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        #sidebar .sidebar-header h5 {
            color: #fff;
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }
        #sidebar .sidebar-header small {
            color: var(--sidebar-color);
            font-size: 0.75rem;
        }
        #sidebar .nav-section-title {
            color: #6c7fa8;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 20px 4px;
        }
        #sidebar .nav-link {
            color: var(--sidebar-color);
            padding: 10px 20px;
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            border-radius: 0;
            transition: all 0.2s;
        }
        #sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }
        #sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: #fff;
            border-left: 3px solid #6ea6ff;
        }
        #sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
            font-size: 0.875rem;
        }
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }
        .navbar-top {
            background: var(--header-bg);
            border-bottom: 1px solid #e0e4ec;
            padding: 0 20px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .page-content { padding: 24px; }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); }
        .card-header { background: #fff; border-bottom: 1px solid #f0f2f5; padding: 16px 20px; font-weight: 600; border-radius: 12px 12px 0 0 !important; }
        .stat-card { border-radius: 12px; padding: 20px; color: #fff; }
        .stat-card .stat-icon { width: 50px; height: 50px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
        .stat-card .stat-number { font-size: 2rem; font-weight: 700; line-height: 1; }
        .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; margin-top: 4px; }
        .badge-role-owner { background: #6f42c1; }
        .badge-role-admin { background: #0d6efd; }
        .badge-role-karyawan { background: #198754; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: #2d4785; border-color: #2d4785; }
        .table th { font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; color: #6c757d; background: #f8f9fc; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-title { font-size: 1.4rem; font-weight: 700; color: #1e2a3a; margin: 0; }
        .breadcrumb { font-size: 0.8rem; }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            @php $logo = \App\Models\CompanySetting::get('company_logo'); @endphp
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="Logo" style="height: 36px; margin-right: 10px; border-radius: 6px;">
            @else
                <div style="width:36px; height:36px; background:#3b5998; border-radius:8px; display:flex; align-items:center; justify-content:center; margin-right:10px;">
                    <i class="fas fa-users text-white" style="font-size:1rem;"></i>
                </div>
            @endif
            <div>
                <h5>{{ \App\Models\CompanySetting::get('company_name', 'HR Sistem') }}</h5>
                <small>{{ \App\Models\CompanySetting::get('company_address', '') }}</small>
            </div>
        </div>
    </div>

    <nav class="mt-2">
        <div class="nav-section-title">MENU UTAMA</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        @auth
            @if(auth()->user()->hasRole(['owner', 'admin']))
                <a href="{{ route('employees.index') }}" class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Karyawan
                </a>
            @endif

            @if(auth()->user()->hasRole('karyawan'))
                <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Profil Saya
                </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <div class="nav-section-title">DATA MASTER</div>
                <a href="{{ route('departments.index') }}" class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Departemen
                </a>
                <a href="{{ route('positions.index') }}" class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i> Jabatan
                </a>
            @endif

            <div class="nav-section-title">ABSENSI & CUTI</div>
            @if(auth()->user()->hasRole('karyawan'))
                <a href="{{ route('attendance.own') }}" class="nav-link {{ request()->is('attendance*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Absensi Saya
                </a>
                <a href="{{ route('leaves.own') }}" class="nav-link {{ request()->is('leaves*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i> Cuti Saya
                </a>
            @else
                <a href="/attendance" class="nav-link {{ request()->is('attendance') || request()->is('attendance?*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Absensi
                </a>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('attendance.recap') }}" class="nav-link {{ request()->routeIs('attendance.recap') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Rekap Absensi
                    </a>
                @endif
                <a href="/leaves" class="nav-link {{ request()->is('leaves') || request()->is('leaves?*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-times"></i> Cuti
                </a>
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('leaves.balance') }}" class="nav-link {{ request()->routeIs('leaves.balance') ? 'active' : '' }}">
                        <i class="fas fa-balance-scale"></i> Saldo Cuti
                    </a>
                @endif
            @endif

            <div class="nav-section-title">PENGGAJIAN</div>
            @if(auth()->user()->hasRole('karyawan'))
                <a href="{{ route('payroll.own') }}" class="nav-link {{ request()->is('payroll*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Slip Gaji
                </a>
            @else
                <a href="/payroll" class="nav-link {{ request()->is('payroll') || request()->is('payroll?*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Penggajian
                </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <div class="nav-section-title">KARYAWAN</div>
                <a href="{{ route('contracts.index') }}" class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                    <i class="fas fa-file-contract"></i> Kontrak Kerja
                </a>
                <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="fas fa-folder-open"></i> Dokumen
                </a>
            @endif

            @if(auth()->user()->hasRole(['admin', 'owner']))
                <a href="{{ route('performance.index') }}" class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Evaluasi Kinerja
                </a>
            @endif

            <div class="nav-section-title">INFORMASI</div>
            <a href="/announcements" class="nav-link {{ request()->is('announcements*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i> Pengumuman
            </a>

            @if(auth()->user()->hasRole(['admin', 'owner']))
                <div class="nav-section-title">LAPORAN</div>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Laporan
                </a>
            @endif

            @if(auth()->user()->hasRole(['admin', 'owner']))
                <a href="{{ route('audit-logs.index') }}" class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Audit Log
                </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
                <div class="nav-section-title">PENGATURAN</div>
                <a href="{{ route('company.settings') }}" class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Pengaturan Perusahaan
                </a>
            @endif
        @endauth
    </nav>
</div>

<!-- Main Content -->
<div id="main-content">
    <!-- Top Navbar -->
    <div class="navbar-top">
        <div class="d-flex align-items-center">
            <button class="btn btn-sm btn-outline-secondary me-3 d-md-none" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <nav aria-label="breadcrumb" class="d-none d-md-block">
                <ol class="breadcrumb mb-0">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-3">
            @auth
                <!-- Notifications -->
                @php
                    $unreadCount = auth()->user()->unreadNotificationsCount();
                @endphp
                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-secondary position-relative">
                    <i class="fas fa-bell"></i>
                    @if($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.65rem;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- User menu -->
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        @if(auth()->user()->employee?->photo)
                            <img src="{{ Storage::url(auth()->user()->employee->photo) }}" alt="Avatar" class="rounded-circle" style="width:28px; height:28px; object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:28px; height:28px; font-size:0.75rem; font-weight:600;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="d-none d-md-block">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">
                            @foreach(auth()->user()->getRoleNames() as $role)
                                <span class="badge badge-role-{{ $role }}">{{ ucfirst($role) }}</span>
                            @endforeach
                        </h6></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('toggleSidebar')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });
</script>
@stack('scripts')
</body>
</html>
