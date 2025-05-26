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
                    <button class="flex items-center focus:outline-none">
                        <span class="ml-2 text-sm font-medium text-gray-700 hidden md:block">Admin</span>
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
                       {{ request()->routeIs('admin.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-user-shield w-5"></i>
                        <span class="ml-3">Admin</span>
                    </a>

                    {{-- Booked --}}
                    <a href="{{ route('booked.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('booked.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-calendar-check w-5"></i>
                        <span class="ml-3">Booked</span>
                    </a>

                    {{-- Pelanggan --}}
                    <a href="{{ route('pelanggan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('pelanggan.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Pelanggan</span>
                    </a>

                    {{-- Pembayaran --}}
                    <a href="{{ route('pembayaran.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('pembayaran.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-credit-card w-5"></i>
                        <span class="ml-3">Pembayaran</span>
                    </a>

                    {{-- Pemesanan --}}
                    <a href="{{ route('pemesanan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('pemesanan.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="ml-3">Pemesanan</span>
                    </a>

                    {{-- Perawatan --}}
                    <a href="{{ route('perawatan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('perawatan.*')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-spa w-5"></i>
                        <span class="ml-3">Perawatan</span>
                    </a>

                    {{-- Karyawan --}}
                    <a href="{{ route('karyawan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                    {{ request()->routeIs('karyawan.*')
                        ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                        : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-user w-5"></i>
                        <span class="ml-3">Karyawan</span>
                    </a>

                    {{-- Shift --}}
                    <a href="{{ route('shift.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                    {{ request()->routeIs('shift.*')
                        ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                        : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-clock w-5"></i>
                        <span class="ml-3">Shift</span>
                    </a>


                    {{-- Laporan --}}
                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center px-4 py-3 rounded-sm
                       {{ request()->routeIs('laporan.index')
                           ? 'text-sidebar-hover bg-opacity-20 bg-sidebar-hover'
                           : 'text-sidebar-text hover:text-sidebar-hover' }}">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span class="ml-3">Laporan</span>
                    </a>

                    <!-- Trigger Button -->
                    <button type="button" onclick="openLogoutModal()"
                        class="flex items-center mt-12 px-4 py-3 text-sidebar-text hover:text-sidebar-hover rounded-sm w-full text-left">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-3">Keluar</span>
                    </button>

                </nav>

            </div>



        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 overflow-y-auto">
            <div class="mx-auto">
                {{ $slot }}

                <!-- Logout Modal -->
                <div id="logout-modal-overlay"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
                    <div id="logout-modal-box"
                        class="bg-white rounded-xl shadow-2xl p-6 w-80 relative opacity-0 scale-95 translate-y-4 transition-all duration-300">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Konfirmasi Logout</h3>
                        <p class="mb-6 text-gray-600">Yakin ingin keluar dari akun ini?</p>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeLogoutModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                                Batal
                            </button>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function openLogoutModal() {
                        const overlay = document.getElementById('logout-modal-overlay');
                        const modal = document.getElementById('logout-modal-box');

                        overlay.classList.remove('hidden');
                        setTimeout(() => {
                            modal.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
                            modal.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                        }, 10);

                        overlay.addEventListener('click', function(e) {
                            if (e.target === overlay) {
                                closeLogoutModal();
                            }
                        });
                    }

                    function closeLogoutModal() {
                        const overlay = document.getElementById('logout-modal-overlay');
                        const modal = document.getElementById('logout-modal-box');

                        modal.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                        modal.classList.add('opacity-0', 'scale-95', 'translate-y-4');

                        setTimeout(() => {
                            overlay.classList.add('hidden');
                        }, 200);
                    }
                </script>
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
