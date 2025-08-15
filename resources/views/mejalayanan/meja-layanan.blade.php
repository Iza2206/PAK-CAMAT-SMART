@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    {{-- Main --}}
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            📊 Dashboard Indeks Kepuasan Masyarakat
        </h1>

        {{-- Ringkasan Data --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Responden</p>
                <p class="text-2xl font-bold">{{ $statistik['total_responden'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Durasi ke Sekcam</p>
                <p class="text-2xl font-bold">{{ $statistik['avg_durasi_sekcam'] }} menit</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Durasi ke Camat</p>
                <p class="text-2xl font-bold">{{ $statistik['avg_durasi_camat'] }} menit</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">NRR</p>
                <p class="text-2xl font-bold">{{ number_format($statistik['nrr'], 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">IKM</p>
                <p class="text-2xl font-bold">{{ number_format($statistik['ikm'], 2) }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat Pengisian --}}
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold">
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Nama Pemohon</th>
                        <th class="px-4 py-2">Layanan</th>
                        <th class="px-4 py-2">Penilaian</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Verified At</th>
                        <th class="px-4 py-2">Approved Sekcam</th>
                        <th class="px-4 py-2">Approved Camat</th>
                        <th class="px-4 py-2">Durasi ke Sekcam (menit)</th>
                        <th class="px-4 py-2">Durasi ke Camat (menit)</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    @forelse ($submissions as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $item->id }}</td>
                        <td class="px-4 py-2">{{ $item->nama_pemohon }}</td>
                        <td class="px-4 py-2">{{ ucwords(str_replace('_', ' ', $item->layanan)) }}</td>
                        <td class="px-4 py-2">{{ $item->penilaian }}</td>
                        <td class="px-4 py-2">{{ $item->status }}</td>
                        <td class="px-4 py-2">{{ $item->verified_at ? \Carbon\Carbon::parse($item->verified_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->approved_sekcam_at ? \Carbon\Carbon::parse($item->approved_sekcam_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="px-4 py-2">{{ $item->approved_camat_at ? \Carbon\Carbon::parse($item->approved_camat_at)->format('d-m-Y H:i') : '-' }}</td>
                        <td class="px-4 py-2 text-center">{{ $item->durasi_sekcam }}</td>
                        <td class="px-4 py-2 text-center">{{ $item->durasi_camat }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4 text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $submissions->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </main>
</div>
@endsection