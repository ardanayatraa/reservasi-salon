<?php

namespace App\Http\Livewire\Table;

use App\Models\Review;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Illuminate\Support\Facades\DB;

class ReviewTable extends LivewireDatatable
{
    public $model = Review::class;
    public $statuses = [
        'approved' => 'Disetujui',
        'pending' => 'Pending',
        'rejected' => 'Ditolak',
    ];

    protected $listeners = ['changeStatus' => 'updateStatus'];

    public function builder()
    {
        return Review::with(['pelanggan', 'pemesanan.bookeds.perawatan']);
    }

    public function columns()
    {
        return [
            Column::name('id_review')
                ->label('ID Review')
                ->defaultSort('desc'),

            Column::name('pelanggan.nama_lengkap')
                ->label('Customer')
                ->searchable(),

            Column::callback(['rating'], function ($rating) {
                $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                return '<div class="text-warning">' . $stars . ' <span class="text-muted">(' . $rating . ')</span></div>';
            })->label('Rating'),

            Column::callback(['komentar'], function ($komentar) {
                return '<div class="text-truncate" style="max-width: 200px;" title="' . $komentar . '">' . 
                       \Str::limit($komentar, 50) . '</div>';
            })->label('Komentar'),

            Column::callback(['status'], function ($status) {
                $badgeClass = [
                    'approved' => 'bg-success',
                    'pending' => 'bg-warning',
                    'rejected' => 'bg-danger'
                ];
                
                $statusText = $this->statuses[$status] ?? $status;
                return '<span class="badge ' . ($badgeClass[$status] ?? 'bg-secondary') . '">' . $statusText . '</span>';
            })->label('Status'),

            Column::callback(['created_at'], function ($created_at) {
                return \Carbon\Carbon::parse($created_at)->format('d/m/Y H:i');
            })->label('Tanggal'),

            Column::callback(['id_review'], function ($id) {
                $review = Review::find($id); // Ambil object review lengkap
                return view('components.review-actions', [
                    'review' => $review, // Pass object review, bukan hanya ID
                ]);
            })->label('Aksi'),
        ];
    }

    public function updateStatus($id, $newStatus)
    {
        $review = Review::find($id);
        if ($review) {
            $review->status = $newStatus;
            $review->save();
        }
    }
} 