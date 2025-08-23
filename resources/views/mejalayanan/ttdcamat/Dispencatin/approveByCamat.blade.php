@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        {{-- Tampilkan pesan success atau error --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded shadow">
                {{ session('error') }}
            </div>
        @endif

        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📥 Verifikasi Pengajuan Dispensasi Nikah
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Tidak ada pengajuan baru.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 dark:border-gray-700 rounded-lg shadow-md">
                    <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-4 py-2 border">#</th>
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">NIK</th>
                            <th class="px-4 py-2 border">Jenis Kelamin</th>
                            <th class="px-4 py-2 border">Pendidikan</th>
                            <th class="px-4 py-2 border">Dokumen</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800">
                        @foreach ($pengajuan as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-2 border text-center">
                                    {{ $pengajuan->firstItem() + $loop->index }}
                                </td>
                                <td class="px-4 py-2 border font-semibold">{{ $item->nama_pemohon }}</td>
                                <td class="px-4 py-2 border">{{ $item->nik_pemohon }}</td>
                                <td class="px-4 py-2 border">{{ $item->jenis_kelamin }}</td>
                                <td class="px-4 py-2 border">{{ $item->pendidikan }}</td>
                                <td class="px-4 py-2 border text-sm">
                                    <ul class="list-disc list-inside space-y-1">
                                        @if ($item->file_na_pria)
                                            <li><a href="{{ asset('storage/' . $item->file_na_pria) }}" target="_blank">📄 NA Pria</a></li>
                                        @endif
                                        @if ($item->file_na_wanita)
                                            <li><a href="{{ asset('storage/' . $item->file_na_wanita) }}" target="_blank">📄 NA Wanita</a></li>
                                        @endif
                                        @if ($item->file_kk_pria)
                                            <li><a href="{{ asset('storage/' . $item->file_kk_pria) }}" target="_blank">📄 KK Pria</a></li>
                                        @endif
                                        @if ($item->file_kk_wanita)
                                            <li><a href="{{ asset('storage/' . $item->file_kk_wanita) }}" target="_blank">📄 KK Wanita</a></li>
                                        @endif
                                        @if ($item->file_ktp_pria)
                                            <li><a href="{{ asset('storage/' . $item->file_ktp_pria) }}" target="_blank">📄 KTP Pria</a></li>
                                        @endif
                                        @if ($item->file_ktp_wanita)
                                            <li><a href="{{ asset('storage/' . $item->file_ktp_wanita) }}" target="_blank">📄 KTP Wanita</a></li>
                                        @endif
                                        @if ($item->file_akte_cerai_pria)
                                            <li><a href="{{ asset('storage/' . $item->file_akte_cerai_pria) }}" target="_blank">📄 Akta Cerai Pria</a></li>
                                        @endif
                                        @if ($item->file_akte_cerai_wanita)
                                            <li><a href="{{ asset('storage/' . $item->file_akte_cerai_wanita) }}" target="_blank">📄 Akta Cerai Wanita</a></li>
                                        @endif
                                        @if ($item->file_pbb)
                                            <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 PBB</a></li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <span class="bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                        ✔️ Disetujui Camat
                                    </span>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <form method="GET" action="{{ route('mejalayanan.ttdcamat.dispencatin.dispencatinProses', $item->id) }}" class="inline">
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs shadow disabled:opacity-50 disabled:cursor-not-allowed"
                                            @if($item->surat_final) disabled @endif>
                                            📄 Proses
                                        </button>
                                    </form>
                                    @if ($item->surat_final)
                                        <a href="{{ asset('storage/' . $item->surat_final) }}" target="_blank" 
                                           class="ml-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs shadow">
                                            📥 Surat Final
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $pengajuan->links() }}
                </div>
            </div>
        @endif
    </main>
</div>
@endsection
