<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject ?? 'Dewi Beauty Salon' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #FF6B9D, #FF8FA3);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }

        .booking-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #FF6B9D;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }

        .status {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }

        .status.confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>DEWI BEAUTY SALON</h1>
            <p>Pengalaman Kecantikan Premium</p>
        </div>

        <div class="content">
            @if ($type === 'booking_confirmation')
                <h2>Konfirmasi Booking Anda</h2>
                <p>Halo {{ $pemesanan->pelanggan->nama_lengkap }},</p>
                <p>Terima kasih telah melakukan booking di Dewi Beauty Salon. Berikut adalah detail booking Anda:</p>

                <div class="booking-details">
                    <h3>Detail Booking</h3>
                    <p><strong>ID Booking:</strong> #{{ $pemesanan->id_pemesanan }}</p>
                    <p><strong>Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d F Y') }}</p>
                    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pemesanan->waktu)->format('H:i') }}</p>
                    <p><strong>Layanan:</strong>
                        @foreach ($pemesanan->bookeds as $booked)
                            {{ $booked->perawatan->nama_perawatan }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                    <p><strong>Total:</strong> Rp {{ number_format($pemesanan->total, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> <span class="status confirmed">Terkonfirmasi</span></p>
                </div>

                <p>Mohon datang 15 menit sebelum waktu appointment Anda. Jika ada pertanyaan, silakan hubungi kami.</p>
            @elseif($type === 'cancellation')
                <h2>Pembatalan Booking</h2>
                <p>Halo {{ $pemesanan->pelanggan->nama_lengkap }},</p>
                <p>Booking Anda telah berhasil dibatalkan.</p>

                <div class="booking-details">
                    <h3>Detail Booking yang Dibatalkan</h3>
                    <p><strong>ID Booking:</strong> #{{ $pemesanan->id_pemesanan }}</p>
                    <p><strong>Tanggal:</strong>
                        {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d F Y') }}</p>
                    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pemesanan->waktu)->format('H:i') }}</p>
                    <p><strong>Status:</strong> <span class="status cancelled">Dibatalkan</span></p>
                    @if (isset($additionalData['reason']) && $additionalData['reason'])
                        <p><strong>Alasan:</strong> {{ $additionalData['reason'] }}</p>
                    @endif
                </div>

                @if ($pemesanan->status_pembayaran === 'paid')
                    <p><strong>Refund:</strong> Dana sebesar Rp {{ number_format($pemesanan->total, 0, ',', '.') }}
                        akan dikembalikan dalam 3-5 hari kerja.</p>
                @endif
            @elseif($type === 'reschedule')
                <h2>Perubahan Jadwal Booking</h2>
                <p>Halo {{ $pemesanan->pelanggan->nama_lengkap }},</p>
                <p>Jadwal booking Anda telah berhasil diubah.</p>

                <div class="booking-details">
                    <h3>Detail Booking Baru</h3>
                    <p><strong>ID Booking:</strong> #{{ $pemesanan->id_pemesanan }}</p>
                    @if (isset($additionalData['old_date']) && isset($additionalData['old_time']))
                        <p><strong>Jadwal Lama:</strong>
                            {{ \Carbon\Carbon::parse($additionalData['old_date'])->format('d F Y') }} -
                            {{ \Carbon\Carbon::parse($additionalData['old_time'])->format('H:i') }}</p>
                    @endif
                    <p><strong>Jadwal Baru:</strong>
                        {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d F Y') }} -
                        {{ \Carbon\Carbon::parse($pemesanan->waktu)->format('H:i') }}</p>
                    <p><strong>Status:</strong> <span class="status confirmed">Terkonfirmasi</span></p>
                </div>

                <p>Mohon datang 15 menit sebelum waktu appointment baru Anda.</p>
            @elseif($type === 'refund')
                <h2>Konfirmasi Refund</h2>
                <p>Halo {{ $pemesanan->pelanggan->nama_lengkap }},</p>
                <p>Refund untuk booking yang dibatalkan telah diproses.</p>

                <div class="booking-details">
                    <h3>Detail Refund</h3>
                    <p><strong>ID Booking:</strong> #{{ $pemesanan->id_pemesanan }}</p>
                    <p><strong>Jumlah Refund:</strong> Rp
                        {{ number_format($additionalData['refund_amount'] ?? $pemesanan->total, 0, ',', '.') }}</p>
                    <p><strong>Metode Refund:</strong> Kembali ke rekening asal</p>
                    <p><strong>Estimasi:</strong> 3-5 hari kerja</p>
                </div>

            @endif

            <div style="margin: 30px 0; text-align: center;">
                <a href="{{ url('/customer/dashboard') }}" class="btn">Lihat Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <h3>Dewi Beauty Salon</h3>
            <p>Jl. Raya Mas No.31, MAS, Ubud, Bali</p>
            <p>Telepon: +62 878-6178-6535 | Email: info@dewibeautysalon.com</p>
            <p>Jam Operasional: Setiap hari 09:00 - 19:00</p>

            <div style="margin-top: 20px;">
                <p style="font-size: 12px; color: #666;">
                    Email ini dikirim otomatis. Mohon tidak membalas email ini.
                    Jika ada pertanyaan, silakan hubungi customer service kami.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
