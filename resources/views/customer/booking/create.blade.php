@extends('layouts.customer')

@section('title', 'Buat Booking Baru')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-0">Buat Booking Baru</h1>
                        <p class="text-muted">Pilih layanan dan jadwal yang Anda inginkan</p>
                    </div>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Kembali ke Dashboard
                    </a>
                </div>

                <div class="row">
                    <!-- Form Booking -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    Informasi Booking
                                </h5>
                            </div>
                            <div class="card-body">
                                <form id="bookingForm">
                                    @csrf

                                    <!-- Step 1: Pilih Tanggal -->
                                    <div class="booking-step mb-4">
                                        <div class="step-header mb-3">
                                            <span class="step-number">1</span>
                                            <h6 class="step-title mb-0">Pilih Tanggal Booking</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="booking_date" class="form-label">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Tanggal <span class="text-danger">*</span>
                                                </label>
                                                <input type="date" class="form-control form-control-lg" id="booking_date"
                                                    name="booking_date" value="{{ $selectedDate }}"
                                                    min="{{ date('Y-m-d') }}"
                                                    max="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Booking maksimal 30 hari ke depan
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 2: Pilih Layanan -->
                                    <div class="booking-step mb-4">
                                        <div class="step-header mb-3">
                                            <span class="step-number">2</span>
                                            <h6 class="step-title mb-0">Pilih Layanan</h6>
                                        </div>
                                        <div class="row" id="servicesContainer">
                                            @forelse($services as $service)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card service-card h-100"
                                                        data-service-id="{{ $service->id_perawatan }}">
                                                        <div class="card-body">
                                                            <div class="form-check">
                                                                <input class="form-check-input service-checkbox"
                                                                    type="checkbox" value="{{ $service->id_perawatan }}"
                                                                    id="service_{{ $service->id_perawatan }}"
                                                                    data-name="{{ $service->nama_perawatan }}"
                                                                    data-price="{{ $service->harga }}"
                                                                    data-duration="{{ $service->waktu }}">
                                                                <label class="form-check-label w-100"
                                                                    for="service_{{ $service->id_perawatan }}">
                                                                    <div class="service-content">
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-start mb-2">
                                                                            <h6 class="service-name mb-1">
                                                                                {{ $service->nama_perawatan }}</h6>
                                                                            <span class="badge bg-primary">
                                                                                <i class="fas fa-clock me-1"></i>
                                                                                {{ $service->waktu }} min
                                                                            </span>
                                                                        </div>

                                                                        @if ($service->deskripsi)
                                                                            <p
                                                                                class="service-description text-muted small mb-2">
                                                                                {{ Str::limit($service->deskripsi, 100) }}
                                                                            </p>
                                                                        @endif

                                                                        <div
                                                                            class="d-flex justify-content-between align-items-center">
                                                                            <div class="service-features">
                                                                                <small class="text-info">
                                                                                    <i class="fas fa-spa me-1"></i>
                                                                                    Perawatan Premium
                                                                                </small>
                                                                            </div>
                                                                            <div class="service-price">
                                                                                <strong class="text-success fs-5">
                                                                                    Rp
                                                                                    {{ number_format($service->harga, 0, ',', '.') }}
                                                                                </strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <div class="alert alert-warning text-center">
                                                        <i class="fas fa-spa fa-2x mb-2 d-block"></i>
                                                        <h5>Belum Ada Layanan Tersedia</h5>
                                                        <p class="mb-0">Saat ini belum ada layanan perawatan yang
                                                            tersedia.</p>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <!-- Step 3: Pilih Waktu -->
                                    <div class="booking-step mb-4">
                                        <div class="step-header mb-3">
                                            <span class="step-number">3</span>
                                            <h6 class="step-title mb-0">Pilih Waktu</h6>
                                        </div>
                                        <div id="timeSlotsContainer">
                                            <div class="text-center py-4">
                                                <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">Silakan pilih layanan terlebih dahulu.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Step 4: Metode Pembayaran -->
                                    <div class="booking-step mb-4">
                                        <div class="step-header mb-3">
                                            <span class="step-number">4</span>
                                            <h6 class="step-title mb-0">Metode Pembayaran</h6>
                                        </div>
                                        <div class="payment-methods">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="payment_method"
                                                            id="midtrans" value="midtrans" checked>
                                                        <label class="form-check-label w-100" for="midtrans">
                                                            <div class="d-flex align-items-center">
                                                                <div class="payment-icon me-3">
                                                                    <i class="fas fa-credit-card fa-2x text-primary"></i>
                                                                </div>
                                                                <div>
                                                                    <strong class="d-block">Midtrans Payment
                                                                        Gateway</strong>
                                                                    <small class="text-muted">
                                                                        Kartu Kredit, Virtual Account, E-Wallet, Bank
                                                                        Transfer
                                                                    </small>
                                                                    <div class="mt-1">
                                                                        <span class="badge bg-success me-1">Aman</span>
                                                                        <span class="badge bg-info me-1">Instan</span>
                                                                        <span class="badge bg-warning">24/7</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Availability Info -->
                                    <div id="availabilityInfo" class="alert" style="display: none;"></div>

                                    <!-- Submit Button -->
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" id="bookButton" disabled>
                                            <i class="fas fa-spa me-2"></i>
                                            Pilih Layanan Terlebih Dahulu
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm position-sticky" style="top: 20px;">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    Ringkasan Booking
                                </h5>
                            </div>
                            <div class="card-body">
                                <!-- Selected Services -->
                                <div class="section mb-3">
                                    <h6 class="section-title">
                                        <i class="fas fa-spa me-1"></i>
                                        Layanan Dipilih
                                    </h6>
                                    <div id="selectedServices">
                                        <div class="empty-state text-center py-3">
                                            <i class="fas fa-hand-pointer text-muted mb-2"></i>
                                            <p class="text-muted mb-0">Belum ada layanan yang dipilih</p>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Booking Details -->
                                <div id="bookingDetails" style="display: none;">
                                    <h6 class="section-title">
                                        <i class="fas fa-calendar-check me-1"></i>
                                        Detail Booking
                                    </h6>
                                    <div class="detail-item mb-2">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Tanggal:</small>
                                            <span id="selectedDate" class="fw-medium">-</span>
                                        </div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Waktu:</small>
                                            <span id="selectedTime" class="fw-medium">-</span>
                                        </div>
                                    </div>
                                    <div class="detail-item mb-2">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Durasi Total:</small>
                                            <span id="totalDuration" class="fw-medium">-</span>
                                        </div>
                                    </div>
                                    <div class="detail-item mb-3">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Terapis:</small>
                                            <span class="fw-medium text-primary">Auto-assign</span>
                                        </div>
                                    </div>
                                    <hr>
                                </div>

                                <!-- Total Price -->
                                <div class="total-section">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Total:</h5>
                                        <h4 class="mb-0 text-primary" id="totalPrice">Rp 0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3"></div>
                    <h5>Memproses Booking</h5>
                    <p class="text-muted mb-0">Mohon tunggu...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .service-card {
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .service-card:hover {
            border-color: #007bff;
            box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.15);
        }

        .service-card.selected {
            border-color: #007bff;
            background: #f8f9ff;
        }

        .booking-step {
            position: relative;
            padding-left: 3rem;
        }

        .step-number {
            position: absolute;
            left: -3rem;
            top: 0;
            width: 2rem;
            height: 2rem;
            background: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .time-slot {
            border: 2px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 80px;
            text-align: center;
            font-weight: 500;
            margin: 0.25rem;
            display: inline-block;
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

        .time-slot.time-conflict {
            background: #ffe5e5;
            border-color: #ffcccc;
            color: #dc3545;
        }

        .empty-state {
            background: #f8f9fa;
            border-radius: 0.5rem;
            border: 2px dashed #dee2e6;
        }

        @media (max-width: 768px) {
            .booking-step {
                padding-left: 2rem;
            }

            .step-number {
                left: -2rem;
                width: 1.5rem;
                height: 1.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        let selectedServices = [];
        let selectedTime = null;
        let totalDuration = 0;
        let isProcessing = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Service selection
            document.querySelectorAll('.service-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectedServices();
                    updateTimeSlots();
                    validateForm();
                });
            });

            // Date change
            document.getElementById('booking_date').addEventListener('change', function() {
                updateBookingDetails();
                if (selectedServices.length > 0) {
                    updateTimeSlots();
                }
                validateForm();
            });

            // Form submission
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
                e.preventDefault();
                if (!isProcessing) {
                    processBooking();
                }
            });
        });

        function updateSelectedServices() {
            selectedServices = [];
            const checkedBoxes = document.querySelectorAll('.service-checkbox:checked');

            // Clear all selected states
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Update selected services
            checkedBoxes.forEach(checkbox => {
                selectedServices.push({
                    id: checkbox.value,
                    name: checkbox.dataset.name,
                    price: parseInt(checkbox.dataset.price),
                    duration: parseInt(checkbox.dataset.duration)
                });

                const card = checkbox.closest('.service-card');
                card.classList.add('selected');
            });

            updateSummary();
        }

        function updateSummary() {
            const container = document.getElementById('selectedServices');
            const totalPriceEl = document.getElementById('totalPrice');

            if (selectedServices.length === 0) {
                container.innerHTML = `
            <div class="empty-state text-center py-3">
                <i class="fas fa-hand-pointer text-muted mb-2"></i>
                <p class="text-muted mb-0">Belum ada layanan yang dipilih</p>
            </div>
        `;
                totalPriceEl.textContent = 'Rp 0';
                totalDuration = 0;
                document.getElementById('totalDuration').textContent = '-';
                return;
            }

            let html = '';
            let totalPrice = 0;
            totalDuration = 0;

            selectedServices.forEach(service => {
                totalPrice += service.price;
                totalDuration += service.duration;

                html += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="fw-medium">${service.name}</div>
                    <small class="text-muted">${service.duration} menit</small>
                </div>
                <div class="text-end">
                    <div class="fw-medium">Rp ${service.price.toLocaleString('id-ID')}</div>
                </div>
            </div>
        `;
            });

            container.innerHTML = html;
            totalPriceEl.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
            document.getElementById('totalDuration').textContent = `${totalDuration} menit`;
        }

        function updateTimeSlots() {
            const container = document.getElementById('timeSlotsContainer');

            if (selectedServices.length === 0) {
                container.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                <p class="text-muted">Silakan pilih layanan terlebih dahulu.</p>
            </div>
        `;
                return;
            }

            container.innerHTML = '<p class="text-muted">Memuat slot waktu...</p>';

            const shifts = @json($timeSlots);
            let html = '';

            Object.keys(shifts).forEach(shiftName => {
                html += `<h6 class="mt-3 mb-2">${shiftName}</h6><div>`;

                shifts[shiftName].forEach(time => {
                    html +=
                        `<span class="time-slot" data-time="${time}" onclick="selectTime('${time}')">${time}</span>`;
                });

                html += '</div>';
            });

            container.innerHTML = html;
            checkAvailability();
        }

        function selectTime(time) {
            const clickedSlot = document.querySelector(`[data-time="${time}"]`);
            if (clickedSlot.classList.contains('unavailable')) {
                return;
            }

            document.querySelectorAll('.time-slot').forEach(slot => {
                slot.classList.remove('selected');
            });

            clickedSlot.classList.add('selected');
            selectedTime = time;

            updateBookingDetails();
            validateForm();
        }

        function updateBookingDetails() {
            const date = document.getElementById('booking_date').value;
            const detailsContainer = document.getElementById('bookingDetails');

            if (date) {
                document.getElementById('selectedDate').textContent = new Date(date).toLocaleDateString('id-ID');
                detailsContainer.style.display = 'block';
            }

            if (selectedTime) {
                document.getElementById('selectedTime').textContent = selectedTime;
            }
        }

        function checkAvailability() {
            if (selectedServices.length === 0) return;

            const date = document.getElementById('booking_date').value;
            if (!date) return;

            document.querySelectorAll('.time-slot').forEach(slot => {
                const time = slot.dataset.time;

                fetch('{{ route('customer.booking.check-availability') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            date: date,
                            start_time: time,
                            duration: totalDuration
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.available) {
                            slot.classList.add('unavailable');
                            slot.style.pointerEvents = 'none';

                            // Jika ada konflik waktu, tambahkan tooltip atau class khusus
                            if (data.has_time_conflict) {
                                slot.title = "Waktu ini sudah dibooking untuk layanan lain";
                                slot.classList.add('time-conflict');
                            }
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        }

        function validateForm() {
            const bookButton = document.getElementById('bookButton');
            const hasServices = selectedServices.length > 0;
            const hasTime = selectedTime !== null;
            const hasDate = document.getElementById('booking_date').value !== '';

            const isValid = hasServices && hasTime && hasDate && !isProcessing;
            bookButton.disabled = !isValid;

            if (!hasServices) {
                bookButton.innerHTML = '<i class="fas fa-spa me-2"></i>Pilih Layanan Terlebih Dahulu';
            } else if (!hasDate) {
                bookButton.innerHTML = '<i class="fas fa-calendar me-2"></i>Pilih Tanggal Terlebih Dahulu';
            } else if (!hasTime) {
                bookButton.innerHTML = '<i class="fas fa-clock me-2"></i>Pilih Waktu Terlebih Dahulu';
            } else {
                bookButton.innerHTML = '<i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran';
            }
        }

        function processBooking() {
            if (selectedServices.length === 0 || !selectedTime || isProcessing) return;

            isProcessing = true;

            const formData = {
                services: selectedServices,
                booking_date: document.getElementById('booking_date').value,
                booking_time: selectedTime,
                payment_method: 'midtrans',
                total_price: selectedServices.reduce((total, service) => total + service.price, 0)
            };

            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();

            const bookButton = document.getElementById('bookButton');
            bookButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            bookButton.disabled = true;

            fetch('{{ route('customer.booking.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    loadingModal.hide();

                    if (data.success) {
                        // Di fungsi processBooking, update callback Midtrans:
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                updatePaymentStatus(data.order_id, 'settlement');
                            },
                            onPending: function(result) {
                                updatePaymentStatus(data.order_id, 'pending');
                            },
                            onError: function(result) {
                                updatePaymentStatus(data.order_id, 'failure');
                            },
                            onClose: function() {
                                updatePaymentStatus(data.order_id, 'cancel');
                            }
                        });

                        // Function untuk update payment status
                        function updatePaymentStatus(orderId, status) {
                            fetch('{{ route('customer.payment.update-status') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            ?.getAttribute('content') ||
                                            document.querySelector('input[name="_token"]')?.value
                                    },
                                    body: JSON.stringify({
                                        order_id: orderId,
                                        transaction_status: status
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Redirect ke dashboard dengan message
                                        window.location.href = '{{ route('customer.dashboard') }}?status=' +
                                            data.alert_type + '&message=' + encodeURIComponent(data.message);
                                    } else {
                                        alert('Error: ' + data.message);
                                        resetForm();
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat memproses pembayaran');
                                    resetForm();
                                });
                        }
                    } else {
                        alert('Error: ' + data.message);
                        resetForm();
                    }
                })
                .catch(error => {
                    loadingModal.hide();
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    resetForm();
                });
        }

        function resetForm() {
            isProcessing = false;
            validateForm();
        }
    </script>
@endpush
