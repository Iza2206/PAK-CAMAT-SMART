@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold">Dashboard kasubbag umpeg</h1>
        <p class="text-gray-600">Selamat datang, {{ getRoleName(auth()->user()->role) }}!</p>
    </div>
@endsection
