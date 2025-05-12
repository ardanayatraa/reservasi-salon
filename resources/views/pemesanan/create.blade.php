<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Pemesanan</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('pemesanan.store') }}">
                @csrf
                <div class="space-y-4">
                    {{-- User --}}
                    <div>
                        <x-label for="id_user" value="User" />
                        <select id="id_user" name="id_user"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (\App\Models\User::all() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_user" class="mt-2" />
                    </div>
                    {{-- Pelanggan --}}
                    <div>
                        <x-label for="id_pelanggan" value="Pelanggan" />
                        <select id="id_pelanggan" name="id_pelanggan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (\App\Models\Pelanggan::all() as $p)
                                <option value="{{ $p->id_pelanggan }}">{{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_pelanggan" class="mt-2" />
                    </div>
                    {{-- Layanan --}}
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
                    {{-- Tanggal --}}
                    <div>
                        <x-label for="tanggal_pemesanan" value="Tanggal Pemesanan" />
                        <x-input id="tanggal_pemesanan" name="tanggal_pemesanan" type="date"
                            class="mt-1 block w-full" required />
                        <x-input-error for="tanggal_pemesanan" class="mt-2" />
                    </div>
                    {{-- Waktu --}}
                    <div>
                        <x-label for="waktu" value="Waktu" />
                        <x-input id="waktu" name="waktu" type="text" class="mt-1 block w-full" required />
                        <x-input-error for="waktu" class="mt-2" />
                    </div>
                    {{-- Jumlah --}}
                    <div>
                        <x-label for="jumlah_perawatan" value="Jumlah Perawatan" />
                        <x-input id="jumlah_perawatan" name="jumlah_perawatan" type="number" class="mt-1 block w-full"
                            min="1" required />
                        <x-input-error for="jumlah_perawatan" class="mt-2" />
                    </div>
                    {{-- Status Pemesanan --}}
                    <div>
                        <x-label for="status_pemesanan" value="Status Pemesanan" />
                        <x-input id="status_pemesanan" name="status_pemesanan" type="text" class="mt-1 block w-full"
                            required />
                        <x-input-error for="status_pemesanan" class="mt-2" />
                    </div>
                    {{-- Metode Pembayaran --}}
                    <div>
                        <x-label for="metode_pembayaran" value="Metode Pembayaran" />
                        <x-input id="metode_pembayaran" name="metode_pembayaran" type="text"
                            class="mt-1 block w-full" />
                        <x-input-error for="metode_pembayaran" class="mt-2" />
                    </div>
                    {{-- Status Pembayaran --}}
                    <div>
                        <x-label for="status_pembayaran" value="Status Pembayaran" />
                        <x-input id="status_pembayaran" name="status_pembayaran" type="text"
                            class="mt-1 block w-full" />
                        <x-input-error for="status_pembayaran" class="mt-2" />
                    </div>
                    {{-- Token --}}
                    <div>
                        <x-label for="token" value="Token" />
                        <x-input id="token" name="token" type="text" class="mt-1 block w-full" />
                        <x-input-error for="token" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Simpan</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
