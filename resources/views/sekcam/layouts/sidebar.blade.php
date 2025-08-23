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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🗂️ Sekcam</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav 
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
            x-data="{
                openBpjs: {{ request()->routeIs('sekcam.bpjs.*') ? 'true' : 'false' }},
                openSktm: {{ request()->routeIs('sekcam.sktm.*') ? 'true' : 'false' }},
                openSkt: {{ request()->routeIs('sekcam.skt.*') ? 'true' : 'false' }},
                openSppatgr: {{ request()->routeIs('sekcam.sppatgr.*') ? 'true' : 'false' }},
                openahliwaris: {{ request()->routeIs('sekcam.ahliwaris.*') ? 'true' : 'false' }},
                openAgunan: {{ request()->routeIs('sekcam.agunan.*') ? 'true' : 'false' }},
                openSengketa: {{ request()->routeIs('sekcam.sengketa.*') ? 'true' : 'false' }},
                openCatin: {{ request()->routeIs('sekcam.catin-tni.*') ? 'true' : 'false' }},
                openSkbd: {{ request()->routeIs('sekcam.skbd.*') ? 'true' : 'false' }},
                openDispenNikah: {{ request()->routeIs('sekcam.skbd.*') ? 'true' : 'false' }},
                openskrisetKKN: '{{ request()->routeIs('skrisetKKN.*') ? 'true' : 'false' }}' === 'true',
                openIumk: {{ request()->routeIs('sekcam.iumk.*') ? 'true' : 'false' }},
                
            }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('sekcam.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all 
                {{ request()->routeIs('sekcam.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>

            {{-- === BPJS === --}}
            <button @click="openBpjs = !openBpjs"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openBpjs ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">🩺 BPJS, Narkoba & KIP</span>
                <svg :class="{ 'rotate-180': openBpjs }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openBpjs" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.bpjs.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.bpjs.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi BPJS</a>
                <a href="{{ route('sekcam.bpjs.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.bpjs.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses BPJS</a>
            </div>
            
             {{-- Dispensasi Nikah Menu --}}
           <button @click="openDispenNikah = !openDispenNikah"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openDispenNikah ? 'bg-blu e-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    👫 Dispensasi Nikah
                </span>
                <svg :class="{ 'rotate-180': openDispenNikah }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu Dispensasi Nikah --}}
            <div x-show="openDispenNikah"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('sekcam.dispencatin.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.dispencatin.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Dispensasi Nikah
                </a>
                <a href="{{ route('sekcam.dispencatin.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.dispencatin.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses Dispensasi Nikah
                </a>
            </div>  

            {{-- === SKTM === --}}
            <button @click="openSktm = !openSktm"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSktm ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">📄 SKTM Dispen Cerai</span>
                <svg :class="{ 'rotate-180': openSktm }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSktm" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.sktm.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.sktm.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi SKTM</a>
                <a href="{{ route('sekcam.sktm.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.sktm.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses SKTM</a>
            </div>

            {{-- === SKT === --}}
            <button @click="openSkt = !openSkt"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkt ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">🌍 SK Tanah (SKT)</span>
                <svg :class="{ 'rotate-180': openSkt }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSkt" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.skt.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.skt.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi SKT</a>
                <a href="{{ route('sekcam.skt.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.skt.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses SKT</a>
            </div>

             {{-- === SPPAT-GR === --}}
            <button @click="openSppatgr = !openSppatgr"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSppatgr ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">✍️ SPPAT-GR</span>
                <svg :class="{ 'rotate-180': openSppatgr }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSppatgr" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.sppatgr.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.sppatgr.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi SPPAT-GR</a>
                <a href="{{ route('sekcam.sppatgr.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.sppatgr.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses SPPAT-GR</a>
            </div>

            {{-- === Ahli Waris === --}}
            <button @click="openahliwaris = !openahliwaris"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openahliwaris ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">👨‍👩‍👧‍👦 Ahli Waris</span>
                <svg :class="{ 'rotate-180': openahliwaris }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openahliwaris" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.ahliwaris.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.ahliwaris.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi Ahli Waris</a>
                <a href="{{ route('sekcam.ahliwaris.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.ahliwaris.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses Ahli Waris</a>
            </div>

            {{-- === Agunan Bank === --}}
            <button @click="openAgunan = !openAgunan"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAgunan ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">🏦 Agunan Bank</span>
                <svg :class="{ 'rotate-180': openAgunan }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openAgunan" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.agunan.index') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.agunan.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Agunan Bank
                </a>
                <a href="{{ route('sekcam.agunan.proses') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.agunan.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📂 Riwayat Agunan Bank
                </a>
            </div>

            {{-- === Silang Sengketa === --}}
            <button @click="openSengketa = !openSengketa"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSengketa ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">⚖️ Silang Sengketa</span>
                <svg :class="{ 'rotate-180': openSengketa }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSengketa" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.silang_sengketa.index') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.silang_sengketa.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Silang Sengketa
                </a>
                <a href="{{ route('sekcam.silang_sengketa.proses') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.silang_sengketa.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📂 Riwayat Silang Sengketa
                </a>
            </div>

            {{-- === CATIN TNI/POLRI === --}}
            <button @click="openCatin = !openCatin"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openCatin ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">❤️ Catin TNI/POLRI</span>
                <svg :class="{ 'rotate-180': openCatin }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openCatin" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.catin-tni.index') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.catin-tni.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Catin TNI/POLRI
                </a>
                <a href="{{ route('sekcam.catin-tni.proses') }}"
                    class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.catin-tni.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">
                    📂 Riwayat Proses Catin TNI/POLRI
                </a>
            </div>

            {{-- === SKBD === --}}
            <button @click="openSkbd = !openSkbd"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkbd ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">🧼 SK Bersih Diri (SKBD)</span>
                <svg :class="{ 'rotate-180': openSkbd }" class="w-4 h-4 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="openSkbd" x-collapse class="ml-6 space-y-1">
                <a href="{{ route('sekcam.skbd.index') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.skbd.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📝 Verifikasi SKBD</a>
                <a href="{{ route('sekcam.skbd.proses') }}" class="block px-3 py-1 rounded transition-all {{ request()->routeIs('sekcam.skbd.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 dark:hover:bg-blue-900/20 hover:text-blue-700 dark:hover:text-blue-300' }}">📁 Riwayat Proses SKBD</a>
            </div>

           
            
            {{-- IUMK --}}
           <button @click="openIumk = !openIumk"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openIumk ? 'bg-blu e-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                   Izin Usaha Mikro
                </span>
                <svg :class="{ 'rotate-180': openIumk }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu Izin Usaha Mikro --}}
            <div x-show="openIumk"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('sekcam.iumk.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.iumk.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Izin Usaha Mikro
                </a>
                <a href="{{ route('sekcam.iumk.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.iumk.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses Izin Usaha Mikro
                </a>
            </div>

            {{-- SK RISET KKN --}}
            <button @click="openskrisetKKN = !openskrisetKKN"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openskrisetKKN ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-300' : 'hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900/20 dark:hover:text-green-300'">
                <span class="flex items-center gap-2">
                    🏪 SK Riset KKN
                </span>
                <svg :class="{ 'rotate-180': openskrisetKKN }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="openskrisetKKN" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('sekcam.skrisetKKN.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.skrisetKKN.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi SK Riset KKN
                </a>
                <a href="{{ route('sekcam.skrisetKKN.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('sekcam.skrisetKKN.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses SK Riset KKN
                </a>
            </div>
        </nav>
    </aside>
</div>
