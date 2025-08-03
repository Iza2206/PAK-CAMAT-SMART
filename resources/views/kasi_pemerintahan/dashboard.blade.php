@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('kasi_pemerintahan.layouts.sidebar') {{-- Pastikan file ini ada --}}

    {{-- Main Content --}}
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            🏛️ Dashboard Kasi Pemerintahan
        </h1>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
            {{-- SKT --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">SKT</p>
                <p class="text-3xl font-bold text-blue-800 dark:text-blue-300 mt-1">{{ $jumlahSkt }}</p>
            </div>

            {{-- SPPAT-GR --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">SPPAT-GR</p>
                <p class="text-3xl font-bold text-indigo-700 dark:text-indigo-300 mt-1">{{ $jumlahSppatGr }}</p>
            </div>

            {{-- Ahli Waris --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Ahli Waris</p>
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $jumlahAhliWaris }}</p>
            </div>

            {{-- Agunan Bank --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Agunan ke Bank</p>
                <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $jumlahAgunan }}</p>
            </div>

            {{-- Silang Sengketa --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-md">
                <p class="text-sm text-gray-600 dark:text-gray-400">Silang Sengketa</p>
                <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $jumlahSengketa }}</p>
            </div>
        </div>
    </main>
</div>
@endsection
