<x-app-layout>

    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Karyawan
        </h2>

    </div>


    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('karyawan.update', $karyawan) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-label for="nama_lengkap" value="Nama Lengkap" />
                        <x-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full"
                            :value="old('nama_lengkap', $karyawan->nama_lengkap)" required />
                        <x-input-error for="nama_lengkap" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="email" value="Email" />
                        <x-input id="email" name="email" type="email" class="mt-1 block w-full"
                            :value="old('email', $karyawan->email)" />
                        <x-input-error for="email" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="no_telepon" value="No. Telepon" />
                        <x-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full"
                            :value="old('no_telepon', $karyawan->no_telepon)" />
                        <x-input-error for="no_telepon" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="alamat" value="Alamat" />
                        <textarea id="alamat" name="alamat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('alamat', $karyawan->alamat) }}</textarea>
                        <x-input-error for="alamat" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="id_shift" value="Shift" />
                        <select id="id_shift" name="id_shift"
                            class="mt-1 block w-full border-gray-300 p-2 rounded-md shadow-sm" required>
                            <option value="">-- pilih shift --</option>
                            @foreach ($shifts as $s)
                                <option value="{{ $s->id_shift }}" @selected(old('id_shift', $karyawan->id_shift) == $s->id_shift)>
                                    {{ $s->nama_shift }} ({{ $s->start_time }}–{{ $s->end_time }})
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="id_shift" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="availability_status" value="Status Ketersediaan" />
                        <div class="mt-2">
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="availability_status" value="available"
                                        class="form-radio"
                                        {{ old('availability_status', $karyawan->availability_status) === 'available' ? 'checked' : '' }}>
                                    <span class="ml-2">Tersedia</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="availability_status" value="unavailable"
                                        class="form-radio"
                                        {{ old('availability_status', $karyawan->availability_status) === 'unavailable' ? 'checked' : '' }}>
                                    <span class="ml-2">Tidak Tersedia</span>
                                </label>
                            </div>
                        </div>
                        <x-input-error for="availability_status" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
