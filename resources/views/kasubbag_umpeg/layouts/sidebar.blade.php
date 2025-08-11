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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🛡️ Kasubbag Umpeg</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav
            class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
            x-data="{
                openskrisetKKN: '{{ request()->routeIs('kasubbag_umpeg.skrisetKKN.*') ? 'true' : 'false' }}' === 'true',
            }"
        >
            {{-- Dashboard --}}
            <a href="{{ route('kasubbag_umpeg.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all
                {{ request()->routeIs('kasubbag_umpeg.dashboard') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                🏠 Dashboard
            </a>

            {{-- Menu SK Riset KKN --}}
            <button @click="openskrisetKKN = !openskrisetKKN"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openskrisetKKN ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300' : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300'">
                <span class="flex items-center gap-2">
                    📄 Layanan SK Riset KKN
                </span>
                <svg :class="{ 'rotate-180': openskrisetKKN }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu SK Riset KKN --}}
            <div x-show="openskrisetKKN" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('kasubbag_umpeg.skrisetKKN.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('kasubbag_umpeg.skrisetKKN.index') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Verifikasi SK Riset KKN
                </a>
                <a href="{{ route('kasubbag_umpeg.skrisetKKN.proses') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('kasubbag_umpeg.skrisetKKN.proses') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    🕓 Riwayat Proses SK Riset KKN
                </a>
                  <a href="{{ route('kasubbag_umpeg.skrisetKKN.approveByCamat') }}"
                    class="block px-3 py-1 rounded transition-all 
                    {{ request()->routeIs('kasubbag_umpeg.skrisetKKN.approveByCamat') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' : 'hover:bg-blue-100 hover:text-blue-700 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                    📝 Izin Usaha Mikro Approve Camat
                </a>
            </div>
        </nav>
    </aside>
</div>
