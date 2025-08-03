<!DOCTYPE html>
<html lang="en" 
      x-data="{ dark: localStorage.getItem('theme') === 'dark' }" 
      :class="{ 'dark': dark }" 
      x-init="$watch('dark', val => localStorage.setItem('theme', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAK CAMAT SMART</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo-camat.png') }}">
    <script>
    if (localStorage.getItem('theme') === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">

<!-- Navbar -->
<nav class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center">
    <div class="flex items-center space-x-3">
        
        <!-- Logo -->
        <img src="{{ asset('images/logo-camat.png') }}" alt="Logo" class="h-8 w-8 rounded-full">

        <!-- Judul -->
        <div class="text-xl font-semibold text-gray-800 dark:text-white">
            PAK CAMAT SMART
        </div>
    </div>

    <div class="flex items-center space-x-4">
        <span class="inline-block text-white text-sm bg-{{ getRoleBadgeColor(auth()->user()->role) }}-600 px-3 py-1 rounded">
            {{ getRoleName(auth()->user()->role) }}
        </span>

        <!-- Tombol Dark Mode Toggle -->
        <button @click="dark = !dark"
            class="text-xs px-3 py-1 rounded bg-gray-300 dark:bg-gray-700 dark:text-white hover:bg-gray-400 dark:hover:bg-gray-600 transition">
            <span x-text="dark ? '☀️ Terang' : '🌙 Gelap'"></span>
        </button>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
        </form>
    </div>
</nav>


    <!-- Main Content -->
    <main class="p-6">
        @yield('content')
    </main>

</body>
</html>
