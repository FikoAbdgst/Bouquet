@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h1 class="text-center text-3xl font-bold text-pink-800">🌸 BuketBunga</h1>
            <h2 class="mt-6 text-center text-2xl font-bold text-pink-700">Buat Akun Baru</h2>
            <p class="mt-2 text-center text-sm text-pink-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-pink-600 hover:text-pink-500">
                    Masuk di sini
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-pink-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                    <label for="phone" class="block text-sm font-medium text-pink-700">Nomor HP/WhatsApp</label>
                    <input id="phone" name="phone" type="text" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="08xxxxxxxxxx"
                           value="{{ old('phone') }}">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-pink-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="Minimal 8 karakter, huruf & angka">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-pink-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-pink-300 placeholder-pink-400 text-pink-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="Ulangi password">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                    Daftar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
