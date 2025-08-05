@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">📄 Daftar pengurusan Catin (calon Pengantin) TNI/Polri </h1>

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
                        <p class="mb-2"><strong>🔢 Nomor Antrian:</strong> {{ $item->queue_number }}</p>

                        <div class="text-sm space-y-1 text-blue-600 dark:text-blue-300 mt-3">
                            @php
                                $isSipilLengkap = $item->file_ktp && $item->file_kk && $item->file_surat_n1;
                                $isTniLengkap = $item->file_kta && $item->file_surat_izin_kawin && $item->file_pernyataan_kesediaan;
                            @endphp

                            {{-- 👤 Pihak SIPIL --}}
                            @if ($isSipilLengkap && !$isTniLengkap)
                                <div class="font-semibold text-gray-800 dark:text-gray-200">👤 Dokumen Sipil</div>
                                @if ($item->file_ktp) <div><a href="{{ asset('storage/' . $item->file_ktp) }}" target="_blank">📄 KTP</a></div> @endif
                                @if ($item->file_kk) <div><a href="{{ asset('storage/' . $item->file_kk) }}" target="_blank">📄 KK</a></div> @endif
                                @if ($item->file_akta_kelahiran) <div><a href="{{ asset('storage/' . $item->file_akta_kelahiran) }}" target="_blank">📄 Akta Kelahiran</a></div> @endif
                                @if ($item->file_pas_foto_3x4) <div><a href="{{ asset('storage/' . $item->file_pas_foto_3x4) }}" target="_blank">🖼️ Pas Foto 3x4</a></div> @endif
                                @if ($item->file_pas_foto_4x6) <div><a href="{{ asset('storage/' . $item->file_pas_foto_4x6) }}" target="_blank">🖼️ Pas Foto 4x6</a></div> @endif
                                @if ($item->file_pengantar_rt_rw) <div><a href="{{ asset('storage/' . $item->file_pengantar_rt_rw) }}" target="_blank">📄 Surat Pengantar RT/RW</a></div> @endif
                                @if ($item->file_surat_n1) <div><a href="{{ asset('storage/' . $item->file_surat_n1) }}" target="_blank">📄 Surat N1</a></div> @endif
                                @if ($item->file_surat_n2) <div><a href="{{ asset('storage/' . $item->file_surat_n2) }}" target="_blank">📄 Surat N2</a></div> @endif
                                @if ($item->file_surat_n3) <div><a href="{{ asset('storage/' . $item->file_surat_n3) }}" target="_blank">📄 Surat N3</a></div> @endif
                                @if ($item->file_surat_n4) <div><a href="{{ asset('storage/' . $item->file_surat_n4) }}" target="_blank">📄 Surat N4</a></div> @endif
                                @if ($item->file_izin_orang_tua) <div><a href="{{ asset('storage/' . $item->file_izin_orang_tua) }}" target="_blank">📄 Izin Orang Tua</a></div> @endif
                                @if ($item->file_status_pernikahan) <div><a href="{{ asset('storage/' . $item->file_status_pernikahan) }}" target="_blank">📄 Status Pernikahan / Cerai</a></div> @endif
                                @if ($item->file_pbb) <div><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></div> @endif
                            @endif

                            {{-- 🪖 Pihak TNI/POLRI --}}
                            @if ($isTniLengkap && !$isSipilLengkap)
                                <div class="font-semibold text-gray-800 dark:text-gray-200">🪖 Dokumen TNI/POLRI</div>
                                @if ($item->file_surat_izin_kawin) <div><a href="{{ asset('storage/' . $item->file_surat_izin_kawin) }}" target="_blank">📄 Surat Izin Kawin</a></div> @endif
                                @if ($item->file_keterangan_belum_menikah_tni) <div><a href="{{ asset('storage/' . $item->file_keterangan_belum_menikah_tni) }}" target="_blank">📄 Keterangan Belum Menikah</a></div> @endif
                                @if ($item->file_kta) <div><a href="{{ asset('storage/' . $item->file_kta) }}" target="_blank">📄 KTA</a></div> @endif
                                @if ($item->file_sk_pangkat_terakhir) <div><a href="{{ asset('storage/' . $item->file_sk_pangkat_terakhir) }}" target="_blank">📄 SK Pangkat Terakhir</a></div> @endif
                                @if ($item->file_pernyataan_kesediaan) <div><a href="{{ asset('storage/' . $item->file_pernyataan_kesediaan) }}" target="_blank">📄 Pernyataan Kesediaan</a></div> @endif
                                @if ($item->file_pas_foto_berdampingan_dinas) <div><a href="{{ asset('storage/' . $item->file_pas_foto_berdampingan_dinas) }}" target="_blank">🖼️ Foto Dinas</a></div> @endif
                                @if ($item->file_pas_foto_berdampingan_formal) <div><a href="{{ asset('storage/' . $item->file_pas_foto_berdampingan_formal) }}" target="_blank">🖼️ Foto Formal</a></div> @endif
                                @if ($item->file_pbb) <div><a href="{{ asset('storage/' . $item->file_pbb) }}" target="_blank">📄 Bukti Lunas PBB</a></div> @endif
                            @endif

                            @if (!$isSipilLengkap && !$isTniLengkap)
                                <div class="text-gray-500 italic">Tidak ada dokumen lengkap</div>
                            @endif
                        </div>
                        <div class="mt-4 flex gap-3">
                            <form method="POST" action="{{ route('camat.catin.approve', $item->id) }}">
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
                            <form method="POST" action="{{ route('camat.catin.reject', $item->id) }}">
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
