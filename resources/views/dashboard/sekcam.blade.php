@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-xl font-bold mb-4">Dashboard sekcam</h1>
        <p class="text-gray-600">Selamat datang, {{ getRoleName(auth()->user()->role) }}!</p>
    </div>
@endsection
