<x-app-layout>
    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Pelanggan
        </h2>

    </div>


    <div class="py-6">
        <div class="w-full  bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('pelanggan.update', $pelanggan->id_pelanggan) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    {{-- Nama Lengkap --}}
                    <div>
                        <x-label for="nama_lengkap" value="Nama Lengkap" />
                        <x-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full"
                            value="{{ old('nama_lengkap', $pelanggan->nama_lengkap) }}" required />
                        <x-input-error for="nama_lengkap" class="mt-2" />
                    </div>
                    {{-- Username --}}
                    <div>
                        <x-label for="username" value="Username" />
                        <x-input id="username" name="username" type="text" class="mt-1 block w-full"
                            value="{{ old('username', $pelanggan->username) }}" required />
                        <x-input-error for="username" class="mt-2" />
                    </div>
                    {{-- Email --}}
                    <div>
                        <x-label for="email" value="Email" />
                        <x-input id="email" name="email" type="email" class="mt-1 block w-full"
                            value="{{ old('email', $pelanggan->email) }}" required />
                        <x-input-error for="email" class="mt-2" />
                    </div>
                    {{-- Password (optional) --}}
                    <div>
                        <x-label for="password" value="Password Baru (kosongkan jika tak diubah)" />
                        <x-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        <x-input-error for="password" class="mt-2" />
                    </div>
                    {{-- Confirm Password --}}
                    <div>
                        <x-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full" />
                    </div>
                    {{-- No. Telepon --}}
                    <div>
                        <x-label for="no_telepon" value="No. Telepon" />
                        <x-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full"
                            value="{{ old('no_telepon', $pelanggan->no_telepon) }}" />
                        <x-input-error for="no_telepon" class="mt-2" />
                    </div>
                    {{-- Alamat --}}
                    <div>
                        <x-label for="alamat" value="Alamat" />
                        <x-input id="alamat" name="alamat" type="text" class="mt-1 block w-full"
                            value="{{ old('alamat', $pelanggan->alamat) }}" />
                        <x-input-error for="alamat" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                    <a href="{{ route('pelanggan.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
