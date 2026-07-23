@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Keranjang Belanja</h1>
        <p class="text-slate-400 mt-1">{{ count($cartItems) }} item di keranjang Anda.</p>
    </div>

    @if(empty($cartItems))
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-16 text-center shadow-sm">
            <p class="text-5xl mb-4">🛒</p>
            <p class="text-slate-500 text-lg">Keranjang Anda masih kosong.</p>
            <a href="{{ route('customer.catalog') }}" class="inline-block mt-4 bg-rose-400 text-white px-6 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                Jelajahi Katalog
            </a>
        </div>
    @else
        <div class="space-y-4 mb-8">
            @foreach($cartItems as $item)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center gap-4">
                    {{-- Product Image --}}
                    <div class="w-20 h-20 flex-shrink-0 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl overflow-hidden">
                        @if($item['product']->primaryImage)
                            <img src="{{ Storage::url($item['product']->primaryImage->image_url) }}" alt="{{ $item['product']->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-2xl text-rose-200">🌸</div>
                        @endif
                    </div>

                    {{-- Product Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-800 truncate">{{ $item['product']->name }}</h3>
                        <p class="text-sm text-slate-400 mt-0.5">{{ $item['product']->category }}</p>
                        <p class="text-rose-500 font-bold mt-1">{{ $item['product']->formatted_price }}</p>
                    </div>

                    {{-- Quantity + Price --}}
                    <div class="flex items-center gap-4">
                        <div class="flex items-center bg-rose-50 rounded-xl border border-rose-100 overflow-hidden">
                            <span class="px-3 py-2 text-sm font-medium text-slate-600 min-w-[2.5rem] text-center">{{ $item['quantity'] }}</span>
                        </div>

                        <p class="font-bold text-slate-800 min-w-[100px] text-right">Rp {{ number_format($item['line_total'], 0, ',', '.') }}</p>

                        <form action="{{ route('customer.cart.remove', $item['product']->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Summary --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <form action="{{ route('customer.cart.clear') }}" method="POST" class="inline"
                          onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-slate-400 hover:text-rose-500 transition font-medium">
                            Kosongkan Keranjang
                        </button>
                    </form>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-sm text-slate-400">Total</p>
                        <p class="text-2xl font-bold text-slate-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                    </div>
                    @auth
                        @if(Auth::user()->isCustomer())
                            <a href="{{ route('customer.orders.index') }}" class="bg-rose-400 text-white px-6 py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                Checkout →
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-rose-400 text-white px-6 py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                            Login untuk Checkout
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
