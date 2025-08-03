{{-- Sidebar Kasi Trantib --}}
<div x-data="{ open: false }" class="md:flex">
    {{-- Tombol Toggle Mobile --}}
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

        {{-- Header --}}
        <div class="p-6 border-b bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🛡️ Kasi Trantib</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
            x-data="{
                openSkbd: '{{ request()->routeIs('kasi_trantib.skbd.*') ? 'true' : 'false' }}' === 'true',
                openCatin: '{{ request()->routeIs('catin.tni.*') ? 'true' : 'false' }}' === 'true'
            }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('kasi_trantib.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all
                {{ request()->routeIs('kasi_trantib.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>

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
                <a href="{{ route('kasi_trantib.catin_tni.index') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_trantib.catin_tni.index') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 font-semibold' : 'hover:bg-green-100 hover:text-green-700 dark:hover:bg-green-900/20 dark:hover:text-green-300' }}">
                    📝 Verifikasi SKBD
                </a>
                <a href="{{ route('kasi_trantib.catin_tni.proses') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasi_trantib.catin_tni.proses') ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 font-semibold' : 'hover:bg-green-100 hover:text-green-700 dark:hover:bg-green-900/20 dark:hover:text-green-300' }}">
                    🕓 Riwayat Proses SKBD
                </a>
            </div>

            {{-- Menu SKBD --}}
            <button @click="openSkbd = !openSkbd"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openSkbd ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    📄 Layanan SKBD
                </span>
                <svg :class="{ 'rotate-180': openSkbd }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu SKBD --}}
            <div x-show="openSkbd" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasi_trantib.skbd.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('kasi_trantib.skbd.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi SKBD
                </a>
                <a href="{{ route('kasi_trantib.skbd.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('kasi_trantib.skbd.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🕓 Riwayat Proses SKBD
                </a>
            </div>
        </nav>
    </aside>
</div>
