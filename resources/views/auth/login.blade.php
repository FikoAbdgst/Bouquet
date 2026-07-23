@extends('layouts.app')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-4xl">🌸</span>
            </a>
            <h1 class="mt-4 text-3xl font-bold text-slate-800">Masuk ke Akun Anda</h1>
            <p class="mt-2 text-sm text-slate-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-rose-500 hover:text-rose-600 transition">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-5" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-7 space-y-5 shadow-sm">
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
                    <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Password</label>
                    <input id="password" name="password" type="password" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400"
                           placeholder="Masukkan password">
                    @error('password')
                        <p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 text-rose-500 focus:ring-rose-400 border-rose-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-slate-600">
                        Ingat saya
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-rose-400 text-white py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                Masuk
            </button>
        </form>
    </div>
</div>
@endsection
