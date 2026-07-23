<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin BuketBunga' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen">

    {{-- Top Navbar --}}
    <nav class="bg-pink-800 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex justify-between h-16 px-4">
            {{-- Logo + Sidebar Toggle --}}
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" class="text-pink-200 hover:text-white md:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <span class="text-2xl">🌸</span>
                    <span class="text-xl font-bold text-white">Admin Panel</span>
                </a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.catalog') }}" target="_blank" class="text-pink-200 hover:text-white text-sm font-medium transition">
                    Lihat Toko →
                </a>
                <div class="relative">
                    <button onclick="toggleAdminDropdown()" class="flex items-center space-x-2 text-pink-200 hover:text-white focus:outline-none">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-pink-600 text-white text-sm font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden sm:inline font-medium">{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="admin-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-pink-200 py-1 z-50">
                        <div class="px-4 py-2 border-b border-pink-100">
                            <p class="text-sm font-medium text-pink-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-pink-500">Administrator</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-16">

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed md:sticky top-16 left-0 h-[calc(100vh-4rem)] w-64 bg-white border-r border-pink-200 shadow-sm z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
            <div class="py-6 px-4 space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-pink-100 text-pink-800' : 'text-pink-700 hover:bg-pink-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                {{-- Divider --}}
                <div class="border-t border-pink-100 my-3"></div>
                <p class="px-3 text-xs font-semibold text-pink-400 uppercase tracking-wider">Manajemen</p>

                {{-- Produk --}}
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-pink-100 text-pink-800' : 'text-pink-700 hover:bg-pink-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Manajemen Produk</span>
                </a>

                {{-- Pesanan --}}
                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-pink-100 text-pink-800' : 'text-pink-700 hover:bg-pink-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Kelola Pesanan</span>
                </a>

                {{-- Divider --}}
                <div class="border-t border-pink-100 my-3"></div>
                <p class="px-3 text-xs font-semibold text-pink-400 uppercase tracking-wider">Lainnya</p>

                {{-- Pelanggan --}}
                <a href="#"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium text-pink-400 cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Pelanggan</span>
                    <span class="ml-auto text-xs bg-pink-100 text-pink-500 px-2 py-0.5 rounded-full">Soon</span>
                </a>
            </div>
        </aside>

        {{-- Sidebar Overlay (Mobile) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            <div class="p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleAdminDropdown() {
            document.getElementById('admin-dropdown').classList.toggle('hidden');
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('admin-dropdown');
            const button = e.target.closest('[onclick="toggleAdminDropdown()"]');
            if (!button && !e.target.closest('#admin-dropdown')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
