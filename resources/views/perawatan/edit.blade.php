<x-app-layout>


    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Perawatan
        </h2>

    </div>


    <div class="py-6">
        <div class="w-full bg-white shadow-sm rounded-lg p-6 mx-auto">

            {{-- Preview Foto dengan ukuran tetap 16×16 rem --}}
            <div id="preview-container"
                class="mb-6 w-64 h-64 rounded-lg border border-gray-300 shadow-lg overflow-hidden transition-transform duration-300 hover:scale-105"
                style="{{ $perawatan->foto ? '' : 'display: none;' }}">
                <img id="preview-image" src="{{ $perawatan->foto ? asset('storage/' . $perawatan->foto) : '' }}"
                    alt="Preview Foto" class="w-full h-full object-cover" />
            </div>

            <form method="POST" action="{{ route('perawatan.update', $perawatan->id_perawatan) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Foto --}}
                    <div>
                        <x-label for="foto" value="Ganti Foto (opsional)" />
                        <x-input id="foto" name="foto" type="file" accept="image/*"
                            class="mt-1 block w-full" />
                        <x-input-error for="foto" class="mt-2" />
                    </div>

                    {{-- Nama Perawatan --}}
                    <div>
                        <x-label for="nama_perawatan" value="Nama Perawatan" />
                        <x-input id="nama_perawatan" name="nama_perawatan" type="text" class="mt-1 block w-full"
                            value="{{ old('nama_perawatan', $perawatan->nama_perawatan) }}" required />
                        <x-input-error for="nama_perawatan" class="mt-2" />
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <x-label for="deskripsi" value="Deskripsi" />
                        <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm"
                            rows="3">{{ old('deskripsi', $perawatan->deskripsi) }}</textarea>
                        <x-input-error for="deskripsi" class="mt-2" />
                    </div>

                    {{-- Durasi --}}
                    <div>
                        <x-label for="waktu" value="Durasi" />
                        <select id="waktu" name="waktu"
                            class="mt-1 block w-full border-gray-300 p-2 rounded-md shadow-sm" required>
                            <option value="">-- Pilih Durasi --</option>
                            <option value="30" {{ old('waktu', $perawatan->waktu) == 30 ? 'selected' : '' }}>30
                                Menit</option>
                            <option value="60" {{ old('waktu', $perawatan->waktu) == 60 ? 'selected' : '' }}>1 Jam
                            </option>
                            <option value="90" {{ old('waktu', $perawatan->waktu) == 90 ? 'selected' : '' }}>1 Jam
                                30 Menit</option>
                            <option value="120" {{ old('waktu', $perawatan->waktu) == 120 ? 'selected' : '' }}>2 Jam
                            </option>
                        </select>
                        <x-input-error for="waktu" class="mt-2" />
                    </div>

                    {{-- Harga --}}
                    <div>
                        <x-label for="harga" value="Harga" />
                        <x-input id="harga" name="harga" type="number" class="mt-1 block w-full"
                            value="{{ old('harga', $perawatan->harga) }}" required />
                        <x-input-error for="harga" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fotoInput = document.getElementById('foto');
        const previewImage = document.getElementById('preview-image');
        const previewContainer = document.getElementById('preview-container');

        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                // Kalau batal ganti: kembalikan foto lama atau sembunyikan
                @if ($perawatan->foto)
                    previewImage.src = "{{ asset('storage/' . $perawatan->foto) }}";
                    previewContainer.style.display = 'block';
                @else
                    previewImage.src = '';
                    previewContainer.style.display = 'none';
                @endif
            }
        });
    </script>
</x-app-layout>
