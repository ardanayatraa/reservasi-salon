<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 20px;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 8px;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background: #eee;
        }

        .small {
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <h2>
        Laporan Pemesanan<br>
        @if ($start_date && $end_date)
            {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }}
            s/d
            {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
        @endif
        @if ($statusPembayaran)
            — Status: {{ ucfirst($statusPembayaran) }}
        @endif
    </h2>

    <div class="total">
        Total Pendapatan: Rp{{ number_format($totalRevenue, 0, ',', '.') }}
    </div>

    @foreach ($laporans->chunk(20) as $chunkIndex => $slice)
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tgl Pesan</th>
                    <th>Pelanggan</th>
                    <th>Karyawan</th>
                    <th>Perawatan & Waktu</th>
                    <th>Status</th>
                    <th>Tgl Bayar</th>
                    <th>Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($slice as $i => $row)
                    <tr>
                        <td>{{ $chunkIndex * 25 + $i + 1 }}</td>
                        <td>{{ $row->tanggal_pemesanan->format('d-m-Y') }}</td>
                        <td>{{ $row->pelanggan->nama_lengkap }}</td>
                        <td>{{ $row->karyawan->nama_lengkap }}</td>
                        <td class="small">
                            @foreach ($row->bookeds as $b)
                                • {{ $b->perawatan->nama_perawatan }}
                                ({{ $b->tanggal_booked->format('d-m-Y') }}
                                {{ $b->waktu }})
                                <br>
                            @endforeach
                        </td>
                        <td>{{ $row->status_pemesanan }}</td>
                        <td>{{ optional($row->pembayaran->tanggal_pembayaran)->format('d-m-Y') ?? '-' }}</td>
                        <td style="text-align:right;">
                            {{ $row->pembayaran ? number_format($row->pembayaran->total_harga, 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>

</html>
