@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasi_kesos.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">📄 Pengajuan SKTM Dispensasi Cerai (Menunggu Verifikasi)</h1>

        @if ($pengajuan->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400 mt-10">
                Tidak ada pengajuan baru.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <p class="mb-1"><strong>👤 Nama:</strong> {{ $item->nama_pemohon }}</p>
                        <p class="mb-2"><strong>🆔 NIK:</strong> {{ $item->nik_pemohon }}</p>

                        <p class="font-semibold mb-2">📎 Dokumen:</p>
                        <ul class="list-disc list-inside text-sm space-y-1 text-blue-600 dark:text-blue-300">
                            <li><a href="{{ asset('storage/' . $item->surat_permohonan) }}" target="_blank">📄 Surat Permohonan</a></li>
                            <li><a href="{{ asset('storage/' . $item->sktm_desa) }}" target="_blank">📄 SKTM dari Desa</a></li>
                            <li><a href="{{ asset('storage/' . $item->kk) }}" target="_blank">📄 Kartu Keluarga</a></li>
                            <li><a href="{{ asset('storage/' . $item->ktp) }}" target="_blank">📄 KTP</a></li>
                            <li><a href="{{ asset('storage/' . $item->buku_nikah) }}" target="_blank">📄 Buku Nikah</a></li>
                            <li><a href="{{ asset('storage/' . $item->tanda_lunas_pbb) }}" target="_blank">📄 Tanda Lunas PBB</a></li>
                        </ul>

                        <div class="mt-4 flex gap-3">
                            <form method="POST" action="{{ route('kasi_kesos.sktm.approve', $item->id) }}">
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
                        </div>

                        {{-- Modal Tolak --}}
                        <dialog id="rejectModal-{{ $item->id }}" class="rounded-xl backdrop:bg-black/30 p-6 w-full max-w-md">
                            <form method="POST" action="{{ route('kasi_kesos.sktm.reject', $item->id) }}">
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
