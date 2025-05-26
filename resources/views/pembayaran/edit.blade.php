<x-app-layout>

    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Pembayaran
        </h2>

    </div>

    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('pembayaran.update', $pembayaran->id_pembayaran) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Perawatan --}}
                    <div>
                        <x-label for="id_layanan" value="Perawatan" />
                        <select id="id_layanan" name="id_layanan"
                            class="mt-1 block w-full border-gray-300 rounded-md p-2 shadow-sm" required>
                            @foreach (\App\Models\Perawatan::all() as $pt)
                                <option value="{{ $pt->id_perawatan }}" @selected($pt->id_perawatan == $pembayaran->id_layanan)>
                                    {{ $pt->nama_perawatan }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_layanan" class="mt-2" />
                    </div>

                    {{-- Tanggal Pembayaran --}}
                    <div>
                        <x-label for="tanggal_pembayaran" value="Tanggal Pembayaran" />
                        <x-input id="tanggal_pembayaran" name="tanggal_pembayaran" type="date"
                            class="mt-1 block w-full"
                            value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran->format('Y-m-d')) }}"
                            required />
                        <x-input-error for="tanggal_pembayaran" class="mt-2" />
                    </div>

                    {{-- Total Harga --}}
                    <div>
                        <x-label for="total_harga" value="Total Harga" />
                        <x-input id="total_harga" name="total_harga" type="number" class="mt-1 block w-full"
                            value="{{ old('total_harga', $pembayaran->total_harga) }}" required />
                        <x-input-error for="total_harga" class="mt-2" />
                    </div>

                    {{-- Status Pembayaran --}}
                    <div>
                        <x-label for="status_pembayaran" value="Status Pembayaran" />
                        <x-input id="status_pembayaran" name="status_pembayaran" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('status_pembayaran', $pembayaran->status_pembayaran) }}" required />
                        <x-input-error for="status_pembayaran" class="mt-2" />
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <x-label for="metode_pembayaran" value="Metode Pembayaran" />
                        <x-input id="metode_pembayaran" name="metode_pembayaran" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('metode_pembayaran', $pembayaran->metode_pembayaran) }}" />
                        <x-input-error for="metode_pembayaran" class="mt-2" />
                    </div>

                    {{-- Snap Token --}}
                    <div>
                        <x-label for="snap_token" value="Snap Token" />
                        <x-input id="snap_token" name="snap_token" type="text" class="mt-1 block w-full"
                            value="{{ old('snap_token', $pembayaran->snap_token) }}" />
                        <x-input-error for="snap_token" class="mt-2" />
                    </div>

                    {{-- Notifikasi --}}
                    <div>
                        <x-label for="notifikasi" value="Notifikasi" />
                        <x-input id="notifikasi" name="notifikasi" type="text" class="mt-1 block w-full"
                            value="{{ old('notifikasi', $pembayaran->notifikasi) }}" />
                        <x-input-error for="notifikasi" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                    <a href="{{ route('pembayaran.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
