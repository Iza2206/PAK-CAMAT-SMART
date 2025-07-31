@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r shadow-md">
        <div class="p-4 border-b">
            <h2 class="text-lg font-bold">Super Admin</h2>
        </div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Dashboard</a>
            <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100">Master Data Pelayanan</a>
            <a href="{{ route('admin.export') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Export Laporan IKM</a>
        </nav>
    </aside>

    {{-- Main --}}
    <main class="flex-1 p-6 bg-gray-50">
        <h1 class="text-2xl font-semibold mb-6">Dashboard Indeks Kepuasan Masyarakat</h1>

        {{-- Ringkasan Data --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-gray-500 text-sm">Total Nilai IKM</div>
                <div class="text-2xl font-bold">{{ number_format($totalNilai, 2) }}</div>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-gray-500 text-sm">Jumlah Responden</div>
                <div class="text-2xl font-bold">{{ $jumlahResponden }}</div>
            </div>
            <div class="bg-white rounded shadow p-4 text-center">
                <div class="text-gray-500 text-sm">Rata-rata Lama Pengisian</div>
                <div class="text-2xl font-bold">{{ gmdate('i \m\i\n s \d\k', $avgDuration ?? 0) }}</div>
            </div>
        </div>

        {{-- Tabel Data Pengisian --}}
        <div class="bg-white rounded shadow p-4">
            <h2 class="text-lg font-semibold mb-4">Riwayat Pengisian IKM</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border">
                    <thead class="bg-gray-100 text-left">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Nama Pengisi</th>
                            <th class="px-4 py-2 border">Nilai</th>
                            <th class="px-4 py-2 border">Status</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Durasi</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                            <td class="px-4 py-2 border">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ $item->nilai }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst($item->status) }}</td>
                            <td class="px-4 py-2 border">{{ $item->submitted_at ?? '-' }}</td>
                            <td class="px-4 py-2 border">{{ gmdate('i \m s \d\k', $item->duration_seconds ?? 0) }}</td>
                            <td class="px-4 py-2 border space-x-2">
                                <form action="{{ route('admin.delete', $item->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button onclick="return confirm('Hapus data ini?')" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                                <form action="{{ route('admin.resetPassword', $item->user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-blue-600 hover:underline">Reset Password</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-2 text-center text-gray-500">Belum ada data pengisian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $submissions->links() }}
            </div>
        </div>
    </main>
</div>
@endsection
