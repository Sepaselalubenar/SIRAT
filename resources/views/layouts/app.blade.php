<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-72 bg-blue-700 text-white flex flex-col">
            <div class="p-8">
                <h1 class="text-3xl font-bold">SIRAT</h1>
                <p class="text-blue-100 mt-2 text-sm">Sistem Reservasi Area TULT</p>
            </div>

            <nav class="px-4 flex-1">
                <a href="/dashboard" class="block px-5 py-4 rounded-xl hover:bg-blue-600 mb-3">
                    Dashboard
                </a>
                <a href="/reservation" class="block px-5 py-4 rounded-xl hover:bg-blue-600 mb-3">
                    Reservasi Baru
                </a>
                <a href="/history" class="block px-5 py-4 rounded-xl hover:bg-blue-600">
                    Riwayat Reservasi
                </a>
            </nav>

            <div class="p-5">
                <div class="bg-blue-600 rounded-xl p-4 mb-3">
                    <p class="font-semibold">CS TULT</p>
                    <p class="text-sm mt-2">0812-3456-7890</p>
                    <p class="text-xs">cs.tult@telkomuniversity.ac.id</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-blue-100 hover:text-white px-2">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        {{-- Content --}}
        <div class="flex-1 flex flex-col">

            {{-- Topbar --}}
            <header class="bg-white shadow-sm px-8 py-5 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-5">
                    <button class="text-xl">🔔</button>

                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ Str::of(auth()->user()->name)->explode(' ')->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ auth()->user()->name }}</p>
                            <p class="text-gray-500 text-sm">Dosen</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1">
                <section class="p-6">
                    @yield('content')
                </section>
            </main>

        </div>

    </div>

</body>
</html>