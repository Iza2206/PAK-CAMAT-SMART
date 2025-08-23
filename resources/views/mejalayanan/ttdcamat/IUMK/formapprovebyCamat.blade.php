@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    <div class="p-6 w-full max-w-4xl mx-auto space-y-6">
        <h2 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-400">✍️ Tanda Tangani Surat</h2>
        <p class="mb-6 text-lg">Nama Pemohon: <strong>{{ $item->nama_pemohon }}</strong></p>

        {{-- Surat Draft Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-200">📄 Surat Draft (Belum Final)</h3>
            @if ($item->file_surat)
                <a href="{{ asset('storage/' . $item->file_surat) }}" class="inline-block text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    Klik untuk download surat draft
                </a>
            @else
                <p class="text-red-600 dark:text-red-400 font-semibold">⚠️ Surat draft belum tersedia.</p>
            @endif
        </div>

        {{-- Tanda Tangan Camat Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-3 text-gray-700 dark:text-gray-200">🖋️ Tanda Tangan Camat</h3>
            @if ($item->camat->ttd)
                <a href="{{ asset('storage/ttd/' . $item->camat->ttd) }}" class="inline-block text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    Klik untuk download tanda tangan
                </a>
            @else
                <p class="text-red-600 dark:text-red-400 font-semibold">⚠️ Tanda tangan Camat belum tersedia.</p>
            @endif
        </div>

        {{-- Upload Surat Final Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-200">📤 Unggah Surat Final (Disetujui Camat)</h3>

            <form method="POST" action="{{ route('mejalayanan.ttdcamat.IUMK.proses', $item->id) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="surat_final" class="block font-semibold mb-2 text-gray-800 dark:text-gray-100">Pilih File Surat Final:</label>
                    <input type="file" name="surat_final" id="surat_final" accept=".pdf,.doc,.docx" class="w-full border border-gray-300 rounded p-2 dark:bg-gray-700 dark:text-white dark:border-gray-600" required>
                </div>

              <button 
                type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow-md disabled:opacity-50 disabled:cursor-not-allowed"
                @if (!$item->camat || !$item->camat->ttd) disabled @endif
            >
                @if (!$item->camat || !$item->camat->ttd)
                    ⚠️ Tanda Tangan Camat Belum Ada
                @else
                    ✅ Simpan Surat Final
                @endif
            </button>

            </form>
        </div>
    </div>
</div>
@endsection
