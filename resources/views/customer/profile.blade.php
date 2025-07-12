@extends('layouts.customer')

@section('title', 'Profil Saya')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Profile Header -->
            <div class="col-12 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar-circle">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            </div>
                            <div class="col">
                                <h2 class="h3 mb-1">{{ $user->nama_lengkap }}</h2>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                </p>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-phone me-2"></i>{{ $user->no_telepon }}
                                </p>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-light text-primary fs-6">
                                    <i class="fas fa-calendar-check me-1"></i>
                                    Bergabung sejak {{ $user->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Stats -->
            <div class="col-12 mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="text-primary mb-2">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                                <h4 class="mb-1">{{ $user->pemesanans()->count() }}</h4>
                                <small class="text-muted">Total Booking</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="text-success mb-2">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <h4 class="mb-1">
                                    {{ $user->pemesanans()->where('status_pemesanan', 'completed')->count() }}</h4>
                                <small class="text-muted">Booking Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="text-warning mb-2">
                                    <i class="fas fa-star fa-2x"></i>
                                </div>
                                <h4 class="mb-1">{{ $user->reviews()->count() }}</h4>
                                <small class="text-muted">Review Diberikan</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="text-info mb-2">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                                <h4 class="mb-1">Rp
                                    {{ number_format($user->pemesanans()->where('status_pemesanan', 'completed')->sum('total'), 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">Total Pengeluaran</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Informasi Profil
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Success/Error Messages -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Terjadi kesalahan:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('customer.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama_lengkap" class="form-label">
                                        <i class="fas fa-user me-1"></i>
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('nama_lengkap') is-invalid @enderror"
                                        id="nama_lengkap" name="nama_lengkap"
                                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
                                    @error('nama_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="no_telepon" class="form-label">
                                        <i class="fas fa-phone me-1"></i>
                                        Nomor Telepon <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" class="form-control @error('no_telepon') is-invalid @enderror"
                                        id="no_telepon" name="no_telepon"
                                        value="{{ old('no_telepon', $user->no_telepon) }}"
                                        placeholder="Contoh: 08123456789" required>
                                    @error('no_telepon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        Alamat
                                    </label>
                                    <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3"
                                        placeholder="Masukkan alamat lengkap Anda">{{ old('alamat', $user->alamat) }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Informasi Akun
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">ID Pelanggan</small>
                            <strong>#{{ $user->id_pelanggan }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Bergabung Sejak</small>
                            <strong>{{ $user->created_at->format('d F Y') }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Terakhir Diperbarui</small>
                            <strong>{{ $user->updated_at->format('d F Y H:i') }}</strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Status Akun</small>
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Aktif
                            </span>
                        </div>

                        <hr>

                        <!-- Quick Actions -->
                        <div class="d-grid gap-2">
                            {{-- <a href="{{ route('booking.history') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-history me-2"></i>
                                Riwayat Booking
                            </a> --}}
                            {{-- <a href="{{ route('booking.reviews') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-star me-2"></i>
                                Ulasan Saya
                            </a> --}}
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="showChangePasswordModal()">
                                <i class="fas fa-key me-2"></i>
                                Ubah Password
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Aktivitas Terbaru
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $recentBookings = $user->pemesanans()->latest()->limit(3)->get();
                        @endphp

                        @if ($recentBookings->count() > 0)
                            @foreach ($recentBookings as $booking)
                                <div
                                    class="d-flex align-items-center mb-3 pb-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                    <div class="me-3">
                                        <div
                                            class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-calendar text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="small mb-1">
                                            <strong>Booking #{{ $booking->id_pemesanan }}</strong>
                                        </div>
                                        <div class="text-muted small">
                                            {{ $booking->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div>
                                        <span
                                            class="badge bg-{{ $booking->status_pemesanan === 'completed' ? 'success' : ($booking->status_pemesanan === 'confirmed' ? 'primary' : ($booking->status_pemesanan === 'cancelled' ? 'danger' : 'warning')) }}">
                                            {{ ucfirst($booking->status_pemesanan) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center small">Belum ada aktivitas</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>
                        Ubah Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('customer.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-circle {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-sm {
            width: 35px;
            height: 35px;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showChangePasswordModal() {
            var modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            modal.show();
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Phone number formatting
        document.getElementById('no_telp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits

            // Add country code if not present
            if (value.length > 0 && !value.startsWith('62') && !value.startsWith('0')) {
                value = '0' + value;
            }

            e.target.value = value;
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const phone = document.getElementById('no_telp').value;

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Format email tidak valid');
                return;
            }

            // Phone validation
            if (phone.length < 10 || phone.length > 15) {
                e.preventDefault();
                alert('Nomor telepon harus antara 10-15 digit');
                return;
            }
        });
    </script>
@endpush
