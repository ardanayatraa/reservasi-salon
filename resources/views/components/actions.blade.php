<div>
    <!-- Tombol Edit -->
    <a href="{{ route($route . '.edit', $id) }}"
        class="text-indigo-600 hover:text-indigo-900 font-medium mr-2 transition">
        Edit
    </a>

    <!-- Tombol Hapus -->
    <button type="button" class="text-red-600 hover:text-red-800 font-medium transition"
        onclick="openModal{{ $id }}()">
        Hapus
    </button>

    <!-- Modal -->
    <div id="modal-overlay-{{ $id }}"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm hidden">
        <div id="modal-box-{{ $id }}"
            class="bg-white rounded-xl shadow-2xl p-6 w-80 relative opacity-0 scale-95 translate-y-4 transition-all duration-300">
            <h3 class="text-xl font-semibold mb-4 text-gray-800">Konfirmasi Hapus</h3>
            <p class="mb-6 text-gray-600">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal{{ $id }}()"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition">
                    Batal
                </button>
                <form action="{{ route($route . '.destroy', $id) }}" method="POST">
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
    function openModal{{ $id }}() {
        const overlay = document.getElementById('modal-overlay-{{ $id }}');
        const modal = document.getElementById('modal-box-{{ $id }}');

        overlay.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
            modal.classList.add('opacity-100', 'scale-100', 'translate-y-0');
        }, 10);

        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeModal{{ $id }}();
            }
        });
    }

    function closeModal{{ $id }}() {
        const overlay = document.getElementById('modal-overlay-{{ $id }}');
        const modal = document.getElementById('modal-box-{{ $id }}');

        modal.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
        modal.classList.add('opacity-0', 'scale-95', 'translate-y-4');

        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 200); // match duration
    }
</script>
