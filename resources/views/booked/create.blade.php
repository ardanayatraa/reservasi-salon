<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Booked</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('booked.store') }}">
                @csrf
                <div class="space-y-4">
                    {{-- Pemesanan --}}
                    <div>
                        <x-label for="id_pemesanan" value="Pemesanan" />
                        <select id="id_pemesanan" name="id_pemesanan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (\App\Models\Pemesanan::all() as $ps)
                                <option value="{{ $ps->id_pemesanan }}">{{ $ps->id_pemesanan }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_pemesanan" class="mt-2" />
                    </div>
                    {{-- Perawatan --}}
                    <div>
                        <x-label for="id_perawatan" value="Perawatan" />
                        <select id="id_perawatan" name="id_perawatan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (\App\Models\Perawatan::all() as $pt)
                                <option value="{{ $pt->id_perawatan }}">{{ $pt->nama_perawatan }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_perawatan" class="mt-2" />
                    </div>
                    {{-- Tanggal Booked --}}
                    <div>
                        <x-label for="tanggal_booked" value="Tanggal Booked" />
                        <x-input id="tanggal_booked" name="tanggal_booked" type="date" class="mt-1 block w-full"
                            required />
                        <x-input-error for="tanggal_booked" class="mt-2" />
                    </div>
                    {{-- Waktu --}}
                    <div>
                        <x-label for="waktu" value="Waktu" />
                        <x-input id="waktu" name="waktu" type="text" class="mt-1 block w-full" required />
                        <x-input-error for="waktu" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Simpan</x-button>
                    <a href="{{ route('booked.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
