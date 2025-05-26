<x-app-layout>
    <div class="px-4 py-6 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="text-xl font-semibold">Laporan Pemesanan</h2>
    </div>

    <div class="bg-white shadow sm:rounded-lg p-6 space-y-6">
        {{-- Filter + Search --}}
        <form method="GET" action="{{ route('laporan.index') }}">
            <div class="flex flex-wrap items-end gap-4">
                {{-- Search --}}
                <div class="flex-1 min-w-[200px]">
                    <x-label for="search" value="Cari Pelanggan / Karyawan" />
                    <x-input id="search" name="search" type="text" value="{{ $search }}"
                        placeholder="ketik nama..." oninput="this.form.submit()" />
                </div>

                {{-- Dari Tanggal --}}
                <div class="flex-1 min-w-[150px]">
                    <x-label for="start_date" value="Dari Tgl" />
                    <x-input id="start_date" name="start_date" type="date" value="{{ $start_date }}"
                        onchange="this.form.submit()" />
                </div>

                {{-- Sampai Tanggal --}}
                <div class="flex-1 min-w-[150px]">
                    <x-label for="end_date" value="Sampai Tgl" />
                    <x-input id="end_date" name="end_date" type="date" value="{{ $end_date }}"
                        onchange="this.form.submit()" />
                </div>

                {{-- Status Pembayaran --}}
                <div class="flex-1 min-w-[150px]">
                    <x-label for="status_pembayaran" value="Status Bayar" />
                    <select id="status_pembayaran" name="status_pembayaran" class="w-full mt-1 p-2 border rounded"
                        onchange="this.form.submit()">
                        <option value="">Semua</option>
                        <option value="paid" {{ $statusPembayaran == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ $statusPembayaran == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </div>

                {{-- Reset Filter --}}
                <div>
                    <a href="{{ route('laporan.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Reset
                    </a>
                </div>

                {{-- Export PDF --}}
                <div>
                    <a href="{{ route('laporan.pdf', request()->all()) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">
                        Export PDF
                    </a>
                </div>
            </div>
        </form>

        {{-- Ringkasan --}}
        <div class="text-right">
            <span class="text-lg font-semibold">Total Pendapatan:</span>
            <span class="text-green-600 text-xl font-bold">
                Rp{{ number_format($totalRevenue, 0, ',', '.') }}
            </span>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tgl Pesan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Karyawan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Perawatan & Waktu</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status Pesan</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Tgl Bayar</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporans as $row)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $row->tanggal_pemesanan->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $row->pelanggan->nama_lengkap }}</td>
                            <td class="px-4 py-2 text-sm">{{ $row->karyawan->nama_lengkap }}</td>
                            <td class="px-4 py-2 text-sm">
                                @foreach ($row->bookeds as $b)
                                    • {{ $b->perawatan->nama_perawatan }}
                                    ({{ $b->tanggal_booked->format('d-m-Y') }} {{ $b->waktu }})
                                    <br>
                                @endforeach
                            </td>
                            <td class="px-4 py-2 text-sm">{{ $row->status_pemesanan }}</td>
                            <td class="px-4 py-2 text-sm">
                                {{ optional($row->pembayaran->tanggal_pembayaran)->format('d-m-Y') ?? '–' }}
                            </td>
                            <td class="px-4 py-2 text-sm text-right">
                                {{ $row->pembayaran ? number_format($row->pembayaran->total_harga, 0, ',', '.') : '–' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500">
                                Tidak ada data.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $laporans->links() }}
        </div>
    </div>
</x-app-layout>
