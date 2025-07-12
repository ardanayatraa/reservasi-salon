<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['pelanggan', 'pemesanan.bookeds.perawatan']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by service
        if ($request->filled('service')) {
            $query->whereHas('pemesanan.bookeds.perawatan', function($q) use ($request) {
                $q->where('id_perawatan', $request->service);
            });
        }

        // Search by customer name or review text
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('pelanggan', function($subQ) use ($search) {
                    $subQ->where('nama_lengkap', 'like', "%{$search}%");
                })->orWhere('komentar', 'like', "%{$search}%");
            });
        }

        $reviews = $query->orderByDesc('created_at')->paginate(15);
        $services = Perawatan::all();

        // Statistics
        $stats = [
            'total_reviews' => Review::count(),
            'approved_reviews' => Review::where('status', 'approved')->count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'rejected_reviews' => Review::where('status', 'rejected')->count(),
            'average_rating' => round(Review::where('status', 'approved')->avg('rating'), 1),
            'rating_distribution' => Review::where('status', 'approved')
                ->select('rating', DB::raw('count(*) as count'))
                ->groupBy('rating')
                ->orderBy('rating')
                ->get()
                ->pluck('count', 'rating')
                ->toArray()
        ];

        return view('admin.reviews.index', compact('reviews', 'services', 'stats'));
    }

    public function show(Review $review)
    {
        $review->load(['pelanggan', 'pemesanan.bookeds.perawatan']);
        return view('admin.reviews.show', compact('review'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:approved,pending,rejected',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $oldStatus = $review->status;
        $newStatus = $request->status;

        $review->update([
            'status' => $newStatus,
            'admin_notes' => $request->admin_notes
        ]);

        $statusMessages = [
            'approved' => 'Review berhasil disetujui',
            'pending' => 'Review berhasil diubah ke status pending',
            'rejected' => 'Review berhasil ditolak'
        ];

        $message = $statusMessages[$newStatus];
        
        if ($request->admin_notes) {
            $message .= ' dengan catatan admin';
        }

        // Check if request expects JSON response
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'review' => $review->fresh()
            ]);
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', $message);
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }
}
