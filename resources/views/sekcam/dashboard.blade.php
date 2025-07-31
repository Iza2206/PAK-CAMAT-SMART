@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('sekcam.layouts.sidebar')

    {{-- Main --}}
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">📊 Dashboard Indeks Kepuasan Masyarakat</h1>

        {{-- Ringkasan Data --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Pengajuan</p>
                <p class="text-2xl font-bold">{{ $jumlahPengajuan }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <p class="text-sm text-gray-600 dark:text-gray-400">Diajukan</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pengajuanDiterima }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <p class="text-sm text-gray-600 dark:text-gray-400">Disetujui</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pengajuanDisetujui }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow">
                <p class="text-sm text-gray-600 dark:text-gray-400">Ditolak</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $pengajuanDitolak }}</p>
            </div>
        </div>
    </main>
</div>
@endsection
