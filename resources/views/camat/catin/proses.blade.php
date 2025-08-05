@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📁 Riwayat Pengajuan CATIN SIPIL, POLRI/TNI
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Belum ada pengajuan yang diteruskan oleh Sekcam.</p>
        @else
            {{-- Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Menunggu Persetujuan</p>
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
                                    'approved_by_camat' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                    'rejected_by_camat' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300',
                                    'approved_by_sekcam' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-300',
                                    default => 'bg-gray-200 text-gray-800 dark:bg-gray-700/30 dark:text-gray-100',
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-3 border">{{ $pengajuan->firstItem() + $index }}</td>
                                <td class="px-4 py-3 border">{{ $item->nama_pemohon }}</td>
                                <td class="px-4 py-3 border">{{ $item->nik_pemohon }}</td>
                                <td class="px-4 py-3 border">{{ $item->queue_number ?? '-' }}</td>
                                <td class="px-4 py-3 border">{{ $item->jenis_kelamin }}</td>
                                <td class="px-4 py-3 border">{{ $item->pendidikan }}</td>
                                <td class="px-4 py-3 border">
                                    <span class="px-2 py-1 text-sm font-semibold rounded-lg {{ $statusColor }}">
                                        @switch($item->status)
                                            @case('approved_by_camat')
                                                Disetujui
                                                @break
                                            @case('rejected_by_camat')
                                                Ditolak
                                                @break
                                            @case('approved_by_sekcam')
                                                Menunggu Persetujuan
                                                @break
                                            @default
                                                {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-4 py-3 border">
                                    @if ($item->approved_camat_at)
                                        {{ \Carbon\Carbon::parse($item->approved_camat_at)->format('d M Y H:i') }}<br>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            ⏱️ Proses:
                                            {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans($item->approved_camat_at, true) }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 border text-red-500 dark:text-red-300">
                                    {{ $item->rejected_camat_reason ?? '-' }}
                                </td>
                                <td class="px-4 py-3 border text-blue-600 dark:text-blue-300 text-sm space-y-1">
                                    <div class="text-sm space-y-1 text-blue-600 dark:text-blue-300 mt-3">
                                        @php
                                            $isSipilLengkap = $item->file_ktp && $item->file_kk && $item->file_surat_n1;
                                            $isTniLengkap = $item->file_kta && $item->file_surat_izin_kawin && $item->file_pernyataan_kesediaan;
                                        @endphp

                                        {{-- 👤 Pihak SIPIL --}}
                                        @if ($isSipilLengkap && !$isTniLengkap)
                                            <div class="font-semibold text-gray-800 dark:text-gray-200">👤 Dokumen Sipil</div>
                                            @if ($item->file_ktp) <div><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 KTP</a></div> @endif
                                            @if ($item->file_kk) <div><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 KK</a></div> @endif
                                            @if ($item->file_akta_kelahiran) <div><a href="{{ asset('storage/' . $item->file_akta_kelahiran) }}" target="_blank">📄 Akta Kelahiran</a></div> @endif
                                            @if ($item->file_pas_foto_3x4) <div><a href="{{ asset('storage/' . $item->file_pas_foto_3x4) }}" target="_blank">🖼️ Pas Foto 3x4</a></div> @endif
                                            @if ($item->file_pas_foto_4x6) <div><a href="{{ asset('storage/' . $item->file_pas_foto_4x6) }}" target="_blank">🖼️ Pas Foto 4x6</a></div> @endif
                                            @if ($item->file_pengantar_rt_rw) <div><a href="{{ asset('storage/' . $item->file_pengantar_rt_rw) }}" target="_blank">📄 Surat Pengantar RT/RW</a></div> @endif
                                            @if ($item->file_surat_n1) <div><a href="{{ asset('storage/' . $item->file_surat_n1) }}" target="_blank">📄 Surat N1</a></div> @endif
                                            @if ($item->file_surat_n2) <div><a href="{{ asset('storage/' . $item->file_surat_n2) }}" target="_blank">📄 Surat N2</a></div> @endif
                                            @if ($item->file_surat_n3) <div><a href="{{ asset('storage/' . $item->file_surat_n3) }}" target="_blank">📄 Surat N3</a></div> @endif
                                            @if ($item->file_surat_n4) <div><a href="{{ asset('storage/' . $item->file_surat_n4) }}" target="_blank">📄 Surat N4</a></div> @endif
                                            @if ($item->file_izin_orang_tua) <div><a href="{{ asset('storage/' . $item->file_izin_orang_tua) }}" target="_blank">📄 Izin Orang Tua</a></div> @endif
                                            @if ($item->file_status_pernikahan) <div><a href="{{ asset('storage/' . $item->file_status_pernikahan) }}" target="_blank">📄 Status Pernikahan / Cerai</a></div> @endif
                                            @if ($item->file_pbb) <div><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></div> @endif
                                        @endif

                                        {{-- 🪖 Pihak TNI/POLRI --}}
                                        @if ($isTniLengkap && !$isSipilLengkap)
                                            <div class="font-semibold text-gray-800 dark:text-gray-200">🪖 Dokumen TNI/POLRI</div>
                                            @if ($item->file_surat_izin_kawin) <div><a href="{{ asset('storage/' . $item->file_surat_izin_kawin) }}" target="_blank">📄 Surat Izin Kawin</a></div> @endif
                                            @if ($item->file_keterangan_belum_menikah_tni) <div><a href="{{ asset('storage/' . $item->file_keterangan_belum_menikah_tni) }}" target="_blank">📄 Keterangan Belum Menikah</a></div> @endif
                                            @if ($item->file_kta) <div><a href="{{ asset('storage/' . $item->file_kta) }}" target="_blank">📄 KTA</a></div> @endif
                                            @if ($item->file_sk_pangkat_terakhir) <div><a href="{{ asset('storage/' . $item->file_sk_pangkat_terakhir) }}" target="_blank">📄 SK Pangkat Terakhir</a></div> @endif
                                            @if ($item->file_pernyataan_kesediaan) <div><a href="{{ asset('storage/' . $item->file_pernyataan_kesediaan) }}" target="_blank">📄 Pernyataan Kesediaan</a></div> @endif
                                            @if ($item->file_pas_foto_berdampingan_dinas) <div><a href="{{ asset('storage/' . $item->file_pas_foto_berdampingan_dinas) }}" target="_blank">🖼️ Foto Dinas</a></div> @endif
                                            @if ($item->file_pas_foto_berdampingan_formal) <div><a href="{{ asset('storage/' . $item->file_pas_foto_berdampingan_formal) }}" target="_blank">🖼️ Foto Formal</a></div> @endif
                                            @if ($item->file_pbb) <div><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></div> @endif
                                        @endif

                                        @if (!$isSipilLengkap && !$isTniLengkap)
                                            <div class="text-gray-500 italic">Tidak ada dokumen lengkap</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
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
