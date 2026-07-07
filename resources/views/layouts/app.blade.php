<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Prevent sidebar layout shift on reload
        (function() {
            const state = localStorage.getItem('sidebar-minimized');
            if (state === 'true') {
                document.documentElement.classList.add('sidebar-is-minimized');
            }
        })();
    </script>

    <style>
        /* Sidebar custom transitions and classes */
        #sidebar {
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.minimized {
            width: 5rem !important; /* w-20 */
        }
        #sidebar.minimized .sidebar-text,
        #sidebar.minimized .sidebar-logo-text,
        #sidebar.minimized .sidebar-cs-card {
            display: none !important;
        }
        #sidebar.minimized .sidebar-logo-short {
            display: block !important;
        }
        #sidebar.minimized .sidebar-header {
            flex-direction: column-reverse !important;
            gap: 1rem !important;
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
            align-items: center !important;
            justify-content: center !important;
        }
        #sidebar.minimized .sidebar-nav-link {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        #sidebar.minimized .sidebar-logout-btn {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-72 bg-blue-700 text-white flex flex-col shrink-0 h-screen sticky top-0">
            <!-- Brand & Toggle Header -->
            <div class="sidebar-header p-8 flex items-center justify-between border-b border-blue-600/50">
                <div>
                    <h1 class="text-3xl font-bold sidebar-logo-text">SIRAT</h1>
                    <h1 class="text-3xl font-bold sidebar-logo-short hidden">S</h1>
                    <p class="text-blue-100 mt-2 text-sm sidebar-text">Sistem Reservasi Area TULT</p>
                </div>
                <button type="button" id="sidebar-toggle" class="text-white hover:bg-blue-600 p-2 rounded-xl transition cursor-pointer">
                    <!-- Collapse Icon (Burger) -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-4 flex-1 overflow-y-auto">
                <a href="/dashboard" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('dashboard') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Dashboard Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                
                <a href="/reservation" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('reservation*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Calendar Plus Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text">Reservasi Baru</span>
                </a>
                
                <a href="/history" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('history*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- History/Clock Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="sidebar-text">Riwayat Reservasi</span>
                </a>
            </nav>

            <!-- CS & Logout Section -->
            <div class="p-5 border-t border-blue-600/50">
                <div class="sidebar-cs-card bg-blue-600 rounded-xl p-4 mb-3">
                    <p class="font-semibold">CS TULT</p>
                    <p class="text-sm mt-2">0812-3456-7890</p>
                    <p class="text-xs">cs.tult@telkomuniversity.ac.id</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn w-full flex items-center gap-3 bg-blue-600 rounded-xl p-4 text-left hover:bg-blue-500 transition duration-150 cursor-pointer">
                        <!-- Logout Icon -->
                        <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="sidebar-text">Keluar</span>
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

    <!-- Script to toggle sidebar state -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            
            // Sync state with HTML header script check
            if (document.documentElement.classList.contains('sidebar-is-minimized')) {
                sidebar.classList.add('minimized');
            }
            
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('minimized');
                const isMin = sidebar.classList.contains('minimized');
                localStorage.setItem('sidebar-minimized', isMin);
                
                // Keep html tag in sync
                if (isMin) {
                    document.documentElement.classList.add('sidebar-is-minimized');
                } else {
                    document.documentElement.classList.remove('sidebar-is-minimized');
                }
            });
        });
    </script>
</body>
</html>