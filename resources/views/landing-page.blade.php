<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dewi Beauty Salon | Pengalaman Kecantikan Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Midtrans Script -->
    <script type="text/javascript" src="{{ config('midtrans.snap_url') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // Cek menggunakan guard 'pelanggan'
        window.isLoggedIn = @json(auth()->guard('pelanggan')->check());
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#C68EFD', // Soft purple
                        secondary: '#F9F5F0', // Cream white
                        dark: '#1A1A1A',
                        light: '#FFFFFF',
                        accent: '#FED2E2', // Warm pink
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

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFFFFF;
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
            background-color: #C68EFD;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #b57aec;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-outline {
            border: 2px solid #C68EFD;
            color: #C68EFD;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background-color: #C68EFD;
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

        .google-maps-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #5F6368;
            margin-top: 12px;
        }

        /* Modal Styles */
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
        }

        .modal-content {
            background-color: #fff;
            margin: 2rem auto;
            max-width: 800px;
            border-radius: 0.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: #1A1A1A;
        }

        /* Stepper Styles */
        .stepper {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
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
        }

        .step.active {
            background-color: #C68EFD;
            border-color: #C68EFD;
            color: white;
        }

        .step.completed {
            background-color: #C68EFD;
            border-color: #C68EFD;
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
            background-color: #C68EFD;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            z-index: 1;
            transition: width 0.3s ease;
        }

        .time-slot {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .time-slot:hover {
            border-color: #C68EFD;
            background-color: #f9f5ff;
        }

        .time-slot.selected {
            border-color: #C68EFD;
            background-color: #f9f5ff;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white bg-opacity-95 shadow-sm">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center">
                    <span class="text-3xl font-serif font-bold text-primary">DEWI</span>
                    <span class="ml-2 text-lg font-light text-dark">BEAUTY SALON</span>
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
                    @guest('pelanggan')
                        {{-- Belum login sebagai pelanggan --}}
                        <a href="/login" class="hidden md:block px-6 py-2 btn-outline rounded-sm font-light text-sm">
                            MASUK
                        </a>
                    @else
                        {{-- Logout pelanggan --}}
                        <form action="/logout" method="POST" class="hidden md:block">
                            @csrf
                            <button type="submit" class="px-6 py-2 btn-outline rounded-sm font-light text-sm">
                                KELUAR
                            </button>
                        </form>
                    @endguest

                    @auth('admin')
                        {{-- Jika login sebagai admin --}}
                        <a href="/dashboard" class="hidden md:block px-6 py-2 btn-outline rounded-sm font-light text-sm">
                            DASHBOARD ADMIN
                        </a>
                    @endauth

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
                    <a href="#services"class="text-dark hover:text-primary transition duration-300">Layanan</a>
                    <a href="#history" class="text-dark hover:text-primary transition duration-300">History</a>

                    @guest('pelanggan')
                        {{-- Tampilkan tombol Masuk --}}
                        <a href="/login"
                            class="block w-full text-center px-6 py-2 border border-primary rounded-sm font-light text-sm hover:bg-primary hover:text-white transition">
                            MASUK
                        </a>
                    @else
                        {{-- Tampilkan tombol Keluar --}}
                        <form action="/logout" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="block w-full text-center px-6 py-2 border border-primary rounded-sm font-light text-sm hover:bg-primary hover:text-white transition">
                                KELUAR
                            </button>
                        </form>
                    @endguest

                    @auth('admin')
                        {{-- Tampilkan link Dashboard Admin --}}
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
        <div class="container mx-auto px-6 z-10 text-center">
            <h1 data-aos="fade-up" data-aos-duration="1000"
                class="text-5xl md:text-7xl font-serif font-bold text-white mb-6 leading-tight text-shadow">
                TINGKATKAN <br><span class="text-primary">KECANTIKAN ANDA</span>
            </h1>
            <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200"
                class="text-lg md:text-xl text-white mb-10 max-wxl mx-auto font-light">
                Nikmati perawatan kecantikan premium dalam lingkungan yang mewah dan menenangkan
            </p>
            <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400"
                class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="#services"
                    class="px-8 py-3 btn-primary rounded-sm font-light text-sm uppercase tracking-wider">
                    Layanan Kami
                </a>
                <a href="#contact"
                    class="px-8 py-3 border-2 border-white text-white rounded-sm font-light text-sm uppercase tracking-wider hover:bg-white hover:text-dark transition duration-300">
                    Reservasi
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-center gap-16">
                <div data-aos="fade-right" class="md:w-1/2">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1470259078422-826894b933aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80"
                            alt="Interior Salon" class="w-full h-auto object-cover">
                        <div class="absolute -bottom-6 -right-6 bg-primary p-6 hidden md:block">
                            <p class="text-white font-serif text-xl">Sejak 2005</p>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-left" class="md:w-1/2">
                    <h6 class="text-primary font-light tracking-widest mb-2">TENTANG KAMI</h6>
                    <h2 class="text-4xl font-serif font-bold text-dark mb-6">Seni Kecantikan</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Didirikan pada tahun 2005, Dewi Beauty Salon telah menjadi tempat perawatan kecantikan dan
                        kesehatan selama hampir dua dekade. Perjalanan kami dimulai dengan visi sederhana: menciptakan
                        tempat di mana pelanggan dapat menikmati perawatan kecantikan terbaik dalam suasana mewah dan
                        menenangkan.
                    </p>
                    <p class="text-gray-600 mb-8 leading-relaxed">
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
    <section id="services" class="py-24 bg-secondary">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">LAYANAN KAMI</h6>
                <h2 data-aos="fade-up" data-aos-delay="100" class="text-4xl font-serif font-bold text-dark mb-6">
                    Perawatan Kecantikan Premium</h2>
                <p data-aos="fade-up" data-aos-delay="200" class="text-gray-600 w-full mx-auto">
                    Nikmati pilihan perawatan kecantikan kami yang dirancang khusus untuk meningkatkan kecantikan alami
                    Anda dan memberikan pengalaman yang benar-benar mewah.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($services as $service)
                    <div data-aos="fade-up" data-aos-delay="{{ $loop->index * 200 }}"
                        class="service-card bg-white shadow-sm">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $service['foto'] }}" alt="{{ $service['foto'] }}"
                                class="w-full h-full object-cover hover-scale">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-serif font-bold text-dark mb-2">{{ $service['nama_perawatan'] }}
                            </h3>
                            <p class="text-gray-600 mb-4 text-sm">
                                {{ $service['deskripsi'] }}
                            </p>
                            <div class="flex justify-between items-center">
                                <span class="text-primary font-serif text-xl">Rp
                                    {{ number_format($service['harga'], 0, ',', '.') }}</span>
                                <button
                                    class="text-primary hover:text-accent transition duration-300 text-sm book-service-btn"
                                    data-service="{{ $service['id_perawatan'] }}"
                                    data-name="{{ $service['nama_perawatan'] }}"
                                    data-price="{{ $service['harga'] }}" data-duration="{{ $service['waktu'] }}">
                                    Reservasi <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="#contact"
                    class="inline-block px-8 py-3 btn-primary rounded-sm font-light text-sm uppercase tracking-wider">
                    Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>



    <!-- Testimonials Section -->
    <section id="testimonials" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">TESTIMONI</h6>
                <h2 data-aos="fade-up" data-aos-delay="100" class="text-4xl font-serif font-bold text-dark mb-6">Apa
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
                    <span class="text-gray-600">Berdasarkan 52 ulasan</span>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="review-card bg-white p-8 shadow-sm">
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
                <div data-aos="fade-up" data-aos-delay="200" class="review-card bg-white p-8 shadow-sm">
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
                <div data-aos="fade-up" data-aos-delay="400" class="review-card bg-white p-8 shadow-sm">
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
                    class="inline-block px-8 py-3 btn-outline rounded-sm font-light text-sm uppercase tracking-wider">
                    Lihat Semua Ulasan
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-cover bg-center relative"
        style="background-image: url('https://images.unsplash.com/photo-1470259078422-826894b933aa?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');">
        <div class="absolute inset-0 gradient-overlay"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="w-full mx-auto text-center">
                <h2 data-aos="fade-up" class="text-4xl font-serif font-bold text-white mb-6">Rasakan Kecantikan Mewah
                </h2>
                <p data-aos="fade-up" data-aos-delay="200" class="text-lg text-white mb-10 font-light">
                    Manjakan diri Anda dengan layanan premium kami dan temukan perbedaan yang dibuat oleh kemewahan.
                </p>
                <div data-aos="fade-up" data-aos-delay="400">
                    <a href="#contact"
                        class="inline-block px-8 py-3 btn-primary rounded-sm font-light text-sm uppercase tracking-wider">
                        Reservasi Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="history" class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h6 data-aos="fade-up" class="text-primary font-light tracking-widest mb-2">RIWAYAT PEMESANAN</h6>
                <h2 data-aos="fade-up" data-aos-delay="100" class="text-4xl font-serif font-bold text-dark mb-6">
                    Cek Riwayat Anda
                </h2>
                <p data-aos="fade-up" data-aos-delay="200" class="text-gray-600 w-full mx-auto">
                    Masukkan email Anda untuk melihat riwayat pemesanan sebelumnya.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                {{-- Kolom Kiri: Form & Tabel Riwayat --}}
                <div data-aos="fade-right">
                    {{-- Form Cek Riwayat --}}
                    <form action="{{ url()->current() }}#history" method="GET"
                        class="bg-secondary p-8 shadow-sm mb-8">
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
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white shadow-sm rounded-sm">
                                    <thead>
                                        <!-- ... kepala tabel tetap sama ... -->
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $idx => $order)
                                            <tr class="{{ $idx % 2 ? 'bg-gray-50' : '' }}">
                                                <td class="px-4 py-2 text-sm">{{ $histories->firstItem() + $idx }}
                                                </td>
                                                <td class="px-4 py-2 text-sm">
                                                    {{ \Carbon\Carbon::parse($order->tanggal_pemesanan)->format('d M Y') }}
                                                </td>
                                                <td class="px-4 py-2 text-sm">{{ $order->waktu }}</td>
                                                <td class="px-4 py-2 text-sm">{{ $order->perawatan->nama_perawatan }}
                                                </td>
                                                <td class="px-4 py-2 text-sm">Rp
                                                    {{ number_format($order->total, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 text-sm uppercase">{{ $order->status_pemesanan }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination links --}}
                            <div class="mt-4">
                                {{ $histories->links('vendor.pagination.tailwind') }}
                            </div>
                        @else
                            <p class="text-center text-gray-600">
                                Tidak ada riwayat untuk email <strong>{{ $email }}</strong>.
                            </p>
                        @endif
                    @endif

                </div>

                {{-- Kolom Kanan: Informasi Kontak (TIDAK DIUBAH) --}}
                <div data-aos="fade-left">
                    <div class="mb-8">
                        <h3 class="text-2xl font-serif font-bold text-dark mb-6">Informasi Kontak</h3>

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
                                    <p class="text-gray-600 text-sm">
                                        +62 878-6178-6535
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-envelope text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Alamat Email</h4>
                                    <p class="text-gray-600 text-sm">
                                        info@dewibeautysalon.com
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="text-primary mr-4 mt-1">
                                    <i class="fas fa-clock text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-serif font-bold text-dark mb-2">Jam Buka</h4>
                                    <p class="text-gray-600 text-sm">
                                        Buka setiap hari: 9:00 - 21:00
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-2xl font-serif font-bold text-dark mb-6">Ikuti Kami</h3>

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
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="#" class="flex items-center mb-6">
                        <span class="text-3xl font-serif font-bold text-primary">DEWI</span>
                        <span class="ml-2 text-lg font-light text-white">BEAUTY SALON</span>
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
                        <li><a href="#gallery"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Galeri</a>
                        </li>
                        <li><a href="#contact"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Kontak</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-serif font-bold mb-6">Layanan</h3>
                    <ul class="space-y-3">
                        @foreach ($services as $service)
                            <li><a href="#services"
                                    class="text-gray-400 hover:text-primary transition duration-300 text-sm">{{ $service['nama_perawatan'] }}</a>
                            </li>
                        @endforeach
                        <li><a href="#services"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Perawatan
                                Tubuh</a></li>
                        <li><a href="#services"
                                class="text-gray-400 hover:text-primary transition duration-300 text-sm">Perawatan
                                Kuku</a></li>
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
                            <span class="text-gray-400 text-sm">Buka setiap hari: 9:00-21:00</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                <p class="text-gray-400 text-sm">&copy; 2024 Dewi Beauty Salon. Hak Cipta Dilindungi.</p>
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

            <div class="p-6 mx-4">
                <!-- Stepper -->
                <div class="stepper mb-8">
                    <div class="progress-bar" id="progress-bar"></div>
                    <div class="step active" data-step="1">1</div>
                    <div class="step" data-step="2">2</div>
                    <div class="step" data-step="3">3</div>
                </div>

                <!-- Step Labels -->
                <div class="flex justify-between text-sm text-gray-600 mb-8 px-4">
                    <div class="text-center" style="width: 100px; margin-left: -30px;">Detail & Waktu</div>
                    <div class="text-center" style="width: 100px; margin-left: -30px;">Pembayaran</div>
                    <div class="text-center" style="width: 100px; margin-left: -30px;">Konfirmasi</div>
                </div>

                <form id="booking-form" action="{{ route('book.service') }}" method="POST">
                    @csrf
                    <input type="hidden" id="service-id" name="service" value="">

                    <!-- Step 1: Package Details & Time Selection -->
                    <div class="step-content active" id="step-1">
                        <div class="bg-white rounded-lg">
                            <h3 class="text-2xl font-serif font-bold text-dark mb-6">Detail Paket & Pilih Waktu</h3>

                            <div class="mb-6">
                                <div class="bg-secondary p-4 rounded-md mb-6">
                                    <div class="flex items-center">
                                        <div class="w-24 h-24 rounded-md overflow-hidden mr-4">
                                            <img id="package-image" src="/placeholder.svg" alt="Package Image"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <h4 id="package-name" class="font-serif font-bold text-lg mb-1"></h4>
                                            <p id="package-price" class="text-primary font-serif"></p>
                                            <p id="package-duration" class="text-sm text-gray-600"></p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p id="package-description" class="text-sm text-gray-600"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="booking-date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal
                                    Booking</label>
                                <input type="date" id="booking-date" name="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Tersedia</label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach ($timeSlots as $time)
                                        <div class="time-slot border border-gray-300 rounded-md p-3 text-center"
                                            data-time="{{ $time }}">
                                            {{ $time }}
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" id="selected-time" name="time" value="">
                            </div>

                            <div class="flex justify-end">
                                <button type="button" id="next-to-step-2"
                                    class="px-6 py-2 btn-primary rounded-md">Lanjutkan</button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Payment -->
                    <div class="step-content" id="step-2">
                        <div class="bg-white rounded-lg">
                            <h3 class="text-2xl font-serif font-bold text-dark mb-6">Detail Pembayaran</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="font-serif font-bold text-lg mb-4">Informasi Pelanggan</h4>

                                    <div class="space-y-4">
                                        {{-- Nama Lengkap --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama
                                                Lengkap</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800">
                                                {{ $user->nama_lengkap ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Email --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800">
                                                {{ $user->email ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Nomor Telepon --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                                Telepon</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800">
                                                {{ $user->no_telepon ?? '' }}
                                            </p>
                                        </div>

                                        {{-- Alamat --}}
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                            <p
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-800">
                                                {{ $user->alamat ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                </div>

                                <div>
                                    <h4 class="font-serif font-bold text-lg mb-4">Ringkasan Pemesanan</h4>

                                    <div class="bg-gray-50 p-4 rounded-md mb-6">
                                        <div class="space-y-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Layanan:</span>
                                                <span class="font-medium" id="summary-service">-</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600">Tanggal:</span>
                                                <span class="font-medium" id="summary-date">-</span>
                                            </div>
                                            <div class="flex justify-between">
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

                                    <h4 class="font-serif font-bold text-lg mb-4">Metode Pembayaran</h4>

                                    <div class="space-y-3">
                                        <div class="border border-gray-300 rounded-md p-3 flex items-center">
                                            <input type="radio" id="payment-midtrans" name="payment_method"
                                                value="midtrans" class="mr-3" checked>
                                            <label for="payment-midtrans" class="flex items-center">
                                                <span class="mr-2">Midtrans</span>
                                                <img src="https://midtrans.com/assets/images/logo-midtrans-color.png"
                                                    alt="Midtrans" class="h-6">
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500">*Pembayaran akan diproses melalui gateway
                                            Midtrans
                                            yang aman.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" id="back-to-step-1"
                                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">Kembali</button>
                                <button type="button" id="pay-button" class="px-6 py-2 btn-primary rounded-md">Bayar
                                    Sekarang</button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Step 3: Success -->
                <div class="step-content" id="step-3">
                    <div class="bg-white rounded-lg text-center">
                        <div class="mb-6 text-primary">
                            <i class="fas fa-check-circle text-6xl"></i>
                        </div>

                        <h3 class="text-2xl font-serif font-bold text-dark mb-4">Transaksi Berhasil!</h3>
                        <p class="text-gray-600 mb-8">Terima kasih atas pemesanan Anda. Detail reservasi telah dikirim
                            ke email Anda.</p>

                        <div class="bg-secondary p-6 rounded-md text-left mb-8">
                            <h4 class="font-serif font-bold text-lg mb-4">Detail Reservasi</h4>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">ID Reservasi:</span>
                                    <span class="font-medium" id="reservation-id">
                                        @if (session('reservation_id'))
                                            {{ session('reservation_id') }}
                                        @else
                                            DBS-2024-0001
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Nama:</span>
                                    <span class="font-medium" id="reservation-name">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Layanan:</span>
                                    <span class="font-medium" id="reservation-service">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tanggal & Waktu:</span>
                                    <span class="font-medium" id="reservation-datetime">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Pembayaran:</span>
                                    <span class="font-medium text-primary" id="reservation-price">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-medium text-green-600">Terkonfirmasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button id="close-booking"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition duration-300">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
        });
    </script>

    <!-- Main Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuToggle && mobileMenu) {
                menuToggle.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Back to Top Button
            window.addEventListener('scroll', function() {
                var backToTopButton = document.querySelector('a[href="#home"]');
                if (document.documentElement.scrollTop > 300) {
                    backToTopButton.classList.remove('hidden');
                } else {
                    backToTopButton.classList.add('hidden');
                }
            });

            // Booking Modal
            const modal = document.getElementById('booking-modal');
            const bookButtons = document.querySelectorAll('.book-service-btn');
            const closeBtn = document.querySelector('.modal-close');
            const progressBar = document.getElementById('progress-bar');
            const steps = document.querySelectorAll('.step');
            const stepContents = document.querySelectorAll('.step-content');
            const bookingForm = document.getElementById('booking-form');

            // Service Data (akan diisi dari data-attributes)
            let bookingData = {
                service: null,
                serviceName: null,
                price: null,
                duration: null,
                date: null,
                time: null
            };

            // Function to set active step
            function setActiveStep(stepNumber) {
                steps.forEach(step => step.classList.remove('active', 'completed'));
                stepContents.forEach(content => content.classList.remove('active'));

                for (let i = 0; i < stepNumber; i++) {
                    steps[i].classList.add('completed');
                }

                steps[stepNumber].classList.add('active');
                stepContents[stepNumber].classList.add('active');

                const progress = ((stepNumber) / (steps.length - 1)) * 100;
                progressBar.style.width = progress + '%';
            }

            // Function to go to step
            function goToStep(stepNumber) {
                setActiveStep(stepNumber - 1);
            }

            // Event listeners for booking buttons
            if (bookButtons.length > 0) {
                bookButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const service = this.dataset.service;
                        const name = this.dataset.name;
                        const price = parseInt(this.dataset.price);
                        const duration = this.dataset.duration;

                        bookingData.service = service;
                        bookingData.serviceName = name;
                        bookingData.price = price;
                        bookingData.duration = duration;

                        // Set hidden input value
                        document.getElementById('service-id').value = service;

                        openModal();
                    });
                });
            }

            // Function to open modal
            function openModal() {
                if (!window.isLoggedIn) {
                    window.location.href = '/login';
                    return;
                }

                if (modal) {
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden'; // Prevent scrolling
                    resetBookingForm();

                    // Display package details immediately in step 1
                    const serviceData = getServiceData(bookingData.service);
                    if (serviceData) {
                        document.getElementById('package-image').src = serviceData.image;
                        document.getElementById('package-name').textContent = bookingData.serviceName;
                        document.getElementById('package-price').textContent = formatPrice(bookingData.price);
                        document.getElementById('package-duration').textContent = serviceData.duration;
                        document.getElementById('package-description').textContent = serviceData.description;
                    }
                }
            }

            // Function to close modal
            function closeModal() {
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto'; // Enable scrolling
                    localStorage.removeItem('bookingData');
                    history.replaceState(null, '', window.location.pathname);
                    Object.assign(bookingData);
                    resetBookingForm();
                    goToStep(1);
                }
            }

            // Event listener for close button
            if (closeBtn) {
                closeBtn.addEventListener('click', closeModal);
            }

            // Event listener for closing modal when clicking outside
            if (modal) {
                window.addEventListener('click', (event) => {
                    if (event.target == modal) {
                        closeModal();
                    }
                });
            }

            // Time slot selection
            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('time-slot')) {
                    document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove(
                        'selected'));
                    event.target.classList.add('selected');
                    bookingData.time = event.target.dataset.time;

                    // Set hidden input value
                    document.getElementById('selected-time').value = bookingData.time;
                }
            });

            // Next button for Step 1
            const nextToStep2Button = document.getElementById('next-to-step-2');
            if (nextToStep2Button) {
                nextToStep2Button.addEventListener('click', function() {
                    const dateInput = document.getElementById('booking-date');
                    const selectedTimeSlot = document.querySelector('.time-slot.selected');

                    if (!dateInput.value) {
                        alert('Silakan pilih tanggal booking.');
                        return;
                    }

                    if (!selectedTimeSlot) {
                        alert('Silakan pilih waktu booking.');
                        return;
                    }

                    bookingData.date = dateInput.value;

                    // Update summary in Step 2
                    document.getElementById('summary-service').textContent = bookingData.serviceName;
                    document.getElementById('summary-date').textContent = formatDate(bookingData.date);
                    document.getElementById('summary-time').textContent = bookingData.time;
                    document.getElementById('summary-price').textContent = formatPrice(bookingData.price);

                    goToStep(2);
                });
            }

            // Back button for Step 2
            const backToStep1Button = document.getElementById('back-to-step-1');
            if (backToStep1Button) {
                backToStep1Button.addEventListener('click', function() {
                    goToStep(1);
                });
            }

            // Pay button for Midtrans integration
            const payButton = document.getElementById('pay-button');
            if (payButton) {
                payButton.addEventListener('click', function() {
                    // Tampilkan loading
                    payButton.disabled = true;
                    payButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

                    // Kumpulkan data untuk dikirim ke server
                    const formData = new FormData(bookingForm);
                    formData.append('service_id', bookingData.service);
                    formData.append('service_name', bookingData.serviceName);
                    formData.append('price', bookingData.price);
                    formData.append('booking_date', bookingData.date);
                    formData.append('booking_time', bookingData.time);

                    // Kirim data ke server untuk mendapatkan token Midtrans
                    fetch('/book-service', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Simpan data booking untuk digunakan setelah pembayaran
                                localStorage.setItem('bookingData', JSON.stringify({
                                    orderId: data.order_id,
                                    customerName: "{{ $user->nama_lengkap ?? '' }}",
                                    serviceName: bookingData.serviceName,
                                    bookingDate: formatDate(bookingData.date),
                                    bookingTime: bookingData.time,
                                    price: bookingData.price
                                }));

                                // Buka Snap Midtrans
                                window.snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        window.location.href =
                                            '/payment/finish?status=success&order_id=' +
                                            data.order_id;
                                    },
                                    onPending: function(result) {
                                        window.location.href =
                                            '/payment/finish?status=pending&order_id=' +
                                            data.order_id;
                                    },
                                    onError: function(result) {
                                        window.location.href =
                                            '/payment/finish?status=error&order_id=' + data
                                            .order_id;
                                    },
                                    onClose: function() {
                                        payButton.disabled = false;
                                        payButton.innerHTML = 'Bayar Sekarang';
                                        alert(
                                            'Anda menutup popup pembayaran sebelum menyelesaikan transaksi.'
                                        );
                                    }
                                });

                            } else {
                                alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                                payButton.disabled = false;
                                payButton.innerHTML = 'Bayar Sekarang';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan. Silakan coba lagi.');
                            payButton.disabled = false;
                            payButton.innerHTML = 'Bayar Sekarang';
                        });
                });
            }

            // Close booking
            const closeBookingButton = document.getElementById('close-booking');
            if (closeBookingButton) {
                closeBookingButton.addEventListener('click', closeModal);
            }

            // Helper Functions
            function resetBookingForm() {
                // Reset time slots
                document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));

                // Reset date input
                const dateInput = document.getElementById('booking-date');
                if (dateInput) dateInput.value = '';

                // Reset hidden time input
                const selectedTime = document.getElementById('selected-time');
                if (selectedTime) selectedTime.value = '';
            }

            function formatPrice(price) {
                return 'Rp ' + price.toLocaleString('id-ID');
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return date.toLocaleDateString('id-ID', options);
            }

            function getServiceData(serviceId) {
                // This function would normally fetch from an API or database
                // For simplicity, we're using data attributes from the buttons
                const button = document.querySelector(`.book-service-btn[data-service="${serviceId}"]`);
                if (!button) return null;

                return {
                    id: serviceId,
                    name: button.dataset.name,
                    price: parseInt(button.dataset.price),
                    duration: button.dataset.duration,
                    description: button.closest('.service-card').querySelector('p').textContent.trim(),
                    image: button.closest('.service-card').querySelector('img').src
                };
            }

            // Check if we need to show success message (after form submission)
            const urlParams = new URLSearchParams(window.location.search);
            const paymentStatus = urlParams.get('status');
            const orderId = urlParams.get('order_id');

            if (paymentStatus && orderId) {
                // Ambil data booking dari localStorage
                const savedBookingData = JSON.parse(localStorage.getItem('bookingData') || '{}');

                if (paymentStatus === 'success') {
                    // Tampilkan modal sukses
                    if (modal) {
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden';

                        // Go to success step
                        goToStep(3);

                        // Set reservation details
                        document.getElementById('reservation-id').textContent = savedBookingData.orderId || orderId;
                        document.getElementById('reservation-name').textContent = savedBookingData.customerName ||
                            "{{ $user->nama_lengkap ?? '-' }}";
                        document.getElementById('reservation-service').textContent = savedBookingData.serviceName ||
                            '-';
                        document.getElementById('reservation-datetime').textContent =
                            (savedBookingData.bookingDate ? savedBookingData.bookingDate + ', ' : '') +
                            (savedBookingData.bookingTime || '-');
                        document.getElementById('reservation-price').textContent =
                            savedBookingData.price ? formatPrice(savedBookingData.price) : '-';
                    }

                    // Hapus data dari localStorage setelah digunakan
                    localStorage.removeItem('bookingData');
                } else if (paymentStatus === 'pending') {
                    // Tampilkan notifikasi pending
                    const notification = document.createElement('div');
                    notification.id = 'info-notification';
                    notification.className =
                        'fixed top-20 right-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-md z-50';
                    notification.innerHTML = `
                <div class="flex items-center">
                    <div class="py-1 mr-3"><i class="fas fa-info-circle text-xl"></i></div>
                    <div>
                        <p class="font-bold">Pembayaran Tertunda</p>
                        <p class="text-sm">Pembayaran Anda sedang diproses. Kami akan mengirimkan konfirmasi setelah pembayaran selesai.</p>
                    </div>
                </div>
            `;
                    document.body.appendChild(notification);

                    // Auto-hide notification after 5 seconds
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => {
                            notification.remove();
                        }, 500);
                    }, 5000);
                } else if (paymentStatus === 'error') {
                    // Tampilkan notifikasi error
                    const notification = document.createElement('div');
                    notification.id = 'error-notification';
                    notification.className =
                        'fixed top-20 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-md z-50';
                    notification.innerHTML = `
                <div class="flex items-center">
                    <div class="py-1 mr-3"><i class="fas fa-exclamation-circle text-xl"></i></div>
                    <div>
                        <p class="font-bold">Pembayaran Gagal</p>
                        <p class="text-sm">Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.</p>
                    </div>
                </div>
            `;
                    document.body.appendChild(notification);

                    // Auto-hide notification after 5 seconds
                    setTimeout(() => {
                        notification.style.opacity = '0';
                        notification.style.transition = 'opacity 0.5s ease';
                        setTimeout(() => {
                            notification.remove();
                        }, 500);
                    }, 5000);
                }
            }
        });
    </script>
</body>

</html>
