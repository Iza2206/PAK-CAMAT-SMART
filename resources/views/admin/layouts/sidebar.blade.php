<!-- resources/views/admin/layouts/sidebar.blade.php -->

<aside class="w-64 h-screen bg-gray-800 text-white fixed">
    <div class="p-4 text-xl font-bold border-b border-gray-700">
        Admin Panel
    </div>

    <nav class="mt-4 px-2 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
            🏠 Dashboard
        </a>

        <!-- Manajemen Akun -->
        <a href="{{ route('admin.accounts.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.accounts.*') ? 'bg-gray-700' : '' }}">
            👥 Manajemen Akun
        </a>

        <!-- Tambah Akun -->
        <a href="{{ route('admin.accounts.create') }}" class="block px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.accounts.create') ? 'bg-gray-700' : '' }}">
            ➕ Tambah Akun
        </a>

        <!-- Export Akun -->
        <a href="{{ route('admin.accounts.export') }}" class="block px-4 py-2 rounded hover:bg-gray-700">
            📤 Export Akun
        </a>
    </nav>
</aside>
