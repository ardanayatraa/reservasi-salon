<x-app-layout>
    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Booked
        </h2>

    </div>

    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('booked.update', $booked->id_booked) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Pemesanan --}}
                    <div>
                        <x-label for="id_pemesanan" value="Pemesanan" />
                        <select id="id_pemesanan" name="id_pemesanan"
                            class="mt-1 block w-full border-gray-300 disable rounded-md  p-2 shadow-sm" disabled
                            required>
                            @foreach (\App\Models\Pemesanan::all() as $ps)
                                <option value="{{ $ps->id_pemesanan }}" @selected($ps->id_pemesanan == $booked->id_pemesanan)>
                                    {{ $ps->id_pemesanan }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="id_pemesanan" class="mt-2" />
                    </div>

                    {{-- Perawatan --}}
                    <div>
                        <x-label for="id_perawatan" value="Perawatan" />
                        <select id="id_perawatan" name="id_perawatan"
                            class="mt-1 block w-full border-gray-300 rounded-md p-2 shadow-sm" required>
                            @foreach (\App\Models\Perawatan::all() as $pt)
                                <option value="{{ $pt->id_perawatan }}" @selected($pt->id_perawatan == $booked->id_perawatan)>
                                    {{ $pt->nama_perawatan }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="id_perawatan" class="mt-2" />
                    </div>

                    {{-- Tanggal Booked --}}
                    <div>
                        <x-label for="tanggal_booked" value="Tanggal Booked" />
                        <x-input id="tanggal_booked" name="tanggal_booked" type="date" class="mt-1 block w-full"
                            value="{{ old('tanggal_booked', $booked->tanggal_booked->format('Y-m-d')) }}" required />
                        <x-input-error for="tanggal_booked" class="mt-2" />
                    </div>

                    {{-- Waktu --}}
                    <div>
                        <x-label for="waktu" value="Waktu" />
                        <x-input id="waktu" name="waktu" type="text" class="mt-1 block w-full"
                            value="{{ old('waktu', $booked->waktu) }}" required />
                        <x-input-error for="waktu" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                    <a href="{{ route('booked.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
