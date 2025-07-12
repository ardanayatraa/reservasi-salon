<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Konfirmasi Booking</title>
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
            background: #FF6B9D;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background: #f9f9f9;
        }

        .booking-details {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>DEWI BEAUTY SALON</h1>
            <p>Konfirmasi Booking Anda</p>
        </div>

        <div class="content">
            <h2>Halo {{ $pelanggan->nama_lengkap }},</h2>
            <p>Terima kasih telah melakukan booking di Dewi Beauty Salon. Berikut detail pemesanan Anda:</p>

            <div class="booking-details">
                <h3>Detail Booking</h3>
                <p><strong>ID Booking:</strong> {{ $pemesanan->id_pemesanan }}</p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->format('d F Y') }}
                </p>
                <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($pemesanan->waktu)->format('H:i') }}</p>
                <p><strong>Status:</strong> {{ ucfirst($pemesanan->status_pemesanan) }}</p>

                <h4>Layanan yang Dipilih:</h4>
                <ul>
                    @foreach ($services as $booked)
                        <li>{{ $booked->perawatan->nama_perawatan }} - Rp
                            {{ number_format($booked->perawatan->harga, 0, ',', '.') }}</li>
                    @endforeach
                </ul>

                <p><strong>Total Pembayaran:</strong> Rp {{ number_format($pemesanan->total, 0, ',', '.') }}</p>
            </div>

            <p>Kami menantikan kedatangan Anda. Jika ada pertanyaan, silakan hubungi kami di +62 878-6178-6535.</p>
        </div>

        <div class="footer">
            <p>Dewi Beauty Salon<br>
                Jl. Raya Mas No.31, MAS, Ubud, Bali<br>
                Email: info@dewibeautysalon.com</p>
        </div>
    </div>
</body>

</html>
