@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('sekcam.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-blue-700 dark:text-blue-300 mb-6">
            📥 Verifikasi Pengajuan Surat Keterangan Tanah (SKT)
        </h1>

        @if ($pengajuan->isEmpty())
            <p class="text-gray-500 dark:text-gray-300">Tidak ada pengajuan baru.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <p class="mb-1"><strong>👤 Nama:</strong> {{ $item->nama_pemohon }}</p>
                        <p class="mb-1"><strong>🆔 NIK:</strong> {{ $item->nik_pemohon }}</p>
                        <p class="mb-1"><strong>👫 Jenis Kelamin:</strong> {{ $item->jenis_kelamin }}</p>
                        <p class="mb-2"><strong>🎓 Pendidikan:</strong> {{ $item->pendidikan }}</p>

                        <p class="font-semibold mt-2 mb-1">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 dark:text-blue-300 space-y-1">
                            @if ($item->file_permohonan)
                                <li><a href="{{ asset('storage/' . $item->file_permohonan) }}" target="_blank">📄 Surat Permohonan SKT</a></li>
                            @endif
                            @if ($item->file_alas_hak)
                                <li><a href="{{ asset('storage/' . $item->file_alas_hak) }}" target="_blank">📄 Dokumen Alas Hak</a></li>
                            @endif
                            @if ($item->file_kk)
                                <li><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 Fotokopi KK</a></li>
                            @endif
                            @if ($item->file_ktp)
                                <li><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 Fotokopi KTP</a></li>
                            @endif
                            @if ($item->file_pbb)
                                <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></li>
                            @endif
                        </ul>

                        <div class="mt-4 flex gap-2">
                            <form method="POST" action="{{ route('sekcam.skt.approve', $item->id) }}">
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
                        </div>

                        {{-- Modal Tolak --}}
                        <dialog id="rejectModal-{{ $item->id }}" class="rounded-xl backdrop:bg-black/30 p-6 w-full max-w-md">
                            <form method="POST" action="{{ route('sekcam.skt.reject', $item->id) }}">
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
