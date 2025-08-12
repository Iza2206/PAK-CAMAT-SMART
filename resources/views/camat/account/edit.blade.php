@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('camat.layouts.sidebar')

    <main class="flex-1 p-8">
        <h1 class="text-4xl font-extrabold mb-8 text-blue-700 dark:text-blue-400">
            Manajemen Akun Camat
        </h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-900 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-900 rounded-lg shadow">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            {{-- Form Edit Profil --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-100">Edit Profil</h2>
                <form action="{{ route('camat.account.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                        @error('name') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" />
                        @error('email') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="ttd" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Upload Tanda Tangan (TTD)</label>
                        <input type="file" name="ttd" id="ttd" accept="image/*"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none dark:bg-gray-700" />
                        @error('ttd') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($user->ttd)
                        <div class="mb-4 flex items-center space-x-6 mt-4">
                            <img src="{{ asset('storage/ttd/' . $user->ttd) }}" alt="TTD" class="h-28 rounded-lg border shadow" />
                            <form action="{{ route('camat.account.ttd.delete') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tanda tangan?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition duration-200">
                                    Hapus TTD
                                </button>
                            </form>
                        </div>
                    @endif

                    <button type="submit" class="bg-blue-600 text-white w-full py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Form Ubah Password --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-100">Ubah Password</h2>
                <form action="{{ route('camat.account.password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Password Lama</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                        @error('current_password') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                        @error('password') <p class="text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block mb-2 font-semibold text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100" required />
                    </div>

                    <button type="submit" class="bg-green-600 text-white w-full py-3 rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                        Ubah Password
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
