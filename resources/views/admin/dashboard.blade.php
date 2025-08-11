<!-- resources/views/admin/dashboard.blade.php -->

@extends('layouts.app') {{-- Sesuaikan layout utama jika ada --}}

@section('content')
    <div class="flex">
        @include('admin.layouts.sidebar')

        <main class="ml-64 w-full p-6">
            <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
            {{-- Konten dashboard di sini --}}
        </main>
    </div>
@endsection
