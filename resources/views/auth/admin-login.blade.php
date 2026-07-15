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

<!-- Top-Right Guide Button -->
<div class="absolute top-4 right-4 lg:top-6 lg:right-6 z-50">
    <button type="button" onclick="openGuideModal()" class="flex items-center gap-2 bg-white/95 hover:bg-white text-blue-700 font-bold py-2 px-3 lg:px-4 rounded-xl shadow-md border border-gray-100 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer text-sm">
        <!-- Book Open Icon -->
        <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        Buku Panduan
    </button>
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

<!-- Guide Modal -->
<div id="guideModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 md:p-6 opacity-0 pointer-events-none transition-all duration-300 ease-out hidden">
    <!-- Backdrop overlay -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300" onclick="closeGuideModal()"></div>
    
    <!-- Modal Content Card -->
    <div class="relative bg-white w-full max-w-4xl max-h-[85vh] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-slate-200/80 transform scale-95 transition-all duration-300 ease-out z-10">
        <!-- Sticky Header -->
        <div class="sticky top-0 bg-slate-50 border-b border-slate-200/80 px-6 py-4 flex items-center justify-between z-20">
            <div class="flex items-center gap-3">
                <div class="bg-blue-100/80 p-2 rounded-lg text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Buku Panduan Pengguna (Sisi Administrator)</h3>
            </div>
            <button type="button" onclick="closeGuideModal()" class="text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 p-2 rounded-lg transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Scrollable Body with Markdown Styled HTML -->
        <div class="overflow-y-auto p-6 md:p-8 flex-1 markdown-content bg-white select-text">
            {!! $guideHtml !!}
        </div>

        <!-- Sticky Footer -->
        <div class="sticky bottom-0 bg-slate-50 border-t border-slate-200/80 px-6 py-4 flex items-center justify-end z-20">
            <button type="button" onclick="closeGuideModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-sm shadow-sm transition-all cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function openGuideModal() {
        const modal = document.getElementById('guideModal');
        const modalContent = modal.querySelector('.relative');
        
        // Prevent scroll on body
        document.body.style.overflow = 'hidden';
        
        modal.classList.remove('hidden');
        // Trigger reflow for animations to work
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeGuideModal() {
        const modal = document.getElementById('guideModal');
        const modalContent = modal.querySelector('.relative');
        
        // Restore scroll on body
        document.body.style.overflow = '';
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        // Wait for animation to finish before adding hidden
        setTimeout(() => {
            if (modal.classList.contains('opacity-0')) {
                modal.classList.add('hidden');
            }
        }, 300);
    }
</script>

</body>

</html>