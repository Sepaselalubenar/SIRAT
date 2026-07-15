<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT - Masuk</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center p-4 md:p-8 relative font-sans select-none overflow-y-auto">

    <!-- Top-Left Faculty Logo -->
    <div class="absolute top-6 left-6 md:top-8 md:left-8 z-20">
        <img src="{{ asset('logo-fte.png') }}" alt="Logo FTE" class="h-12 md:h-14 w-auto bg-white/95 py-2 px-4 rounded-xl shadow-md border border-gray-200/50">
    </div>

    <!-- Top-Right Guide Button -->
    <div class="absolute top-6 right-6 md:top-8 md:right-8 z-20">
        <button type="button" onclick="openGuideModal()" class="flex items-center gap-2 bg-white/95 hover:bg-white text-blue-700 font-bold py-2.5 px-4 rounded-xl shadow-md border border-gray-200/50 hover:scale-[1.02] active:scale-[0.98] transition-all cursor-pointer text-sm shadow-blue-700/5">
            <!-- Book Open Icon -->
            <svg class="w-4 h-4 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            Buku Panduan
        </button>
    </div>

    <!-- Fixed Background Image and Overlay -->
    <div class="fixed inset-0 z-0 bg-cover bg-center bg-no-repeat pointer-events-none" style="background-image: url('{{ asset('bg-login.jpg') }}')">
        <!-- Dark Overlay & Subtle Blur -->
        <div class="absolute inset-0 bg-slate-100/70 backdrop-blur-[4px]"></div>
        
        <!-- Ambient Glowing Lights -->
        <div class="absolute top-[-20%] left-[-15%] w-[60%] h-[70%] rounded-full bg-blue-500/10 blur-[130px]"></div>
        <div class="absolute bottom-[-20%] right-[-15%] w-[60%] h-[70%] rounded-full bg-blue-600/10 blur-[130px]"></div>
    </div>

    <!-- Main Container -->
    <div class="relative w-full max-w-md bg-white/80 backdrop-blur-xl border border-white/50 rounded-3xl overflow-hidden shadow-2xl p-8 sm:p-12 z-10 my-auto flex flex-col justify-center">

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight text-center">Selamat Datang di SIRAT</h2>
            <p class="text-sm text-gray-500 mt-2 text-center">Gunakan NIP dan email Tel-U Anda untuk masuk.</p>
        </div>

        <!-- Errors Alert -->
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-xl p-3.5 mb-6 border border-red-200 shadow-sm shadow-red-500/5">
                <div class="flex gap-2 items-start">
                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">NIP</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
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
                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-xl py-3 pl-11 pr-4 text-base text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:bg-white transition-all"
                        required
                        autofocus
                    >
                </div>
            </div>

            <!-- Email Input -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
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
                        class="w-full bg-gray-50/50 border border-gray-200/80 rounded-xl py-3 pl-11 pr-4 text-base text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent focus:bg-white transition-all"
                        required
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full py-3.5 px-4 mt-2 bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-600 hover:to-blue-500 text-white rounded-xl font-bold text-base shadow-lg shadow-blue-700/20 hover:shadow-blue-700/30 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                Masuk ke Dashboard
            </button>
        </form>

        <!-- Contact LAA FTE Footer Inside Form -->
        <div class="mt-8 pt-6 border-t border-gray-200/60 text-center">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2.5">Butuh Bantuan? Kontak LAA FTE</p>
            <div class="flex flex-col gap-2 text-sm text-gray-500">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-blue-600/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span class="font-medium text-gray-700">0812-2425-3349</span>
                </div>
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 text-blue-600/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <a href="mailto:laa.fte@telkomuniversity.ac.id" class="text-blue-600 hover:text-blue-700 hover:underline">laa.fte@telkomuniversity.ac.id</a>
                </div>
            </div>
        </div>

        <!-- Footer
        <div class="mt-8 text-center text-[10px] text-gray-500">
            Sistem Informasi Ruangan FTE &copy; {{ date('Y') }}
        </div> -->

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
                    <h3 class="text-lg font-bold text-slate-800">Buku Panduan Pengguna (Dosen / Pegawai)</h3>
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