<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi Penilaian IKM Kecamatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .logo-circle {
            background: linear-gradient(to right, #0ea5e9, #22d3ee);
            width: 150px;
            height: 150px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-cyan-100 to-blue-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md animate-fade-in">
        <div class="flex flex-col items-center mb-6">
            <div class="logo-circle mb-3 shadow">
              <img src="{{asset('images/bupati_wakil.png')}}" alt="bupati dan wakil" width="100%">
            </div>
            <h1 class="text-2xl font-semibold text-gray-800 text-center">
                Login Aplikasi PAK CAMAT SMART
            </h1>
            <p class="text-sm text-gray-500 text-center">Kecamatan Sei Bamban
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 p-3 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" name="password" id="password" required
                       class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="form-checkbox text-blue-600">
                    <span class="ml-2">Ingat saya</span>
                </label>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lupa password?</a>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                Login
            </button>
        </form>

        {{-- <p class="text-center text-sm text-gray-500 mt-6">
            Belum punya akun? <a href="#" class="text-blue-600 hover:underline">Hubungi Admin Kecamatan</a>
        </p> --}}
    </div>

    <!-- Tailwind animation setup -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: 0, transform: 'scale(0.95)' },
                            '100%': { opacity: 1, transform: 'scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

</body>
</html>
