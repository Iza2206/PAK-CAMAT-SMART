@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('sekcam.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
               📥 Verifikasi Pengajuan Dispensasi Nikah
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Tidak ada pengajuan baru.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                         <p class="mb-1 font-semibold">👤 Nama: {{ $item->nama_pemohon }}</p>
                        <p class="mb-1">🆔 NIK: {{ $item->nik_pemohon }}</p>
                        <p class="mb-1">👫 Jenis Kelamin: {{ $item->jenis_kelamin }}</p>
                        <p class="mb-2">🎓 Pendidikan: {{ $item->pendidikan }}</p>

                        <p class="font-semibold mb-2">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 dark:text-blue-300 space-y-1">
                            @if ($item->file_na_pria)
                                <li><a href="{{ asset('storage/' . $item->file_na_pria) }}" target="_blank">📄 NA Pria</a></li>
                            @endif
                            @if ($item->file_na_wanita)
                                <li><a href="{{ asset('storage/' . $item->file_na_wanita) }}" target="_blank">📄 NA Wanita</a></li>
                            @endif
                            @if ($item->file_kk_pria)
                                <li><a href="{{ asset('storage/' . $item->file_kk_pria) }}" target="_blank">📄 Kartu Keluarga Pria</a></li>
                            @endif
                            @if ($item->file_kk_wanita)
                                <li><a href="{{ asset('storage/' . $item->file_kk_wanita) }}" target="_blank">📄 Kartu Keluarga Wanita</a></li>
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
                                <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Tanda Lunas PBB</a></li>
                            @endif
                        </ul>
                        <div class="mt-4 flex gap-2">
                            <form method="POST" action="{{ route('sekcam.dispencatin.approve', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm shadow">
                                    ✅ Setujui
                                </button>
                            </form>

                            <button @click="document.getElementById('rejectModal-{{ $item->id }}').showModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm shadow">
                                ❌ Tolak
                            </button>
                            
                            @if ($item->file_surat)
                                <a href="{{ asset('storage/' . $item->file_surat) }}" target="_blank" class="block text-blue-500 hover:underline text-sm">
                                    <button class="bg-indigo-600 hover:bg-green-800 text-white px-4 py-2 rounded text-sm shadow">
                                        Lihat Surat
                                    </button>
                                </a>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm italic">Surat belum diupload.</p>
                            @endif

                        </div>

                        {{-- Modal Tolak --}}
                        <dialog id="rejectModal-{{ $item->id }}" class="rounded-xl backdrop:bg-black/30 p-6 w-full max-w-md">
                            <form method="POST" action="{{ route('sekcam.dispencatin.reject', $item->id) }}">
                                @csrf
                                <p class="font-semibold mb-2">Alasan Penolakan:</p>
                                <textarea name="reason" required class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                                <div class="flex justify-end mt-4 gap-2">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Kirim</button>
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $item->id }}').close()" class="px-4 py-2 border rounded text-sm dark:border-gray-500">Batal</button>
                                </div>
                            </form>
                        </dialog>
                    </div>
                @endforeach
            </div>
        @endif
    </main>
</div>
@endsection
