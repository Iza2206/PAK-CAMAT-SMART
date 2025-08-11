{{-- Sidebar Responsif --}}
<div x-data="{ open: false }" class="md:flex min-h-screen">
    {{-- Tombol Toggle di Mobile --}}
    <div class="md:hidden p-4 bg-white dark:bg-gray-800 shadow z-20">
        <button @click="open = !open"
            class="bg-blue-600 text-white px-4 py-2 rounded shadow-md focus:outline-none">
            ☰ Menu
        </button>
    </div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-30 w-64 h-screen 
               bg-gradient-to-b from-white to-gray-50 dark:from-gray-800 dark:to-gray-900
               border-r border-gray-200 dark:border-gray-700 shadow-xl
               transform md:relative md:translate-x-0 transition-transform duration-200 ease-in-out"
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
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">🔒 Manajemen Akun</h2>
        </div>

        {{-- Menu Navigasi --}}
        <nav class="p-4 text-sm text-gray-700 dark:text-gray-300 font-medium space-y-2"
            x-data="{ openAdmin: '{{ request()->routeIs('admin.accounts.*') ? 'true' : 'false' }}' === 'true' }">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg transition-all
                {{ request()->routeIs('admin.dashboard') 
                    ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 font-semibold' 
                    : 'hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-900/20 dark:hover:text-blue-300' }}">
                📊 Dashboard
            </a>

            {{-- Dropdown Menu Admin --}}
            <button @click="openAdmin = !openAdmin"
                class="flex items-center justify-between w-full px-4 py-2 rounded-lg transition-all"
                :class="openAdmin 
                    ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-300' 
                    : 'hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-300'">
                <span class="flex items-center gap-2">
                    📂 Menu Admin
                </span>
                <svg :class="{ 'rotate-180': openAdmin }" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Submenu --}}
            <div x-show="openAdmin" x-collapse class="ml-6 space-y-1 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.accounts.index') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('admin.accounts.index') 
                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 font-semibold' 
                        : 'hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-300' }}">
                    👥 Kelola Akun
                </a>
                <a href="{{ route('admin.accounts.create') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('admin.accounts.create') 
                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 font-semibold' 
                        : 'hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-300' }}">
                    ➕ Tambah Akun
                </a>
                <a href="{{ route('admin.accounts.export') }}"
                    class="block px-3 py-1 rounded transition-all
                    {{ request()->routeIs('admin.accounts.export') 
                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 font-semibold' 
                        : 'hover:bg-red-100 hover:text-red-700 dark:hover:bg-red-900/20 dark:hover:text-red-300' }}">
                    📤 Export Akun
                </a>
            </div>
        </nav>
    </aside>

    {{-- Konten Utama --}}
    <main class="flex-1 p-6 bg-gray-100 dark:bg-gray-900">
        {{-- Isi konten halaman di sini --}}
    </main>
</div>
