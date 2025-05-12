<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Admin</h2>
    </x-slot>

    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Username --}}
                    <div>
                        <x-label for="username" value="Username" />
                        <x-input id="username" name="username" type="text" class="mt-1 block w-full" required
                            autofocus />
                        <x-input-error for="username" class="mt-2" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-label for="email" value="Email" />
                        <x-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                        <x-input-error for="email" class="mt-2" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-label for="password" value="Password" />
                        <x-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                        <x-input-error for="password" class="mt-2" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <x-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-input id="password_confirmation" name="password_confirmation" type="password"
                            class="mt-1 block w-full" required />
                    </div>

                    {{-- No. Telepon --}}
                    <div>
                        <x-label for="no_telepon" value="No. Telepon" />
                        <x-input id="no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" />
                        <x-input-error for="no_telepon" class="mt-2" />
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <x-label for="alamat" value="Alamat" />
                        <x-input id="alamat" name="alamat" type="text" class="mt-1 block w-full" />
                        <x-input-error for="alamat" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Simpan</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
