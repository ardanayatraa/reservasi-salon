<x-app-layout>
    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Daftar Pelanggan
        </h2>

    </div>

    <div class="bg-white p-4">
        @livewire('table.pelanggan-table')
    </div>
</x-app-layout>
