@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasi_trantib.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            📄 Pengajuan Catin TNI/POLRI (Menunggu Verifikasi)
        </h1>

        @if ($pengajuan->isEmpty())
            <div class="text-center text-gray-500 dark:text-gray-400 mt-10">
                Tidak ada pengajuan baru.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pengajuan as $item)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <p><strong>🧾 No. Antrian:</strong> {{ $item->queue_number }}</p>
                        <p><strong>👤 Nama:</strong> {{ $item->nama_pemohon }}</p>
                        <p><strong>🆔 NIK:</strong> {{ $item->nik_pemohon }}</p>
                        <p><strong>🚻 Jenis Kelamin:</strong> {{ $item->jenis_kelamin }}</p>
                        <p><strong>🎓 Pendidikan:</strong> {{ $item->pendidikan }}</p>
                        <p class="font-semibold mt-4 mb-2">📎 Dokumen Penting:</p>
                        <ul class="list-disc list-inside text-sm space-y-1 text-blue-600 dark:text-blue-300">
                            <li><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 KTP</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 KK</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_akta_kelahiran) }}" target="_blank">📄 Akta Kelahiran</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_surat_n1) }}" target="_blank">📄 Surat N1</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_surat_izin_kawin) }}" target="_blank">📄 Surat Izin Kawin (TNI/Polri)</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_kta) }}" target="_blank">📄 KTA</a></li>
                            <li><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></li>
                        </ul>

                        <div class="mt-4 flex gap-3">
                            <form method="POST" action="{{ route('kasi_trantib.catin_tni.approve', $item->id) }}">
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
                            <form method="POST" action="{{ route('kasi_trantib.catin_tni.reject', $item->id) }}">
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
