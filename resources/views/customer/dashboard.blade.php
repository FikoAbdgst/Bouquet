@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Welcome Header --}}
    <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-slate-800">
            Halo, <span class="bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">{{ Auth::user()->name }}</span> 👋
        </h1>
        <p class="mt-3 text-lg text-slate-500">Selamat datang di BuketBunga. Temukan buket bunga terindah untuk momen spesial Anda.</p>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
        <a href="{{ route('customer.catalog') }}" class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-500 flex items-center justify-center text-white text-xl shadow-sm group-hover:scale-110 transition-transform">
                    🌸
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800 group-hover:text-rose-600 transition">Jelajahi Katalog</h3>
                    <p class="text-sm text-slate-400">Lihat semua buket bunga yang tersedia</p>
                </div>
            </div>
        </a>

        <a href="{{ route('customer.orders.index') }}" class="group bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white text-xl shadow-sm group-hover:scale-110 transition-transform">
                    📦
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800 group-hover:text-rose-600 transition">Riwayat Pesanan</h3>
                    <p class="text-sm text-slate-400">Lacak status pesanan Anda</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Featured Info --}}
    <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-2xl border border-rose-100 p-8 text-center">
        <p class="text-4xl mb-3">💐</p>
        <h2 class="text-xl font-bold text-slate-800">Buket Bunga untuk Momen Spesial</h2>
        <p class="text-slate-500 mt-2 max-w-md mx-auto">Setiap buket dibuat dengan penuh kasih sayang. Pilih, pesan, dan biarkan kami merangkai kebahagiaan untuk Anda.</p>
        <a href="{{ route('customer.catalog') }}" class="inline-block mt-5 bg-rose-400 text-white px-8 py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
            Mulai Belanja →
        </a>
    </div>
</div>
@endsection
