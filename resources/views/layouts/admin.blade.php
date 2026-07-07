<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-72 bg-blue-700 text-white flex flex-col">
            <div class="p-8">
                <h1 class="text-3xl font-bold">SIRAT</h1>
                <p class="text-blue-100 mt-2 text-sm">Panel Admin - Area TULT</p>
            </div>

            <nav class="px-4 flex-1">
                <a href="/admin" class="block px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin') ? 'bg-blue-800 font-semibold' : '' }}">
                    Dashboard
                </a>
                <a href="/admin/rooms" class="block px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/rooms*') ? 'bg-blue-800 font-semibold' : '' }}">
                    Kelola Ruangan
                </a>
                <a href="/admin/reservations" class="block px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/reservations*') ? 'bg-blue-800 font-semibold' : '' }}">
                    Data Reservasi
                </a>
            </nav>

            <div class="p-5">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-blue-600 rounded-xl p-4 text-left hover:bg-blue-500">
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

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        {{ Str::of(auth()->user()->name)->explode(' ')->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-gray-500 text-sm">Admin</p>
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
