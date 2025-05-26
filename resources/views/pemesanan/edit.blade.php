<x-app-layout>

    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Pemesanan
        </h2>

    </div>

    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('pemesanan.update', $pemesanan->id_pemesanan) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">


                    {{-- Pelanggan --}}
                    <div>
                        <x-label for="id_pelanggan" value="Pelanggan" />
                        <select id="id_pelanggan" name="id_pelanggan"
                            class="mt-1 block w-full border-gray-300 rounded-md p-2 shadow-sm" required>
                            @foreach (\App\Models\Pelanggan::all() as $p)
                                <option value="{{ $p->id_pelanggan }}" @selected($p->id_pelanggan == $pemesanan->id_pelanggan)>
                                    {{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_pelanggan" class="mt-2" />
                    </div>

                    {{-- Perawatan --}}
                    <div>
                        <x-label for="id_perawatan" value="Perawatan" />
                        <select id="id_perawatan" name="id_perawatan"
                            class="mt-1 block w-full border-gray-300 p-2 rounded-md shadow-sm" required>
                            @foreach (\App\Models\Perawatan::all() as $pt)
                                <option value="{{ $pt->id_perawatan }}" @selected($pt->id_perawatan == $pemesanan->id_perawatan)>
                                    {{ $pt->nama_perawatan }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_perawatan" class="mt-2" />
                    </div>

                    {{-- Tanggal Pemesanan --}}
                    <div>
                        <x-label for="tanggal_pemesanan" value="Tanggal Pemesanan" />
                        <x-input id="tanggal_pemesanan" name="tanggal_pemesanan" type="date"
                            class="mt-1 block w-full"
                            value="{{ old('tanggal_pemesanan', $pemesanan->tanggal_pemesanan->format('Y-m-d')) }}"
                            required />
                        <x-input-error for="tanggal_pemesanan" class="mt-2" />
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <x-label for="waktu" value="Waktu" />
                        <x-input id="waktu" name="waktu" type="text" class="mt-1 block w-full"
                            value="{{ old('waktu', $pemesanan->waktu) }}" required />
                        <x-input-error for="waktu" class="mt-2" />
                    </div>

                    {{-- Jumlah Perawatan --}}
                    <div>
                        <x-label for="jumlah_perawatan" value="Jumlah Perawatan" />
                        <x-input id="jumlah_perawatan" name="jumlah_perawatan" type="number" min="1"
                            class="mt-1 block w-full"
                            value="{{ old('jumlah_perawatan', $pemesanan->jumlah_perawatan) }}" required />
                        <x-input-error for="jumlah_perawatan" class="mt-2" />
                    </div>

                    {{-- Status Pemesanan --}}
                    <div>
                        <x-label for="status_pemesanan" value="Status Pemesanan" />
                        <x-input id="status_pemesanan" name="status_pemesanan" type="text" class="mt-1 block w-full"
                            value="{{ old('status_pemesanan', $pemesanan->status_pemesanan) }}" required />
                        <x-input-error for="status_pemesanan" class="mt-2" />
                    </div>

                    {{-- Metode Pembayaran --}}
                    <div>
                        <x-label for="metode_pembayaran" value="Metode Pembayaran" />
                        <x-input id="metode_pembayaran" name="metode_pembayaran" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('metode_pembayaran', $pemesanan->metode_pembayaran) }}" />
                        <x-input-error for="metode_pembayaran" class="mt-2" />
                    </div>

                    {{-- Status Pembayaran --}}
                    <div>
                        <x-label for="status_pembayaran" value="Status Pembayaran" />
                        <x-input id="status_pembayaran" name="status_pembayaran" type="text"
                            class="mt-1 block w-full"
                            value="{{ old('status_pembayaran', $pemesanan->status_pembayaran) }}" />
                        <x-input-error for="status_pembayaran" class="mt-2" />
                    </div>

                    {{-- Token --}}
                    <div>
                        <x-label for="token" value="Token" />
                        <x-input id="token" name="token" type="text" class="mt-1 block w-full"
                            value="{{ old('token', $pemesanan->token) }}" />
                        <x-input-error for="token" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                    <a href="{{ route('pemesanan.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
