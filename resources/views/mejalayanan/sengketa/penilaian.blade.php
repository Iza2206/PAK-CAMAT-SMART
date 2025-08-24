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
    x-init="
        if (showToast) {
            setTimeout(() => showToast = false, 4000);
        }
    "
    class="flex flex-col md:flex-row min-h-screen bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100"
>
    @include('mejalayanan.layouts.sidebar')

    <main class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-6 text-blue-700 dark:text-blue-300">📝 Daftar Penilaian Silang Sengketa</h1>

        {{-- Filter --}}
        <form method="GET" action="{{ route('sengketa.penilaian.index') }}" class="mb-4 flex flex-wrap gap-3 items-center">
            <input type="text" name="nik" value="{{ request('nik') }}"
                placeholder="🔍 Cari NIK"
                class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">

            <select name="penilaian" onchange="this.form.submit()"
                class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">
                <option value="">📋 Semua Penilaian</option>
                <option value="belum" {{ request('penilaian') == 'belum' ? 'selected' : '' }}>🕓 Belum Dinilai</option>
                <option value="tidak_puas" {{ request('penilaian') == 'tidak_puas' ? 'selected' : '' }}>😠 Tidak Puas</option>
                <option value="cukup" {{ request('penilaian') == 'cukup' ? 'selected' : '' }}>😐 Cukup</option>
                <option value="puas" {{ request('penilaian') == 'puas' ? 'selected' : '' }}>🙂 Puas</option>
                <option value="sangat_puas" {{ request('penilaian') == 'sangat_puas' ? 'selected' : '' }}>🤩 Sangat Puas</option>
            </select>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter 🔎</button>
            @if(request('nik') || request('penilaian'))
                <a href="{{ route('sengketa.penilaian.index') }}" class="text-sm text-red-600 hover:underline">❌ Reset</a>
            @endif
        </form>
        {{-- Tombol Cetak PDF --}}
        <a href="{{ route('sengketa.penilaian.pdf', request()->query()) }}" target="_blank"
           class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded mb-4">
            🖨️ Cetak PDF
        </a>
        {{-- Toast --}}
        <div 
            x-show="showToast"
            x-transition.duration.500ms
            x-text="toastMessage"
            :class="toastType"
            class="fixed top-6 right-6 px-4 py-3 rounded shadow-lg z-50"
        ></div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 overflow-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 uppercase">
                    <tr>
                        <th class="px-4 py-3 border">Nama Pemohon</th>
                        <th class="px-4 py-3 border">NIK Pemohon</th>
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
                            <td class="px-4 py-3 border text-sm text-gray-800 dark:text-gray-200">
                                @php
                                    $created = \Carbon\Carbon::parse($item->created_at);
                                    $approved = $item->approved_camat_at 
                                                ?? $item->approved_sekcam_at 
                                                ?? $item->verified_at;
                                @endphp

                                @if ($approved)
                                    ⏱️ {{ \Carbon\Carbon::parse($approved)->diffForHumans($created, true) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border">
                                @if ($item->status === 'approved_by_camat')
                                    <span class="text-green-600 font-semibold">Disetujui Camat</span>
                                @else
                                    <span class="text-gray-500">Belum Disetujui</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 border text-sm">
                                @if ($item->status === 'approved_by_camat')
                                    @if (!$item->penilaian)
                                        <button 
                                            @click="openModal({{ $item->id }})"
                                            class="bg-yellow-500 hover:bg-yellow-600 !text-white px-4 py-2 rounded text-sm font-semibold shadow"
                                        >
                                            ✨ Beri Penilaian
                                        </button>
                                    @else
                                        @php
                                            $emoji = ['tidak_puas'=>'😠', 'cukup'=>'😐', 'puas'=>'🙂', 'sangat_puas'=>'🤩'];
                                            $warna = ['tidak_puas'=>'text-red-500', 'cukup'=>'text-yellow-500', 'puas'=>'text-green-500', 'sangat_puas'=>'text-blue-500'];
                                        @endphp
                                        <div class="{{ $warna[$item->penilaian] ?? 'text-gray-500' }} font-semibold">
                                            {{ $emoji[$item->penilaian] ?? '' }} {{ ucfirst(str_replace('_', ' ', $item->penilaian)) }}
                                        </div>
                                        {{-- Saran & Kritik --}}
                                        @if(!empty($item->saran_kritik))
                                            <div class="mt-1 text-sm text-gray-700 dark:text-gray-300 italic">
                                                <span x-data="{showFull: false}">
                                                    <span x-show="!showFull" class="line-clamp-2">{{ $item->saran_kritik }}</span>
                                                    <span x-show="showFull">{{ $item->saran_kritik }}</span>
                                                    <button type="button" @click="showFull = !showFull" class="text-blue-500 hover:underline text-xs ml-1">
                                                        <span x-show="!showFull">Lihat Selengkapnya</span>
                                                        <span x-show="showFull">Tutup</span>
                                                    </button>
                                                </span>
                                            </div>
                                        @endif
                                        <div class="text-sm text-blue-600 dark:text-blue-300 font-semibold mt-1">
                                            📦 Sudah diambil masyarakat
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 dark:text-gray-400 py-10">
                                <div class="text-2xl mb-2">😕</div>
                                <div>Data tidak tersedia atau tidak ditemukan.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $data->links('vendor.pagination.tailwind') }}
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Total: {{ $data->total() }} | Halaman: {{ $data->currentPage() }} dari {{ $data->lastPage() }}
                </p>
            </div>
        </div>
    </main>

    {{-- MODAL --}}
    <div 
        x-show="modalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        style="display: none;"
        @keydown.escape.window="modalOpen = false"
    >
        <div @click.outside="modalOpen = false" class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-sm shadow-lg"
            x-data="{ selectedRating: null, error: false }"
            x-init="$watch('selectedRating', value => error = false)"
        >
            <h2 class="text-lg font-bold mb-4 text-center text-blue-600 dark:text-blue-300">📝 Pilih Penilaian</h2>

            <form 
                method="POST" 
                :action="`/meja-layanan/sengketa/${selectedId}/penilaian`" 
                @submit.prevent="
                    if (!selectedRating) { 
                        error = true; 
                        return; 
                    }
                    $el.submit();
                "
                class="space-y-4"
            >
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    @foreach (['tidak_puas'=>'😠 Tidak Puas', 'cukup'=>'😐 Cukup', 'puas'=>'🙂 Puas', 'sangat_puas'=>'🤩 Sangat Puas'] as $value => $label)
                        <button 
                            type="button" 
                            @click="selectedRating = '{{ $value }}'; error = false"
                            :class="selectedRating === '{{ $value }}' ? 'bg-blue-600 text-white' : 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-100'"
                            class="w-full px-4 py-2 rounded font-semibold transition-colors"
                        >
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <input type="hidden" name="penilaian" :value="selectedRating" required>

                <textarea 
                    name="saran_kritik" 
                    rows="3" 
                    placeholder="💬 Tulis saran dan kritik Anda..."
                    class="w-full p-2 border rounded dark:bg-gray-700 dark:text-white resize-none"
                ></textarea>

                <template x-if="error">
                    <p class="text-red-600 text-sm">⚠️ Silakan pilih penilaian sebelum submit.</p>
                </template>

                <div class="mt-4 text-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-semibold">
                        ✅ Simpan Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
