<x-app-layout>
    <div class="px-4 py-6 sm:px-6 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Daftar Pemesanan
        </h2>
        <a href="{{ route('pemesanan.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-500 transition">
            Tambah Pemesanan
        </a>
    </div>

    <div class="py-6">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            @livewire('table.pemesanan-table')

        </div>
    </div>
</x-app-layout>
