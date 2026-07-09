<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>SIRAT Admin Login</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-gradient-to-b from-[#050b2e] via-blue-700 to-white min-h-screen">

<div class="min-h-screen flex items-center justify-center p-6">

    <div class="bg-white/90 backdrop-blur p-10 rounded-2xl shadow-xl w-full max-w-[440px] border border-blue-100">

        <h1 class="text-4xl font-bold text-blue-600 mb-2 text-center">
            SIRAT
        </h1>

        <p class="text-gray-400 text-sm mb-1 text-center">
            Sistem Reservasi Ruangan FTE
        </p>

        <p class="text-gray-700 font-semibold mb-1 text-center">
            Masuk sebagai Admin
        </p>

        <p class="text-gray-500 text-sm mb-8 text-center">
            Gunakan email dan password admin Anda
        </p>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email admin"
                class="w-full border border-gray-200 rounded-lg p-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >

            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                class="w-full border border-gray-200 rounded-lg p-3 mb-6 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
            >

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg p-3 font-semibold hover:from-blue-600 hover:to-blue-800 transition">

                Masuk

            </button>
        </form>

        <p class="text-center text-sm text-gray-400 mt-6">
            Dosen? <a href="{{ route('login') }}" class="text-blue-600 font-medium">Masuk di sini</a>
        </p>

    </div>

</div>

</body>

</html>