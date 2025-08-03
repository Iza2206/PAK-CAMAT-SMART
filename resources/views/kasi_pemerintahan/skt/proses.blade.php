@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasi_pemerintahan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📁 Riwayat Pengajuan SKT
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Belum ada pengajuan yang diproses.</p>
        @else
            {{-- Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Pengajuan</p>
                    <p class="text-2xl font-bold">{{ $jumlahPengajuan }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Diajukan</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pengajuanDiajukan }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Disetujui</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pengajuanDisetujui }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Ditolak</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pengajuanDitolak }}</p>
                </div>
            </div>

            {{-- Tabel --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 overflow-auto">
                <table class="min-w-full text-base border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm">
                        <tr>
                            <th class="px-4 py-3 border">#</th>
                            <th class="px-4 py-3 border">Nama</th>
                            <th class="px-4 py-3 border">NIK</th>
                            <th class="px-4 py-3 border">Nomor Antrian</th>
                            <th class="px-4 py-3 border">Jenis Kelamin</th>
                            <th class="px-4 py-3 border">Pendidikan</th>
                            <th class="px-4 py-3 border">Status</th>
                            <th class="px-4 py-3 border">Tanggal Proses</th>
                            <th class="px-4 py-3 border">Alasan Penolakan</th>
                            <th class="px-4 py-3 border">Dokumen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuan as $index => $item)
                            @php
                                $statusColor = match($item->status) {
                                    'diajukan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300',
                                    'checked_by_kasi' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                    'rejected' => 'bg-red-200 text-red-800 dark:bg-red-600/20 dark:text-red-400',
                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-3 border">{{ $pengajuan->firstItem() + $index }}</td>
                                <td class="px-4 py-3 border">{{ $item->nama_pemohon }}</td>
                                <td class="px-4 py-3 border">{{ $item->nik_pemohon }}</td>
                                <td class="px-4 py-3 border">{{ $item->queue_number }}</td>
                                <td class="px-4 py-3 border">{{ $item->jenis_kelamin }}</td>
                                <td class="px-4 py-3 border">{{ $item->pendidikan }}</td>
                                <td class="px-4 py-3 border">
                                    <span class="px-2 py-1 text-sm font-semibold rounded-lg {{ $statusColor }}">
                                        {{ $item->status === 'checked_by_kasi' ? 'Disetujui' : ($item->status === 'rejected' ? 'Ditolak' : ucfirst($item->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border">
                                    @if ($item->verified_at)
                                        {{ \Carbon\Carbon::parse($item->verified_at)->format('d M Y H:i') }}<br>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ⏱️ Proses: {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans($item->verified_at, true) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 border text-red-500 dark:text-red-300">
                                    {{ $item->status === 'rejected' ? $item->rejected_reason : '-' }}
                                </td>
                                <td class="px-4 py-3 border text-blue-600 dark:text-blue-300 text-sm space-y-1">
                                    @if ($item->file_permohonan)
                                        <div><a href="{{ asset('storage/' . $item->file_permohonan) }}" target="_blank">📄 Surat Permohonan SKT</a></div>
                                    @endif
                                    @if ($item->file_alas_hak)
                                        <div><a href="{{ asset('storage/' . $item->file_alas_hak) }}" target="_blank">📄 Surat Keterangan Tanah / Alas Hak</a></div>
                                    @endif
                                    @if ($item->file_ktp)
                                        <div><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 KTP</a></div>
                                    @endif
                                    @if ($item->file_kk)
                                        <div><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 Kartu Keluarga</a></div>
                                    @endif
                                    @if ($item->file_pbb)
                                        <div><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $pengajuan->links('vendor.pagination.tailwind') }}
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        Total: {{ $pengajuan->total() }} | Halaman: {{ $pengajuan->currentPage() }} dari {{ $pengajuan->lastPage() }}
                    </p>
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
