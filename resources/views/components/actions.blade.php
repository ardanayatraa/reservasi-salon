<div>
    <!-- Tombol Edit -->
    <a href="{{ route($route . '.edit', $id) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">
        Edit
    </a>

    <!-- Tombol Hapus -->
    <button id="delete-btn-{{ $id }}" type="button" class="text-red-600 hover:text-red-900">
        Hapus
    </button>

    <!-- Modal Konfirmasi -->
    <div id="confirm-modal-{{ $id }}"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
            <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end space-x-3">
                <button id="cancel-btn-{{ $id }}" type="button"
                    class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">
                    Batal
                </button>
                <form action="{{ route($route . '.destroy', $id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampilkan modal saat tombol Hapus diklik
    document.getElementById('delete-btn-{{ $id }}')
        .addEventListener('click', function() {
            document.getElementById('confirm-modal-{{ $id }}')
                .classList.remove('hidden');
        });

    // Sembunyikan modal saat tombol Batal diklik
    document.getElementById('cancel-btn-{{ $id }}')
        .addEventListener('click', function() {
            document.getElementById('confirm-modal-{{ $id }}')
                .classList.add('hidden');
        });
</script>
