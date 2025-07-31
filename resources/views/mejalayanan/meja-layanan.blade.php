@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
    @include('mejalayanan.layouts.sidebar')

    {{-- Main --}}
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-blue-700 dark:text-blue-300">
            📊 Dashboard Indeks Kepuasan Masyarakat
        </h1>

        {{-- Ringkasan Data --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Nilai IKM</p>
                <p class="text-2xl font-bold">{{ number_format($totalNilai, 2) }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Responden</p>
                <p class="text-2xl font-bold">{{ $jumlahResponden }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Lama Pengisian</p>
                <p class="text-2xl font-bold">{{ gmdate('i \m\i\n s \d\k', $avgDuration ?? 0) }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat Pengisian --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
            <h2 class="text-lg font-semibold mb-4">Riwayat Pengisian IKM</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 dark:border-gray-700">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Nama Pengisi</th>
                            <th class="px-4 py-2 border">Nilai</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Durasi</th>
                            <th class="px-4 py-2 border text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                <td class="px-4 py-2 border">{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                                <td class="px-4 py-2 border">{{ $item->user->name ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ $item->nilai }}</td>
                                <td class="px-4 py-2 border capitalize">{{ $item->status }}</td>
                                <td class="px-4 py-2 border">{{ $item->submitted_at ?? '-' }}</td>
                                <td class="px-4 py-2 border">{{ gmdate('i \m\i\n s \d\k', $item->duration_seconds ?? 0) }}</td>
                                <td class="px-4 py-2 border text-center space-x-2">
                                    <form action="{{ route('admin.delete', $item->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus data ini?')" class="text-red-600 dark:text-red-400 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                    @if ($item->user)
                                    <form action="{{ route('admin.resetPassword', $item->user->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-blue-600 dark:text-blue-400 hover:underline">
                                            Reset Password
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data pengisian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $submissions->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </main>
</div>
@endsection
