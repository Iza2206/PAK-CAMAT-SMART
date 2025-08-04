<div x-data="liveFilter()" x-init="search()" class="mb-6">
    <div class="flex flex-wrap gap-3 items-center">
        <input type="text" x-model="nik" @input.debounce.500ms="search"
            placeholder="🔍 Cari NIK"
            class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">

        <select x-model="status" @change="search"
            class="px-4 py-2 border rounded dark:bg-gray-800 dark:text-white">
            <option value="">🧾 Semua Status</option>
            <option value="diajukan">Diajukan</option>
            <option value="checked_by_kasi">Diproses Kasi</option>
            <option value="approved_by_camat">Disetujui Camat</option>
            <option value="rejected_by_sekcam">Ditolak Sekcam</option>
            <option value="rejected_by_camat">Ditolak Camat</option>
        </select>
    </div>

    <div class="mt-4">
        <template x-if="loading">
            <p class="text-gray-500 italic">Memuat...</p>
        </template>

        <template x-if="results.length === 0 && !loading">
            <p class="text-gray-500 italic">Tidak ada data ditemukan.</p>
        </template>

        <template x-for="(item, index) in results" :key="item.id">
            <div class="p-3 my-2 border rounded bg-white dark:bg-gray-800">
                <div><strong>NIK:</strong> <span x-text="item.nik_pemohon"></span></div>
                <div><strong>Nama:</strong> <span x-text="item.nama"></span></div>
                <div><strong>Status:</strong> <span x-text="item.status"></span></div>
            </div>
        </template>
    </div>
</div>

<script>
    function liveFilter() {
        return {
            nik: '',
            status: '',
            results: [],
            loading: false,
            async search() {
                this.loading = true;
                try {
                    const response = await fetch(`{{ route('bpjs.list') }}?ajax=1&nik=${this.nik}&status=${this.status}`);
                    const data = await response.json();
                    this.results = data;
                } catch (e) {
                    console.error("Gagal fetch:", e);
                }
                this.loading = false;
            }
        }
    }
</script>
