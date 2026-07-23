@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-4xl">🌸</span>
            </a>
            <h1 class="mt-4 text-3xl font-bold text-slate-800">Buat Akun Baru</h1>
            <p class="mt-2 text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-rose-500 hover:text-rose-600 transition">
                    Masuk di sini
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-5" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-7 space-y-5 shadow-sm">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-600 mb-1.5">Email</label>
                    <input id="email" name="email" type="email" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="email@contoh.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-600 mb-1.5">Nomor HP/WhatsApp</label>
                    <input id="phone" name="phone" type="text" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="08xxxxxxxxxx"
                           value="{{ old('phone') }}">
                    @error('phone')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Password</label>
                    <input id="password" name="password" type="password" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="Minimal 8 karakter, huruf & angka">
                    @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-600 mb-1.5">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="Ulangi password">
                    @error('password_confirmation')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-rose-400 text-white py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                Daftar Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
