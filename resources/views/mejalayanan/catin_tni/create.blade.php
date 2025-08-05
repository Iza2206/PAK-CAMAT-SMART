@extends('layouts.app')

@section('content')
<div x-data="{ kategori: '' }" class="md:flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400">
                ➕ Tambah Pengajuan Catin TNI/Polri
            </h1>

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-300 rounded-lg border border-red-300 dark:border-red-500">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pilih Kategori --}}
            <div class="mb-6">
                <label class="block mb-2 font-semibold">Pilih Pihak Pemohon</label>
                <select x-model="kategori" class="w-full px-4 py-2 rounded-lg border dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-blue-400">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="sipil">Calon Pengantin Sipil (Non-TNI/Polri)</option>
                    <option value="tni">Calon Pengantin TNI/Polri</option>
                </select>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Wajib unggah dokumen dari salah satu pihak saja (Sipil atau TNI/Polri).
                </p>
            </div>

            {{-- Formulir --}}
            <template x-if="kategori">
                <form action="{{ route('catin.tni.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- Data Umum --}}
                    <div>
                        <label class="block mb-1 font-semibold">NIK Pemohon</label>
                        <input type="text" name="nik_pemohon" maxlength="16" minlength="16" required
                            value="{{ old('nik_pemohon') }}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div>
                        <label class="block mb-1 font-semibold">Nama Pemohon</label>
                        <input type="text" name="nama_pemohon" required
                            value="{{ old('nama_pemohon') }}"
                            class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 font-semibold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" required
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600">
                                <option value="">Pilih</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-1 font-semibold">Pendidikan</label>
                            <select name="pendidikan" required
                                class="w-full px-4 py-2 rounded-lg border bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600">
                                <option value="">Pilih</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="D1">D1</option>
                                <option value="D2">D2</option>
                                <option value="D3">D3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select>
                        </div>
                    </div>

                    {{-- Dokumen Sipil --}}
                    <template x-if="kategori === 'sipil'">
                        <div class="space-y-4">
                            <h2 class="font-semibold text-lg text-blue-600 dark:text-blue-300">📂 Dokumen Pihak Sipil</h2>

                            @foreach([
                                'file_ktp' => 'Fotokopi KTP',
                                'file_kk' => 'Fotokopi KK',
                                'file_akta_kelahiran' => 'Akta Kelahiran',
                                'file_pas_foto_3x4' => 'Pas Foto 3x4',
                                'file_pas_foto_4x6' => 'Pas Foto 4x6',
                                'file_pengantar_rt_rw' => 'Surat Pengantar RT/RW',
                                'file_surat_n1' => 'Formulir N1',
                                'file_surat_n2' => 'Formulir N2',
                                'file_surat_n3' => 'Formulir N3',
                                'file_surat_n4' => 'Formulir N4',
                                'file_izin_orang_tua' => 'Surat Izin Orang Tua (jika <21 tahun)',
                                'file_status_pernikahan' => 'Surat Belum Menikah / Akta Cerai',
                            ] as $name => $label)
                                <div>
                                    <label class="block mb-1 font-semibold">{{ $label }} <span class="text-xs text-gray-500">(PDF)</span></label>
                                    <input type="file" name="{{ $name }}" accept="application/pdf"
                                        class="w-full file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white dark:bg-gray-700 text-sm rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </template>

                    {{-- Dokumen TNI/Polri --}}
                    <template x-if="kategori === 'tni'">
                        <div class="space-y-4">
                            <h2 class="font-semibold text-lg text-blue-600 dark:text-blue-300">📂 Dokumen Pihak TNI/Polri</h2>

                            @foreach([
                                'file_surat_izin_kawin' => 'Surat Izin Kawin dari Komandan',
                                'file_keterangan_belum_menikah_tni' => 'Surat Belum Menikah dari Kesatuan',
                                'file_pernyataan_kesediaan' => 'Surat Pernyataan Kesediaan Mendampingi',
                                'file_kta' => 'Fotokopi KTA',
                                'file_sk_pangkat_terakhir' => 'SK Pangkat Terakhir',
                                'file_pas_foto_berdampingan_dinas' => 'Pas Foto Berdampingan (Pakaian Dinas)',
                                'file_pas_foto_berdampingan_formal' => 'Pas Foto Berdampingan (Pakaian Formal)',
                            ] as $name => $label)
                                <div>
                                    <label class="block mb-1 font-semibold">{{ $label }} <span class="text-xs text-gray-500">(PDF)</span></label>
                                    <input type="file" name="{{ $name }}" accept="application/pdf"
                                        class="w-full file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white dark:bg-gray-700 text-sm rounded-lg">
                                </div>
                            @endforeach
                        </div>
                    </template>

                    {{-- Tanda Lunas PBB --}}
                    <div class="mt-4">
                        <label class="block mb-1 font-semibold">Tanda Lunas PBB <span class="text-xs text-gray-500">(PDF)</span></label>
                        <input type="file" name="file_pbb" accept="application/pdf"
                            class="w-full file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white dark:bg-gray-700 text-sm rounded-lg">
                    </div>

                    {{-- Tombol --}}
                    <div class="pt-6 flex justify-between items-center">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition duration-200 shadow-md">
                            💾 Simpan
                        </button>
                        <a href="{{ route('catin.tni.list') }}"
                            class="text-sm text-gray-600 dark:text-gray-300 hover:underline hover:text-gray-900 dark:hover:text-white">
                            ← Kembali ke Daftar
                        </a>
                    </div>
                </form>
            </template>
        </div>
    </main>
</div>
@endsection
