@extends('layouts.app')

@section('content')
<div class="md:flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400">
                📄 Tambah Pengajuan Persyaratan Dispensasi Catin
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

            <form action="{{ route('dispencatin.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Data Pemohon --}}
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
                            @foreach(['SD','SMP','SMA','D1','D2','D3','S1','S2','S3'] as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr class="border-gray-300 dark:border-gray-600 my-6">

                {{-- Upload Dokumen --}}
                <h2 class="font-semibold text-lg text-blue-600 dark:text-blue-300">📎 Dokumen Persyaratan</h2>

                @foreach([
                    'file_na_pria' => 'File NA Pria',
                    'file_na_wanita' => 'File NA Wanita',
                    'file_kk_pria' => 'Fotokopi Kartu Keluarga Pria',
                    'file_kk_wanita' => 'Fotokopi Kartu Keluarga Wanita',
                    'file_ktp_pria' => 'Fotokopi KTP Pria',
                    'file_ktp_wanita' => 'Fotokopi KTP Wanita',
                    'file_akte_cerai_pria' => 'Fotokopi Akta Cerai Pria (Opsional)',
                    'file_akte_cerai_wanita' => 'Fotokopi Akta Cerai Wanita (Opsional)',
                    'file_pbb' => 'Tanda Lunas PBB',
                ] as $field => $label)
                    <div>
                        <label class="block mb-1 font-semibold">
                            {{ $label }}
                            <span class="text-xs text-gray-500">(PDF)</span>
                        </label>
                        <input 
                            type="file" 
                            name="{{ $field }}" 
                            accept="application/pdf"
                            @if(!in_array($field, ['file_akte_cerai_pria', 'file_akte_cerai_wanita'])) required @endif
                            class="w-full file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white dark:bg-gray-700 text-sm rounded-lg"
                        >
                    </div>
                @endforeach


                {{-- Tombol --}}
                <div class="pt-6 flex justify-between items-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition duration-200 shadow-md">
                        💾 Simpan Pengajuan
                    </button>
                    <a href="{{ route('dispencatin.list') }}"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:underline hover:text-gray-900 dark:hover:text-white">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
