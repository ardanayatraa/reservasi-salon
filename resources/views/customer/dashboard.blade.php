@extends('layouts.customer')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="container-fluid py-4">
        @if ($alertType && $alertMessage)
            <div class="alert alert-{{ $alertType }} alert-dismissible fade show" role="alert">
                <i
                    class="fas fa-{{ $alertType === 'success' ? 'check-circle' : ($alertType === 'warning' ? 'exclamation-triangle' : 'exclamation-circle') }} me-2"></i>
                {{ $alertMessage }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4">Dashboard Pelanggan</h1>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $totalBookings }}</h4>
                                        <p class="mb-0">Total Booking</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $completedBookings }}</h4>
                                        <p class="mb-0">Selesai</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>{{ $upcomingBookings }}</h4>
                                        <p class="mb-0">Mendatang</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h4>Rp {{ number_format($totalSpent, 0, ',', '.') }}</h4>
                                        <p class="mb-0">Total Pengeluaran</p>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-money-bill fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Upcoming Bookings -->
                @if ($upcomingBookingsList->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Booking Mendatang</h5>
                                </div>
                                <div class="card-body">
                                    @foreach ($upcomingBookingsList as $booking)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                            <div>
                                                <h6 class="mb-1">
                                                    @foreach ($booking->bookeds as $booked)
                                                        {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </h6>
                                                <p class="text-muted mb-0">
                                                    {{ $booking->tanggal_pemesanan->format('d F Y') }} -
                                                    {{ \Carbon\Carbon::parse($booking->waktu)->format('H:i') }}
                                                </p>
                                                @if ($booking->karyawan)
                                                    <small class="text-muted">Terapis:
                                                        {{ $booking->karyawan->nama_lengkap }}</small>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="badge badge-{{ $booking->status_pemesanan === 'confirmed' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($booking->status_pemesanan) }}
                                                </span>
                                                <br>
                                                <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Bookings -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Booking Terbaru</h5>
                            </div>
                            <div class="card-body">
                                @if ($recentBookings->count() > 0)
                                    @foreach ($recentBookings as $booking)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                                            <div>
                                                <h6 class="mb-1">
                                                    @foreach ($booking->bookeds as $booked)
                                                        {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </h6>
                                                <p class="text-muted mb-0">
                                                    {{ $booking->tanggal_pemesanan->format('d F Y') }} -
                                                    {{ \Carbon\Carbon::parse($booking->waktu)->format('H:i') }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="badge badge-{{ $booking->status_pemesanan === 'completed'
                                                        ? 'success'
                                                        : ($booking->status_pemesanan === 'confirmed'
                                                            ? 'primary'
                                                            : ($booking->status_pemesanan === 'cancelled'
                                                                ? 'danger'
                                                                : 'warning')) }}">
                                                    {{ ucfirst($booking->status_pemesanan) }}
                                                </span>
                                                <br>
                                                <strong>Rp {{ number_format($booking->total, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="text-center mt-3">
                                        <a href="{{ route('customer.booking.history') }}" class="btn btn-outline-primary">
                                            Lihat Semua Booking
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada booking.</p>
                                        <a href="{{ route('landing-page') }}" class="btn btn-primary">
                                            Buat Booking Pertama
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Reviewable Bookings -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Beri Ulasan</h5>
                            </div>
                            <div class="card-body">
                                @if ($reviewableBookings->count() > 0)
                                    @foreach ($reviewableBookings as $booking)
                                        <div class="border-bottom py-2 mb-2">
                                            <h6 class="mb-1">
                                                @foreach ($booking->bookeds as $booked)
                                                    {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </h6>
                                            <p class="text-muted small mb-2">
                                                {{ $booking->tanggal_pemesanan->format('d M Y') }}
                                            </p>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="showReviewModal({{ $booking->id_pemesanan }})">
                                                <i class="fas fa-star"></i> Beri Ulasan
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">Tidak ada booking yang perlu direview.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal (Updated sesuai model) -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Ulasan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="reviewForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Rating</label>
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="rating" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="komentar" class="form-label">Ulasan (Opsional)</label>
                            <textarea class="form-control" name="komentar" id="komentar" rows="3"
                                placeholder="Ceritakan pengalaman Anda..." maxlength="1000"></textarea>
                            <div class="form-text">Maksimal 1000 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .rating {
            font-size: 24px;
            margin: 10px 0;
        }

        .rating .star {
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating .star:hover,
        .rating .star.active {
            color: #ffc107;
        }

        .rating .star:hover~.star {
            color: #ddd;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showReviewModal(bookingId) {
            $('#reviewForm').attr('action', `/customer/booking/${bookingId}/review`);
            // Reset form
            $('#rating').val('');
            $('#komentar').val('');
            $('.rating .star').removeClass('active');
            $('#reviewModal').modal('show');
        }

        // Rating functionality (Updated)
        $(document).ready(function() {
            $('.rating .star').on('click', function() {
                const rating = $(this).data('rating');
                $('#rating').val(rating);

                $('.rating .star').removeClass('active');
                for (let i = 1; i <= rating; i++) {
                    $(`.rating .star[data-rating="${i}"]`).addClass('active');
                }
            });

            $('.rating .star').on('mouseenter', function() {
                const rating = $(this).data('rating');
                $('.rating .star').removeClass('active');
                for (let i = 1; i <= rating; i++) {
                    $(`.rating .star[data-rating="${i}"]`).addClass('active');
                }
            });

            $('.rating').on('mouseleave', function() {
                const currentRating = $('#rating').val();
                $('.rating .star').removeClass('active');
                for (let i = 1; i <= currentRating; i++) {
                    $(`.rating .star[data-rating="${i}"]`).addClass('active');
                }
            });

            // Form validation
            $('#reviewForm').on('submit', function(e) {
                if (!$('#rating').val()) {
                    e.preventDefault();
                    alert('Silakan pilih rating terlebih dahulu');
                    return false;
                }
            });
        });
    </script>
@endpush
