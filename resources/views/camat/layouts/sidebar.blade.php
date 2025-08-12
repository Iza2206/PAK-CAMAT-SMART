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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🏛️ Camat</h2>
        </div>

        {{-- Menu Navigasi --}}
       <nav
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
            x-data="{
                openBpjs: {{ request()->routeIs('camat.bpjs.*') ? 'true' : 'false' }},
                openDispenNikah: {{ request()->routeIs('camat.dispensasi.*') ? 'true' : 'false' }},
                openSktm: {{ request()->routeIs('camat.sktm.*') ? 'true' : 'false' }},
                openSkt: {{ request()->routeIs('camat.skt.*') ? 'true' : 'false' }},
                openAhliWaris: {{ request()->routeIs('camat.ahliwaris.*') ? 'true' : 'false' }},
                openSppatGr: {{ request()->routeIs('camat.sppatgr.*') ? 'true' : 'false' }},
                openAgunan: {{ request()->routeIs('camat.agunan.*') ? 'true' : 'false' }},
                openSengketa: {{ request()->routeIs('camat.silang_sengketa.*') ? 'true' : 'false' }},
                openCatin: {{ request()->routeIs('camat.catin.*') ? 'true' : 'false' }},
                openSkbd: {{ request()->routeIs('camat.skbd.*') ? 'true' : 'false' }},
                
                 openiumk: {{ request()->routeIs('camat.iumk.*') ? 'true' : 'false' }},
            }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('camat.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all
                {{ request()->routeIs('camat.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>

            {{-- BPJS Menu --}}
            <button @click="openBpjs = !openBpjs"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openBpjs ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    🩺
                    <span>BPJS, Narkoba & KIP</span>
                </span>
                <svg :class="{ 'rotate-180': openBpjs }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openBpjs" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.bpjs.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.bpjs.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi BPJS
                </a>
                <a href="{{ route('camat.bpjs.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.bpjs.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses BPJS
                </a>
            </div>

            {{-- Dispensasi Nikah Menu --}}
            <div x-data="{ openDispenNikah: {{ request()->routeIs('camat.dispencatin.*') ? 'true' : 'false' }} }">
                <button @click="openDispenNikah = !openDispenNikah"
                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                    :class="openDispenNikah ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                    <span class="flex items-center gap-2">
                        👫
                        <span>Dispensasi Nikah</span>
                    </span>
                    <svg :class="{ 'rotate-180': openDispenNikah }" class="w-4 h-4 transform transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Submenu Dispensasi Nikah --}}
                <div x-show="openDispenNikah" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('camat.dispencatin.index') }}"
                        class="block px-3 py-1 rounded transition-all
                        {{ request()->routeIs('camat.dispencatin.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                        📝 Verifikasi Dispensasi Nikah
                    </a>
                    <a href="{{ route('camat.dispencatin.proses') }}"
                        class="block px-3 py-1 rounded transition-all
                        {{ request()->routeIs('camat.dispencatin.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                        🗂️ Riwayat Proses Dispensasi Nikah
                    </a>
                </div>
            </div>


            {{-- SKTM Menu --}}
            <button @click="openSktm = !openSktm"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSktm ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    👩‍⚖️
                    <span>SKTM Dispen Cerai</span>
                </span>
                <svg :class="{ 'rotate-180': openSktm }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSktm" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.sktm.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.sktm.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi SKTM
                </a>
                <a href="{{ route('camat.sktm.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.sktm.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses SKTM
                </a>
            </div>

            {{-- SKT Menu --}}
            <button @click="openSkt = !openSkt"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkt ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    🧾
                    <span>SK Tanah (SKT)</span>
                </span>
                <svg :class="{ 'rotate-180': openSkt }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSkt" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.skt.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.skt.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi SKT
                </a>
                <a href="{{ route('camat.skt.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.skt.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses SKT
                </a>
            </div>

             {{-- SPPAT-GR --}}
            <button @click="openSppatGr = !openSppatGr"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSppatGr ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    ✍️
                    <span>SPPAT-GR</span>
                </span>
                <svg :class="{ 'rotate-180': openSppatGr }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSppatGr" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.sppatgr.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.sppatgr.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi SPPAT-GR
                </a>
                <a href="{{ route('camat.sppatgr.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.sppatgr.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses SPPAT-GR
                </a>
            </div>

             {{-- AHLI WARIS --}}
            <button @click="openAhliWaris = !openAhliWaris"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAhliWaris ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    👨‍👩‍👧‍👦
                    <span>Ahli Waris</span>
                </span>
                <svg :class="{ 'rotate-180': openAhliWaris }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openAhliWaris" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.ahliwaris.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.ahliwaris.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi AhliWaris
                </a>
                <a href="{{ route('camat.ahliwaris.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.ahliwaris.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses SKT
                </a>
            </div>

             {{-- Menu Agunan Bank --}}
            <button @click="openAgunan = !openAgunan"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAgunan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    🏦
                    <span>Agunan Bank</span>
                </span>
                <svg :class="{ 'rotate-180': openAgunan }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openAgunan" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.agunan.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.agunan.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi Agunan Bank
                </a>
                <a href="{{ route('camat.agunan.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.agunan.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Agunan Bank
                </a>
            </div>


            {{-- Silang sengketa Menu --}}
            <button @click="openSengketa = !openSengketa"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSengketa ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    ⚖️
                    <span>Silang Sengketa</span>
                </span>
                <svg :class="{ 'rotate-180': openSengketa }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSengketa" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.silang_sengketa.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.silang_sengketa.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi Silang Sengketa
                </a>
                <a href="{{ route('camat.silang_sengketa.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.silang_sengketa.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Silang Sengketa
                </a>
            </div>

            {{-- CATIN Menu --}}
            <button @click="openCatin = !openCatin"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openCatin ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    👩‍❤️‍👨
                    <span>Catin Sipil, TNI/POLRI</span>
                </span>
                <svg :class="{ 'rotate-180': openCatin }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openCatin" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.catin.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.catin.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi catin
                </a>
                <a href="{{ route('camat.catin.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.catin.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses catin
                </a>
            </div>

            {{-- SKBD Menu --}}
            <button @click="openSkbd = !openSkbd"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkbd ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    🧼
                    <span>SK Bersih Diri (SKBD)</span>
                </span>
                <svg :class="{ 'rotate-180': openSkbd }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSkbd" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.skbd.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.skbd.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📋 Verifikasi SKBD
                </a>
                <a href="{{ route('camat.skbd.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('camat.skbd.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🗂️ Riwayat Proses SKBD
                </a>
            </div>  

            {{-- Izin Usaha Mikro --}}
           <button @click="openiumk = !openiumk"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openiumk ? 'bg-blu e-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                  Izin Usaha Mikro
                </span>
                <svg :class="{ 'rotate-180': openiumk }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu Izin Usaha Mikro --}}
            <div x-show="openiumk"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('camat.iumk.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('camat.iumk.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Izin Usaha Mikro
                </a>
                <a href="{{ route('camat.iumk.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('camat.iumk.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses Izin Usaha Mikro
                </a>
            </div>  
           {{-- Pengaturan Akun --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                <a href="{{ route('camat.account.edit') }}" 
                class="block px-4 py-2 text-gray-700 hover:bg-blue-100 hover:text-blue-700 rounded transition duration-150 font-semibold">
                    🛠️ Manajemen Akun
                </a>
            </div>
        </nav>
    </aside>
</div>
