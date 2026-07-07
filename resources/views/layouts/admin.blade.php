<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIRAT Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Prevent sidebar layout shift on reload
        (function() {
            const state = localStorage.getItem('sidebar-minimized');
            if (state === 'true' && window.innerWidth >= 1024) {
                document.documentElement.classList.add('sidebar-is-minimized');
            }
        })();
    </script>

    <style>
        /* Sidebar custom transitions and classes */
        #sidebar {
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1), transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar.minimized {
            width: 5rem !important; /* w-20 */
        }
        #sidebar.minimized .sidebar-text,
        #sidebar.minimized .sidebar-logo-text {
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

        /* Mobile responsive sidebar rules */
        @media (max-width: 1023px) {
            #sidebar {
                position: fixed !important;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 50 !important;
                height: 100vh !important;
                transform: translateX(-100%);
            }
            #sidebar.open {
                transform: translateX(0) !important;
                width: 18rem !important;
            }
            /* Never minimize to w-20 on mobile drawer */
            #sidebar.minimized {
                width: 18rem !important;
            }
            #sidebar.minimized .sidebar-text,
            #sidebar.minimized .sidebar-logo-text {
                display: block !important;
            }
            #sidebar.minimized .sidebar-logo-short {
                display: none !important;
            }
            #sidebar.minimized .sidebar-header {
                flex-direction: row !important;
                padding-left: 2rem !important;
                padding-right: 2rem !important;
                justify-content: space-between !important;
            }
            #sidebar.minimized .sidebar-nav-link,
            #sidebar.minimized .sidebar-logout-btn {
                justify-content: flex-start !important;
                padding-left: 1.25rem !important;
                padding-right: 1.25rem !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- Sidebar Backdrop for mobile -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden"></div>

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside id="sidebar" class="w-72 bg-blue-700 text-white flex flex-col shrink-0 h-screen sticky top-0">
            <!-- Brand & Toggle Header -->
            <div class="sidebar-header p-8 flex items-center justify-between border-b border-blue-600/50">
                <div>
                    <h1 class="text-3xl font-bold sidebar-logo-text">SIRAT</h1>
                    <h1 class="text-3xl font-bold sidebar-logo-short hidden">S</h1>
                    <p class="text-blue-100 mt-2 text-sm sidebar-text">Panel Admin - Area TULT</p>
                </div>
                <button type="button" id="sidebar-toggle" class="text-white hover:bg-blue-600 p-2 rounded-xl transition cursor-pointer">
                    <!-- Collapse/Close Icon -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="px-4 py-4 flex-1 overflow-y-auto">
                <a href="/admin" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Dashboard Grid Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                
                <a href="/admin/rooms" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/rooms*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Building Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="sidebar-text">Kelola Ruangan</span>
                </a>
                
                <a href="/admin/reservations" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/reservations*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Calendar Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="sidebar-text">Data Reservasi</span>
                </a>

                <a href="/admin/calendar" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/calendar*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Calendar Grid Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="sidebar-text">Kalender Ruangan</span>
                </a>

                <a href="/admin/users" class="sidebar-nav-link flex items-center gap-3 px-5 py-4 rounded-xl hover:bg-blue-600 mb-3 {{ request()->is('admin/users*') ? 'bg-blue-800 font-semibold' : '' }}">
                    <!-- Users Icon -->
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="sidebar-text">Kelola Dosen</span>
                </a>
            </nav>

            <!-- Logout Section -->
            <div class="p-4 border-t border-blue-600/50">
                <form method="POST" action="{{ route('admin.logout') }}">
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
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Topbar --}}
            <header class="bg-white/95 backdrop-blur-md shadow-sm px-4 lg:px-8 py-5 flex justify-between items-center sticky top-0 z-20">
                <div class="flex items-center gap-3">
                    <!-- Burger toggle for mobile -->
                    <button type="button" id="mobile-sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 p-2 rounded-xl hover:bg-gray-150 transition cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        {{ Str::of(auth()->user()->name)->explode(' ')->map(fn($w) => strtoupper($w[0] ?? ''))->take(2)->implode('') }}
                    </div>
                    <div class="hidden sm:block">
                        <p class="font-semibold text-gray-800 text-sm leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-gray-500 text-xs mt-0.5">Admin</p>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-x-hidden">
                <section class="p-4 lg:p-6">
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
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            // Sync state with HTML header script check
            if (window.innerWidth >= 1024) {
                if (document.documentElement.classList.contains('sidebar-is-minimized')) {
                    sidebar.classList.add('minimized');
                }
            }
            
            // Toggle sidebar minimized (on Desktop) or close (on Mobile)
            toggle.addEventListener('click', function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.toggle('minimized');
                    const isMin = sidebar.classList.contains('minimized');
                    localStorage.setItem('sidebar-minimized', isMin);
                    
                    if (isMin) {
                        document.documentElement.classList.add('sidebar-is-minimized');
                    } else {
                        document.documentElement.classList.remove('sidebar-is-minimized');
                    }
                } else {
                    // Mobile close drawer
                    sidebar.classList.remove('open');
                    backdrop.classList.add('hidden');
                }
            });

            // Mobile Open Toggle
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.add('open');
                    backdrop.classList.remove('hidden');
                });
            }

            // Click backdrop to close
            if (backdrop) {
                backdrop.addEventListener('click', function() {
                    sidebar.classList.remove('open');
                    backdrop.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
