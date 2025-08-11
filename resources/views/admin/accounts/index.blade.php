@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('admin.layouts.sidebar')

    <main class="flex-1 p-6">
        <div class="p-6">
            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
                <h1 class="text-2xl font-bold text-red-600 dark:text-red-300">👥 Kelola Akun</h1>

                <div class="flex gap-2">
                    <a href="{{ route('admin.accounts.create') }}"
                       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                       ➕ Tambah Akun
                    </a>
                    <a href="{{ route('admin.accounts.export') }}"
                       class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
                       📤 Export Akun
                    </a>
                </div>
            </div>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Filter --}}
            <form method="GET" action="{{ route('admin.accounts.index') }}" class="mb-4 bg-white dark:bg-gray-800 p-4 rounded shadow flex flex-wrap gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email"
                       class="border border-gray-300 dark:border-gray-600 p-2 rounded w-full md:w-1/4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.accounts.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                    ❌ Reset
                </a>
            </form>

            {{-- Tabel Data --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow overflow-x-auto">
                <table class="w-full border border-gray-300 dark:border-gray-600 text-sm">
                   <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border">#</th>
                            <th class="py-2 px-4 border">Nama</th>
                            <th class="py-2 px-4 border">Email</th>
                            <th class="py-2 px-4 border">Role</th>
                            <th class="py-2 px-4 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 border">{{ $account->name }}</td>
                                <td class="py-2 px-4 border">{{ $account->email }}</td>
                                <td class="py-2 px-4 border">{{ ucfirst($account->role) }}</td>
                                <td class="py-2 px-4 border">
                                    <a href="{{ route('admin.accounts.editPassword', $account->id) }}" 
                                    class="bg-yellow-500 text-white px-3 py-1 rounded">🔑 Password</a>
                                    
                                    <form action="{{ route('admin.accounts.destroy', $account->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin hapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-600 text-white px-3 py-1 rounded">🗑 Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-3 text-center text-gray-500">Tidak ada akun ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $accounts->links() }}
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
