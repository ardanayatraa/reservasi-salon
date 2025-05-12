<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Perawatan</h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full  bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('perawatan.update', $perawatan->id_perawatan) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Nama --}}
                    <div>
                        <x-label for="nama_perawatan" value="Nama Perawatan" />
                        <x-input id="nama_perawatan" name="nama_perawatan" type="text" class="mt-1 block w-full"
                            value="{{ old('nama_perawatan', $perawatan->nama_perawatan) }}" required />
                        <x-input-error for="nama_perawatan" class="mt-2" />
                    </div>
                    {{-- Foto --}}
                    <div>
                        <x-label for="foto" value="Ganti Foto (opsional)" />
                        <x-input id="foto" name="foto" type="file" class="mt-1 block w-full" />
                        <x-input-error for="foto" class="mt-2" />
                    </div>
                    {{-- Deskripsi --}}
                    <div>
                        <x-label for="deskripsi" value="Deskripsi" />
                        <textarea id="deskripsi" name="deskripsi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ old('deskripsi', $perawatan->deskripsi) }}</textarea>
                        <x-input-error for="deskripsi" class="mt-2" />
                    </div>
                    {{-- Waktu --}}
                    <div>
                        <x-label for="waktu" value="Waktu" />
                        <x-input id="waktu" name="waktu" type="text" class="mt-1 block w-full"
                            value="{{ old('waktu', $perawatan->waktu) }}" required />
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
</x-app-layout>
