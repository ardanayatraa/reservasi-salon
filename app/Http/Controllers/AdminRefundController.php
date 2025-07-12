<?php

namespace App\Http\Controllers;

use App\Models\BookingLog;
use App\Models\Pembayaran;
use App\Mail\BookingNotificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminRefundController extends Controller
{
    public function index(Request $request)
    {
        $query = BookingLog::with(['pemesanan.pelanggan', 'pemesanan.bookeds.perawatan'])
            ->where('action_type', 'cancel')
            ->where('refund_amount', '>', 0);

        if ($request->filled('status')) {
            $query->where('refund_status', $request->status);
        }

        $refunds = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.refunds.index', compact('refunds'));
    }

    public function updateStatus(Request $request, BookingLog $refund)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,failed'
        ]);

        $refund->update(['refund_status' => $request->status]);

        // Update pembayaran jika ada
        if ($refund->pemesanan->pembayaran) {
            $refund->pemesanan->pembayaran->update([
                'refund_status' => $request->status,
                'refund_date' => $request->status === 'completed' ? now() : null
            ]);
        }

        // Kirim email jika refund completed
        if ($request->status === 'completed') {
            try {
                Mail::to($refund->pemesanan->pelanggan->email)
                    ->send(new BookingNotificationMail(
                        $refund->pemesanan,
                        'refund',
                        ['refund_amount' => $refund->refund_amount]
                    ));
            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan proses
            }
        }

        return back()->with('success', 'Status refund berhasil diupdate');
    }
}
