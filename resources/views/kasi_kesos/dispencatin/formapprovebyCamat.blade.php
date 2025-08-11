@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasi_kesos.layouts.sidebar')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">✍️ Tanda Tangani Surat</h2>
    <p class="mb-4">Nama Pemohon: <strong>{{ $item->nama_pemohon }}</strong></p>

    <form method="POST" action="{{ route('kasi_kesos.dispencatin.proses.storeTTD', $item->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold mb-2">Unggah Tanda Tangan:</label>
            <input type="file" name="ttd" class="border p-2 rounded w-full" required>
        </div>

        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            ✅ Simpan & Selesai
        </button>
    </form>
</div>
@endsection
