@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            📊 Dashboard Indeks Kepuasan Masyarakat
        </h1>

        {{-- Ringkasan Total --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Responden</p>
                <p class="text-2xl font-bold">{{ $grand_total_rows }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Grand Total Penilaian</p>
                <p class="text-2xl font-bold">{{ $grand_total_penilaian }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">NRR Unsur</p>
                <p class="text-2xl font-bold">{{ $nrrUnsurBulat }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">NRR Tertimbang</p>
                <p class="text-2xl font-bold">{{ $NRRtertimbangbulat }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total NRR Tertimbang</p>
                <p class="text-2xl font-bold">{{ $totalnrrtertimbang }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total IKM</p>
                <p class="text-2xl font-bold">{{ $totalIKMbulat }}</p>
            </div>
        </div>
         <h2 class="text-xl font-semibold mb-2 text-gray-700 dark:text-gray-300">RATA - RATA WAKTU PENILAIAN DARI MEJA LAYANAN - CAMAT</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @foreach($avg_durasi_camat_per_layanan as $layanan => $avg)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $layanan)) }}</p>
                    <p class="text-2xl font-bold">{{ number_format($avg, 2) }} menit</p>
                </div>
                @endforeach
            </div>

        {{-- Ringkasan Responden per Layanan --}}
        {{-- <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2 text-gray-700 dark:text-gray-300">Responden per Layanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($data as $layanan => $values)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucwords(str_replace('_', ' ', $layanan)) }}</p>
                        <p class="text-2xl font-bold">{{ $values['rows'] }}</p>
                        <p class="text-sm text-gray-400 mt-1">Total Nilai: <span class="font-medium">{{ $values['penilaian'] }}</span></p>
                    </div>
                @endforeach
            </div>
        </div> --}}

        {{-- Tabel Responden --}}
        {{-- <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold">
                    <tr>
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Nama Pemohon</th>
                        <th class="px-4 py-2">Layanan</th>
                        <th class="px-4 py-2">Penilaian</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Verified At</th>
                        <th class="px-4 py-2">Approved Sekcam</th>
                        <th class="px-4 py-2">Approved Camat</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    @forelse($submissions as $item)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-2">
                            {{ $loop->index + 1 + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                        </td>
                        <td class="px-4 py-2">{{ $item->nama_pemohon ?? '-' }}</td>
                        <td class="px-4 py-2">{{ ucwords(str_replace('_', ' ', $item->layanan ?? '')) }}</td>
                        <td class="px-4 py-2">{{ $item->penilaian ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->status ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->verified_at ? \Carbon\Carbon::parse($item->verified_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->approved_sekcam_at ? \Carbon\Carbon::parse($item->approved_sekcam_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->approved_camat_at ? \Carbon\Carbon::parse($item->approved_camat_at)->format('d-m-Y H:i') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table> --}}

            {{-- Pagination --}}
            {{-- <div class="mt-6">
                {{ $submissions->links('vendor.pagination.tailwind') }}
            </div> --}}
        </div>
    </main>
</div>
@endsection
