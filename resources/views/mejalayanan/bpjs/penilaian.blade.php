@extends('layouts.app')

@section('content')
<div 
    x-data="{
        modalOpen: false,
        selectedId: null,
        showToast: {{ session('success') || session('error') ? 'true' : 'false' }},
        toastMessage: '{{ session('success') ?? session('error') }}',
        toastType: '{{ session('success') ? 'bg-green-200 text-green-800' : (session('error') ? 'bg-red-200 text-red-800' : '') }}',
        openModal(id) {
            this.selectedId = id;
            this.modalOpen = true;
        }
    }"
    x-init="if (showToast) { setTimeout(() => showToast = false, 4000) }"
    class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"
>
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-300">📝 Daftar Penilaian Layanan BPJS</h1>

        {{-- Filter --}}
        <form method="GET" action="{{ route('bpjs.penilaian.index') }}" class="mb-4 flex flex-wrap gap-3 items-center">
            <input type="text" name="nik" value="{{ request('nik') }}" placeholder="🔍 Cari NIK" class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <select name="penilaian" onchange="this.form.submit()" class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">
                <option value="">📋 Semua Penilaian</option>
                <option value="0" {{ request('penilaian') == '0' ? 'selected' : '' }}>🕓 Belum Dinilai</option>
                <option value="1" {{ request('penilaian') == '1' ? 'selected' : '' }}>😠 Tidak Puas</option>
                <option value="2" {{ request('penilaian') == '2' ? 'selected' : '' }}>😐 Cukup</option>
                <option value="3" {{ request('penilaian') == '3' ? 'selected' : '' }}>🙂 Puas</option>
                <option value="4" {{ request('penilaian') == '4' ? 'selected' : '' }}>🤩 Sangat Puas</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter 🔎</button>
            @if(request('nik') || request('penilaian') || request('start_date') || request('end_date'))
                <a href="{{ route('bpjs.penilaian.index') }}" class="text-sm text-red-600 hover:underline">❌ Reset</a>
            @endif
        </form>

        {{-- Tombol Cetak PDF --}}
        <a href="{{ route('bpjs.penilaian.pdf', request()->query()) }}" target="_blank"
           class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4">
            🖨️ Cetak PDF
        </a>

        {{-- Toast --}}
        <div x-show="showToast" x-transition.duration.500ms x-text="toastMessage"
            :class="toastType" class="fixed top-6 right-6 px-4 py-3 rounded shadow-lg z-50"></div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 overflow-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase">
                    <tr>
                        <th class="px-4 py-3 border">Nama Pemohon</th>
                        <th class="px-4 py-3 border">NIK Pemohon</th>
                        <th class="px-4 py-3 border">Tanggal Masuk</th>
                        <th class="px-4 py-3 border">Total Durasi</th>
                        <th class="px-4 py-3 border">Status</th>
                        <th class="px-4 py-3 border">Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                        <td class="px-4 py-3 border">{{ $item->nama_pemohon }}</td>
                        <td class="px-4 py-3 border">{{ $item->nik_pemohon }}</td>
                        <td class="px-4 py-3 border">
                            {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 border text-sm">
                            @php
                                $created = \Carbon\Carbon::parse($item->created_at);
                                $approved = $item->approved_camat_at ?? $item->approved_sekcam_at ?? $item->verified_at;
                            @endphp
                            @if ($approved)
                                ⏱️ {{ \Carbon\Carbon::parse($approved)->diffForHumans($created, true) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 border">
                            @if ($item->status === 'approved_by_camat')
                                <span class="text-green-600 font-semibold">Disetujui Camat</span>
                            @else
                                <span class="text-gray-500">Belum Disetujui</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border text-sm" x-data="{ showFull: false }">
                            @if ($item->status === 'approved_by_camat')
                                @if (!$item->penilaian)
                                    <button @click="openModal({{ $item->id }})" class="bg-yellow-500 hover:bg-yellow-600 !text-white px-4 py-2 rounded text-sm font-semibold shadow">
                                        ✨ Beri Penilaian
                                    </button>
                                @else
                                    @php
                                        $emoji=['tidak_puas'=>'😠','cukup'=>'😐','puas'=>'🙂','sangat_puas'=>'🤩'];
                                        $warna=['tidak_puas'=>'text-red-500','cukup'=>'text-yellow-500','puas'=>'text-green-500','sangat_puas'=>'text-blue-500'];
                                    @endphp
                                    <div class="{{ $warna[$item->penilaian] ?? 'text-gray-500' }} font-semibold">
                                        {{ $emoji[$item->penilaian] ?? '' }} {{ ucfirst(str_replace('_',' ',$item->penilaian)) }}
                                    </div>
                                    @if(!empty($item->saran_kritik))
                                        <div class="mt-1 text-sm italic">
                                            <span x-show="!showFull" class="line-clamp-2">{{ $item->saran_kritik }}</span>
                                            <span x-show="showFull">{{ $item->saran_kritik }}</span>
                                            <button type="button" @click="showFull = !showFull" class="text-blue-500 hover:underline text-xs ml-1">
                                                <span x-show="!showFull">Lihat Selengkapnya</span>
                                                <span x-show="showFull">Tutup</span>
                                            </button>
                                        </div>
                                    @endif
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-gray-500">Data tidak tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $data->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </main>

    {{-- Modal --}}
    <div x-show="modalOpen" x-transition class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50" style="display:none;">
        <div @click.outside="modalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm shadow-lg">
            <h2 class="text-lg font-bold mb-4 text-center text-blue-600 dark:text-blue-300">📝 Pilih Penilaian</h2>
            <form method="POST" :action="`/meja-layanan/bpjs/${selectedId}/penilaian`">
                @csrf
                <div class="grid grid-cols-2 gap-3 mb-4">
                    @foreach(['tidak_puas'=>'😠 Tidak Puas','cukup'=>'😐 Cukup','puas'=>'🙂 Puas','sangat_puas'=>'🤩 Sangat Puas'] as $v=>$l)
                        <label class="flex items-center space-x-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded">
                            <input type="radio" name="penilaian" value="{{ $v }}" required>
                            <span>{{ $l }}</span>
                        </label>
                    @endforeach
                </div>
                <textarea name="saran_kritik" rows="3" placeholder="💬 Saran & kritik..." class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white"></textarea>
                <div class="mt-4 text-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">✅ Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
