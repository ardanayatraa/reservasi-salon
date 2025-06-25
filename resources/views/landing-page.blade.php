<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dewi Beauty Salon | Pengalaman Kecantikan Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Midtrans Script -->
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        // Global data untuk JavaScript
        window.isLoggedIn = @json(auth()->check());
        window.userData = @json($user ?? null);
        window.servicesData = @json($services ?? []);
        window.shiftsData = @json($shifts ?? []);
        window.timeSlotsData = @json($timeSlots ?? []);
    </script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#FF6B9D',
                        secondary: '#F9F5F0',
                        dark: '#1A1A1A',
                        light: '#FFFFFF',
                        accent: '#FED2E2',
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    },
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&display=swap');

        /* Prevent horizontal scroll */
        html,
        body {
            overflow-x: hidden;
            max-width: 100vw;
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFFFF;
        }

        /* Ensure all containers respect viewport width */
        * {
            box-sizing: border-box;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (min-width: 640px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (min-width: 1024px) {
            .container {
                max-width: 1200px;
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }

        .font-serif {
            font-family: 'Cormorant Garamond', serif;
        }

        .text-shadow {
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .service-card {
            transition: all 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-8px);
        }

        .gradient-overlay {
            background: linear-gradient(to bottom, rgba(26, 26, 26, 0.1), rgba(26, 26, 26, 0.7));
        }

        .btn-primary {
            background-color: #FF6B9D;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #ff5288;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-outline {
            border: 2px solid #FF6B9D;
            color: #FF6B9D;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background-color: #FF6B9D;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .review-card {
            transition: all 0.3s ease;
        }

        .review-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Modal Styles - Fixed for mobile */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            overflow-y: auto;
            padding: 1rem;
        }

        .modal-content {
            background-color: #fff;
            margin: 0 auto;
            max-width: 800px;
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
            max-height: calc(100vh - 2rem);
            overflow-y: auto;
        }

        @media (max-width: 767px) {
            .modal {
                padding: 0.5rem;
            }

            .modal-content {
                max-height: calc(100vh - 1rem);
                border-radius: 0.25rem;
            }
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .modal-close:hover {
            color: #1A1A1A;
        }

        /* Stepper Styles - Mobile responsive */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
            padding: 0 1rem;
        }

        @media (max-width: 767px) {
            .stepper {
                margin-bottom: 1rem;
                padding: 0 0.5rem;
            }
        }

        .stepper::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #e5e7eb;
            transform: translateY(-50%);
            z-index: 1;
        }

        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            position: relative;
            z-index: 2;
            font-size: 0.875rem;
        }

        @media (max-width: 767px) {
            .step {
                width: 32px;
                height: 32px;
                font-size: 0.75rem;
            }
        }

        .step.active {
            background-color: #FF6B9D;
            border-color: #FF6B9D;
            color: white;
        }

        .step.completed {
            background-color: #FF6B9D;
            border-color: #FF6B9D;
            color: white;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .progress-bar {
            height: 2px;
            background-color: #FF6B9D;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            z-index: 1;
            transition: width 0.3s ease;
        }

        /* Date picker styles */
        .date-picker {
            position: relative;
        }

        .date-picker input[type="date"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            background-color: white;
            color: #374151;
        }

        .date-picker input[type="date"]:focus {
            outline: none;
            border-color: #FF6B9D;
            box-shadow: 0 0 0 3px rgba(255, 107, 157, 0.1);
        }

        .date-picker input[type="date"]:disabled {
            background-color: #f9fafb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Time slot responsive grid */
        .time-slot {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.75rem;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }

        @media (max-width: 767px) {
            .time-slot {
                padding: 0.5rem;
                font-size: 0.75rem;
            }
        }

        .time-slot:hover:not(.disabled):not(.unavailable) {
            border-color: #FF6B9D;
            background-color: #fff0f5;
        }

        .time-slot.selected {
            border-color: #FF6B9D;
            background-color: #fff0f5;
            color: #FF6B9D;
            font-weight: 600;
        }

        .time-slot.unavailable {
            background-color: #fee2e2 !important;
            color: #dc2626 !important;
            cursor: not-allowed !important;
            opacity: 0.6;
        }

        .time-slot.unavailable:hover {
            background-color: #fee2e2 !important;
            border-color: #dc2626 !important;
        }

        .time-slot.disabled {
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
            cursor: not-allowed !important;
            opacity: 0.5;
        }

        .time-slot.disabled:hover {
            background-color: #f3f4f6 !important;
            border-color: #e5e7eb !important;
        }

        .time-slot.checking {
            background-color: #fef3c7;
            border-color: #f59e0b;
            cursor: wait;
        }

        /* Service selection responsive */
        .selected-service {
            background-color: #fff0f5;
            border-left: 3px solid #FF6B9D;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 4px;
            transition: all 0.3s ease;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .selected-service:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsive table */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive table {
            min-width: 600px;
        }

        @media (max-width: 767px) {
            .table-responsive table {
                min-width: 500px;
                font-size: 0.75rem;
            }

            .table-responsive th,
            .table-responsive td {
                padding: 0.5rem 0.25rem;
            }
        }

        /* Prevent text overflow */
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Grid responsive fixes */
        .grid-responsive {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .grid-responsive {
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            }
        }

        /* Availability indicator */
        .availability-indicator {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            font-size: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .availability-indicator.available {
            background-color: #10b981;
            color: white;
        }

        .availability-indicator.unavailable {
            background-color: #ef4444;
            color: white;
        }

        .availability-indicator.checking {
            background-color: #f59e0b;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white bg-opacity-95 shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center">
                    <span class="text-2xl md:text-3xl font-serif font-bold text-primary">DEWI</span>
                    <span class="ml-2 text-sm md:text-lg font-light text-dark">BEAUTY SALON</span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="#home"
                        class="text-dark hover:text-primary transition duration-300 font-light">Beranda</a>
                    <a href="#about"
                        class="text-dark hover:text-primary transition duration-300 font-light">Tentang</a>
                    <a href="#services"
                        class="text-dark hover:text-primary transition duration-300 font-light">Layanan</a>
                    <a href="#history"
                        class="text-dark hover:text-primary transition duration-300 font-light">History</a>
                </div>

                <div class="flex items-center space-x-4">
                    {{-- Pelanggan menggunakan auth() default --}}
                    @if (!auth()->guard('admin')->user())
                        <a href="/login"
                            class="hidden md:block px-4 lg:px-6 py-2 btn-outline rounded-sm font-light text-sm">
                            LOGIN
                        </a>
                    @else
                        <form action="/logout" method="POST" class="hidden md:block">
                            @csrf
                            <button type="submit" class="px-4 lg:px-6 py-2 btn-outline rounded-sm font-light text-sm">
                                LOGOUT
                            </button>
                        </form>
                    @endif

                    {{-- Admin menggunakan guard 'admin' --}}
                    @if (auth()->guard('admin')->user())
                        <a href="/dashboard"
                            class="hidden md:block px-4 lg:px-6 py-2 btn-outline rounded-sm font-light text-sm">
                            DASHBOARD ADMIN
                        </a>
                    @endif

                    <button id="menu-toggle" class="md:hidden text-dark focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4">
                <div class="flex flex-col space-y-4">
                    <a href="#home" class="text-dark hover:text-primary transition duration-300">Beranda</a>
                    <a href="#about" class="text-dark hover:text-primary transition duration-300">Tentang</a>
                    <a href="#services" class="text-dark hover:text-primary transition duration-300">Layanan</a>
                    <a href="#history" class="text-dark hover:text-primary transition duration-300">History</a>

                    @guest('pelanggan')
                        <a href="/login"
                            class="block w-full text-center px-6 py-2 border border-primary rounded-sm font-light text-sm hover:bg-primary hover:text-white transition">
                            LOGIN
                        </a>
                    @else
                        <form action="/logout" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="block w-full text-center px-6 py-2 border border-primary rounded-sm font-light text-sm hover:bg-primary hover:text-white transition">
                                LOGOUT
                            </button>
                        </form>
                    @endguest

                    @auth('admin')
                        <a href="/dashboard"
                            class="block w-full text-center px-6 py-2 border border-primary rounded-sm font-light text-sm hover:bg-primary hover:text-white transition">
                            DASHBOARD ADMIN
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative h-screen flex items-center justify-center bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1470259078422-826894b933aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');">
        <div class="absolute inset-0 gradient-overlay"></div>
        <div class="container mx-auto px-4 z-10 text-center">
            <h1 data-aos="fade-up" data-aos-duration="1000"
                class="text-3xl sm:text-5xl md:text-7xl font-serif font-bold text-white mb-6 leading-tight text-shadow">
                TINGKATKAN <br><span class="text-primary">KECANTIKAN ANDA</span>
            </h1>
            <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"
                class="text-base md:text-lg lg:text-xl text-white mb-10 max-w-xl mx-auto font-light px-4">
                Nikmati perawatan kecantikan premium dalam lingkungan yang mewah dan menenangkan
            </p>
            <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400"
                class="flex flex-col sm:flex-row justify-center gap-6 px-4">
                <a href="#services"
                    class="px-6 md:px-8 py-3 btn-primary rounded-sm font-light text-sm uppercase tracking-wider">
                    Layanan Kami
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center gap-8 lg:gap-16">
                <div data-aos="fade-right" class="w-full md:w-1/2">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1470259078422-826894b933aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80"
                            alt="Interior Salon" class="w-full h-auto object-cover">
                        <div class="absolute -bottom-6 -right-6 bg-primary p-6 hidden md:block">
                            <p class="text-white font-serif text-xl">Sejak 2005</p>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left" class="w-full md:w-1/2">
                    <h6 class="text-primary font-light tracking-widest mb-2">TENTANG KAMI</h6>
                    <h2 class="text-2xl md:text-4xl font-serif font-bold text-dark mb-6">Seni Kecantikan</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed text-sm md:text-base">
                        Didirikan pada tahun 2005, Dewi Beauty Salon telah menjadi tempat perawatan kecantikan dan
                        kesehatan selama hampir dua dekade. Perjalanan kami dimulai dengan visi sederhana: menciptakan
                        tempat di mana pelanggan dapat menikmati perawatan kecantikan terbaik dalam suasana mewah dan
                        menenangkan.
                    </p>
                    <p class="text-gray-600 mb-8 leading-relaxed text-sm md:text-base">
                        Dinamai dari dewi kecantikan Bali, Dewi, salon kami mewujudkan esensi ilahi kecantikan yang ada
                        pada setiap individu. Kami percaya bahwa kecantikan sejati berasal dari penghormatan terhadap
                        keunikan fitur seseorang dan meningkatkannya dengan perawatan ahli dan produk premium.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="flex items-start">
                            <div class="text-primary mr-4">
                                <i class="fas fa-spa text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-serif font-bold text-dark mb-2">Visi Kami</h4>
                                <p class="text-gray-600 text-sm">Mendefinisikan ulang standar kecantikan dengan
                                    merayakan individualitas dan keanggunan alami.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="text-primary mr-4">
                                <i class="fas fa-heart text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-serif font-bold text-dark mb-2">Misi Kami</h4>
                                <p class="text-gray-600 text-sm">Menyediakan layanan kecantikan luar biasa yang
                                    meningkatkan penampilan dan kesejahteraan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16 md:py-24 bg-secondary">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">LAYANAN KAMI</h6>
                <h2 data-aos="fade-up" data-aos-delay="100"
                    class="text-2xl md:text-4xl font-serif font-bold text-dark mb-6">
                    Perawatan Kecantikan Premium</h2>
                <p data-aos="fade-up" data-aos-delay="200"
                    class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base px-4">
                    Nikmati pilihan perawatan kecantikan kami yang dirancang khusus untuk meningkatkan kecantikan alami
                    Anda dan memberikan pengalaman yang benar-benar mewah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($services as $service)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}"
                        class="service-card bg-white shadow-sm">
                        <div class="relative h-48 md:h-64 overflow-hidden">
                            <img src="{{ asset('storage/' . $service->foto) }}" alt="{{ $service->nama_perawatan }}"
                                class="w-full h-full object-cover hover-scale">
                        </div>
                        <div class="p-4 md:p-6">
                            <h3 class="text-lg md:text-xl font-serif font-bold text-dark mb-2">
                                {{ $service->nama_perawatan }}</h3>
                            <p class="text-gray-600 mb-4 text-sm">
                                {{ $service->deskripsi ?? 'Perawatan premium untuk kecantikan Anda' }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-primary font-serif text-lg md:text-xl">Rp
                                    {{ number_format($service->harga, 0, ',', '.') }}</span>
                                <button
                                    class="text-primary hover:text-accent transition duration-300 text-sm book-service-btn"
                                    data-service="{{ $service->id_perawatan }}"
                                    data-name="{{ $service->nama_perawatan }}" data-price="{{ $service->harga }}"
                                    data-duration="{{ $service->waktu }}">
                                    Reservasi <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">TESTIMONI</h6>
                <h2 data-aos="fade-up" data-aos-delay="100"
                    class="text-2xl md:text-4xl font-serif font-bold text-dark mb-6">Apa
                    Kata Klien Kami</h2>
                <div data-aos="fade-up" data-aos-delay="200" class="flex items-center justify-center mb-6">
                    <div class="flex items-center">
                        <div class="flex text-primary">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="ml-2 text-dark font-medium">4.9/5</span>
                    </div>
                    <span class="mx-3 text-gray-400">|</span>
                    <span class="text-gray-600 text-sm">Berdasarkan 52 ulasan</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Testimonial 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="review-card bg-white p-6 md:p-8 shadow-sm">
                    <div class="flex text-primary mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6 text-sm">
                        "Ritual Tubuh Emas benar-benar luar biasa! Saya belum pernah mengalami perawatan semewah ini
                        sebelumnya. Kulit saya bersinar selama berminggu-minggu setelahnya, dan stafnya membuat saya
                        merasa seperti ratu."
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Klien"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-serif font-bold text-dark">Siti Rahayu</h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>Pemandu Lokal</span>
                                <span class="mx-1">•</span>
                                <span>2 bulan yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div data-aos="fade-up" data-aos-delay="200" class="review-card bg-white p-6 md:p-8 shadow-sm">
                    <div class="flex text-primary mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6 text-sm">
                        "Saya sudah ke banyak salon, tapi Dewi Beauty Salon menonjol karena layanan luar biasa dan
                        perhatian terhadap detail. Ritual Rambut Mewah mengubah rambut rusak saya menjadi rambut yang
                        halus dan sehat. Sangat sepadan dengan harganya!"
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Klien"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-serif font-bold text-dark">Dewi Putri</h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>Klien VIP</span>
                                <span class="mx-1">•</span>
                                <span>1 bulan yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div data-aos="fade-up" data-aos-delay="400" class="review-card bg-white p-6 md:p-8 shadow-sm">
                    <div class="flex text-primary mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 italic mb-6 text-sm">
                        "Pijat Bali adalah yang saya butuhkan setelah minggu yang penuh stres. Suasana, terapis yang
                        terampil, dan minyak premium menciptakan pengalaman yang benar-benar luar biasa. Saya pasti akan
                        kembali lagi!"
                    </p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Klien"
                            class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h4 class="font-serif font-bold text-dark">Budi Santoso</h4>
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>Klien Baru</span>
                                <span class="mx-1">•</span>
                                <span>3 bulan yang lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- More Reviews Button -->
            <div class="text-center mt-10">
                <a href="https://g.co/kgs/WraDjHe" target="_blank"
                    class="inline-block px-6 md:px-8 py-3 btn-outline rounded-sm font-light text-sm uppercase tracking-wider">
                    Lihat Semua Ulasan
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-24 bg-cover bg-center relative"
        style="background-image: url('https://images.unsplash.com/photo-1470259078422-826894b933aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');">
        <div class="absolute inset-0 gradient-overlay"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 data-aos="fade-up" class="text-2xl md:text-4xl font-serif font-bold text-white mb-6">Rasakan
                    Kecantikan Mewah
                </h2>
                <p data-aos="fade-up" data-aos-delay="200"
                    class="text-base md:text-lg text-white mb-10 font-light px-4">
                    Manjakan diri Anda dengan layanan premium kami dan temukan perbedaan yang dibuat oleh kemewahan.
                </p>
                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="#services"
                        class="inline-block px-6 md:px-8 py-3 btn-primary rounded-sm font-light text-sm uppercase tracking-wider">
                        Reservasi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section id="history" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">RIWAYAT PEMESANAN</h6>
                <h2 data-aos="fade-up" data-aos-delay="100"
                    class="text-2xl md:text-4xl font-serif font-bold text-dark mb-6">
                    Cek Riwayat Anda
                </h2>
                <p data-aos="fade-up" data-aos-delay="200"
                    class="text-gray-600 max-w-2xl mx-auto text-sm md:text-base px-4">
                    Masukkan email Anda untuk melihat riwayat pemesanan sebelumnya.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
                {{-- Kolom Kiri: Form & Tabel Riwayat --}}
                <div data-aos="fade-right">
                    {{-- Form Cek Riwayat --}}
                    <form action="{{ url()->current() }}#history" method="GET"
                        class="bg-secondary p-6 md:p-8 shadow-sm mb-8">
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                Email</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $email ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 bg-white rounded-sm focus:outline-none focus:border-primary"
                                placeholder="Email Anda">
                        </div>
                        <button type="submit"
                            class="w-full btn-primary py-3 px-6 rounded-sm font-light text-sm uppercase tracking-wider">
                            Cek Riwayat
                        </button>
                    </form>

                    {{-- Hanya tampilkan kalau sudah submit --}}
                    @if (!is_null($histories))
                        @if ($histories->count())
                            <div class="table-responsive">
                                <table class="min-w-full bg-white shadow-sm rounded-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Waktu</th>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Layanan</th>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total</th>
                                            <th
                                                class="px-2 md:px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $idx => $order)
                                            <tr class="{{ $idx % 2 ? 'bg-gray-50' : '' }}">
                                                <td class="px-2 md:px-4 py-2 text-sm">
                                                    {{ $histories->firstItem() + $idx }}
                                                </td>
                                                <td class="px-2 md:px-4 py-2 text-sm">
                                                    {{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y') }}
                                                </td>
                                                <td class="px-2 md:px-4 py-2 text-sm">{{ $order->waktu }}</td>
                                                <td class="px-2 md:px-4 py-2 text-sm">
                                                    <div class="text-truncate max-w-32">
                                                        @if ($order->bookeds->count() > 0)
                                                            @foreach ($order->bookeds as $booked)
                                                                {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-2 md:px-4 py-2 text-sm">Rp
                                                    {{ number_format($order->total, 0, ',', '.') }}</td>
                                                <td class="px-2 md:px-4 py-2 text-sm">
                                                    <span
                                                        class="px-2 py-1 text-xs rounded-full
                                                        @if ($order->status_pemesanan == 'confirmed') bg-green-100 text-green-800
                                                        @elseif($order->status_pemesanan == 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($order->status_pemesanan) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination links --}}
                            <div class="mt-4">
                                {{ $histories->links() }}
                            </div>
                        @else
                            <p class="text-center text-gray-600 text-sm">
                                Tidak ada riwayat untuk email <strong>{{ $email }}</strong>.
                            </p>
                        @endif
                    @endif
                </div>

                {{-- Kolom Kanan: Informasi Kontak --}}
                <div data-aos="fade-left">
                    <div class="mb-8">
                        <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-6">Informasi Kontak</h3>

                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-map-marker-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Lokasi Kami</h4>
                                    <p class="text-gray-600 text-sm">
                                        Jl. Raya Mas No.31, MAS, Kecamatan Ubud, Kabupaten Gianyar, Bali 80571,
                                        Indonesia
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-phone-alt text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Nomor Telepon</h4>
                                    <p class="text-gray-600 text-sm">+62 878-6178-6535</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Alamat Email</h4>
                                    <p class="text-gray-600 text-sm">info@dewibeautysalon.com</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Jam Buka</h4>
                                    <p class="text-gray-600 text-sm">Buka setiap hari: 9:00 AM - 7:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-6">Ikuti Kami</h3>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="w-10 h-10 border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://www.instagram.com/dewisalonspa/" target="_blank"
                                class="w-10 h-10 border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#"
                                class="w-10 h-10 border border-primary text-primary hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <a href="#" class="flex items-center mb-6">
                        <span class="text-2xl md:text-3xl font-serif font-bold text-primary">DEWI</span>
                        <span class="ml-2 text-base md:text-lg font-light text-white">BEAUTY SALON</span>
                    </a>
                    <p class="text-gray-400 mb-6 text-sm">
                        Temukan kecantikan ilahi dalam diri Anda di salon kecantikan mewah terbaik di Bali.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/dewisalonspa/" target="_blank"
                            class="text-gray-400 hover:text-primary transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                            <i class="fab fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-serif font-bold mb-6">Tautan Cepat</h3>
                    <ul class="space-y-3">
                        <li><a href="#home"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Beranda</a>
                        </li>
                        <li><a href="#about"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Tentang
                                Kami</a></li>
                        <li><a href="#services"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Layanan</a>
                        </li>
                        <li><a href="#history"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Riwayat</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-serif font-bold mb-6">Layanan</h3>
                    <ul class="space-y-3">
                        @foreach ($services->take(4) as $service)
                            <li><a href="#services"
                                    class="text-gray-400 hover:text-primary transition duration-300 text-sm">{{ $service->nama_perawatan }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-serif font-bold mb-6">Hubungi Kami</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-3"></i>
                            <span class="text-gray-400 text-sm">Jl. Raya Mas No.31, MAS, Ubud, Bali</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone-alt text-primary mt-1 mr-3"></i>
                            <span class="text-gray-400 text-sm">+62 878-6178-6535</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-primary mt-1 mr-3"></i>
                            <span class="text-gray-400 text-sm">info@dewibeautysalon.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-clock text-primary mt-1 mr-3"></i>
                            <span class="text-gray-400 text-sm">Buka setiap hari: 9:00 AM - 7:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; 2025 Dewi Beauty Salon. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#home"
        class="fixed bottom-8 right-8 bg-primary hover:bg-accent text-white w-10 h-10 flex items-center justify-center shadow-lg transition duration-300 z-50">
        <i class="fas fa-chevron-up"></i>
    </a>

    <!-- Booking Modal -->
    <div id="booking-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>

            <div class="p-4 md:p-6">
                <!-- Stepper -->
                <div class="stepper mb-6 md:mb-8">
                    <div class="progress-bar" id="progress-bar"></div>
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                </div>

                <!-- Step Labels -->
                <div class="flex justify-between text-xs md:text-sm text-gray-600 mb-6 md:mb-8 px-2 md:px-4">
                    <div class="text-center flex-1">Detail & Waktu</div>
                    <div class="text-center flex-1">Pembayaran</div>
                </div>

                <form id="booking-form" action="{{ route('book.service') }}" method="POST">
                    @csrf
                    <input type="hidden" id="booking-date" name="booking_date" value="{{ date('Y-m-d') }}">
                    <input type="hidden" id="selected-time" name="booking_time" value="">

                    <!-- Step 1: Detail Paket & Pilih Waktu -->
                    <div class="step-content active" id="step-1">
                        <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-6">Pilih Layanan & Waktu</h3>

                        <!-- Container untuk layanan yang dipilih -->
                        <div id="selected-services-container" class="mb-6">
                            <!-- Layanan yang dipilih akan ditampilkan di sini -->
                        </div>

                        {{-- Container dinamis untuk baris layanan --}}
                        <div id="services-container" class="space-y-3 mb-6">
                            {{-- Baris template pertama --}}
                            <div
                                class="service-row flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-2">
                                <select name="service_select" class="service-select border p-2 rounded flex-1 w-full">
                                    <option value="">-- Pilih Layanan --</option>
                                    @foreach ($services as $s)
                                        <option value="{{ $s->id_perawatan }}" data-id="{{ $s->id_perawatan }}"
                                            data-name="{{ $s->nama_perawatan }}" data-price="{{ $s->harga }}"
                                            data-duration="{{ $s->waktu }}">
                                            {{ $s->nama_perawatan }} ({{ $s->waktu }} menit)
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button"
                                    class="add-to-selected px-3 py-2 bg-primary text-white rounded hover:bg-accent transition w-full md:w-auto">
                                    Tambah
                                </button>
                            </div>
                        </div>

                        <button type="button" id="add-service-row"
                            class="mb-4 text-primary hover:underline text-sm">
                            + Tambah Layanan Lainnya
                        </button>

                        {{-- Info Durasi --}}
                        <div class="mb-4 text-sm text-gray-700">
                            Total durasi: <span id="displayDurasi">0</span> menit
                            &nbsp;|&nbsp;
                            Selesai (jika mulai dipilih): <span id="displayEnd">–</span>
                        </div>

                        {{-- Pilih Tanggal --}}
                        <div class="mb-6">
                            {{-- Pilih Tanggal --}}
                            <label for="booking-date-picker" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-primary mr-2"></i>Pilih Tanggal
                            </label>
                            <input type="date" id="booking-date-picker"
                                class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-primary mb-6"
                                value="{{ $selectedDate }}" min="{{ now()->toDateString() }}">


                        </div>

                        <!-- Employee availability info -->
                        <div id="employee-info" class="mb-4"></div>

                        {{-- Pilih Shift & Slot --}}
                        <div id="time-slots-container">
                            @foreach ($shifts as $shift)
                                <div class="mb-6 shift-block" data-shift-id="{{ $shift->id_shift }}"
                                    data-start="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}"
                                    data-end="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}">
                                    <h4 class="font-semibold mb-2 text-sm md:text-base">
                                        {{ $shift->nama_shift }}
                                        <span class="text-xs md:text-sm text-gray-500">
                                            ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                                            – {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                        </span>
                                    </h4>
                                    <div class="grid grid-cols-3 md:grid-cols-5 gap-2"
                                        data-shift-name="{{ $shift->nama_shift }}">
                                        @foreach ($timeSlots[$shift->nama_shift] as $time)
                                            <div class="time-slot border cursor-pointer relative"
                                                data-time="{{ $time }}"
                                                onclick="selectSlot('{{ $shift->id_shift }}','{{ $time }}')">
                                                {{ $time }}
                                                <div class="availability-indicator" style="display: none;"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end">
                            <button type="button" id="next-to-step-2"
                                class="px-4 md:px-6 py-2 btn-primary rounded-md text-sm md:text-base" disabled>
                                Lanjutkan
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Payment -->
                    <div class="step-content" id="step-2">
                        <div class="bg-white rounded-lg">
                            <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-6">Detail Pembayaran</h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                                <div>
                                    <h4 class="font-serif font-bold text-base md:text-lg mb-4">Informasi Pelanggan</h4>

                                    <div class="space-y-4">
                                        {{-- Nama Lengkap --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama
                                                Lengkap</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800 text-sm">
                                                {{ $user->nama_lengkap ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800 text-sm">
                                                {{ $user->email ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Nomor Telepon --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                                Telepon</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800 text-sm">
                                                {{ $user->no_telepon ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Alamat --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800 text-sm">
                                                {{ $user->alamat ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-serif font-bold text-base md:text-lg mb-4">Ringkasan Pemesanan</h4>

                                    <div class="bg-gray-50 p-4 rounded-md mb-6">
                                        <div class="space-y-3">
                                            <div id="summary-services-list" class="mb-3">
                                                <!-- Daftar layanan akan ditampilkan di sini -->
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-medium" id="summary-date">-</span>
                                            </div>
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">Waktu:</span>
                                                <span class="font-medium" id="summary-time">-</span>
                                            </div>
                                            <div class="border-t border-gray-200 my-2 pt-2">
                                                <div class="flex justify-between font-bold">
                                                    <span>Total:</span>
                                                    <span class="text-primary" id="summary-price">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h4 class="font-serif font-bold text-base md:text-lg mb-4">Metode Pembayaran</h4>

                                    <div class="space-y-3">
                                        <div class="border border-gray-300 rounded-md p-3 flex items-center">
                                            <input type="radio" id="payment-midtrans" name="payment_method"
                                                value="midtrans" class="mr-3" checked>
                                            <label for="payment-midtrans" class="flex items-center text-sm">
                                                <span class="mr-2">Midtrans</span>
                                                <img src="https://midtrans.com/assets/images/logo-midtrans-color.png"
                                                    alt="Midtrans" class="h-6">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">*Pembayaran akan diproses melalui gateway
                                            Midtrans yang aman.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row justify-between gap-4 mt-8">
                                <button type="button" id="back-to-step-1"
                                    class="px-4 md:px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300 text-sm md:text-base">Kembali</button>
                                <button type="button" id="pay-button"
                                    class="px-4 md:px-6 py-2 btn-primary rounded-md text-sm md:text-base">Bayar
                                    Sekarang</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Step 3: Success -->
                <div class="step-content" id="step-3">
                    <div class="bg-white rounded-lg text-center">
                        <div class="mb-6 text-primary">
                            <i class="fas fa-check-circle text-4xl md:text-6xl"></i>
                        </div>

                        <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-4">Transaksi Berhasil!</h3>
                        <p class="text-gray-600 mb-8 text-sm md:text-base px-4">Terima kasih atas pemesanan Anda.
                            Detail reservasi telah dikirim
                            ke email Anda.</p>

                        <div class="bg-secondary p-4 md:p-6 rounded-md text-left mb-8">
                            <h4 class="font-serif font-bold text-base md:text-lg mb-4">Detail Reservasi</h4>

                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">ID Reservasi:</span>
                                    <span class="font-medium" id="reservation-id">DBS-2024-0001</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium" id="reservation-name">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Layanan:</span>
                                    <span class="font-medium" id="reservation-service">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Tanggal & Waktu:</span>
                                    <span class="font-medium" id="reservation-datetime">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Pembayaran:</span>
                                    <span class="font-medium text-primary" id="reservation-price">-</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium text-green-600">Terkonfirmasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button id="close-booking"
                                class="px-4 md:px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300 text-sm md:text-base">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('status_message'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="z-index: 9999;">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6 relative">
                <button @click="show = false"
                    class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

                <div class="text-center">
                    <div class="text-green-500 text-4xl md:text-5xl mb-4">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-serif font-bold text-dark mb-2">Transaksi Berhasil!</h3>
                    <p class="text-gray-600 mb-6 text-sm md:text-base">{{ session('status_message') }}</p>

                    <div class="bg-secondary p-4 rounded text-left text-sm mb-6">
                        <h4 class="font-serif font-semibold mb-2">Detail Reservasi</h4>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">ID Reservasi:</span>
                                <span class="font-medium" id="s3-id">–</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Nama:</span>
                                <span class="font-medium" id="s3-nama">–</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Layanan:</span>
                                <span class="font-medium" id="s3-layanan">–</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal & Waktu:</span>
                                <span class="font-medium" id="s3-jadwal">–</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total:</span>
                                <span class="font-medium text-primary" id="s3-total">–</span>
                            </div>
                        </div>
                    </div>

                    <button @click="show = false"
                        class="px-4 md:px-6 py-2 bg-primary text-white rounded-md hover:bg-pink-600 transition text-sm md:text-base">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const booking = JSON.parse(localStorage.getItem("bookingData") || "{}")

                document.getElementById("s3-id").textContent = booking.orderId || "-"
                document.getElementById("s3-nama").textContent = booking.customerName || "-"
                document.getElementById("s3-layanan").textContent = booking.serviceName || "-"
                document.getElementById("s3-jadwal").textContent =
                    (booking.bookingDate || "-") + ", " + (booking.bookingTime || "-")
                document.getElementById("s3-total").textContent =
                    booking.price ? "Rp " + booking.price.toLocaleString("id-ID") : "-"

                // Hapus setelah tampil (opsional)
                localStorage.removeItem("bookingData")
            })
        </script>
    @endif

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
        });
    </script>

    <!-- Main Script -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
