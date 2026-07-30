@extends('layouts.app', ['hideNav' => true])

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <style>
        .font-display { font-family: "Cormorant Garamond", serif; font-optical-sizing: auto; font-weight: 500; font-style: normal; }
        .font-body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
@endpush

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md mb-6">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <h1 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A]">Buat Akun Baru</h1>
            <p class="text-sm text-[#6E8577] mt-2">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">
                    Masuk di sini
                </a>
            </p>
        </div>

        <form class="space-y-6" method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nama Lengkap</label>
                <input id="name" name="name" type="text" required autocomplete="name"
                       class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2.5 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                       placeholder="Masukkan nama lengkap"
                       value="{{ old('name') }}">
                @error('name')
                    <p class="mt-1.5 text-xs text-[#D37897]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Email</label>
                <input id="email" name="email" type="email" required autocomplete="email"
                       class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2.5 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                       placeholder="email@contoh.com"
                       value="{{ old('email') }}">
                @error('email')
                    <p class="mt-1.5 text-xs text-[#D37897]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nomor HP / WhatsApp</label>
                <input id="phone" name="phone" type="text" required autocomplete="tel"
                       class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2.5 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                       placeholder="08xxxxxxxxxx"
                       value="{{ old('phone') }}">
                @error('phone')
                    <p class="mt-1.5 text-xs text-[#D37897]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2.5 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                       placeholder="Minimal 8 karakter, huruf & angka">
                @error('password')
                    <p class="mt-1.5 text-xs text-[#D37897]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2.5 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                       placeholder="Ulangi password">
                @error('password_confirmation')
                    <p class="mt-1.5 text-xs text-[#D37897]">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide py-3 transition-colors duration-200">
                Daftar Sekarang
            </button>
        </form>
    </div>
</div>
@endsection
