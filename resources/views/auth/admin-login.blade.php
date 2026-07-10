<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <title>SIRAT Admin Login</title>

    @vite(['resources/css/app.css'])

</head>

<body class="bg-gradient-to-b from-[#050b2e] via-blue-700 to-white min-h-screen relative">

<!-- Logo FTE -->
<div class="absolute top-4 left-4 lg:top-6 lg:left-6 bg-white py-2 px-3 lg:px-4 rounded-xl shadow-md border border-gray-100 flex items-center justify-center z-50">
    <img src="{{ asset('logo-fte.png') }}" alt="Logo Fakultas Teknik Elektro Telkom University" class="h-8 lg:h-10 w-auto">
</div>

<div class="min-h-screen flex items-center justify-center p-6">

    <div class="flex flex-col gap-4 w-full max-w-[440px]">
        <div class="bg-white/90 backdrop-blur p-10 rounded-2xl shadow-xl border border-blue-100">

            <h1 class="text-4xl font-bold text-blue-600 mb-2 text-center">
                SIRAT
            </h1>

            <p class="text-gray-400 text-sm mb-1 text-center">
                Sistem Reservasi Ruangan Fakultas Teknik Elektro
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

        </div>

        <!-- Kontak LAA FTE -->
        <div class="bg-white/90 backdrop-blur p-4 rounded-2xl shadow-xl border border-blue-100 text-center">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kontak LAA FTE</p>
            <div class="flex flex-col gap-1.5 text-sm text-gray-700">
                <div class="flex items-center justify-center gap-2">
                    <!-- Phone Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="font-medium">0812-2425-3349</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <!-- Email Icon -->
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <a href="mailto:laa.fte@telkomuniversity.ac.id" class="text-blue-600 hover:underline">laa.fte@telkomuniversity.ac.id</a>
                </div>
            </div>
        </div>
    </div>

</div>

</body>

</html>