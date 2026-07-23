@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h1 class="text-center text-3xl font-bold text-pink-800">🌸 BuketBunga</h1>
            <h2 class="mt-6 text-center text-2xl font-bold text-pink-700">Masuk ke Akun Anda</h2>
            <p class="mt-2 text-center text-sm text-pink-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-pink-600 hover:text-pink-500">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-pink-700">Email</label>
                    <input id="email" name="email" type="email" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="email@contoh.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-pink-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="Masukkan password">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-pink-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-pink-700">
                            Ingat saya
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
