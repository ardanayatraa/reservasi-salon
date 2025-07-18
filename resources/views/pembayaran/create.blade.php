<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Pembayaran</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('pembayaran.store') }}">
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
                    {{-- Layanan --}}
                    <div>
                        <x-label for="id_layanan" value="Perawatan" />
                        <select id="id_layanan" name="id_layanan"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @foreach (\App\Models\Perawatan::all() as $pt)
                                <option value="{{ $pt->id_perawatan }}">{{ $pt->nama_perawatan }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="id_layanan" class="mt-2" />
                    </div>
                    {{-- Tanggal Pembayaran --}}
                    <div>
                        <x-label for="tanggal_pembayaran" value="Tanggal Pembayaran" />
                        <x-input id="tanggal_pembayaran" name="tanggal_pembayaran" type="date"
                            class="mt-1 block w-full" required />
                        <x-input-error for="tanggal_pembayaran" class="mt-2" />
                    </div>
                    {{-- Total Harga --}}
                    <div>
                        <x-label for="total_harga" value="Total Harga" />
                        <x-input id="total_harga" name="total_harga" type="number" class="mt-1 block w-full"
                            required />
                        <x-input-error for="total_harga" class="mt-2" />
                    </div>
                    {{-- Status Pembayaran --}}
                    <div>
                        <x-label for="status_pembayaran" value="Status Pembayaran" />
                        <x-input id="status_pembayaran" name="status_pembayaran" type="text"
                            class="mt-1 block w-full" required />
                        <x-input-error for="status_pembayaran" class="mt-2" />
                    </div>
                    {{-- Metode Pembayaran --}}
                    <div>
                        <x-label for="metode_pembayaran" value="Metode Pembayaran" />
                        <x-input id="metode_pembayaran" name="metode_pembayaran" type="text"
                            class="mt-1 block w-full" />
                        <x-input-error for="metode_pembayaran" class="mt-2" />
                    </div>
                    {{-- Snap Token --}}
                    <div>
                        <x-label for="snap_token" value="Snap Token" />
                        <x-input id="snap_token" name="snap_token" type="text" class="mt-1 block w-full" />
                        <x-input-error for="snap_token" class="mt-2" />
                    </div>
                    {{-- Notifikasi --}}
                    <div>
                        <x-label for="notifikasi" value="Notifikasi" />
                        <x-input id="notifikasi" name="notifikasi" type="text" class="mt-1 block w-full" />
                        <x-input-error for="notifikasi" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Simpan</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
