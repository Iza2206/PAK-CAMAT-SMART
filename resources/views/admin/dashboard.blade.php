<!-- resources/views/admin/dashboard.blade.php -->

@extends('layouts.app') {{-- Sesuaikan layout utama jika ada --}}

@section('content')
    <div class="flex">
        @include('admin.layouts.sidebar')

        <main class="ml-64 w-full p-6">
            <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
            {{-- Konten dashboard di sini --}}
            <h1 class="text-2xl font-bold mb-4">📊 Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Jumlah Akun</h2>
        <p class="text-3xl mt-2">{{ $totalAccounts ?? 0 }}</p>
    </div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Akun Aktif</h2>
        <p class="text-3xl mt-2">{{ $activeAccounts ?? 0 }}</p>
    </div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Akun Nonaktif</h2>
        <p class="text-3xl mt-2">{{ $inactiveAccounts ?? 0 }}</p>
    </div>
</div>
        </main>
    </div>
@endsection

