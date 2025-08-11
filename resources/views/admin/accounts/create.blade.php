@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold text-green-600 dark:text-green-300 mb-6">➕ Tambah Akun</h1>

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <form action="{{ route('admin.accounts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded dark:bg-gray-900" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded dark:bg-gray-900" required>
                </div>

                <div class="mb-4">
                    <label class="block font-semibold mb-1">Password</label>
                    <input type="password" name="password" class="w-full p-2 border rounded dark:bg-gray-900" required>
                </div>

                <div class="mb-4">
            <label class="block mb-1 font-medium">Role</label>
            <select name="role" class="w-full border px-3 py-2 rounded focus:outline-none focus:ring" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="meja_layanan" {{ old('role') == 'meja_layanan' ? 'selected' : '' }}>Meja Layanan</option>
                <option value="kasi_kesos" {{ old('role') == 'kasi_kesos' ? 'selected' : '' }}>Kasi Kesos</option>
                <option value="sekcam" {{ old('role') == 'sekcam' ? 'selected' : '' }}>Sekcam</option>
                <option value="camat" {{ old('role') == 'camat' ? 'selected' : '' }}>Camat</option>
                <option value="kasubbah_umpeg" {{ old('role') == 'kasubbah_umpeg' ? 'selected' : '' }}>Kasubbah Umpeg</option>
                <option value="kasi_pemerintahan" {{ old('role') == 'kasi_pemerintahan' ? 'selected' : '' }}>Kasi Pemerintahan</option>
                <option value="kasi_trantib" {{ old('role') == 'kasi_trantib' ? 'selected' : '' }}>Kasi Trantib</option>
            </select>
            @error('role')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
                    <a href="{{ route('admin.accounts.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection
