@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasubbag_umpeg.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📥 Verifikasi Pengajuan SK Riset KKN
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

                        <p class="mb-2 font-semibold">📄 Status: 
                            <span class="capitalize
                                @if($item->status == 'diajukan') text-red-600
                                @elseif($item->status == 'checked_by_kasi') text-yellow-600
                                @elseif($item->status == 'approved_by_sekcam') text-green-600
                                @elseif($item->status == 'approved_by_camat') text-green-800
                                @elseif($item->status == 'rejected') text-red-800
                                @else text-gray-600
                                @endif
                            ">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </p>

                        {{-- Tampilkan alasan penolakan jika ada --}}
                        @if($item->rejected_reason || $item->rejected_sekcam_reason || $item->rejected_camat_reason)
                            <p class="mb-2 text-red-600 font-semibold">Alasan Penolakan:</p>
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @if($item->rejected_reason)
                                    <li>Kasubbag Umpeg: {{ $item->rejected_reason }}</li>
                                @endif
                                @if($item->rejected_sekcam_reason)
                                    <li>Sekcam: {{ $item->rejected_sekcam_reason }}</li>
                                @endif
                                @if($item->rejected_camat_reason)
                                    <li>Camat: {{ $item->rejected_camat_reason }}</li>
                                @endif
                            </ul>
                        @endif

                        <p class="font-semibold mb-2 mt-4">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 dark:text-blue-300 space-y-1">
                            @if ($item->file_surat_sekolah)
                                <li><a href="{{ asset('storage/' . $item->file_surat_sekolah) }}" target="_blank">📄 Surat Keterangan Sekolah / Universitas</a></li>
                            @endif
                            @if ($item->file_izin_pengambilan)
                                <li><a href="{{ asset('storage/' . $item->file_izin_pengambilan) }}" target="_blank">📄 Surat Izin Pengambilan Data dari Dinas</a></li>
                            @endif
                        </ul>

                        <div class="mt-4 flex gap-2">
                            {{-- Form Approve --}}
                            <form method="POST" action="{{ route('kasubbag_umpeg.skrisetKKN.approve', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm shadow">
                                    ✅ Setujui
                                </button>
                            </form>

                            {{-- Button to open modal --}}
                            <button onclick="document.getElementById('rejectModal-{{ $item->id }}').showModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm shadow">
                                ❌ Tolak
                            </button>
                        </div>

                        {{-- Modal Penolakan --}}
                        <dialog id="rejectModal-{{ $item->id }}" class="rounded-xl backdrop:bg-black/30 p-6 w-full max-w-md">
                            <form method="POST" action="{{ route('kasubbag_umpeg.skrisetKKN.reject', $item->id) }}">
                                @csrf
                                <p class="font-semibold mb-2">Alasan Penolakan:</p>
                                <textarea name="reason" required
                                    class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                                <div class="flex justify-end mt-4 gap-2">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">Kirim</button>
                                    <button type="button" onclick="document.getElementById('rejectModal-{{ $item->id }}').close()"
                                        class="px-4 py-2 border rounded text-sm dark:border-gray-500">Batal</button>
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
