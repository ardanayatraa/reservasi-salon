<x-app-layout>
    <div class="px-4 py-6 bg-white shadow sm:rounded-lg">
        <h2 class="text-2xl font-semibold text-gray-800">Dashboard Ringkasan</h2>
    </div>

    <div class="p-6 space-y-6">
        <div class="flex flex-wrap gap-6">
            {{-- Total Pemesanan --}}
            <div class="flex-1 min-w-[200px] bg-white p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Total Pemesanan</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800">{{ number_format($totalPemesanan) }}</p>
            </div>

            {{-- Total Pelanggan --}}
            <div class="flex-1 min-w-[200px] bg-white p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Total Pelanggan</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800">{{ number_format($totalPelanggan) }}</p>
            </div>

            {{-- Total Karyawan --}}
            <div class="flex-1 min-w-[200px] bg-white p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Total Karyawan</h3>
                <p class="mt-2 text-3xl font-bold text-gray-800">{{ number_format($totalKaryawan) }}</p>
            </div>

            {{-- Pembayaran Paid --}}
            <div class="flex-1 min-w-[200px] bg-white p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Pembayaran “Paid”</h3>
                <p class="mt-2 text-3xl font-bold text-green-600">{{ number_format($totalPaid) }}</p>
            </div>

            {{-- Pembayaran Unpaid --}}
            <div class="flex-1 min-w-[200px] bg-white p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Pembayaran “Unpaid”</h3>
                <p class="mt-2 text-3xl font-bold text-red-600">{{ number_format($totalUnpaid) }}</p>
            </div>
        </div>
    </div>

    {{-- @include('admin.bookings._table') --}}
    @livewire('table.pemesanan-table')
</x-app-layout>
