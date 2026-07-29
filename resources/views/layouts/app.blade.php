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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script>
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script>
        window.CartStorage = {
            KEY: 'cart',
            _key(item) {
                const opts = item.custom_options ? JSON.stringify(item.custom_options) : '';
                return item.id + '::' + opts;
            },
            get() {
                try {
                    const items = JSON.parse(localStorage.getItem(this.KEY)) || [];
                    items.forEach(function(i) {
                        if (!i._key) i._key = CartStorage._key(i);
                    });
                    return items;
                } catch {
                    return [];
                }
            },
            save(items) {
                localStorage.setItem(this.KEY, JSON.stringify(items));
                window.dispatchEvent(new Event('cart-updated'));
            },
            addItem(item) {
                const items = this.get();
                item._key = this._key(item);
                const existing = items.find(i => i._key === item._key);
                if (existing) {
                    existing.qty += item.qty;
                } else {
                    items.push(item);
                }
                this.save(items);
            },
            updateQty(key, qty) {
                this.save(this.get().filter(i => {
                    if (i._key === key) {
                        i.qty = qty;
                        return qty > 0;
                    }
                    return true;
                }));
            },
            removeItem(key) {
                this.save(this.get().filter(i => i._key !== key));
            },
            clear() {
                localStorage.removeItem(this.KEY);
                window.dispatchEvent(new Event('cart-updated'));
            },
            count() {
                return this.get().reduce((s, i) => s + i.qty, 0);
            },
            total() {
                return this.get().reduce((s, i) => s + i.price * i.qty, 0);
            }
        };
        window.formatRupiah = function(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };
        window.updateCartBadges = function() {
            var count = window.CartStorage.count();
            document.querySelectorAll('.cart-badge-desktop, .cart-badge-mobile').forEach(function(el) {
                if (count > 0) {
                    el.textContent = count > 99 ? '99+' : count;
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        };
        window.addEventListener('cart-updated', window.updateCartBadges);
        document.addEventListener('DOMContentLoaded', function() {
            window.updateCartBadges();
        });
    </script>
    @stack('styles')
</head>

<body class="bg-rose-50/50 min-h-screen flex flex-col font-sans antialiased">

    {{-- Floating Pill Navbar --}}
    <nav class="fixed top-4 left-1/2 -translate-x-1/2 z-50 w-[92%] max-w-5xl">
        <div class="relative">
            {{-- Pill Container --}}
            <div
                class="bg-[#FDF8F5]/60 backdrop-blur-md shadow-lg shadow-black/5 border border-pink-100/50 rounded-full px-3 py-1.5">
                <div class="flex items-center justify-between">

                    {{-- Left: Brand Logo --}}
                    <div class="flex items-center shrink-0">
                        <a href="{{ route('home') }}" class="flex items-center group">
                            <img src="{{ asset('images/LOGOTEXT.png') }}" alt="BuketBunga"
                                class="h-6 w-auto group-hover:opacity-80 transition-opacity duration-200">
                        </a>
                    </div>

                    {{-- Center: Desktop Navigation Links --}}
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('home') }}"
                            class="text-slate-600 hover:text-rose-600 font-medium text-sm transition px-4 py-2 rounded-full hover:bg-rose-50/70">
                            Beranda
                        </a>
                        <a href="{{ route('customer.catalog') }}"
                            class="text-slate-600 hover:text-rose-600 font-medium text-sm transition px-4 py-2 rounded-full hover:bg-rose-50/70">
                            Katalog Bunga
                        </a>
                        @auth
                            @if (Auth::user()->isCustomer())
                                <a href="{{ route('customer.orders.index') }}"
                                    class="text-slate-600 hover:text-rose-600 font-medium text-sm transition px-4 py-2 rounded-full hover:bg-rose-50/70">
                                    Pesanan Saya
                                </a>
                            @endif
                        @endauth
                    </div>

                    {{-- Right: Cart, Auth, Hamburger --}}
                    <div class="flex items-center space-x-2">
                        {{-- Cart Icon --}}
                        <a href="{{ route('customer.cart') }}"
                            class="relative text-slate-600 hover:text-rose-600 transition p-2 rounded-full hover:bg-rose-50/70">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                            </svg>
                            <span
                                class="cart-badge-desktop absolute -top-0.5 -right-0.5 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm hidden"></span>
                        </a>

                        {{-- Auth (desktop) --}}
                        <div class="hidden md:flex items-center space-x-2">
                            @auth
                                @if (Auth::user()->isCustomer())
                                    <a href="{{ route('customer.dashboard') }}"
                                        class="text-slate-600 hover:text-rose-600 font-medium text-sm transition px-3 py-2 rounded-full hover:bg-rose-50/70">
                                        Dashboard
                                    </a>
                                @endif
                                <div class="relative">
                                    <button id="user-dropdown-btn" onclick="toggleDropdown()"
                                        class="flex items-center space-x-1 text-slate-600 hover:text-rose-600 transition focus:outline-none px-2 py-1.5 rounded-full hover:bg-rose-50/70">
                                        <span
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 text-white text-sm font-bold shadow-sm">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>

                                    <div id="user-dropdown"
                                        class="hidden absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-rose-100 py-1.5 z-50 overflow-hidden">
                                        <div class="px-4 py-3 border-b border-rose-50">
                                            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</p>
                                        </div>
                                        @if (Auth::user()->isCustomer())
                                            <a href="{{ route('customer.orders.index') }}"
                                                class="block px-4 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition">Pesanan
                                                Saya</a>
                                        @endif
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full text-left px-4 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-slate-600 hover:text-rose-600 font-medium text-sm transition px-4 py-2 rounded-full hover:bg-rose-50/70">Login</a>
                                <a href="{{ route('register') }}"
                                    class="bg-rose-400 text-white px-5 py-2 rounded-full hover:bg-rose-500 font-medium transition shadow-sm text-sm">Daftar</a>
                            @endauth
                        </div>

                        {{-- Mobile Hamburger --}}
                        <button id="mobile-menu-btn"
                            class="block md:hidden text-slate-600 hover:text-rose-600 focus:outline-none p-2 rounded-full hover:bg-rose-50/70">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path id="menu-icon-open" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path id="menu-icon-close" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Mobile Menu --}}
            <div id="mobile-menu"
                class="hidden md:hidden absolute top-full left-0 w-full mt-2 p-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-pink-100/50">
                <div class="space-y-1">
                    <a href="{{ route('home') }}"
                        class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Beranda</a>
                    <a href="{{ route('customer.catalog') }}"
                        class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Katalog
                        Bunga</a>
                    <a href="{{ route('customer.cart') }}"
                        class="flex items-center justify-between py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">
                        <span>Keranjang</span>
                        <span
                            class="cart-badge-mobile w-6 h-6 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center hidden"></span>
                    </a>

                    @auth
                        @if (Auth::user()->isCustomer())
                            <a href="{{ route('customer.orders.index') }}"
                                class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Pesanan
                                Saya</a>
                            <a href="{{ route('customer.dashboard') }}"
                                class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Dashboard</a>
                        @endif
                        <div class="border-t border-rose-100 pt-3 mt-3">
                            <div class="flex items-center space-x-3 px-3 py-2">
                                <span
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 text-white text-sm font-bold shadow-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('logout') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-3 py-2.5 text-sm text-slate-600 hover:bg-rose-50 hover:text-rose-600 rounded-xl font-medium transition">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="border-t border-rose-100 pt-3 mt-3 space-y-1">
                            <a href="{{ route('login') }}"
                                class="block py-2.5 px-3 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl font-medium transition">Login</a>
                            <a href="{{ route('register') }}"
                                class="block text-center bg-rose-400 text-white px-4 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition shadow-sm">Daftar</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div
                class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div
                class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- Main Content --}}
    <main class="flex-1 max-w-7xl mx-auto w-full pt-20 pb-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white/60 backdrop-blur-sm border-t border-rose-100 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <span class="text-xl">🌸</span>
                    <span
                        class="text-sm font-semibold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">BuketBunga</span>
                </div>
                <p class="text-sm text-slate-400">&copy; {{ date('Y') }} BuketBunga. Dibuat dengan ❤️ untuk momen
                    spesial Anda.</p>
                <div class="flex space-x-6 text-sm text-slate-400">
                    <a href="mailto:admin@buketbunga.com" class="hover:text-rose-500 transition">Kontak</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Vanilla JS --}}
    <script>
        function toggleDropdown() {
            document.getElementById('user-dropdown').classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            var menuBtn = document.getElementById('mobile-menu-btn');
            var mobileMenu = document.getElementById('mobile-menu');
            var iconOpen = document.getElementById('menu-icon-open');
            var iconClose = document.getElementById('menu-icon-close');

            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    mobileMenu.classList.toggle('hidden');
                    iconOpen.classList.toggle('hidden');
                    iconClose.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!mobileMenu.classList.contains('hidden') &&
                        !mobileMenu.contains(e.target) &&
                        !menuBtn.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    }
                });
            }

            var dropdown = document.getElementById('user-dropdown');
            var dropdownBtn = document.getElementById('user-dropdown-btn');
            if (dropdown && dropdownBtn) {
                document.addEventListener('click', function(e) {
                    if (!dropdownBtn.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
