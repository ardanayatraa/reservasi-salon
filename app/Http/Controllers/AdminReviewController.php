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
                })->orWhere('review_text', 'like', "%{$search}%");
            });
        }

        $reviews = $query->orderByDesc('created_at')->paginate(15);
        $services = Perawatan::all();

        // Statistics
        $stats = [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating'), 1),
            'rating_distribution' => Review::select('rating', DB::raw('count(*) as count'))
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

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }
}
