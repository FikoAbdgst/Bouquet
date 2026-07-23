<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'BuketBunga' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};</script>
    <script>
    window.CartStorage = {
      KEY: 'cart',
      get() { try { return JSON.parse(localStorage.getItem(this.KEY)) || []; } catch { return []; } },
      save(items) { localStorage.setItem(this.KEY, JSON.stringify(items)); window.dispatchEvent(new Event('cart-updated')); },
      addItem(item) { const items = this.get(); const existing = items.find(i => i.id === item.id); if (existing) { existing.qty += item.qty; } else { items.push(item); } this.save(items); },
      updateQty(id, qty) { this.save(this.get().filter(i => { if (i.id === id) { i.qty = qty; return qty > 0; } return true; })); },
      removeItem(id) { this.save(this.get().filter(i => i.id !== id)); },
      clear() { localStorage.removeItem(this.KEY); window.dispatchEvent(new Event('cart-updated')); },
      count() { return this.get().reduce((s, i) => s + i.qty, 0); },
      total() { return this.get().reduce((s, i) => s + i.price * i.qty, 0); }
    };
    window.formatRupiah = function(num) { return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); };
    window.updateCartBadges = function() {
      var count = window.CartStorage.count();
      document.querySelectorAll('.cart-badge-desktop, .cart-badge-mobile').forEach(function(el) {
        if (count > 0) { el.textContent = count > 99 ? '99+' : count; el.classList.remove('hidden'); } else { el.classList.add('hidden'); }
      });
    };
    window.addEventListener('cart-updated', window.updateCartBadges);
    document.addEventListener('DOMContentLoaded', function() { window.updateCartBadges(); });
    </script>
</head>
<body class="bg-rose-50/50 min-h-screen flex flex-col font-sans antialiased">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-rose-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">

                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <span class="text-2xl group-hover:scale-110 transition-transform duration-200">🌸</span>
                        <span class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">BuketBunga</span>
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex md:items-center md:space-x-1">
                    <a href="{{ route('customer.catalog') }}"
                       class="text-slate-600 hover:text-rose-600 font-medium transition px-3 py-2 rounded-lg hover:bg-rose-50">
                        Katalog
                    </a>

                    {{-- Cart Icon --}}
                    <a href="{{ route('customer.cart') }}" class="relative text-slate-600 hover:text-rose-600 font-medium transition px-3 py-2 rounded-lg hover:bg-rose-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span class="cart-badge-desktop absolute -top-0.5 -right-0.5 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm hidden"></span>
                    </a>

                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('customer.orders.index') }}" class="text-slate-600 hover:text-rose-600 font-medium transition px-3 py-2 rounded-lg hover:bg-rose-50">
                                Pesanan Saya
                            </a>
                        @endif

                        <div class="relative ml-2">
                            <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-slate-600 hover:text-rose-600 font-medium transition focus:outline-none px-2 py-1.5 rounded-xl hover:bg-rose-50">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 text-white text-sm font-bold shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="text-sm">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div id="user-dropdown" class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-rose-100 py-1.5 z-50 overflow-hidden">
                                <div class="px-4 py-3 border-b border-rose-50">
                                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</p>
                                </div>
                                @if(Auth::user()->isCustomer())
                                    <a href="{{ route('customer.orders.index') }}" class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition">Pesanan Saya</a>
                                @endif
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-rose-600 font-medium transition px-3 py-2 rounded-lg hover:bg-rose-50">Login</a>
                        <a href="{{ route('register') }}" class="bg-rose-400 text-white px-5 py-2 rounded-xl hover:bg-rose-500 font-medium transition shadow-sm text-sm">Daftar</a>
                    @endauth
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center md:hidden space-x-1">
                    {{-- Mobile Cart --}}
                    <a href="{{ route('customer.cart') }}" class="relative text-slate-600 hover:text-rose-600 p-2 rounded-lg hover:bg-rose-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span class="cart-badge-mobile absolute -top-0.5 -right-0.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center shadow-sm hidden"></span>
                    </a>
                    <button onclick="toggleMobileMenu()" class="text-slate-600 hover:text-rose-600 focus:outline-none p-2 rounded-lg hover:bg-rose-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-rose-100 bg-white/95 backdrop-blur-md">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('customer.catalog') }}" class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Katalog</a>
                <a href="{{ route('customer.cart') }}" class="flex items-center justify-between py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">
                    <span>Keranjang</span>
                    <span class="cart-badge-mobile w-6 h-6 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center hidden"></span>
                </a>

                @auth
                    @if(Auth::user()->isCustomer())
                        <a href="{{ route('customer.orders.index') }}" class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Pesanan Saya</a>
                    @endif
                    <div class="border-t border-rose-100 pt-2 mt-2">
                        <div class="flex items-center space-x-3 px-3 py-2">
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 text-white text-sm font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="mt-1">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 rounded-xl font-medium transition">Logout</button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-rose-100 pt-2 mt-2 space-y-1">
                        <a href="{{ route('login') }}" class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Login</a>
                        <a href="{{ route('register') }}" class="block text-center bg-rose-400 text-white px-4 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition shadow-sm">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-4">
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl mx-auto w-full py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white/60 backdrop-blur-sm border-t border-rose-100 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🌸</span>
                    <span class="text-sm font-semibold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">BuketBunga</span>
                </div>
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} BuketBunga. Dibuat dengan ❤️ untuk momen spesial Anda.</p>
                <div class="flex space-x-6 text-sm text-slate-400">
                    <a href="mailto:admin@buketbunga.com" class="hover:text-rose-500 transition">Kontak</a>
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
