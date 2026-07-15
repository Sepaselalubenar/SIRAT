<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT - Masuk</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[#070b1e] text-gray-100 min-h-screen flex items-center justify-center p-4 md:p-8 relative font-sans select-none overflow-y-auto">

    <!-- Top-Left Faculty Logo -->
    <div class="absolute top-6 left-6 md:top-8 md:left-8 z-20">
        <img src="{{ asset('logo-fte.png') }}" alt="Logo FTE" class="h-12 md:h-14 w-auto bg-white/95 py-2 px-4 rounded-xl shadow-md border border-white/20">
    </div>

    <!-- Fixed Background Image and Overlay -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat pointer-events-none" style="background-image: url('{{ asset('bg-login.jpg') }}')">
        <!-- Dark Overlay & Subtle Blur -->
        <div class="absolute inset-0 bg-[#070b1e]/70 backdrop-blur-[3px]"></div>
        
        <!-- Ambient Glowing Lights -->
        <div class="absolute top-[-20%] left-[-15%] w-[60%] h-[70%] rounded-full bg-blue-600/10 blur-[130px]"></div>
        <div class="absolute bottom-[-20%] right-[-15%] w-[60%] h-[70%] rounded-full bg-indigo-500/10 blur-[130px]"></div>
    </div>

    <!-- Main Container -->
    <div class="relative w-full max-w-md bg-[#0d1230]/40 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden shadow-2xl p-8 sm:p-12 z-10 my-auto flex flex-col justify-center">

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-white tracking-tight text-center">Selamat Datang di SIRAT</h2>
            <p class="text-sm text-gray-400 mt-2 text-center">Gunakan NIP dan email Tel-U Anda untuk masuk.</p>
        </div>

        <!-- Errors Alert -->
        @if ($errors->any())
            <div class="bg-red-500/10 text-red-400 text-xs rounded-xl p-3.5 mb-6 border border-red-500/20">
                <div class="flex gap-2">
                    <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <!-- NIP Input -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                        <!-- Badge/User Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0m-4 0a2 2 0 004 0" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        name="nip"
                        value="{{ old('nip') }}"
                        placeholder="Masukkan NIP Anda"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-base text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required
                        autofocus
                    >
                </div>
            </div>

            <!-- Email Input -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-500">
                        <!-- Envelope Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email Tel-U Anda"
                        class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-11 pr-4 text-base text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        required
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white rounded-xl font-bold text-base shadow-lg shadow-blue-500/20 hover:shadow-blue-500/30 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                Masuk ke Dashboard
            </button>
        </form>

        <!-- Contact LAA FTE Footer Inside Form -->
        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2.5">Butuh Bantuan? Kontak LAA FTE</p>
            <div class="flex flex-col gap-2 text-sm text-gray-400">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="font-medium">0812-2425-3349</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <a href="mailto:laa.fte@telkomuniversity.ac.id" class="text-blue-400 hover:underline">laa.fte@telkomuniversity.ac.id</a>
                </div>
            </div>
        </div>

        <!-- Footer
        <div class="mt-8 text-center text-[10px] text-gray-500">
            Sistem Informasi Ruangan FTE &copy; {{ date('Y') }}
        </div> -->

    </div>

</body>
</html>