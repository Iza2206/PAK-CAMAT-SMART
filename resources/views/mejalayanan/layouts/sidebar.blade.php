{{-- Sidebar Responsif --}}
<div x-data="{ open: false }" class="md:flex">
    {{-- Tombol Toggle di Mobile --}}
    <div class="md:hidden p-4 bg-white dark:bg-gray-800 shadow z-20">
        <button @click="open = !open"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow-md focus:outline-none">
            ☰ Menu
        </button>
    </div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200 dark:border-gray-700 shadow-xl transform md:relative md:translate-x-0 transition-transform duration-200 ease-in-out"
        :class="{ '-translate-x-full': !open }"
    >
        {{-- Tombol Close Mobile --}}
        <div class="md:hidden flex justify-end p-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <button @click="open = false"
                class="text-gray-600 dark:text-gray-300 hover:text-red-600 transition">
                ✖ Close
            </button>
        </div>

        {{-- Header Sidebar --}}
        <div class="p-6 border-b bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🗂️ Meja Layanan</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
             x-data="{
                        openBpjs: '{{ request()->routeIs('bpjs.*') ? 'true' : 'false' }}' === 'true',
                        openSktm: '{{ request()->routeIs('SKTMs.*') ? 'true' : 'false' }}' === 'true',
                        openSkbd: '{{ request()->routeIs('SKBDs.*') ? 'true' : 'false' }}' === 'true',
                        openCatin: '{{ request()->routeIs('catin.tni.*') ? 'true' : 'false' }}' === 'true',
                        openSengketa: '{{ request()->routeIs('sengketa.*') ? 'true' : 'false' }}' === 'true',
                        openAgunan: '{{ request()->routeIs('agunan.*') ? 'true' : 'false' }}' === 'true',
                        openAhliwaris: '{{ request()->routeIs('ahliwaris.*') ? 'true' : 'false' }}' === 'true',
                        openSppatgr: '{{ request()->routeIs('sppat_gr.*') ? 'true' : 'false' }}' === 'true',
                        openSkt: '{{ request()->routeIs('skt.*') ? 'true' : 'false' }}' === 'true'
                    }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('meja.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all
                {{ request()->routeIs('meja.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>
            {{-- BPJS --}}
            <button @click="openBpjs = !openBpjs"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openBpjs ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-300' : 'hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/20 dark:hover:text-green-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 21h18M5 21V9a2 2 0 012-2h10a2 2 0 012 2v12M9 21V9m6 12V9M10 13h4m-2-2v4" />
                    </svg>
                    BPJS, Narkoba & KIP
                </span>
                <svg :class="{ 'rotate-180': openBpjs }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openBpjs" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('bpjs.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('bpjs.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('bpjs.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('bpjs.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List Data
                </a>
                <a href="{{ route('bpjs.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('bpjs.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian
                </a>
            </div>

            {{-- IUMK --}}
            <a href="{{ route('layanan.iumk') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300">
                🏢 Izin Usaha Mikro (IUMK)
            </a>

            {{-- sktm Dispensasi Nikah --}}
                <button @click="openSktm = !openSktm"
                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                    :class="openSktm ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6M9 4h6m2 0a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2" />
                        </svg>
                        SKTM Dispensasi Cerai
                    </span>
                    <svg :class="{ 'rotate-180': openSktm }" class="w-4 h-4 transform transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="openSktm" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('SKTMs.create') }}"
                        class="block px-3 py-1 rounded transition-all
                        {{ request()->routeIs('SKTMs.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                        ➕ Tambah Data
                    </a>
                    <a href="{{ route('SKTMs.list') }}"
                        class="block px-3 py-1 rounded transition-all
                        {{ request()->routeIs('SKTMs.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                        📄 List Data
                    </a>
                    <a href="{{ route('sktm.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sktm.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian
                    </a>
                </div>

            {{-- Surat Keterangan Tanah (SKT) --}}
            <button @click="openSkt = !openSkt"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkt ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    SK Tanah
                </span>
                <svg :class="{ 'rotate-180': openSkt }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openSkt" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('skt.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('skt.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Registrasi SKT
                </a>
                <a href="{{ route('skt.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('skt.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List SKT
                </a>
                <a href="{{ route('skt.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('skt.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian SK Tanah
                </a>
            </div>

            {{-- SPPAT-GR --}}
            <button @click="openSppatgr = !openSppatgr"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSppatgr ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    SPPAT-GR
                </span>
                <svg :class="{ 'rotate-180': openSppatgr }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openSppatgr" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('sppat_gr.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sppat_gr.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Registrasi SPPAT-GR
                </a>
                <a href="{{ route('sppat_gr.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sppat_gr.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List SPPAT-GR
                </a>
            </div>

            {{-- Ahli Waris --}}
            <button @click="openAhliwaris = !openAhliwaris"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAhliwaris ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Ahli Waris
                </span>
                <svg :class="{ 'rotate-180': openAhliwaris }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openAhliwaris" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('ahliwaris.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('ahliwaris.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('ahliwaris.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('ahliwaris.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List Data
                </a>
            </div>


            {{-- Agunan ke Bank --}}
            <button @click="openAgunan = !openAgunan"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAgunan ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                    </svg>
                    Agunan ke Bank
                </span>
                <svg :class="{ 'rotate-180': openAgunan }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openAgunan" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('agunan.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('agunan.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('agunan.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('agunan.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List Data
                </a>
            </div>


            {{-- Silang Sengketa --}}
            <button @click="openSengketa = !openSengketa"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSengketa ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500 dark:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.382V5.618a2 2 0 011.553-1.948L9 2m0 0l6 3m-6-3v18m6-15l5.447 2.724A2 2 0 0121 8.618v9.764a2 2 0 01-1.553 1.948L15 22m0 0V4" />
                    </svg>
                    Surat Silang Sengketa
                </span>
                <svg :class="{ 'rotate-180': openSengketa }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openSengketa" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('sengketa.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sengketa.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('sengketa.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sengketa.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List Data
                </a>
                <a href="{{ route('sengketa.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('sengketa.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian
                </a>
            </div>


            {{-- Catin TNI/POLRI --}}
            <button @click="openCatin = !openCatin"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openCatin ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-300' : 'hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/20 dark:hover:text-green-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-pink-400 dark:text-pink-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                    </svg>
                    Catin TNI/POLRI
                </span>
                <svg :class="{ 'rotate-180': openCatin }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openCatin" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('catin.tni.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('catin.tni.create') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 font-semibold' : 'hover:bg-green-100 hover:text-green-700 dark:hover:bg-green-900/20 dark:hover:text-green-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('catin.tni.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('catin.tni.list') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 font-semibold' : 'hover:bg-green-100 hover:text-green-700 dark:hover:bg-green-900/20 dark:hover:text-green-300' }}">
                    📄 List Data
                </a>
                <a href="{{ route('catins.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('catins.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian
                </a>
            </div>


            {{-- SKBD --}}
            <button @click="openSkbd = !openSkbd"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkbd ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    🧼 SK Bersih Diri (SKBD)
                </span>
                <svg :class="{ 'rotate-180': openSkbd }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openSkbd" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('SKBDs.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('SKBDs.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    ➕ Tambah Data
                </a>
                <a href="{{ route('SKBDs.list') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('SKBDs.list') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📄 List Data
                </a>
                <a href="{{ route('SKBDs.penilaian.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('SKBDs.penilaian.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Penilaian
                </a>
            </div>

            {{-- Export --}}
            <a href="{{ route('admin.export') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300">
                📤 Export Laporan IKM
            </a>
        </nav>
    </aside>
</div>
