@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasubbag_umpeg.layouts.sidebar')

    {{-- Main Content --}}
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            📊 Dashboard Kasubbag Umpeg
        </h1>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Total Pengajuan --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Pengajuan</p>
                <p class="text-3xl font-bold text-blue-800 dark:text-blue-300 mt-1">{{ $jumlahPengajuan }}</p>
            </div>

            {{-- Diajukan --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Diajukan</p>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $pengajuanDiajukan }}</p>
            </div>

            {{-- Disetujui --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Disetujui</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $pengajuanDisetujui }}</p>
            </div>

            {{-- Ditolak --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Ditolak</p>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $pengajuanDitolak }}</p>
            </div>
        </div>
    </main>
</div>
@endsection
