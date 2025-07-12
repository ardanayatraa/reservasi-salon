<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Review
                </h2>
                <p class="text-sm text-gray-600 mt-1">ID: {{ $review->id_review }} • {{ $review->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-500 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Review Summary Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="text-yellow-400 text-2xl">
                                    {!! str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) !!}
                                </div>
                                <span class="ml-3 text-lg font-semibold text-gray-900">{{ $review->rating }}/5</span>
                            </div>
                            <div class="h-8 w-px bg-gray-300"></div>
                            <div>
                                @if($review->status == 'approved')
                                    <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 cursor-pointer hover:bg-green-200 transition">
                                        <i class="fas fa-check-circle mr-2"></i>Disetujui
                                    </span>
                                @elseif($review->status == 'pending')
                                    <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 cursor-pointer hover:bg-yellow-200 transition">
                                        <i class="fas fa-clock mr-2"></i>Menunggu Review
                                    </span>
                                @elseif($review->status == 'rejected')
                                    <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 cursor-pointer hover:bg-red-200 transition">
                                        <i class="fas fa-times-circle mr-2"></i>Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Dibuat pada</p>
                            <p class="text-sm font-medium text-gray-900">{{ $review->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Komentar Customer</h4>
                        <p class="text-gray-900 leading-relaxed">{{ $review->komentar }}</p>
                    </div>

                    @if($review->admin_notes)
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">
                            <i class="fas fa-sticky-note mr-2"></i>Catatan Admin
                        </h4>
                        <p class="text-yellow-900">{{ $review->admin_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-user-circle text-2xl text-blue-600 mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Informasi Customer</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</dt>
                            <dd class="text-sm text-gray-900 font-medium">{{ $review->pelanggan->nama_lengkap }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $review->pelanggan->email }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">No. Telepon</dt>
                            <dd class="text-sm text-gray-900">{{ $review->pelanggan->no_telepon ?? 'Tidak tersedia' }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 md:col-span-2 lg:col-span-3">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Alamat</dt>
                            <dd class="text-sm text-gray-900">{{ $review->pelanggan->alamat ?? 'Tidak tersedia' }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            @if($review->pemesanan)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-calendar-check text-2xl text-green-600 mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Informasi Booking</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">ID Pemesanan</dt>
                            <dd class="text-sm text-gray-900 font-mono font-medium">{{ $review->pemesanan->id_pemesanan }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Tanggal Booking</dt>
                            <dd class="text-sm text-gray-900">{{ $review->pemesanan->tanggal_pemesanan->format('d/m/Y') }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Waktu</dt>
                            <dd class="text-sm text-gray-900">{{ $review->pemesanan->waktu }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Total Pembayaran</dt>
                            <dd class="text-sm text-gray-900 font-semibold text-green-600">{{ $review->pemesanan->formatted_total }}</dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Status Pembayaran</dt>
                            <dd class="text-sm">
                                @if($review->pemesanan->pembayaran)
                                    @if($review->pemesanan->pembayaran->status == 'paid')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Lunas
                                        </span>
                                    @elseif($review->pemesanan->pembayaran->status == 'pending')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Menunggu
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Gagal
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-500">Tidak ada data</span>
                                @endif
                            </dd>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Metode Pembayaran</dt>
                            <dd class="text-sm text-gray-900">
                                @if($review->pemesanan->pembayaran)
                                    {{ $review->pemesanan->pembayaran->metode_pembayaran ?? 'Tidak diketahui' }}
                                @else
                                    <span class="text-gray-500">Tidak ada data</span>
                                @endif
                            </dd>
                        </div>
                    </div>

                    <!-- Services -->
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500 mb-3">Layanan yang Dipesan</dt>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if($review->pemesanan->bookeds->isNotEmpty())
                                @foreach($review->pemesanan->bookeds as $booked)
                                    @if($booked->perawatan)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-sm font-medium text-blue-900">{{ $booked->perawatan->nama_perawatan }}</h4>
                                                    <p class="text-sm text-blue-700">{{ $booked->perawatan->formatted_harga }}</p>
                                                </div>
                                                @if($booked->karyawan)
                                                    <div class="text-right">
                                                        <p class="text-xs text-blue-600">Dilayani oleh</p>
                                                        <p class="text-xs font-medium text-blue-900">{{ $booked->karyawan->nama_karyawan }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="md:col-span-2 lg:col-span-3">
                                    <p class="text-sm text-gray-500 text-center py-4">Tidak ada data layanan</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Review Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-chart-bar text-2xl text-purple-600 mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Statistik Review</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-purple-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $review->rating }}</div>
                            <div class="text-sm text-purple-700">Rating</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $review->pelanggan->reviews->count() }}</div>
                            <div class="text-sm text-blue-700">Total Review Customer</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4 text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $review->pelanggan->pemesanans->count() }}</div>
                            <div class="text-sm text-green-700">Total Booking</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center mb-6">
                        <i class="fas fa-cogs text-2xl text-indigo-600 mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Aksi Admin</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Update Status -->
                        <div class="bg-indigo-50 rounded-lg p-6">
                            <h4 class="text-md font-medium text-indigo-900 mb-4">
                                <i class="fas fa-edit mr-2"></i>Update Status Review
                            </h4>
                            <p class="text-sm text-indigo-700 mb-4">Klik tombol di bawah untuk mengubah status review ini.</p>
                            <button onclick="openUpdateStatusModal()" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-500 transition">
                                <i class="fas fa-edit mr-2"></i>Update Status
                            </button>
                        </div>

                        <!-- Delete Review -->
                        <div class="bg-red-50 rounded-lg p-6">
                            <h4 class="text-md font-medium text-red-900 mb-4">
                                <i class="fas fa-trash-alt mr-2"></i>Hapus Review
                            </h4>
                            <div class="mb-4">
                                <div class="flex items-start">
                                    <i class="fas fa-exclamation-triangle text-red-500 mt-1 mr-3"></i>
                                    <div>
                                        <p class="text-sm text-red-800 font-medium">Tindakan Permanen</p>
                                        <p class="text-sm text-red-700 mt-1">Review ini akan dihapus secara permanen dan tidak dapat dipulihkan. Pastikan Anda yakin dengan tindakan ini.</p>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-red-500 transition">
                                    <i class="fas fa-trash mr-2"></i>Hapus Review Permanen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div id="update-status-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4 relative">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-edit mr-2 text-indigo-600"></i>Update Status Review
                </h3>
                <button onclick="closeUpdateStatusModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.reviews.update-status', $review) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-4">
                    <div>
                        <label for="modal-status" class="block text-sm font-medium text-gray-700 mb-2">Status Review</label>
                        <select id="modal-status" name="status" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>✓ Disetujui</option>
                            <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu Review</option>
                            <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>✗ Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label for="modal-admin-notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                        <textarea id="modal-admin-notes" name="admin_notes" rows="4" 
                                  class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Tambahkan catatan atau alasan untuk status review ini...">{{ $review->admin_notes }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Catatan ini akan membantu tim dalam memahami keputusan review</p>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeUpdateStatusModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk mengatur status dropdown berdasarkan klik badge
        document.addEventListener('DOMContentLoaded', function() {
            const statusBadges = document.querySelectorAll('.status-badge');
            
            statusBadges.forEach(badge => {
                badge.addEventListener('click', function() {
                    // Buka modal
                    openUpdateStatusModal();
                    
                    // Tambahkan efek visual untuk feedback
                    this.style.transform = 'scale(1.05)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                    }, 150);
                });
            });
        });

        // Fungsi untuk membuka modal update status
        function openUpdateStatusModal() {
            const modal = document.getElementById('update-status-modal');
            
            modal.classList.remove('hidden');
            
            // Focus ke textarea catatan admin
            setTimeout(() => {
                document.getElementById('modal-admin-notes').focus();
            }, 100);
        }

        // Fungsi untuk menutup modal update status
        function closeUpdateStatusModal() {
            const modal = document.getElementById('update-status-modal');
            modal.classList.add('hidden');
        }

        // Tutup modal jika klik di luar modal
        document.getElementById('update-status-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUpdateStatusModal();
            }
        });

        // Tutup modal dengan ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeUpdateStatusModal();
            }
        });
    </script>
</x-app-layout> 