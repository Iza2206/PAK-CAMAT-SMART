@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📁 Riwayat Pengajuan SPPAT-GR
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Belum ada pengajuan yang diproses.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Diajukan</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pengajuanDiterima }}</p>
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
                                $statusLabel = match($item->status) {
                                    'diajukan' => 'Diajukan',
                                    'checked_by_kasi' => 'Disetujui Kasi',
                                    'approved_sekcam' => 'Disetujui Sekcam',
                                    'approved_camat' => 'Disetujui Camat',
                                    'rejected_by_kasi' => 'Ditolak Kasi',
                                    'rejected_by_sekcam' => 'Ditolak Sekcam',
                                    'rejected' => 'Ditolak Camat',
                                    default => ucfirst($item->status),
                                };

                                $statusColor = match(true) {
                                    str_starts_with($item->status, 'approved') || $item->status === 'checked_by_kasi' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                    str_starts_with($item->status, 'rejected') || $item->status === 'rejected' => 'bg-red-200 text-red-800 dark:bg-red-600/20 dark:text-red-400',
                                    $item->status === 'diajukan' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300',
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
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border">
                                    @if ($item->verified_at)
                                        {{ optional($item->verified_at)->format('d M Y H:i') }}<br>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ⏱️ Proses: {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans($item->verified_at, true) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 border text-red-500 dark:text-red-300">
                                    @php
                                        $alasan = $item->rejected_reason ?? $item->rejected_camat_reason ?? '-';
                                    @endphp
                                    {{ $alasan }}
                                </td>
                                <td class="px-4 py-3 border text-blue-600 dark:text-blue-300 text-sm space-y-1">
                                    @foreach ([
                                        'file_permohonan' => '📄 Permohonan',
                                        'file_formulir' => '📄 Formulir',
                                        'file_alas_hak_tanah' => '📄 Alas Hak Tanah',
                                        'file_ktp' => '📄 KTP',
                                        'file_kk' => '📄 KK',
                                        'file_pbb' => '📄 PBB'
                                    ] as $field => $label)
                                        @if ($item->$field)
                                            <div><a href="{{ asset('storage/' . $item->$field) }}" target="_blank">{{ $label }}</a></div>
                                        @endif
                                    @endforeach
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
