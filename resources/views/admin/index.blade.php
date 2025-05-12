<x-app-layout>
    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Daftar Admin
        </h2>
        <a href="{{ route('admin.create') }}"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-indigo-500 transition">
            Tambah Admin
        </a>
    </div>


    <div class="bg-white p-4">
        @livewire('table.admin-table')
    </div>
</x-app-layout>
