<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Dewi Beauty Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9F5F0;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }

        .btn-primary {
            background-color: #C9A57F;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #D6AD60;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm z-10">
        <div class=" mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <button id="sidebar-toggle" class="mr-4 text-dark focus:outline-none md:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="index.html" class="flex items-center">
                    <span class="text-2xl font-serif font-bold text-primary">DEWI</span>
                    <span class="ml-2 text-sm font-light text-dark">ADMIN</span>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="text-gray-500 focus:outline-none">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>

                <div class="relative">
                    <button class="flex items-center focus:outline-none">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Admin"
                            class="w-8 h-8 rounded-full">
                        <span class="ml-2 text-sm font-medium text-gray-700 hidden md:block">Admin</span>
                        <i class="fas fa-chevron-down ml-1 text-xs text-gray-500"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar bg-sidebar-bg text-sidebar-text w-64 fixed inset-y-0 left-0 z-20 md:relative md:translate-x-0 overflow-y-auto">
            <div class="p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-serif font-bold text-primary">Dashboard</h2>
                    <p class="text-xs text-gray-400">Kelola salon Anda</p>
                </div>

                <nav class="space-y-1">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('dashboard')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    {{-- Admin --}}
                    <a href="{{ route('admin.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-user-shield w-5"></i>
                        <span class="ml-3">Admin</span>
                    </a>

                    {{-- Booked --}}
                    <a href="{{ route('booked.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.booked.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span class="ml-3">Booked</span>
                    </a>

                    {{-- Pelanggan --}}
                    <a href="{{ route('pelanggan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.pelanggan.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Pelanggan</span>
                    </a>

                    {{-- Pembayaran --}}
                    <a href="{{ route('pembayaran.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.pembayaran.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-credit-card w-5"></i>
                        <span class="ml-3">Pembayaran</span>
                    </a>

                    {{-- Pemesanan --}}
                    <a href="{{ route('pemesanan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.pemesanan.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3">Pemesanan</span>
                    </a>

                    {{-- Perawatan --}}
                    <a href="{{ route('perawatan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('perawatan.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-spa w-5"></i>
                        <span class="ml-3">Perawatan</span>
                    </a>

                    {{-- Laporan --}}
                    {{-- <a href="{{ route('report') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('admin.report')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="ml-3">Laporan</span>
                    </a> --}}
                </nav>

            </div>

            <div class="p-6 border-t border-gray-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex items-center px-4 py-3 text-sidebar-text hover:text-sidebar-hover rounded-sm w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

    <script>
        // Sidebar Toggle
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    </script>


    @stack('modals')

    @livewireScripts
</body>

</html>
