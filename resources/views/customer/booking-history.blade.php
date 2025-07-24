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
        <div class="modal-dialog modal-lg">
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

                        <div class="form-group mb-3">
                            <label>Waktu Tersedia</label>
                            <div id="timeSlots" class="mt-2">
                                <div class="text-center py-3">
                                    <div class="spinner-border text-primary d-none" id="timeSlotsLoading" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="text-muted" id="timeSlotsMessage">Silakan pilih tanggal terlebih dahulu</p>
                                </div>
                            </div>
                            <input type="hidden" name="new_time" id="new_time" required>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Hanya slot waktu yang tersedia yang akan ditampilkan
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info" id="rescheduleButton" disabled>Reschedule
                            Booking</button>
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

        /* Time slot styles */
        .time-slots-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .time-slot {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 80px;
            text-align: center;
            font-weight: 500;
        }

        .time-slot:hover {
            border-color: #007bff;
            background: #e7f1ff;
        }

        .time-slot.selected {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .time-slot.unavailable {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
        }
    </style>
@endpush
@push('scripts')
    <script>
        let currentBookingId = null;
        let currentBookingServices = [];

        function showCancelModal(bookingId) {
            $('#cancelForm').attr('action', `/customer/booking/${bookingId}/cancel`);
            var cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
            cancelModal.show();
        }

        function showRescheduleModal(bookingId) {
            currentBookingId = bookingId;
            $('#rescheduleForm').attr('action', `/customer/booking/${bookingId}/reschedule`);

            // Reset form
            $('#new_date').val('');
            $('#new_time').val('');
            $('#timeSlots').html('<p class="text-muted">Silakan pilih tanggal terlebih dahulu</p>');
            $('#rescheduleButton').prop('disabled', true);

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

        // Fetch available time slots when date is selected
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('new_date');

            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = this.value;
                    if (!selectedDate) return;

                    fetchAvailableTimeSlots(selectedDate);
                });
            }
        });

        function fetchAvailableTimeSlots(date) {
            const timeSlotsContainer = document.getElementById('timeSlots');
            const loadingSpinner = document.getElementById('timeSlotsLoading');
            const timeSlotsMessage = document.getElementById('timeSlotsMessage');

            // Show loading
            timeSlotsContainer.innerHTML = '';
            loadingSpinner.classList.remove('d-none');
            timeSlotsMessage.textContent = 'Memuat slot waktu yang tersedia...';
            timeSlotsMessage.classList.remove('d-none');

            // Fetch available time slots from server
            fetch('{{ route('customer.booking.check-availability') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        date: date,
                        booking_id: currentBookingId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.classList.add('d-none');

                    if (!data.time_slots || data.time_slots.length === 0) {
                        timeSlotsMessage.textContent = 'Tidak ada slot waktu yang tersedia pada tanggal ini';
                        return;
                    }

                    timeSlotsMessage.classList.add('d-none');

                    // Render time slots
                    let html = '<div class="time-slots-container">';
                    data.time_slots.forEach(slot => {
                        html +=
                            `<span class="time-slot" data-time="${slot}" onclick="selectTimeSlot('${slot}')">${slot}</span>`;
                    });
                    html += '</div>';

                    timeSlotsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    loadingSpinner.classList.add('d-none');
                    timeSlotsMessage.textContent = 'Terjadi kesalahan saat memuat slot waktu';
                });
        }

        function selectTimeSlot(time) {
            // Remove selected class from all time slots
            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
            });

            // Add selected class to clicked time slot
            document.querySelector(`.time-slot[data-time="${time}"]`).classList.add('selected');

            // Set the hidden input value
            document.getElementById('new_time').value = time;

            // Enable the reschedule button
            document.getElementById('rescheduleButton').disabled = false;
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
