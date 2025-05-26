<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Karyawan;

class DashboardController extends Controller
{
    /**
     * Tampilkan data ringkasan untuk dashboard.
     */
    public function index()
    {
        // Total seluruh pemesanan
        $totalPemesanan = Pemesanan::count();

        // Total pelanggan terdaftar
        $totalPelanggan = Pelanggan::count();

        // Total karyawan terdaftar
        $totalKaryawan  = Karyawan::count();

        // Total pembayaran berstatus 'paid' dan 'unpaid'
        $totalPaid   = Pembayaran::where('status_pembayaran', 'paid')->count();
        $totalUnpaid = Pembayaran::where('status_pembayaran', 'unpaid')->count();

        return view('dashboard', compact(
            'totalPemesanan',
            'totalPelanggan',
            'totalKaryawan',
            'totalPaid',
            'totalUnpaid'
        ));
    }
}
