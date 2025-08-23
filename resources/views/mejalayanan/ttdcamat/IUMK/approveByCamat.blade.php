@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-bold mb-4">📝 Proses Izin Usaha Mikro</h1>

    <div class="bg-white shadow rounded p-4">
        <p><strong>Nama:</strong> {{ $item->nama_pemohon }}</p>
        <p><strong>NIK:</strong> {{ $item->nik_pemohon }}</p>
        <p><strong>Alamat Usaha:</strong> {{ $item->alamat_usaha }}</p>
        <p><strong>Status:</strong> {{ $item->status }}</p>

        <hr class="my-3">

        <h2 class="font-semibold mb-2">Tanda Tangan Camat</h2>
        @if ($item->camat && $item->camat->ttd)
            <a href="{{ asset('storage/' . $item->camat->ttd) }}" 
               target="_blank" 
               class="text-blue-600 hover:underline">
               📎 Lihat TTD Camat
            </a>
        @else
            <p class="text-red-500">⚠️ Tanda tangan Camat belum tersedia.</p>
        @endif

        <hr class="my-3">

        <form method="POST" 
              action="{{ route('mejalayanan.ttdcamat.IUMK.proses.store', $item->id) }}">
            @csrf
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                ✅ Simpan & Generate Surat
            </button>
            <a href="{{ route('mejalayanan.ttdcamat.IUMK.index') }}" 
               class="ml-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">
                🔙 Kembali
            </a>
        </form>
    </div>
</div>
@endsection
