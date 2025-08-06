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
                openAgunan: {{ request()->routeIs('sekcam.agunan.*') ? 'true' : 'false' }},
                openSengketa: {{ request()->routeIs('sekcam.sengketa.*') ? 'true' : 'false' }},
                openCatin: {{ request()->routeIs('sekcam.catin-tni.*') ? 'true' : 'false' }},
                openSkbd: {{ request()->routeIs('sekcam.skbd.*') ? 'true' : 'false' }}
                
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
        </nav>
    </aside>
</div>
