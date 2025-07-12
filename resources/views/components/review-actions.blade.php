<div>
    <!-- Tombol View -->
    <a href="{{ route('admin.reviews.show', $review->id_review) }}"
        class="text-blue-600 hover:text-blue-900 font-medium mr-2 transition">
        View
    </a>

    <!-- Tombol Status -->
    <button type="button" class="text-green-600 hover:text-green-800 font-medium mr-2 transition"
        onclick="openStatusModal{{ $review->id_review }}()">
        Status
    </button>

    <!-- Tombol Hapus -->
    <button type="button" class="text-red-600 hover:text-red-800 font-medium transition"
        onclick="openDeleteModal{{ $review->id_review }}()">
        Hapus
    </button>

    <!-- Status Modal -->
    <div id="status-modal-overlay-{{ $review->id_review }}"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div id="status-modal-box-{{ $review->id_review }}"
            class="bg-white rounded-xl shadow-2xl p-6 w-96 relative opacity-0 scale-95 translate-y-4 transition-all duration-300">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Update Status Review</h3>
            <form action="{{ route('admin.reviews.update-status', $review->id_review) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <label for="status-{{ $review->id_review }}" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status-{{ $review->id_review }}" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label for="admin_notes-{{ $review->id_review }}" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                    <textarea id="admin_notes-{{ $review->id_review }}" name="admin_notes" rows="3" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Tambahkan catatan untuk review ini...">{{ $review->admin_notes ?? '' }}</textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal{{ $review->id_review }}()"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal-overlay-{{ $review->id_review }}"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div id="delete-modal-box-{{ $review->id_review }}"
            class="bg-white rounded-xl shadow-2xl p-6 w-80 relative opacity-0 scale-95 translate-y-4 transition-all duration-300">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Konfirmasi Hapus</h3>
            <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus review ini?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal{{ $review->id_review }}()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Batal
                </button>
                <form action="{{ route('admin.reviews.destroy', $review->id_review) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openStatusModal{{ $review->id_review }}() {
        const overlay = document.getElementById('status-modal-overlay-{{ $review->id_review }}');
        const modal = document.getElementById('status-modal-box-{{ $review->id_review }}');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
            modal.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        }, 10);

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeStatusModal{{ $review->id_review }}();
            }
        });
    }

    function closeStatusModal{{ $review->id_review }}() {
        const overlay = document.getElementById('status-modal-overlay-{{ $review->id_review }}');
        const modal = document.getElementById('status-modal-box-{{ $review->id_review }}');

        modal.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        modal.classList.add('opacity-0', 'scale-95', 'translate-y-4');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }

    function openDeleteModal{{ $review->id_review }}() {
        const overlay = document.getElementById('delete-modal-overlay-{{ $review->id_review }}');
        const modal = document.getElementById('delete-modal-box-{{ $review->id_review }}');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
            modal.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        }, 10);

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeDeleteModal{{ $review->id_review }}();
            }
        });
    }

    function closeDeleteModal{{ $review->id_review }}() {
        const overlay = document.getElementById('delete-modal-overlay-{{ $review->id_review }}');
        const modal = document.getElementById('delete-modal-box-{{ $review->id_review }}');

        modal.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        modal.classList.add('opacity-0', 'scale-95', 'translate-y-4');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200);
    }
</script>