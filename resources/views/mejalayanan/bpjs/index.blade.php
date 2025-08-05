@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">📄 Daftar Pengajuan BPJS</h1>

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
            {{-- Form Cari berdasarkan NIK --}}
            {{-- <form method="GET" action="{{ route('bpjs.list') }}" class="mb-6 flex flex-wrap gap-3 items-center">
                <input type="text" name="nik" value="{{ request('nik') }}"
                    placeholder="🔍 Cari NIK"
                    class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white"
                    oninput="this.form.submit()">

                <select name="status" onchange="this.form.submit()"
                    class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">
                    <option value="">🧾 Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="checked_by_kasi" {{ request('status') == 'checked_by_kasi' ? 'selected' : '' }}>Diproses Kasi</option>
                    <option value="approved_by_camat" {{ request('status') == 'approved_by_camat' ? 'selected' : '' }}>Disetujui Camat</option>
                    <option value="rejected_by_sekcam" {{ request('status') == 'rejected_by_sekcam' ? 'selected' : '' }}>Ditolak Sekcam</option>
                    <option value="rejected_by_camat" {{ request('status') == 'rejected_by_camat' ? 'selected' : '' }}>Ditolak Camat</option>
                </select>
            </form> --}}



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
                        {{-- <th class="px-4 py-3 border">Penilaian</th> --}}
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
                                $totalAkhir = $verified ?? $created;
                                $ditolakOleh = 'Kasi Kesos';
                            } elseif ($item->rejected_sekcam_reason) {
                                $totalAkhir = $sekcam ?? $verified ?? $created;
                                $ditolakOleh = 'Sekcam';
                            } elseif ($item->rejected_camat_reason) {
                                $totalAkhir = $camat ?? $sekcam ?? $verified ?? $created;
                                $ditolakOleh = 'Camat';
                            } elseif ($camat) {
                                $totalAkhir = $camat;
                            }

                            $statusColor = match($item->status) {
                                'diajukan' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-300',
                                'diproses', 'checked_by_kasi' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-400/20 dark:text-yellow-300',
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

                                {{-- Kasi kesos --}}
                                <div>✅ Kasi kesos:
                                    @if ($verified)
                                        {{ $verified->diffForHumans($created, true) }}
                                    @elseif ($item->rejected_reason)
                                        <span class="text-red-500">❌ Ditolak oleh Kasi kesos</span>
                                    @else
                                        <span class="text-gray-400">menunggu</span>
                                    @endif
                                </div>

                                {{-- Sekcam hanya ditampilkan jika tidak ditolak oleh Kasi Pemerintahan --}}
                                @if (!$item->rejected_reason)
                                    <div>📝 Sekcam:
                                        @if ($sekcam)
                                            {{ $sekcam->diffForHumans($verified ?? $created, true) }}
                                        @elseif ($item->rejected_sekcam_reason)
                                            <span class="text-red-500">❌ Ditolak oleh Sekcam</span>
                                        @elseif ($verified)
                                            <span class="text-gray-400">menunggu</span>
                                        @endif
                                    </div>

                                    <div>🖋️ Camat:
                                        @if ($camat)
                                            {{ $camat->diffForHumans($sekcam ?? $verified ?? $created, true) }}
                                        @elseif ($item->rejected_camat_reason)
                                            <span class="text-red-500">❌ Ditolak oleh Camat</span>
                                        @elseif ($sekcam && !$item->rejected_sekcam_reason)
                                            <span class="text-gray-400">menunggu</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Total --}}
                                @if ($totalAkhir)
                                    <div class="mt-2 font-semibold text-sm">
                                        ⏱️ Total: {{ $totalAkhir->diffForHumans($created, true) }}
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
                                    'surat_permohonan' => 'Permohonan',
                                    'sktm' => 'SKTM',
                                    'kk' => 'KK',
                                    'ktp' => 'KTP',
                                    'tanda_lunas_pbb' => 'PBB'
                                ] as $field => $label)
                                    @if ($item->$field)
                                        <div>
                                            <a href="{{ asset('storage/' . $item->$field) }}" target="_blank">📄 {{ $label }}</a>
                                        </div>
                                    @endif
                                @endforeach
                            </td>
                            <td class="px-4 py-3 border text-sm">{{ $item->jenis_kelamin ?? '-' }}</td>
                            <td class="px-4 py-3 border text-sm">{{ $item->pendidikan ?? '-' }}</td>
                            {{-- <td class="px-4 py-3 border text-sm">
                                @if ($item->status === 'approved_by_camat')
                                    @if (!$item->penilaian)
                                        <form action="{{ route('bpjs.penilaian', $item->id) }}" method="POST" class="flex flex-col space-y-2">
                                            @csrf
                                            <select name="penilaian" required class="rounded border px-2 py-1 text-sm dark:bg-gray-800 dark:text-white">
                                                <option value="">📝 Pilih</option>
                                                <option value="tidak_puas">Tidak Puas</option>
                                                <option value="cukup">Cukup</option>
                                                <option value="puas">Puas</option>
                                                <option value="sangat_puas">Sangat Puas</option>
                                            </select>
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">Kirim</button>
                                        </form>
                                    @else
                                        <div class="text-green-700 dark:text-green-400">
                                            ✅ Penilaian: <strong>{{ ucfirst(str_replace('_', ' ', $item->penilaian)) }}</strong>
                                        </div>
                                        <div class="text-sm text-blue-600 dark:text-blue-300 font-semibold mt-1">
                                            📦 Sudah diambil masyarakat
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td> --}}

                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 dark:text-gray-400 py-6">Belum ada data pengajuan.</td>
                        </tr>
                        
                    @endforelse
                </tbody>
            </table>

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
