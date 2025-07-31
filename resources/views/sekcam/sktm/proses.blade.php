@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    @include('sekcam.layouts.sidebar')

    <main class="flex-1 p-6 bg-gray-50 dark:bg-gray-800">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">
            📁 Riwayat Pengajuan SKTM Dispensasi Cerai (Sekcam)
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Belum ada pengajuan yang diproses oleh Sekcam.</p>
        @else
            {{-- Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pengajuan</p>
                    <p class="text-2xl font-bold">{{ $jumlahPengajuan }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Diteruskan Kasi</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pengajuanDiterima }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Disetujui Sekcam</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pengajuanDisetujui }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ditolak Sekcam</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pengajuanDitolak }}</p>
                </div>
            </div>

            {{-- Tabel Riwayat --}}
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded shadow">
                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-200">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-xs uppercase text-gray-600 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Nama Pemohon</th>
                            <th class="px-4 py-3">NIK</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Alasan Penolakan</th>
                            <th class="px-4 py-3">Tanggal Proses Sekcam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $index => $item)
                            <tr class="border-t dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2">{{ $pengajuan->firstItem() + $index }}</td>
                                <td class="px-4 py-2">{{ $item->nama_pemohon }}</td>
                                <td class="px-4 py-2">{{ $item->nik_pemohon }}</td>
                                <td class="px-4 py-2">
                                    @if ($item->status === 'approved_by_sekcam')
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                    @elseif ($item->status === 'rejected_by_sekcam')
                                        <span class="text-red-600 font-semibold">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->status === 'rejected_by_sekcam' ? $item->rejected_sekcam_reason : '-' }}
                                </td>
                                <td class="px-4 py-2">
                                    {{ $item->approved_sekcam_at ? \Carbon\Carbon::parse($item->approved_sekcam_at)->format('d M Y H:i') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $pengajuan->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </main>
</div>
@endsection
