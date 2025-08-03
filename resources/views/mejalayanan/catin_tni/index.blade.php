@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">📄 Daftar Pengajuan Catin TNI/Polri</h1>

       {{-- Alert sukses dan nomor antrian --}}
        @if (session('antrian'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-800/30 text-green-900 dark:text-green-100 rounded-lg border border-green-400 dark:border-green-600 shadow">
                🎟️ Nomor Antrian Anda:
                <span class="font-bold text-2xl text-green-800 dark:text-green-300">
                    {{ session('antrian') }}
                </span>
            </div>
        @endif
        @if (session('success'))
            <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 dark:bg-green-600/20 dark:text-green-300 shadow-sm border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif


        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 overflow-auto">
            <table class="min-w-full text-base border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase text-sm">
                    <tr>
                        <th class="px-4 py-3 border">Nama</th>
                        <th class="px-4 py-3 border">NIK</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Durasi</th>
                        <th class="px-4 py-3 border">Alasan Penolakan</th>
                        <th class="px-4 py-3 border">Dokumen</th>
                        <th class="px-4 py-3 border">Jenis Kelamin</th>
                        <th class="px-4 py-3 border">Pendidikan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        @php
                            $created = \Carbon\Carbon::parse($item->created_at);
                            $verified = $item->verified_at ? \Carbon\Carbon::parse($item->verified_at) : null;
                            $sekcam = $item->approved_sekcam_at ? \Carbon\Carbon::parse($item->approved_sekcam_at) : null;
                            $camat = $item->approved_camat_at ? \Carbon\Carbon::parse($item->approved_camat_at) : null;

                            $totalAkhir = null;
                            $ditolakOleh = null;

                            if ($item->rejected_reason) {
                                $totalAkhir = $verified;
                                $ditolakOleh = 'Kasi Trantib';
                            } elseif ($item->rejected_sekcam_reason) {
                                $totalAkhir = $sekcam;
                                $ditolakOleh = 'Sekcam';
                            } elseif ($item->rejected_camat_reason) {
                                $totalAkhir = $camat;
                                $ditolakOleh = 'Camat';
                            } elseif ($camat) {
                                $totalAkhir = $camat;
                            }

                            $statusColor = match($item->status) {
                                'diajukan' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300',
                                'checked_by_kasi' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-400/20 dark:text-yellow-300',
                                'approved_by_camat' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-300',
                                'rejected_by_sekcam', 'rejected_by_camat' => 'bg-red-200 text-red-800 dark:bg-red-600/20 dark:text-red-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-300',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                            <td class="px-4 py-3 border">{{ $item->nama_pemohon }}</td>
                            <td class="px-4 py-3 border">{{ $item->nik_pemohon }}</td>
                            <td class="px-4 py-3 border">
                                <span class="px-2 py-1 text-sm font-semibold rounded-lg {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 border text-sm space-y-1">
                                <div>📥 Diajukan: {{ $created->diffForHumans() }}</div>
                                <div>
                                    ✅ Kasi Trantib:
                                    {{ $verified ? $verified->diffForHumans($created, true) : 'menunggu' }}
                                </div>
                                <div>
                                    📝 Sekcam:
                                    {{ $sekcam ? $sekcam->diffForHumans($verified ?? $created, true) : 'menunggu' }}
                                </div>
                                <div>
                                    🖋️ Camat:
                                    {{ $camat ? $camat->diffForHumans($sekcam ?? $verified ?? $created, true) : 'menunggu' }}
                                </div>

                                @if ($totalAkhir)
                                    <div class="mt-2 font-semibold text-sm">
                                        ⏱️ Total:
                                        {{ $totalAkhir->diffForHumans($created, true) }}
                                        @if($ditolakOleh)
                                            <span class="text-red-500">(ditolak oleh {{ $ditolakOleh }})</span>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td class="px-4 py-3 border text-sm text-red-500 dark:text-red-300">
                                {{ $item->rejected_reason ?? $item->rejected_sekcam_reason ?? $item->rejected_camat_reason ?? '-' }}
                            </td>
                            <td class="px-4 py-3 border text-sm space-y-1 text-blue-600 dark:text-blue-300">
                                @foreach ([
                                    'file_ktp_kk',
                                    'file_akta_kelahiran',
                                    'file_pas_foto_3x4',
                                    'file_pas_foto_4x6',
                                    'file_pengantar_rt_rw',
                                    'file_surat_n1',
                                    'file_surat_n2',
                                    'file_surat_n3',
                                    'file_surat_n4',
                                    'file_izin_orang_tua',
                                    'file_status_pernikahan',
                                    'file_surat_izin_kawin',
                                    'file_keterangan_belum_menikah_tni',
                                    'file_kta',
                                    'file_sk_pangkat_terakhir',
                                    'file_pernyataan_kesediaan',
                                    'file_pas_foto_berdampingan',
                                    'file_pbb'
                                ] as $field)
                                    @if ($item->$field)
                                        <div>
                                            <a href="{{ asset('storage/' . $item->$field) }}" target="_blank">
                                                📄 {{
                                                    [
                                                        'file_ktp_kk' => 'KTP & KK',
                                                        'file_akta_kelahiran' => 'Akta Kelahiran',
                                                        'file_pas_foto_3x4' => 'Pas Foto 3x4',
                                                        'file_pas_foto_4x6' => 'Pas Foto 4x6',
                                                        'file_pengantar_rt_rw' => 'Surat Pengantar RT/RW',
                                                        'file_surat_n1' => 'Formulir N1 (Keterangan Nikah)',
                                                        'file_surat_n2' => 'Formulir N2 (Asal Usul)',
                                                        'file_surat_n3' => 'Formulir N3 (Persetujuan Mempelai)',
                                                        'file_surat_n4' => 'Formulir N4 (Tentang Orang Tua)',
                                                        'file_izin_orang_tua' => 'Izin Orang Tua (<21th)',
                                                        'file_status_pernikahan' => 'Surat Belum Menikah / Akta Cerai',
                                                        'file_surat_izin_kawin' => 'Surat Izin Kawin dari Komandan',
                                                        'file_keterangan_belum_menikah_tni' => 'Surat Belum Menikah dari Kesatuan',
                                                        'file_kta' => 'Fotokopi KTA',
                                                        'file_sk_pangkat_terakhir' => 'SK Pangkat Terakhir',
                                                        'file_pernyataan_kesediaan' => 'Pernyataan Kesediaan Mendampingi',
                                                        'file_pas_foto_berdampingan' => 'Pas Foto Berdampingan',
                                                        'file_pbb' => 'Tanda Lunas PBB',
                                                    ][$field]
                                                }}
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </td>


                            <td class="px-4 py-3 border text-sm">{{ $item->jenis_kelamin ?? '-' }}</td>
                            <td class="px-4 py-3 border text-sm">{{ $item->pendidikan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6">Belum ada data pengajuan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $data->links('vendor.pagination.tailwind') }}
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Total: {{ $data->total() }} | Halaman: {{ $data->currentPage() }} dari {{ $data->lastPage() }}
                </p>
            </div>
        </div>
    </main>
</div>
@endsection
