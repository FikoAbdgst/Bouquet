<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin BuketBunga' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFFDFC] min-h-screen">

    {{-- Top Navbar --}}
    <nav class="bg-[#D37897] fixed top-0 left-0 right-0 z-50">
        <div class="flex justify-between h-14 px-4">
            {{-- Logo + Sidebar Toggle --}}
            <div class="flex items-center space-x-3">
                <button onclick="toggleSidebar()" class="text-[#C9A9B4] hover:text-white md:hidden focus:outline-none transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/LOGO.png') }}" alt="BuketBunga" class="h-10 w-auto brightness-0 invert">
                </a>
            </div>

            {{-- Right Side --}}
            <div class="flex items-center space-x-4">
                <a href="{{ route('customer.catalog') }}" target="_blank" class="text-[#C9A9B4] hover:text-white text-sm tracking-wide transition-colors">
                    Lihat Toko
                </a>
                <div class="relative">
                    <button onclick="toggleAdminDropdown()" class="flex items-center space-x-2 text-[#C9A9B4] hover:text-white focus:outline-none transition-colors">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-[#D37897] text-white text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span class="hidden sm:inline text-sm">{{ Auth::user()->name }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div id="admin-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-[#EFD3DE] py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-[#EFD3DE]">
                            <p class="text-sm font-medium text-[#33413A]">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#6E8577]">Administrator</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-[#D37897] hover:bg-[#F9DEE5] transition">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex pt-14">

        {{-- Sidebar --}}
        <aside id="sidebar" class="fixed md:sticky top-14 left-0 h-[calc(100vh-3.5rem)] w-60 bg-white border-r border-[#EFD3DE] z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
            <div class="py-5 px-3 space-y-0.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium transition border-r-2 {{ request()->routeIs('admin.dashboard') ? 'border-[#D37897] text-[#D37897] bg-[#F9DEE5]' : 'border-transparent text-[#5C6F5E] hover:text-[#D37897] hover:bg-[#F9DEE5]' }}">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <div class="border-t border-[#EFD3DE] my-3"></div>
                <p class="px-3 text-[11px] font-medium text-[#C9A9B4] uppercase tracking-wider mb-1">Manajemen</p>

                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium transition border-r-2 {{ request()->routeIs('admin.products.*') ? 'border-[#D37897] text-[#D37897] bg-[#F9DEE5]' : 'border-transparent text-[#5C6F5E] hover:text-[#D37897] hover:bg-[#F9DEE5]' }}">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Produk</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium transition border-r-2 {{ request()->routeIs('admin.categories.*') ? 'border-[#D37897] text-[#D37897] bg-[#F9DEE5]' : 'border-transparent text-[#5C6F5E] hover:text-[#D37897] hover:bg-[#F9DEE5]' }}">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium transition border-r-2 {{ request()->routeIs('admin.orders.*') ? 'border-[#D37897] text-[#D37897] bg-[#F9DEE5]' : 'border-transparent text-[#5C6F5E] hover:text-[#D37897] hover:bg-[#F9DEE5]' }}">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Pesanan</span>
                </a>

                <div class="border-t border-[#EFD3DE] my-3"></div>
                <p class="px-3 text-[11px] font-medium text-[#C9A9B4] uppercase tracking-wider mb-1">Lainnya</p>

                <a href="{{ route('admin.customers.index') }}"
                   class="flex items-center space-x-3 px-3 py-2.5 text-sm font-medium transition border-r-2 {{ request()->routeIs('admin.customers.*') ? 'border-[#D37897] text-[#D37897] bg-[#F9DEE5]' : 'border-transparent text-[#5C6F5E] hover:text-[#D37897] hover:bg-[#F9DEE5]' }}">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Pelanggan</span>
                </a>
            </div>
        </aside>

        {{-- Sidebar Overlay (Mobile) --}}
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden md:hidden" onclick="toggleSidebar()"></div>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            <div class="p-6">
                @include('partials.toasts')

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
    @stack('scripts')
</body>
</html>
