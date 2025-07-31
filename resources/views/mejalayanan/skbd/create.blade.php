@extends('layouts.app')

@section('content')
<div x-data="{ open: false }" class="md:flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

    {{-- Sidebar --}}
    @include('mejalayanan.layouts.sidebar')

    {{-- Konten Utama --}}
    <main class="flex-1 p-6">
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-md p-6">
            <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400">
                ➕ Tambah Pengajuan SKBD
            </h1>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-300 rounded-lg border border-red-300 dark:border-red-500">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('SKBDs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- NIK Pemohon --}}
                <div>
                    <label class="block mb-1 font-semibold">NIK Pemohon</label>
                    <input type="text" name="nik_pemohon"
                        maxlength="16" minlength="16" inputmode="numeric" required
                        value="{{ old('nik_pemohon') }}"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16);"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan 16 digit NIK">
                </div>

                {{-- Nama Pemohon --}}
                <div>
                    <label class="block mb-1 font-semibold">Nama Pemohon</label>
                    <input type="text" name="nama_pemohon"
                        value="{{ old('nama_pemohon') }}"
                        required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Masukkan nama lengkap">
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block mb-1 font-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Pilih jenis kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                {{-- Pendidikan --}}
                <div>
                    <label class="block mb-1 font-semibold">Pendidikan</label>
                    <select name="pendidikan" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Pilih pendidikan terakhir</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="D1">D1</option>
                        <option value="D2">D2</option>
                        <option value="D3">D3</option>
                        <option value="S1">Strata I (S1)</option>
                        <option value="S2">Strata II (S2)</option>
                        <option value="S3">Strata III (S3)</option>
                    </select>
                </div>

                {{-- Dokumen Upload --}}
                @php
                    $documents = [
                        'skbd_desa' => 'Surat Keterangan Tidak Mampu dari Desa',
                        'kk' => 'Fotokopi Kartu Keluarga (KK)',
                        'ktp' => 'Fotokopi KTP',
                        'tanda_lunas_pbb' => 'Tanda Lunas PBB',
                    ];
                @endphp

                @foreach ($documents as $name => $label)
                    <div>
                        <label class="block mb-1 font-semibold">{{ $label }} <span class="text-xs text-gray-500">(PDF)</span></label>
                        <input type="file" name="{{ $name }}" accept="application/pdf" required
                            class="w-full file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700 bg-white dark:bg-gray-700 text-sm rounded-lg">
                    </div>
                @endforeach

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg transition duration-200 shadow-md">
                        💾 Simpan
                    </button>

                    <a href="{{ route('SKBDs.list') }}"
                        class="text-sm text-gray-600 dark:text-gray-300 hover:underline hover:text-gray-900 dark:hover:text-white">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
