<div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">📄 Daftar Pengajuan BPJS</h2>

        <button @click="$dispatch('toggle-dark')" class="bg-gray-200 dark:bg-gray-700 text-sm px-3 py-1 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
            🌗 Toggle Mode
        </button>
    </div>

    {{-- Filter --}}
    <div class="mb-4">
        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter Status</label>
        <select wire:model="filterStatus" class="w-full md:w-64 border px-3 py-2 rounded dark:bg-gray-800 dark:text-white">
            <option value="semua">Semua</option>
            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
            <option value="revisi">Revisi</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm border dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">Nama Pemohon</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Dokumen</th>
                </tr>
            </thead>
            <tbody class="dark:text-white">
                @forelse ($data as $i => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-4 py-2 border">{{ $i + 1 }}</td>
                        <td class="px-4 py-2 border">{{ $item->nama_pemohon }}</td>
                        <td class="px-4 py-2 border">
                            <span class="text-xs px-2 py-1 rounded-full
                                {{ $item->status === 'selesai' ? 'bg-green-200 text-green-800' : ($item->status === 'revisi' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border space-y-1">
                            @foreach (['surat_permohonan', 'sktm', 'kk', 'ktp', 'tanda_lunas_pbb'] as $dok)
                                @if ($item->$dok)
                                    <button wire:click="openModal('{{ $item->$dok }}')" class="block text-blue-600 hover:underline dark:text-blue-400">
                                        📎 {{ strtoupper(str_replace('_', ' ', $dok)) }}
                                    </button>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada pengajuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Preview Dokumen --}}
    @if ($previewUrl)
        <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center" x-data @click.self="$wire.emit('closeModal')">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-4 relative">
                <button class="absolute top-2 right-2 text-gray-500 hover:text-red-500" @click="$wire.emit('closeModal')">✖</button>
                <iframe src="{{ $previewUrl }}" class="w-full h-[500px]" frameborder="0"></iframe>
            </div>
        </div>
    @endif
</div>
