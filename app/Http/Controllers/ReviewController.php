<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Pemesanan;
use App\Models\EmailNotification;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews for public viewing
     */
    public function index()
    {
        $query = Review::with(['pelanggan', 'pemesanan.bookeds.perawatan']);

        // Filter by status if column exists, otherwise show all
        if (Schema::hasColumn('reviews', 'status')) {
            $query->where('status', 'approved');
        }

        // Order by tanggal_review if exists, otherwise by created_at
        if (Schema::hasColumn('reviews', 'tanggal_review')) {
            $query->orderByDesc('tanggal_review');
        } else {
            $query->latest();
        }

        $reviews = $query->paginate(10);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new review
     */
    public function create($pemesananId)
    {
        $pemesanan = Pemesanan::with(['bookeds.perawatan'])
                             ->where('id_pemesanan', $pemesananId)
                             ->where('id_pelanggan', Auth::guard('pelanggan')->id())
                             ->where('status_pemesanan', 'completed')
                             ->firstOrFail();

        // Cek apakah sudah pernah review
        $existingReview = Review::where('id_pemesanan', $pemesananId)
                               ->where('id_pelanggan', Auth::guard('pelanggan')->id())
                               ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
        }

        return view('reviews.create', compact('pemesanan'));
    }

    /**
     * Store a newly created review in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pemesanan' => 'required|exists:pemesanans,id_pemesanan',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000' // Sesuai dengan model
        ]);

        try {
            DB::beginTransaction();

            // Pastikan pemesanan milik user dan sudah selesai
            $pemesanan = Pemesanan::where('id_pemesanan', $request->id_pemesanan)
                                 ->where('id_pelanggan', Auth::guard('pelanggan')->id())
                                 ->where('status_pemesanan', 'completed')
                                 ->firstOrFail();

            // Double check existing review
            $existingReview = Review::where('id_pemesanan', $request->id_pemesanan)
                                   ->where('id_pelanggan', Auth::guard('pelanggan')->id())
                                   ->first();

            if ($existingReview) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
            }

            // Prepare review data sesuai dengan model
            $reviewData = [
                'id_pemesanan' => $request->id_pemesanan,
                'id_pelanggan' => Auth::guard('pelanggan')->id(),
                'rating' => $request->rating,
                'komentar' => $request->komentar, // Sesuai dengan model
                'tanggal_review' => now(), // Set tanggal review
                // status akan otomatis ter-set ke 'pending' dari model default
            ];

            // Create review
            Review::create($reviewData);

            // Log email notification jika tabel ada
            if (Schema::hasTable('email_notifications')) {
                $this->logEmailNotification(
                    Auth::guard('pelanggan')->id(),
                    'review_submitted',
                    'Review Berhasil Dikirim',
                    'Terima kasih telah memberikan review untuk layanan kami.'
                );
            }

            DB::commit();

            $message = 'Review berhasil dikirim dan menunggu persetujuan admin.';

            return redirect()->route('customer.dashboard')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat mengirim review. Silakan coba lagi.')
                            ->withInput();
        }
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $query = Review::with(['pelanggan', 'pemesanan.bookeds.perawatan']);

        if (Schema::hasColumn('reviews', 'status')) {
            $query->where('status', 'approved');
        }

        $review = $query->findOrFail($id);

        return view('reviews.show', compact('review'));
    }

    // ==================== ADMIN FUNCTIONS ====================

    /**
     * Display a listing of all reviews for admin
     */
    public function adminIndex(Request $request)
    {
        $query = Review::with(['pelanggan', 'pemesanan.bookeds.perawatan']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search berdasarkan nama pelanggan atau review
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pelanggan', function($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                });
                $q->orWhere('komentar', 'like', "%{$search}%"); // Sesuai dengan model
            });
        }

        // Order by tanggal_review
        $query->orderByDesc('tanggal_review');

        $reviews = $query->paginate(15);

        // Statistik untuk dashboard admin
        $stats = [
            'total' => Review::count(),
            'average_rating' => Review::avg('rating'),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * Show the form for editing the specified review (admin only)
     */
    public function adminEdit($id)
    {
        $review = Review::with(['pelanggan', 'pemesanan.bookeds.perawatan'])
                       ->findOrFail($id);

        return view('admin.reviews.edit', compact('review'));
    }

    /**
     * Update the specified review (admin only)
     */
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);
            $oldStatus = $review->status;

            $updateData = [
                'status' => $request->status,
                'admin_notes' => $request->admin_notes
            ];

            $review->update($updateData);

            // Log email notification ke pelanggan jika status berubah
            if ($oldStatus !== $request->status && Schema::hasTable('email_notifications')) {
                $this->logEmailNotification(
                    $review->id_pelanggan,
                    'review_status_update',
                    'Status Review Anda Telah Diperbarui',
                    $this->getReviewStatusMessage($request->status, $review->pelanggan->nama)
                );
            }

            DB::commit();

            return redirect()->route('admin.reviews.index')
                            ->with('success', 'Review berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan saat memperbarui review.')
                            ->withInput();
        }
    }

    /**
     * Approve a review
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);
            $review->update(['status' => 'approved']);

            // Log email notification ke pelanggan
            if (Schema::hasTable('email_notifications')) {
                $this->logEmailNotification(
                    $review->id_pelanggan,
                    'review_approved',
                    'Review Anda Telah Disetujui',
                    $this->getReviewStatusMessage('approved', $review->pelanggan->nama)
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Review berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui review.');
        }
    }

    /**
     * Reject a review
     */
    public function reject($id)
    {
        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);
            $review->update(['status' => 'rejected']);

            // Log email notification ke pelanggan
            if (Schema::hasTable('email_notifications')) {
                $this->logEmailNotification(
                    $review->id_pelanggan,
                    'review_rejected',
                    'Review Anda Ditolak',
                    $this->getReviewStatusMessage('rejected', $review->pelanggan->nama)
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Review berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak review.');
        }
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id_review'
        ]);

        try {
            DB::beginTransaction();

            $reviews = Review::whereIn('id_review', $request->review_ids)->get();

            foreach ($reviews as $review) {
                $review->update(['status' => 'approved']);

                // Log email notification
                if (Schema::hasTable('email_notifications')) {
                    $this->logEmailNotification(
                        $review->id_pelanggan,
                        'review_approved',
                        'Review Anda Telah Disetujui',
                        $this->getReviewStatusMessage('approved', $review->pelanggan->nama)
                    );
                }
            }

            DB::commit();

            return redirect()->back()
                            ->with('success', count($request->review_ids) . ' review berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat bulk approve review.');
        }
    }

    /**
     * Delete a review (admin only)
     */
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return redirect()->back()->with('success', 'Review berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus review.');
        }
    }

    /**
     * Get reviews by customer (for customer dashboard)
     */
    public function customerReviews()
    {
        $query = Review::with(['pemesanan.bookeds.perawatan'])
                      ->where('id_pelanggan', Auth::guard('pelanggan')->id());

        // Order by tanggal_review
        $query->orderByDesc('tanggal_review');

        $reviews = $query->paginate(10);

        return view('customer.reviews.index', compact('reviews'));
    }

    /**
     * Get reviews statistics for dashboard
     */
    public function getReviewStats()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'average_rating' => number_format(Review::avg('rating'), 1),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'approved_reviews' => Review::where('status', 'approved')->count(),
            'rejected_reviews' => Review::where('status', 'rejected')->count(),
        ];

        $latestQuery = Review::with(['pelanggan', 'pemesanan'])
                            ->where('status', 'approved')
                            ->orderByDesc('tanggal_review');

        $stats['latest_reviews'] = $latestQuery->limit(5)->get();

        return $stats;
    }

    // ==================== HELPER METHODS ====================

    /**
     * Log email notification
     */
    private function logEmailNotification($pelangganId, $emailType, $subject, $body)
    {
        if (Schema::hasTable('email_notifications')) {
            EmailNotification::create([
                'id_pelanggan' => $pelangganId,
                'email_type' => $emailType,
                'subject' => $subject,
                'body' => $body,
                'status' => 'sent'
            ]);
        }
    }

    /**
     * Get review status message
     */
    private function getReviewStatusMessage($status, $pelangganNama)
    {
        switch ($status) {
            case 'approved':
                return "Halo {$pelangganNama}, review Anda telah disetujui dan sekarang dapat dilihat oleh pelanggan lain. Terima kasih atas feedback Anda!";
            case 'rejected':
                return "Halo {$pelangganNama}, mohon maaf review Anda tidak dapat ditampilkan karena tidak memenuhi guidelines kami. Silakan hubungi customer service untuk informasi lebih lanjut.";
            default:
                return "Status review Anda telah diperbarui.";
        }
    }

    /**
     * Check if review system has full features
     */
    public function hasFullFeatures()
    {
        return Schema::hasColumn('reviews', 'status') &&
               Schema::hasColumn('reviews', 'tanggal_review') &&
               Schema::hasTable('email_notifications') &&
               Schema::hasTable('booking_logs');
    }
}
