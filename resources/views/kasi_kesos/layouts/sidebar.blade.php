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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🗂️ Kasi Kesos</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav 
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
          x-data="{
        openBpjs: '{{ request()->routeIs('bpjs.*') ? 'true' : 'false' }}' === 'true',
        openDispenNikah: '{{ request()->routeIs('dispencatin.*') ? 'true' : 'false' }}' === 'true',
        openSktm: '{{ request()->routeIs('SKTMs.*') ? 'true' : 'false' }}' === 'true',
        openiumk: '{{ request()->routeIs('iumk.*') ? 'true' : 'false' }}' === 'true'
    }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('kasi_kesos.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all 
                {{ request()->routeIs('kasi_kesos.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>

            {{-- BPJS Menu --}}
            <button @click="openBpjs = !openBpjs"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openBpjs ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                    BPJS, Narkoba & KIP
                </span>
                <svg :class="{ 'rotate-180': openBpjs }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu BPJS --}}
            <div x-show="openBpjs" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasi_kesos.bpjs.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.bpjs.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi BPJS
                </a>
                <a href="{{ route('kasi_kesos.bpjs.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.bpjs.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses BPJS
                </a>
            </div>

             {{-- Dispensasi Nikah Menu --}}
           <button @click="openDispenNikah = !openDispenNikah"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openDispenNikah ? 'bg-blu e-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                   Dispensasi Nikah
                </span>
                <svg :class="{ 'rotate-180': openDispenNikah }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu Dispensasi Nikah --}}
            <div x-show="openDispenNikah"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasi_kesos.dispencatin.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.dispencatin.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Dispensasi Nikah
                </a>
                <a href="{{ route('kasi_kesos.dispencatin.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.dispencatin.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses Dispensasi Nikah
                </a>
                <a href="{{ route('kasi_kesos.dispencatin.approveByCamat') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.dispencatin.approveByCamat') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Dispensasi Nikah Approve Camat
                </a>
            </div>    
                
            
            {{-- SKTM Menu --}}
           <button @click="openSktm = !openSktm"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSktm ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400 dark:text-blue-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                   SKTM Dispen Cerai
                </span>
                <svg :class="{ 'rotate-180': openSktm }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu SKTM --}}
            <div x-show="openSktm"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasi_kesos.sktm.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.sktm.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi SKTM
                </a>
                <a href="{{ route('kasi_kesos.sktm.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.sktm.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses SKTM
                </a>
            </div> 
            
                         {{-- IUMK Menu --}}
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

            {{-- Submenu IUMK --}}
            <div x-show="openiumk"  x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasi_kesos.iumk.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.iumk.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi Izin Usaha Mikro
                </a>
                <a href="{{ route('kasi_kesos.iumk.proses') }}" 
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.iumk.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 7l4 0 2-2h6l2 2h4M4 17h6m-6-4h10m-6 8h6M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Riwayat Proses Izin Usaha Mikro
                </a>
                <a href="{{ route('kasi_kesos.iumk.approveByCamat') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_kesos.iumk.approveByCamat') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Izin Usaha Mikro Approve Camat
                </a>
            </div>  
        </nav>
    </aside>
</div>
