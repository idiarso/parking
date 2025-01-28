<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Parkir') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @yield('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('logo.png') }}" alt="Sistem Parkir Logo" height="40">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">Daftar</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar & Content -->
        <div class="container-fluid">
            <div class="row">
                @auth
                    <!-- Sidebar -->
                    <nav class="col-md-2 d-md-block bg-light sidebar">
                        <div class="position-sticky">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}" href="{{ route('kendaraan.index') }}">
                                        <i class="fas fa-car"></i> Manajemen Kendaraan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('menu.parkir.*') ? 'active' : '' }}" href="{{ route('menu.parkir.sistem') }}">
                                        <i class="fas fa-parking me-2"></i>
                                        <span>Sistem Parkir</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                                        <i class="fas fa-chart-bar"></i> Laporan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('menu.pengaturan.*') ? 'active' : '' }}" href="{{ route('menu.pengaturan.index') }}">
                                        <i class="fas fa-cogs me-2"></i>
                                        <span>Pengaturan</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('menu.keamanan.*') ? 'active' : '' }}" href="{{ route('menu.keamanan.index') }}">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <span>Keamanan Sistem</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                @endauth

                <!-- Main Content -->
                <main class="col-md-10 ms-sm-auto px-md-4">
                    @yield('content')
                </main>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-auto py-3 bg-light">
            <div class="container text-center">
                <span class="text-muted"> {{ date('Y') }} Sistem Parkir. All Rights Reserved.</span>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
</body>
</html>
