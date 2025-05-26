<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan dengan filter, pencarian, dan paginasi.
     */
    public function index(Request $request)
    {
        $query = Pemesanan::with([
            'pelanggan',
            'karyawan',
            'bookeds.perawatan',
            'pembayaran',
        ]);

        // 1) Filter tanggal pemesanan
        $start_date = $request->input('start_date');
        $end_date   = $request->input('end_date');
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_pemesanan', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay(),
            ]);
        }

        // 2) Filter status pembayaran
        $statusPembayaran = $request->input('status_pembayaran');
        if (in_array($statusPembayaran, ['paid','unpaid'], true)) {
            $query->whereHas('pembayaran', function($q) use($statusPembayaran) {
                $q->where('status_pembayaran', $statusPembayaran);
            });
        }

        // 3) Search real-time: cari di nama pelanggan atau nama karyawan
        if ($search = $request->input('search')) {
            $query->where(function($q) use($search) {
                $q->whereHas('pelanggan', fn($q2) =>
                        $q2->where('nama_lengkap','like', "%{$search}%"))
                  ->orWhereHas('karyawan', fn($q3) =>
                        $q3->where('nama_lengkap','like', "%{$search}%"));
            });
        }

        // 4) Ambil data & total pendapatan
        $laporans     = $query
            ->orderBy('tanggal_pemesanan','desc')
            ->paginate(25)
            ->withQueryString();
        $totalRevenue = $laporans->sum(fn($row) => $row->pembayaran?->total_harga ?? 0);

        return view('laporan.index', compact(
            'laporans','start_date','end_date','statusPembayaran','totalRevenue','search'
        ));
    }

    /**
     * Export data laporan (seluruh hasil filter/search) ke PDF,
     * 25 baris per halaman otomatis dipisah.
     */
    public function exportPdf(Request $request)
    {
        $query = Pemesanan::with([
            'pelanggan','karyawan','bookeds.perawatan','pembayaran'
        ]);

        // ulangi filter tanggal
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_pemesanan', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay(),
            ]);
        }

        // ulangi filter status pembayaran
        $statusPembayaran = $request->status_pembayaran;
        if (in_array($statusPembayaran,['paid','unpaid'],true)) {
            $query->whereHas('pembayaran', fn($q) =>
                $q->where('status_pembayaran',$statusPembayaran)
            );
        }

        // ulangi search
        if ($search = $request->search) {
            $query->where(fn($q) =>
                $q->whereHas('pelanggan', fn($q2) =>
                        $q2->where('nama_lengkap','like',"%{$search}%"))
                  ->orWhereHas('karyawan', fn($q3) =>
                        $q3->where('nama_lengkap','like',"%{$search}%"))
            );
        }

        $laporans     = $query->orderBy('tanggal_pemesanan','desc')->get();
        $totalRevenue = $laporans->sum(fn($row)=>$row->pembayaran?->total_harga ?? 0);

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'laporans','start_date','end_date','statusPembayaran','totalRevenue'
        ))
        ->setPaper('a4','landscape');

        return $pdf->download('laporan_pemesanan_'.now()->format('Ymd_His').'.pdf');
    }
}
