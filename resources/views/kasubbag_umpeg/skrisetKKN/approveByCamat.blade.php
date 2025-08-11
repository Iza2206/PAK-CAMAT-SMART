@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasubbag_umpeg.layouts.sidebar')

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
            📥 Verifikasi Pengajuan SK Riset KKN
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Tidak ada pengajuan baru.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 relative">
                            {{-- Tanda Approved --}}
                            <span class="absolute top-4 right-4 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">
                                ✔️ Disetujui Camat
                            </span>
                 
                        <p class="mb-1 mt-5 font-semibold">👤 Nama: {{ $item->nama_pemohon }}</p>
                        <p class="mb-1">🆔 NIK: {{ $item->nik_pemohon }}</p>
                        <p class="mb-1">👫 Jenis Kelamin: {{ $item->jenis_kelamin }}</p>
                        <p class="mb-2">🎓 Pendidikan: {{ $item->pendidikan }}</p>

                        <p class="font-semibold mb-2">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 dark:text-blue-300 space-y-1">
                              @if ($item->file_surat_sekolah)
                                        <div>📄 <a href="{{ asset('storage/' . $item->file_surat_sekolah) }}" target="_blank">Surat Keterangan Sekolah / Universitas</a></div>
                                    @endif
                                    @if ($item->file_izin_pengambilan)
                                        <div>📄 <a href="{{ asset('storage/' . $item->file_izin_pengambilan) }}" target="_blank">Surat Izin Pengambilan Data dari Dinas</a></div>
                                    @endif
                        </ul>

                        <div class="mt-4 flex gap-2">
                            <form method="GET" action="{{ route('kasubbag_umpeg.skrisetKKN.prosesTTD', $item->id) }}">
                                <button 
                                    type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm shadow disabled:opacity-50 disabled:cursor-not-allowed"
                                    @if($item->surat_final) disabled @endif
                                >
                                    📄 Proses
                                </button>
                            </form>

                            @if ($item->surat_final)
                                <a href="{{ asset('storage/' . $item->surat_final) }}" target="_blank" 
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm shadow flex items-center">
                                    📥 Surat Final
                                </a>
                            @endif
                        </div>


                        {{-- Modal Penolakan --}}
                        <dialog id="rejectModal-{{ $item->id }}" class="rounded-xl backdrop:bg-black/30 p-6 w-full max-w-md">
                            <form method="POST" action="{{ route('kasubbag_umpeg.skrisetKKN.reject', $item->id) }}">
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
