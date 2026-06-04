<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ \App\Models\CompanySetting::get('company_name', 'HR Sistem') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/sb-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}">
    @stack('styles')
</head>
<body class="sb-nav-fixed">

    <!-- Top Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('dashboard') }}">HR Sistem</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>

        <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0"></div>

        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            @auth
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell fa-fw"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.65rem;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                        <span class="d-none d-md-inline ms-1">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><h6 class="dropdown-header">
                            @foreach(auth()->user()->getRoleNames() as $role)
                                <span class="badge bg-secondary me-1">{{ ucfirst($role) }}</span>
                            @endforeach
                        </h6></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-circle fa-fw me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @endauth
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">

                        @auth
                            @if(auth()->user()->hasRole('owner'))
                                <!-- Owner Menu -->
                                <div class="sb-sidenav-menu-heading">MENU UTAMA</div>
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                    Karyawan
                                </a>
                                <a class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                    Absensi
                                </a>
                                <a class="nav-link collapsed {{ request()->routeIs('leaves.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOwnerLeaves" aria-expanded="{{ request()->routeIs('leaves.*') ? 'true' : 'false' }}" aria-controls="collapseOwnerLeaves">
                                    <div class="sb-nav-link-icon"><i class="fas fa-umbrella-beach"></i></div>
                                    Cuti
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('leaves.*') ? 'show' : '' }}" id="collapseOwnerLeaves" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}" href="{{ route('leaves.index') }}">Daftar Cuti</a>
                                        <a class="nav-link {{ request()->routeIs('leaves.index') && request('status') === 'pending' ? 'active' : '' }}" href="{{ route('leaves.index') }}?status=pending">Persetujuan Cuti</a>
                                    </nav>
                                </div>
                                <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Penggajian
                                </a>
                                <a class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}" href="{{ route('performance.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                                    Evaluasi
                                </a>
                                <a class="nav-link collapsed {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOwnerReports" aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}" aria-controls="collapseOwnerReports">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                    Laporan
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="collapseOwnerReports" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('reports.employees') ? 'active' : '' }}" href="{{ route('reports.employees') }}">Karyawan</a>
                                        <a class="nav-link {{ request()->routeIs('reports.attendance') ? 'active' : '' }}" href="{{ route('reports.attendance') }}">Absensi</a>
                                        <a class="nav-link {{ request()->routeIs('reports.leaves') ? 'active' : '' }}" href="{{ route('reports.leaves') }}">Cuti</a>
                                        <a class="nav-link {{ request()->routeIs('reports.payroll') ? 'active' : '' }}" href="{{ route('reports.payroll') }}">Penggajian</a>
                                    </nav>
                                </div>
                                <a class="nav-link {{ request()->routeIs('audit-logs.*') ? 'active' : '' }}" href="{{ route('audit-logs.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                                    Audit Log
                                </a>

                            @elseif(auth()->user()->hasRole('admin'))
                                <!-- Admin Menu -->
                                <div class="sb-sidenav-menu-heading">UTAMA</div>
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>

                                <div class="sb-sidenav-menu-heading">MASTER DATA</div>
                                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                    Karyawan
                                </a>
                                <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                    Departemen
                                </a>
                                <a class="nav-link {{ request()->routeIs('positions.*') ? 'active' : '' }}" href="{{ route('positions.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-briefcase"></i></div>
                                    Jabatan
                                </a>

                                <div class="sb-sidenav-menu-heading">OPERASIONAL</div>
                                <a class="nav-link collapsed {{ request()->routeIs('attendance.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAttendance" aria-expanded="{{ request()->routeIs('attendance.*') ? 'true' : 'false' }}" aria-controls="collapseAttendance">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                    Absensi
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('attendance.*') ? 'show' : '' }}" id="collapseAttendance" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">Daftar Absensi</a>
                                        <a class="nav-link {{ request()->routeIs('attendance.create') ? 'active' : '' }}" href="{{ route('attendance.create') }}">Tambah Manual</a>
                                        <a class="nav-link {{ request()->routeIs('attendance.recap') ? 'active' : '' }}" href="{{ route('attendance.recap') }}">Rekap Bulanan</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed {{ request()->routeIs('leaves.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLeaves" aria-expanded="{{ request()->routeIs('leaves.*') ? 'true' : 'false' }}" aria-controls="collapseLeaves">
                                    <div class="sb-nav-link-icon"><i class="fas fa-umbrella-beach"></i></div>
                                    Cuti
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('leaves.*') ? 'show' : '' }}" id="collapseLeaves" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('leaves.index') ? 'active' : '' }}" href="{{ route('leaves.index') }}">Semua Cuti</a>
                                        <a class="nav-link {{ request()->routeIs('leaves.balance') ? 'active' : '' }}" href="{{ route('leaves.balance') }}">Saldo Cuti</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePayroll" aria-expanded="{{ request()->routeIs('payroll.*') ? 'true' : 'false' }}" aria-controls="collapsePayroll">
                                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Penggajian
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('payroll.*') ? 'show' : '' }}" id="collapsePayroll" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('payroll.index') ? 'active' : '' }}" href="{{ route('payroll.index') }}">Daftar Payroll</a>
                                        <a class="nav-link {{ request()->routeIs('payroll.create') ? 'active' : '' }}" href="{{ route('payroll.create') }}">Buat Payroll</a>
                                    </nav>
                                </div>
                                <a class="nav-link {{ request()->routeIs('contracts.*') ? 'active' : '' }}" href="{{ route('contracts.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-file-contract"></i></div>
                                    Kontrak
                                </a>
                                <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-folder-open"></i></div>
                                    Dokumen
                                </a>

                                <div class="sb-sidenav-menu-heading">LAINNYA</div>
                                <a class="nav-link {{ request()->routeIs('performance.*') ? 'active' : '' }}" href="{{ route('performance.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-star"></i></div>
                                    Evaluasi
                                </a>
                                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                                    Pengumuman
                                </a>
                                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-bar"></i></div>
                                    Laporan
                                </a>
                                <a class="nav-link {{ request()->routeIs('company.*') ? 'active' : '' }}" href="{{ route('company.settings') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                    Pengaturan Perusahaan
                                </a>

                            @elseif(auth()->user()->hasRole('karyawan'))
                                <!-- Karyawan Menu -->
                                <div class="sb-sidenav-menu-heading">MENU</div>
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                    Profil Saya
                                </a>
                                <a class="nav-link {{ request()->routeIs('attendance.index') ? 'active' : '' }}" href="{{ route('attendance.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                    Absensi
                                </a>
                                <a class="nav-link collapsed {{ request()->routeIs('leaves.*') ? 'active' : '' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapseKaryawanLeaves" aria-expanded="{{ request()->routeIs('leaves.*') ? 'true' : 'false' }}" aria-controls="collapseKaryawanLeaves">
                                    <div class="sb-nav-link-icon"><i class="fas fa-umbrella-beach"></i></div>
                                    Cuti
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse {{ request()->routeIs('leaves.*') ? 'show' : '' }}" id="collapseKaryawanLeaves" data-bs-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link {{ request()->routeIs('leaves.own') ? 'active' : '' }}" href="{{ route('leaves.own') }}">Cuti Saya</a>
                                        <a class="nav-link {{ request()->routeIs('leaves.create-own') ? 'active' : '' }}" href="{{ route('leaves.create') }}">Ajukan Cuti</a>
                                    </nav>
                                </div>
                                <a class="nav-link {{ request()->routeIs('payroll.own') ? 'active' : '' }}" href="{{ route('payroll.own') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    Slip Gaji
                                </a>
                                <a class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-bullhorn"></i></div>
                                    Pengumuman
                                </a>

                            @else
                                <!-- Fallback menu -->
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                    Dashboard
                                </a>
                            @endif
                        @endauth

                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ auth()->user()->name ?? 'Guest' }}
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">@yield('title', 'Dashboard')</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        @yield('breadcrumb')
                    </ol>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; HR Sistem {{ date('Y') }}</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin.js') }}"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
