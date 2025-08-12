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
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Nilai IKM</p>
                <p class="text-2xl font-bold">{{ number_format($ikm, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Responden</p>
                <p class="text-2xl font-bold">{{ $jumlahRespondenTotal }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Lama Pengisian</p>
                <p class="text-2xl font-bold">{{ gmdate('i \m\i\n s \d\k', $avgDurasi) }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat Pengisian --}}
        <div class="overflow-x-auto rounded-lg shadow">
            <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold">
                    <tr>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600">No</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-left">Nama Pemohon</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-center">Nilai Penilaian</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-center">Nilai Angka</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-center">Status</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-center">Durasi Approve</th>
                        <th class="px-4 py-3 border border-gray-300 dark:border-gray-600 text-center">Layanan</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                    @forelse ($submissions as $index => $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 border-t border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center">
                                {{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700">
                                {{ $item->nama_pemohon }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center">
                                {{ $item->nilai }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center">
                                {{ $item->nilaiAngka }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center">
                                {{ $item->status }}
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center whitespace-nowrap">
                                @php
                                    $created = \Carbon\Carbon::parse($item->created_at);
                                    $approved = $item->approved_camat_at ?? $item->approved_sekcam_at ?? $item->verified_at;
                                @endphp

                                @if ($approved)
                                    ⏱️ {{ \Carbon\Carbon::parse($approved)->diffForHumans($created, true) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-center">
                                {{ ucfirst(str_replace('_', ' ', $item->layanan)) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data pengisian.
                            </td>
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
