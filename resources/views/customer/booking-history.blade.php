@extends('layouts.customer')

@section('title', 'Riwayat Booking')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-4">Riwayat Booking Anda</h1>

                <!-- Filter Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Filter Riwayat Booking</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('customer.booking.history') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                                            Confirmed</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                            Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_from" class="form-label">Dari Tanggal</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                        value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                        value="{{ request('date_to') }}">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                                    <a href="{{ route('customer.booking.history') }}" class="btn btn-secondary">Reset
                                        Filter</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Booking List -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Daftar Booking</h5>
                    </div>
                    <div class="card-body">
                        @if ($bookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID Booking</th>
                                            <th>Layanan</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Terapis</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>#{{ $booking->id_pemesanan }}</td>
                                                <td>
                                                    @foreach ($booking->bookeds as $booked)
                                                        {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $booking->tanggal_pemesanan->format('d F Y') }} -
                                                    {{ \Carbon\Carbon::parse($booking->waktu)->format('H:i') }}</td>
                                                <td>{{ $booking->karyawan->nama_lengkap ?? 'Belum Ditentukan' }}</td>
                                                <td>Rp {{ number_format($booking->total, 0, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $booking->status_pemesanan === 'completed'
                                                            ? 'success'
                                                            : ($booking->status_pemesanan === 'confirmed'
                                                                ? 'primary'
                                                                : ($booking->status_pemesanan === 'cancelled'
                                                                    ? 'danger'
                                                                    : 'warning')) }}">
                                                        {{ ucfirst($booking->status_pemesanan) }}
                                                    </span>
                                                    {{-- Tampilkan status review jika sudah ada --}}
                                                    @if ($booking->review && $booking->status_pemesanan === 'completed')
                                                        <br><small class="text-success">
                                                            <i class="fas fa-check"></i> Sudah direview
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- DEBUGGING INFO --}}
                                                    <div class="small text-muted mb-2">
                                                        Status: {{ $booking->status_pemesanan }}<br>
                                                        Tanggal Booking:
                                                        {{ $booking->tanggal_pemesanan->format('Y-m-d') }}<br>
                                                        Waktu Booking:
                                                        {{ \Carbon\Carbon::parse($booking->waktu)->format('H:i') }}<br>
                                                        Reschedule Count: {{ $booking->reschedule_count }}<br>
                                                        Can Cancel: {{ $booking->canCancel() ? 'True' : 'False' }}<br>
                                                        Can Reschedule:
                                                        {{ $booking->canReschedule() ? 'True' : 'False' }}<br>
                                                        Can Review: {{ $booking->canReview() ? 'True' : 'False' }}<br>
                                                        Has Review: {{ $booking->review ? 'True' : 'False' }}
                                                    </div>
                                                    {{-- END DEBUGGING INFO --}}

                                                    @if ($booking->canCancel())
                                                        <button class="btn btn-sm btn-danger mb-1"
                                                            onclick="showCancelModal({{ $booking->id_pemesanan }})">
                                                            Batal
                                                        </button>
                                                    @endif

                                                    @if ($booking->canReschedule())
                                                        <button class="btn btn-sm btn-info mb-1"
                                                            onclick="showRescheduleModal({{ $booking->id_pemesanan }})">
                                                            Reschedule
                                                        </button>
                                                    @endif

                                                    @if ($booking->canReview())
                                                        <button class="btn btn-sm btn-warning mb-1"
                                                            onclick="showReviewModal({{ $booking->id_pemesanan }})">
                                                            Beri Ulasan
                                                        </button>
                                                    @endif

                                                    @if (!$booking->canCancel() && !$booking->canReschedule() && !$booking->canReview() && !$booking->review)
                                                        <span class="text-muted">Tidak ada aksi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $bookings->links('pagination::bootstrap-5') }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada riwayat booking yang ditemukan.</p>
                                <a href="{{ route('landing-page') }}" class="btn btn-primary">
                                    Buat Booking Baru
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="cancelForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin membatalkan booking ini?</p>
                        <div class="form-group">
                            <label for="cancel_reason">Alasan Pembatalan (Opsional)</label>
                            <textarea class="form-control" name="reason" id="cancel_reason" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Batalkan Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reschedule Booking Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reschedule Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rescheduleForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Pilih tanggal dan waktu baru untuk booking Anda.</p>
                        <div class="form-group mb-3">
                            <label for="new_date">Tanggal Baru</label>
                            <input type="date" class="form-control" name="new_date" id="new_date" required
                                min="{{ \Carbon\Carbon::tomorrow()->toDateString() }}">
                        </div>
                        <div class="form-group">
                            <label for="new_time">Waktu Baru</label>
                            <input type="time" class="form-control" name="new_time" id="new_time" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info">Reschedule Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Review Modal (Updated sesuai model) -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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

        .review-stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function showCancelModal(bookingId) {
            $('#cancelForm').attr('action', `/customer/booking/${bookingId}/cancel`);
            var cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
            cancelModal.show();
        }

        function showRescheduleModal(bookingId) {
            $('#rescheduleForm').attr('action', `/customer/booking/${bookingId}/reschedule`);
            var rescheduleModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
            rescheduleModal.show();
        }

        function showReviewModal(bookingId) {
            $('#reviewForm').attr('action', `/customer/booking/${bookingId}/review`);
            // Reset rating dan textarea
            $('#rating').val('');
            $('#komentar').val('');
            $('.rating .star').removeClass('active');
            var reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        }


        // Rating functionality (Updated)
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.rating .star');
            const ratingInput = document.getElementById('rating');

            stars.forEach((star, index) => {
                star.addEventListener('click', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    ratingInput.value = rating;

                    // Update star display
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const rating = parseInt(this.getAttribute('data-rating'));
                    stars.forEach((s, i) => {
                        if (i < rating) {
                            s.style.color = '#ffc107';
                        } else {
                            s.style.color = '#ddd';
                        }
                    });
                });
            });

            // Reset hover effect when mouse leaves rating container
            document.querySelector('.rating').addEventListener('mouseleave', function() {
                const currentRating = parseInt(ratingInput.value) || 0;
                stars.forEach((s, i) => {
                    if (i < currentRating) {
                        s.style.color = '#ffc107';
                    } else {
                        s.style.color = '#ddd';
                    }
                });
            });

            // Form validation
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                if (!ratingInput.value) {
                    e.preventDefault();
                    alert('Silakan pilih rating terlebih dahulu');
                    return false;
                }
            });
        });
    </script>
@endpush
