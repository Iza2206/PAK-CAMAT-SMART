@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">  📥 Verifikasi Pengajuan Izin Usaha Mikro</h1>

        @if ($pengajuan->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400 mt-10">
                Tidak ada pengajuan baru.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <p class="mb-1"><strong>👤 Nama:</strong> {{ $item->nama_pemohon }}</p>
                        <p class="mb-1"><strong>🆔 NIK:</strong> {{ $item->nik_pemohon }}</p>
                        <p class="mb-1"><strong>👫 Jenis Kelamin:</strong> {{ $item->jenis_kelamin }}</p>
                        <p class="mb-2"><strong>🎓 Pendidikan:</strong> {{ $item->pendidikan }}</p>
                        <p class="font-semibold mb-2">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm text-blue-600 dark:text-blue-300 space-y-1">
                           @if ($item->surat_keterangan_usaha)
                                <li><a href="{{ asset('storage/' . $item->surat_keterangan_usaha) }}" target="_blank">📄 Surat Keterangan Usaha dari Desa</a></li>
                            @endif
                            @if ($item->foto_tempat_usaha)
                                <li><a href="{{ asset('storage/' . $item->foto_tempat_usaha) }}" target="_blank">📄 Foto/Gambar Tempat Usaha</a></li>
                            @endif
                            @if ($item->file_kk)
                                <li><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 Fotokopi Kartu Keluarga</a></li>
                            @endif
                            @if ($item->file_ktp)
                                <li><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 Fotokopi KTP</a></li>
                            @endif
                            @if ($item->pasphoto_3x4_1)
                                <li><a href="{{ asset('storage/' . $item->pasphoto_3x4_1) }}" target="_blank">📄 Pasphoto 3x4 Warna (1)</a></li>
                            @endif
                            @if ($item->pasphoto_3x4_2)
                                <li><a href="{{ asset('storage/' . $item->pasphoto_3x4_2) }}" target="_blank">📄 Pasphoto 3x4 Warna (2)</a></li>
                            @endif
                            @if ($item->file_pbb)
                                <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Tanda Lunas PBB</a></li>
                            @endif
                        </ul>

                        <div class="mt-4 flex gap-3">
                            <form method="POST" action="{{ route('camat.iumk.approve', $item->id) }}">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                                    ✅ Setujui
                                </button>
                            </form>

                            <button @click="document.getElementById('rejectModal-{{ $item->id }}').showModal()"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm shadow">
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
                            <form method="POST" action="{{ route('camat.iumk.reject', $item->id) }}">
                                @csrf
                                <p class="font-semibold mb-2">Alasan Penolakan:</p>
                                <textarea name="reason" required class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
                                <div class="flex justify-end mt-4 gap-2">
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                        Kirim
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('rejectModal-{{ $item->id }}').close()"
                                        class="px-4 py-2 border rounded-lg text-sm dark:border-gray-500">
                                        Batal
                                    </button>
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
