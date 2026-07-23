<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BuketBunga' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-pink-50 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm border-b border-pink-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <span class="text-2xl">🌸</span>
                        <span class="text-xl font-bold text-pink-800">BuketBunga</span>
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex md:items-center md:space-x-6">
                    <a href="{{ route('customer.catalog') }}"
                       class="text-pink-700 hover:text-pink-900 font-medium transition">
                        Katalog Produk
                    </a>

                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('customer.orders.index') }}" class="text-pink-700 hover:text-pink-900 font-medium transition">
                                Riwayat Pesanan
                            </a>
                        @endif

                        <div class="relative ml-2">
                            <button onclick="toggleDropdown()" class="flex items-center space-x-1 text-pink-700 hover:text-pink-900 font-medium transition focus:outline-none">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-pink-200 text-pink-700 text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-pink-200 py-1 z-50">
                                <div class="px-4 py-2 border-b border-pink-100">
                                    <p class="text-sm font-medium text-pink-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-pink-500">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->isCustomer())
                                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2 text-sm text-pink-700 hover:bg-pink-50">Riwayat Pesanan</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-pink-700 hover:text-pink-900 font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 font-medium transition shadow-sm">Daftar</a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-pink-700 hover:text-pink-900 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-pink-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('customer.catalog') }}" class="block py-2 text-pink-700 hover:text-pink-900 font-medium">Katalog Produk</a>

                @auth
                    @if(Auth::user()->isCustomer())
                        <a href="{{ route('customer.orders.index') }}" class="block py-2 text-pink-700 hover:text-pink-900 font-medium">Riwayat Pesanan</a>
                    @endif
                    <div class="border-t border-pink-100 pt-2 mt-2">
                        <p class="text-sm text-pink-800 font-medium">{{ Auth::user()->name }}</p>
                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-pink-100 pt-2 mt-2 space-y-2">
                        <a href="{{ route('login') }}" class="block py-2 text-pink-700 hover:text-pink-900 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="block text-center bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 font-medium">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
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
    </div>

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl mx-auto w-full py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-pink-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">🌸</span>
                    <span class="text-sm font-medium text-pink-700">BuketBunga</span>
                </div>
                <p class="text-sm text-pink-500">&copy; {{ date('Y') }} BuketBunga. Semua hak dilindungi.</p>
                <div class="flex space-x-4 text-sm text-pink-500">
                    <a href="#" class="hover:text-pink-700">Tentang Kami</a>
                    <a href="#" class="hover:text-pink-700">Kontak</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script>
        function toggleDropdown() {
            document.getElementById('user-dropdown').classList.toggle('hidden');
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = e.target.closest('[onclick="toggleDropdown()"]');
            if (!button && !e.target.closest('#user-dropdown')) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
