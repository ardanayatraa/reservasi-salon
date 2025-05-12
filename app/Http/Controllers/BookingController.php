<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class BookingController extends Controller
{
     /**
     * Tampilkan landing page dengan daftar Perawatan.
     */
public function index(Request $request)
    {
        // Ambil semua layanan dan time slots seperti sebelumnya
        $services  = Perawatan::all()->toArray();
        $timeSlots = [
            '08:00','09:00','10:00','11:00',
            '12:00','13:00','14:00','15:00','16:00',
        ];

        // Ambil data user pelanggan (jika login)
        $user = Auth::guard('pelanggan')->user();

        // Siapkan variabel riwayat
        $email     = $request->query('email');
        $histories = null;

        if ($email) {
            $pel = Pelanggan::where('email', $email)->first();

            if ($pel) {
                // Ganti → paginate(10) untuk 10 item per halaman
                $histories = Pemesanan::with('perawatan')
                    ->where('id_pelanggan', $pel->id_pelanggan)
                    ->orderByDesc('tanggal_pemesanan')
                    ->paginate(10)
                    ->appends(['email' => $email]); // supaya query email tetap di URL
            } else {
                $histories = collect(); // atau bisa buat paginator kosong
            }
        }

        // Kirim semua variabel ke view landing-page
        return view('landing-page', compact(
            'services',
            'timeSlots',
            'user',
            'email',
            'histories'
        ));
    }


    public function bookService(Request $request)
{
    $auth=Auth::guard('pelanggan')->user();

    Log::info($request);
    // Validasi input
    $request->validate([

        'service_id' => 'required|numeric',

    ]);

    // Ambil data layanan berdasarkan ID
    $serviceId = $request->service_id;
    $serviceName = $request->service_name;
    $price = $request->price;

    // Generate order ID unik
    $orderId = 'DBS-' . date('Ymd') . '-' . Str::random(5);

    $pemesanan = Pemesanan::create([
        'id_user' =>$auth->id_pelanggan,
        'id_pelanggan' => $auth->id_pelanggan,
        'id_perawatan' => $request->service_id,
        'tanggal_pemesanan' => $request->booking_date,
        'waktu' => $request->booking_time,
        'jumlah_perawatan' => 1,
        'status_pemesanan' => 'pending',
        'total' => $price,
        'sub_total' => $price,
        'metode_pembayaran' => 'midtrans',
        'status_pembayaran' => 'unpaid',
        'token' => ''
    ]);


    $pembayaran = Pembayaran::create([
        'id_pemesanan' => $pemesanan->id_pemesanan,
      'tanggal_pembayaran' => now(),
        'total_harga' => $price,
        'status_pembayaran' => 'unpaid',
        'metode_pembayaran' => 'midtrans',
        'snap_token' => '',
        'notifikasi' => 'Menunggu pembayaran'
    ]);

    // Siapkan data untuk Midtrans
    $midtransData = [
        'transaction_details' => [
            'order_id' => $pembayaran->id_pembayaran,
            'gross_amount' => $price,
        ],
        'customer_details' => [
            'first_name' => $auth->nama_lengkap,
            'email' => $auth->email,
            'phone' => $auth->no_telepon,
        ],

    ];

    // Inisialisasi Midtrans
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    try {
        // Buat Snap Token
        $snapToken = \Midtrans\Snap::getSnapToken($midtransData);

        // Jika request AJAX, kembalikan token dalam format JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $pembayaran->id_pembayaran
            ]);
        }

        // Jika bukan AJAX, redirect ke halaman pembayaran Midtrans
        return redirect()->away(\Midtrans\Snap::getSnapUrl($snapToken));
    } catch (\Exception $e) {
        // Tangani error
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


  public function finish(Request $request)
    {

        $orderId = $request->query('order_id');
        $status  = $request->query('status'); // success | pending | error

        Log::info("Frontend callback: order_id=$orderId, status=$status");

        $pembayaran = Pembayaran::where('id_pembayaran', $orderId)->first();
        if (!$pembayaran) {
            return redirect('/')->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Map status Midtrans ke kolom di DB
        switch ($status) {
            case 'success':
                $pembayaran->update([
                    'status_pembayaran' => 'paid',
                    'notifikasi'        => 'Pembayaran sukses',
                ]);
                // (opsional) update juga status pemesanan
                $pembayaran->pemesanan->update([
                    'status_pemesanan'  => 'confirmed',
                    'status_pembayaran' => 'paid',
                ]);
                $message = 'Pembayaran berhasil. Terima kasih!';
                break;

            case 'pending':
                $pembayaran->update([
                    'status_pembayaran' => 'pending',
                    'notifikasi'        => 'Pembayaran menunggu konfirmasi',
                ]);
                $message = 'Pembayaran masih pending.';
                break;

            case 'error':
            default:
                $pembayaran->update([
                    'status_pembayaran' => 'unpaid',
                    'notifikasi'        => 'Pembayaran gagal atau dibatalkan',
                ]);
                $message = 'Pembayaran gagal atau dibatalkan.';
                break;
        }

        // Redirect ke halaman result, bisa ganti view sesuai kebutuhan
        return redirect('/')
            ->with('status_message', $message);
    }
}
