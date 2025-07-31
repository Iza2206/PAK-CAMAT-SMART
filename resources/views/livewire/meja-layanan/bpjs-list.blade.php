<div>
    <div class="mb-4 flex justify-between items-center">
        <input type="text" wire:model.debounce.300ms="search"
            class="px-4 py-2 border rounded w-1/3"
            placeholder="🔍 Cari nama pemohon...">
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
        <table class="table-auto w-full text-sm border">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">NIK</th>
                    <th class="px-4 py-2 border">Status</th>
                    <th class="px-4 py-2 border">Dokumen</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-2 border">{{ $item->nama_pemohon }}</td>
                    <td class="px-4 py-2 border">{{ $item->nik_pemohon }}</td>
                    <td class="px-4 py-2 border">{{ ucfirst($item->status) }}</td>
                    <td class="px-4 py-2 border text-xs space-y-1">
                        <a href="{{ asset('storage/'.$item->surat_permohonan) }}" class="text-blue-600 dark:text-blue-400 underline" target="_blank">📄 Surat</a>
                        <a href="{{ asset('storage/'.$item->sktm) }}" class="text-blue-600 dark:text-blue-400 underline" target="_blank">📄 SKTM</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-gray-400 p-4">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4">
            {{ $data->links() }}
        </div>
    </div>
</div>
