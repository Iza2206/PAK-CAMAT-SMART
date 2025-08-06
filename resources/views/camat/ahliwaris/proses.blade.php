@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
             📁 Riwayat Pengajuan Surat Pernyataan Ahli Waris
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
                                    {{ $item->rejected_camat_reason ?? '-' }}
                                </td>
                                <td class="px-4 py-3 border text-blue-600 dark:text-blue-300 text-sm space-y-1">
                                   @if ($item->file_permohonan)
                                        <li><a href="{{ asset('storage/' . $item->file_permohonan) }}" target="_blank">📄 Surat Permohonan Registrasi</a></li>
                                    @else
                                        <li class="text-red-500">❌ Surat Permohonan belum diunggah</li>
                                    @endif

                                    @if ($item->file_pernyataan_ahliwaris)
                                        <li><a href="{{ asset('storage/' . $item->file_pernyataan_ahliwaris) }}" target="_blank">📄 Surat Pernyataan Ahli Waris</a></li>
                                    @else
                                        <li class="text-red-500">❌ Surat Pernyataan belum diunggah</li>
                                    @endif

                                    @if ($item->file_akta_kematian)
                                        <li><a href="{{ asset('storage/' . $item->file_akta_kematian) }}" target="_blank">📄 Akta Kematian</a></li>
                                    @else
                                        <li class="text-red-500">❌ Akta Kematian belum diunggah</li>
                                    @endif

                                    @if ($item->file_ktp)
                                        <li><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 Fotokopi KTP</a></li>
                                    @else
                                        <li class="text-red-500">❌ KTP belum diunggah</li>
                                    @endif

                                    @if ($item->file_kk)
                                        <li><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 Fotokopi KK</a></li>
                                    @else
                                        <li class="text-red-500">❌ KK belum diunggah</li>
                                    @endif

                                    @if ($item->file_pbb)
                                        <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Tanda Lunas PBB</a></li>
                                    @else
                                        <li class="text-red-500">❌ PBB belum diunggah</li>
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
