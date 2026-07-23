@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-pink-200 p-6">
    <h1 class="text-2xl font-bold text-pink-800 mb-2">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-pink-600">Selamat datang di BuketBunga. Temukan buket bunga terindah untuk momen spesial Anda.</p>

    <div class="mt-6">
        <a href="{{ route('customer.catalog') }}" class="inline-block bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
            Lihat Katalog →
        </a>
    </div>
</div>
@endsection
