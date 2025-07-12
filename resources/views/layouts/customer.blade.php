<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard') - Dewi Beauty Salon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #C9A57F;
            --secondary: #F9F5F0;
            --dark: #1A1A1A;
            --light: #FFFFFF;
            --accent: #D6AD60;
            --sidebar-bg: #1A1A1A;
            --sidebar-text: #F9F5F0;
            --sidebar-hover: #C9A57F;
            --text-muted: #6B7280;
            --border-light: rgba(201, 165, 127, 0.1);
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background: var(--secondary);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
            color: var(--dark);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            color: var(--primary) !important;
            font-size: 1.25rem;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid rgba(201, 165, 127, 0.15);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: rgba(249, 245, 240, 0.8);
            padding: 0.75rem 1.25rem;
            border-radius: 6px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-size: 13px;
            font-weight: 400;
            position: relative;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: var(--dark);
            background: var(--primary);
            transform: translateX(3px);
        }

        .sidebar .nav-link i {
            width: 16px;
            margin-right: 10px;
            font-size: 14px;
        }

        .sidebar-brand {
            background: rgba(201, 165, 127, 0.08);
            border-radius: 8px;
            padding: 1.5rem 1rem;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid rgba(201, 165, 127, 0.1);
        }

        .sidebar-brand h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            color: var(--primary);
        }

        .sidebar-brand p {
            color: var(--sidebar-text);
            opacity: 0.8;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 0;
            font-weight: 300;
        }

        .sidebar-brand small {
            color: var(--sidebar-text);
            opacity: 0.6;
            font-size: 10px;
            font-style: italic;
        }

        .main-content {
            background: var(--secondary);
            min-height: 100vh;
            border-radius: 0;
            margin-left: 250px;
        }

        .header-section {
            background: linear-gradient(135deg, var(--dark) 0%, #2C2C2C 100%);
            border-radius: 8px;
            color: var(--secondary);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(201, 165, 127, 0.1);
        }

        .header-section h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            font-size: 1.5rem;
            color: var(--secondary);
            margin-bottom: 0.25rem;
        }

        .header-section p {
            font-size: 13px;
            opacity: 0.8;
            margin: 0;
        }

        .header-section .fa-sparkles {
            color: var(--primary);
            font-size: 1.2rem;
        }

        .alert {
            border-radius: 6px;
            border: none;
            font-size: 13px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #059669;
            border-left: 3px solid #10b981;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border-left: 3px solid #ef4444;
        }

        .btn-outline-light {
            background: rgba(201, 165, 127, 0.1);
            border: 1px solid rgba(201, 165, 127, 0.3);
            color: var(--sidebar-text);
            border-radius: 6px;
            padding: 0.5rem 1rem;
            font-weight: 400;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .btn-outline-light:hover {
            background: var(--primary);
            color: var(--dark);
            border-color: var(--primary);
        }

        .notification-bell {
            background: rgba(201, 165, 127, 0.1);
            border: 1px solid rgba(201, 165, 127, 0.2);
            color: var(--secondary);
            padding: 0.5rem;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.2s ease;
            position: relative;
        }

        .notification-bell:hover {
            background: var(--primary);
            color: var(--dark);
        }

        .notification-bell::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            border: 1px solid var(--dark);
        }

        .user-section {
            background: rgba(201, 165, 127, 0.08);
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            border: 1px solid rgba(201, 165, 127, 0.1);
        }

        .user-section p {
            color: var(--sidebar-text);
            margin-bottom: 0.75rem;
            font-size: 12px;
        }

        .user-section strong {
            color: var(--primary);
            font-weight: 500;
        }

        /* Typography */
        .h2,
        h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 500;
            color: var(--dark);
        }

        h2 {
            font-size: 1.4rem;
        }

        h3 {
            font-size: 1.2rem;
        }

        h4 {
            font-size: 1.1rem;
        }

        h5 {
            font-size: 1rem;
        }

        h6 {
            font-size: 0.9rem;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        /* Borders */
        .border-bottom {
            border-bottom: 1px solid var(--border-light) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -250px;
                width: 250px;
                height: 100vh;
                z-index: 1000;
                transition: left 0.3s ease;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .header-section {
                padding: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .sidebar-brand {
                padding: 1.25rem 0.75rem;
                margin-bottom: 1.25rem;
            }

            .sidebar .nav-link {
                padding: 0.75rem 1rem;
                font-size: 13px;
            }

            .user-section {
                padding: 0.875rem;
                margin-top: 1.25rem;
            }
        }

        @media (min-width: 769px) {

            .col-md-3,
            .col-lg-2 {
                flex: none;
                width: 250px;
            }

            .col-md-9,
            .col-lg-10 {
                flex: none;
                width: calc(100% - 250px);
            }
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: var(--dark);
            border: 1px solid rgba(201, 165, 127, 0.2);
            color: var(--primary);
            padding: 0.5rem;
            border-radius: 6px;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
        }

        /* Content styling */
        .content-wrapper {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 0.5rem;
            border: 1px solid var(--border-light);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Form elements */
        .form-control,
        .form-select {
            border: 1px solid var(--border-light);
            border-radius: 6px;
            font-size: 13px;
            padding: 0.5rem 0.75rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(201, 165, 127, 0.15);
        }

        /* Buttons */
        .btn {
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--dark);
        }

        .btn-primary:hover {
            background: var(--accent);
            border-color: var(--accent);
            color: var(--dark);
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 12px;
        }

        /* Tables */
        .table {
            font-size: 13px;
        }

        .table th {
            font-weight: 500;
            border-bottom: 1px solid var(--border-light);
            color: var(--dark);
        }

        .table td {
            border-bottom: 1px solid rgba(201, 165, 127, 0.05);
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-light);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: rgba(201, 165, 127, 0.05);
            border-bottom: 1px solid var(--border-light);
            font-weight: 500;
        }

        /* Badges */
        .badge {
            font-size: 11px;
            font-weight: 500;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(201, 165, 127, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }

        /* Subtle animations */
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Hover states */
        .nav-link:hover {
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Focus states */
        .btn:focus,
        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(201, 165, 127, 0.15);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar">
                <div class="position-sticky pt-3">
                    <div class="sidebar-brand">
                        <h4>Dewi</h4>
                        <p>Beauty Salon</p>
                        <small>Customer Panel</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
                                href="{{ route('customer.dashboard') }}">
                                <i class="fas fa-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.booking.history') ? 'active' : '' }}"
                                href="{{ route('customer.booking.history') }}">
                                <i class="fas fa-calendar"></i>
                                Riwayat Booking
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.booking.create') ? 'active' : '' }}"
                                href="{{ route('customer.booking.create') }}">
                                <i class="fas fa-plus"></i>
                                Booking Baru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}"
                                href="{{ route('customer.profile') }}">
                                <i class="fas fa-user"></i>
                                Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reviews.index') ? 'active' : '' }}"
                                href="{{ route('reviews.index') }}">
                                <i class="fas fa-star"></i>
                                Ulasan
                            </a>
                        </li>
                    </ul>

                    <hr style="border-color: rgba(201, 165, 127, 0.2); margin: 1.5rem 0;">

                    <div class="user-section text-center">
                        <p class="mb-2">
                            <i class="fas fa-user-circle me-1" style="color: var(--primary);"></i>
                            Halo, <strong>{{ Auth::guard('pelanggan')->user()->nama_lengkap ?? 'Pelanggan' }}</strong>
                        </p>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                                <i class="fas fa-sign-out-alt me-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="header-section">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <div>
                            <h1 class="mb-1">
                                <i class="fas fa-sparkles me-2"></i>
                                @yield('title', 'Dashboard')
                            </h1>
                            <p>Selamat datang kembali di Dewi Beauty Salon</p>
                        </div>

                    </div>
                </div>

                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Content Wrapper -->
                <div class="content-wrapper fade-in">
                    <!-- Content -->
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.mobile-menu-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Form submission loading state
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }
            });
        });

        // Enhanced focus management
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus first input in forms
            const firstInput = document.querySelector('form input:not([type="hidden"]):first-of-type');
            if (firstInput && window.innerWidth > 768) {
                setTimeout(() => firstInput.focus(), 100);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
