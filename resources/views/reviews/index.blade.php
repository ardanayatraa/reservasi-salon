@extends('layouts.customer')

@section('title', 'Ulasan Saya')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-0">Ulasan Saya</h2>
                <p class="text-muted">Kelola review dan feedback Anda</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Ulasan</h6>
                                <h3 class="mb-0">{{ $reviews->total() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-star fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Rating Rata-rata</h6>
                                <h3 class="mb-0">
                                    {{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '0.0' }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Booking Selesai</h6>
                                <h3 class="mb-0">
                                    {{ Auth::guard('pelanggan')->user()->pemesanans()->where('status_pemesanan', 'completed')->count() }}
                                </h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Bisa Direview</h6>
                                @php
                                    $canReviewCount = Auth::guard('pelanggan')
                                        ->user()
                                        ->pemesanans()
                                        ->where('status_pemesanan', 'completed')
                                        ->whereDoesntHave('review')
                                        ->count();
                                @endphp
                                <h3 class="mb-0">{{ $canReviewCount }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-edit fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        Daftar Ulasan Saya
                    </h5>
                    <a href="{{ route('customer.booking.history') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>
                        Buat Review Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($reviews->count() > 0)
                    <div class="row">
                        @foreach ($reviews as $review)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <!-- Review Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="card-title mb-1">
                                                    Booking #{{ $review->id_pemesanan }}
                                                </h6>
                                                <small class="text-muted">
                                                    {{ $review->tanggal_review->format('d M Y H:i') }}
                                                </small>
                                            </div>

                                            <!-- Review Status (Simple) -->
                                            <span class="badge bg-primary">
                                                <i class="fas fa-star me-1"></i>
                                                Review Anda
                                            </span>
                                        </div>

                                        <!-- Services -->
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Layanan:</small>
                                            @if ($review->pemesanan && $review->pemesanan->bookeds)
                                                @foreach ($review->pemesanan->bookeds as $booked)
                                                    <span class="badge bg-light text-dark me-1 mb-1">
                                                        {{ $booked->perawatan->nama_perawatan ?? 'N/A' }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>

                                        <!-- Rating -->
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Rating:</small>
                                            <div class="d-flex align-items-center">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                            </div>
                                        </div>

                                        <!-- Comment -->
                                        <div class="mb-3">
                                            <small class="text-muted d-block mb-1">Ulasan:</small>
                                            <p class="card-text">{{ $review->komentar ?: 'Tidak ada komentar' }}</p>
                                        </div>

                                        <!-- Admin Notes (hanya jika ditolak) -->
                                        @if ($review->admin_notes && $review->status === 'rejected')
                                            <div class="alert alert-info py-2">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Catatan:
                                                </small>
                                                <small>{{ $review->admin_notes }}</small>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="card-footer bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Booking:
                                                {{ $review->pemesanan->tanggal_pemesanan->format('d M Y') ?? 'N/A' }}
                                            </small>

                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    onclick="viewReviewDetail({{ $review->id_review }})"
                                                    title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if ($review->status === 'rejected')
                                                    <a href="{{ route('reviews.create', $review->id_pemesanan) }}"
                                                        class="btn btn-outline-warning btn-sm" title="Review Ulang">
                                                        <i class="fas fa-redo"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="fas fa-star text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <h4 class="mt-3 text-muted">Belum Ada Ulasan</h4>
                        <p class="text-muted mb-4">Anda belum memberikan ulasan untuk booking yang telah selesai.</p>
                        <a href="{{ route('customer.booking.history') }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Lihat Riwayat Booking
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Booking yang bisa direview -->
        @php
            $completedBookings = Auth::guard('pelanggan')
                ->user()
                ->pemesanans()
                ->with(['bookeds.perawatan'])
                ->where('status_pemesanan', 'completed')
                ->whereDoesntHave('review')
                ->latest()
                ->limit(3)
                ->get();
        @endphp

        @if ($completedBookings->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Booking Siap Direview
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Anda memiliki booking yang sudah selesai dan belum direview:</p>
                    <div class="row">
                        @foreach ($completedBookings as $booking)
                            <div class="col-md-4 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body p-3">
                                        <h6 class="card-title">Booking #{{ $booking->id_pemesanan }}</h6>
                                        <p class="card-text small text-muted">
                                            @foreach ($booking->bookeds as $booked)
                                                {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </p>
                                        <small class="text-muted d-block mb-2">
                                            {{ $booking->tanggal_pemesanan->format('d M Y') }}
                                        </small>
                                        <a href="{{ route('customer.booking.history') }}"
                                            class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-star me-1"></i>
                                            Beri Review
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (Auth::guard('pelanggan')->user()->pemesanans()->where('status_pemesanan', 'completed')->whereDoesntHave('review')->count() > 3)
                        <div class="text-center mt-3">
                            <a href="{{ route('customer.booking.history') }}" class="btn btn-outline-primary">
                                Lihat Semua Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Review Detail Modal -->
    <div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewDetailModalLabel">Detail Ulasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reviewDetailModalBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function viewReviewDetail(reviewId) {
            // Find the review data from the current page
            const reviews = @json($reviews->items());
            const review = reviews.find(r => r.id_review === reviewId);

            if (!review) {
                alert('Review tidak ditemukan');
                return;
            }

            // Build services list
            let services = 'Tidak ada data layanan';
            if (review.pemesanan && review.pemesanan.bookeds) {
                services = review.pemesanan.bookeds.map(booked =>
                    booked.perawatan ? booked.perawatan.nama_perawatan : 'N/A'
                ).join(', ');
            }

            // Build rating stars
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                stars += `<i class="fas fa-star ${i <= review.rating ? 'text-warning' : 'text-muted'}"></i>`;
            }

            // Get status - tidak perlu ditampilkan ke user
            const statusBadge = `<span class="badge bg-primary"><i class="fas fa-star me-1"></i>Review Anda</span>`;

            // Get comment - sesuai dengan model yang menggunakan 'komentar'
            const comment = review.komentar || 'Tidak ada komentar';

            // Get date
            const reviewDate = review.tanggal_review;

            // Admin notes - ubah label jadi lebih netral
            let adminNotes = '';
            if (review.admin_notes && review.status === 'rejected') {
                adminNotes = `
                    <div class="alert alert-info">
                        <strong><i class="fas fa-info-circle me-1"></i>Catatan:</strong><br>
                        ${review.admin_notes}
                    </div>
                `;
            }

            const modalContent = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Booking</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>ID Booking:</strong></td>
                                <td>#${review.id_pemesanan}</td>
                            </tr>
                            <tr>
                                <td><strong>Layanan:</strong></td>
                                <td>${services}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Review:</strong></td>
                                <td>${new Date(reviewDate).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>${statusBadge}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Rating & Ulasan</h6>
                        <div class="mb-3">
                            <strong>Rating:</strong><br>
                            ${stars} (${review.rating}/5)
                        </div>
                        <div>
                            <strong>Ulasan:</strong><br>
                            <p class="border p-3 rounded bg-light">${comment}</p>
                        </div>
                    </div>
                </div>
                ${adminNotes}
            `;

            document.getElementById('reviewDetailModalBody').innerHTML = modalContent;
            new bootstrap.Modal(document.getElementById('reviewDetailModal')).show();
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
