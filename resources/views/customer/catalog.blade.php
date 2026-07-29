@extends('layouts.app')

@section('content')
{{-- Hero Section --}}
<div class="text-center mb-10">
    <h1 class="text-4xl sm:text-5xl font-bold text-slate-800 tracking-tight">
        Katalog <span class="bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">Buket Bunga</span>
    </h1>
    <p class="mt-3 text-lg text-slate-500 max-w-xl mx-auto">Temukan buket bunga terindah untuk momen spesial Anda</p>
</div>

{{-- Filter Section --}}
<div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-5 mb-8 shadow-sm">
    <form action="{{ route('customer.catalog') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama buket..."
                   class="w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
        </div>

        <div class="min-w-[150px]">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
            <select name="category" class="w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[120px]">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Harga Min</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Rp 0" min="0"
                   class="w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
        </div>

        <div class="min-w-[120px]">
            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Harga Max</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp ..." min="0"
                   class="w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
        </div>

        <button type="submit" class="bg-rose-400 text-white px-6 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition-all duration-200 shadow-sm hover:shadow-md text-sm">
            Filter
        </button>
    </form>
</div>

@if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <a href="{{ route('customer.catalog.show', $product) }}" class="group block bg-white rounded-2xl border border-rose-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 relative">
                <div class="aspect-square bg-gradient-to-br from-rose-50 to-pink-50 overflow-hidden relative">
                    @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl text-rose-200 group-hover:scale-110 transition-transform duration-500">🌸</div>
                    @endif
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all duration-300 flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 rounded-full p-3 shadow-lg">
                            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <span class="text-xs font-semibold text-rose-500 bg-rose-50 px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $product->productCategory?->name ?? $product->category }}</span>
                    <h3 class="mt-2.5 text-base font-semibold text-slate-800 line-clamp-1 group-hover:text-rose-600 transition-colors">{{ $product->name }}</h3>
                    <p class="mt-1 text-rose-500 font-bold text-lg">{{ $product->formatted_price }}</p>
                    @if($product->stock <= 5 && $product->stock > 0)
                        <p class="mt-1.5 text-xs font-medium text-amber-600 bg-amber-50 inline-block px-2 py-0.5 rounded-full">Stok tersisa {{ $product->stock }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-10">
        {{ $products->withQueryString()->links() }}
    </div>
@else
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-16 text-center shadow-sm">
        <p class="text-5xl mb-4">🌺</p>
        <p class="text-slate-500 text-lg">Belum ada produk yang tersedia.</p>
        <p class="text-sm text-slate-400 mt-1">Coba ubah filter pencarian Anda.</p>
    </div>
@endif
@endsection


