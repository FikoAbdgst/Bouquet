@extends('layouts.app')

@section('content')

    {{-- ===== SECTION 1: HERO ===== --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-rose-100 via-pink-50 to-white mb-16">
        <div class="absolute inset-0 opacity-30">
            <div class="absolute top-10 left-10 w-72 h-72 bg-rose-200 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-pink-200 rounded-full blur-3xl"></div>
            <div
                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-rose-300 rounded-full blur-3xl opacity-40">
            </div>
        </div>
        <div class="relative px-8 py-16 sm:px-12 sm:py-20 lg:px-20 lg:py-28 text-center">
            <span class="inline-block text-6xl mb-6 animate-bounce" style="animation-duration:3s">💐</span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-800 leading-tight max-w-3xl mx-auto">
                Sampaikan Perasaanmu Lewat
                <span class="bg-gradient-to-r from-rose-500 to-pink-500 bg-clip-text text-transparent">Keindahan Buket
                    Bunga</span>
            </h1>
            <p class="mt-5 text-base sm:text-lg text-slate-500 max-w-xl mx-auto leading-relaxed">
                Buket bunga segar dengan desain elegan, dibuat khusus untuk momen spesialmu. Dari ulang tahun hingga
                pernikahan — kami hadirkan keindahan yang berkesan.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('customer.catalog') }}"
                    class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white font-semibold px-8 py-3.5 rounded-2xl shadow-lg shadow-rose-200 hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Lihat Katalog Buket
                </a>
                <a href="#custom-order"
                    class="inline-flex items-center gap-2 bg-white hover:bg-rose-50 text-rose-600 font-semibold px-8 py-3.5 rounded-2xl border border-rose-200 shadow-sm hover:shadow-md transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Pesan Custom Buket
                </a>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 2: KATEGORI ===== --}}
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Buket Untuk Setiap Momen</h2>
            <p class="mt-2 text-slate-500">Pilih kategori sesuai acaramu</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <a href="{{ route('customer.catalog', ['category' => 'Ulang Tahun']) }}"
                class="group bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <span class="text-4xl block mb-3 group-hover:scale-110 transition-transform duration-300">🎂</span>
                <h3 class="font-semibold text-slate-700 group-hover:text-rose-600 transition">Ulang Tahun</h3>
                <p class="text-xs text-slate-400 mt-1">Rayakan hari spesialmu</p>
            </a>
            <a href="{{ route('customer.catalog', ['category' => 'Wisuda']) }}"
                class="group bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <span class="text-4xl block mb-3 group-hover:scale-110 transition-transform duration-300">🎓</span>
                <h3 class="font-semibold text-slate-700 group-hover:text-rose-600 transition">Wisuda</h3>
                <p class="text-xs text-slate-400 mt-1">Selamat atas pencapaianmu</p>
            </a>
            <a href="{{ route('customer.catalog', ['category' => 'Pernikahan']) }}"
                class="group bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <span class="text-4xl block mb-3 group-hover:scale-110 transition-transform duration-300">💍</span>
                <h3 class="font-semibold text-slate-700 group-hover:text-rose-600 transition">Pernikahan</h3>
                <p class="text-xs text-slate-400 mt-1">Momen bahagia bersama</p>
            </a>
            <a href="{{ route('customer.catalog', ['category' => 'Duka Cita']) }}"
                class="group bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <span class="text-4xl block mb-3 group-hover:scale-110 transition-transform duration-300">🕊️</span>
                <h3 class="font-semibold text-slate-700 group-hover:text-rose-600 transition">Duka Cita</h3>
                <p class="text-xs text-slate-400 mt-1">Ucapan simpati yang tulus</p>
            </a>
        </div>
    </section>

    {{-- ===== SECTION 3: BEST SELLER ===== --}}
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">🔥 Best Seller</h2>
            <p class="mt-2 text-slate-500">Buket paling favorit dari pelanggan kami</p>
        </div>
        @if ($bestSellers->isEmpty())
            <div class="text-center py-12 bg-white rounded-3xl border border-rose-100 shadow-sm">
                <span class="text-5xl block mb-4">🌸</span>
                <p class="text-slate-400">Best seller belum tersedia.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($bestSellers as $index => $product)
                    <div
                        class="group bg-white rounded-3xl border border-rose-100 overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 relative">
                        <span
                            class="absolute top-4 left-4 z-10 bg-gradient-to-r from-orange-400 to-rose-400 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            🔥 Best Seller
                        </span>
                        <a href="{{ route('customer.catalog.show', $product) }}"
                            class="block aspect-[4/3] bg-gradient-to-br from-rose-50 to-pink-50 overflow-hidden relative">
                            @if ($product->primaryImage)
                                <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-6xl text-rose-200">🌸</div>
                            @endif
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                                <span
                                    class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-white/90 rounded-full p-3 shadow-lg">
                                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                        <div class="p-5">
                            <h3 class="font-semibold text-slate-800 text-lg line-clamp-1">{{ $product->name }}</h3>
                            <p class="mt-1 text-rose-500 font-bold text-xl">{{ $product->formatted_price }}</p>
                            @if ($product->stock > 0)
                                <button type="button"
                                    onclick="CartStorage.addItem({{ json_encode(['id' => $product->id, 'name' => $product->name, 'price' => $product->price, 'image' => $product->primaryImage ? Storage::url($product->primaryImage->image_url) : '', 'qty' => 1]) }})"
                                    class="mt-4 w-full bg-rose-400 hover:bg-rose-500 text-white font-semibold py-2.5 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                                    </svg>
                                    Tambah ke Keranjang
                                </button>
                            @else
                                <button disabled
                                    class="mt-4 w-full bg-slate-200 text-slate-400 font-semibold py-2.5 rounded-2xl cursor-not-allowed">Stok
                                    Habis</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('customer.catalog') }}"
                    class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-600 font-semibold transition">
                    Lihat Semua Produk
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        @endif
    </section>

    {{-- ===== SECTION 4: KENAPA PILIH KAMI ===== --}}
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Kenapa Pilih Kami?</h2>
            <p class="mt-2 text-slate-500">Kami hadirkan yang terbaik untuk momen spesialmu</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-14 h-14 mx-auto bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">🌹
                </div>
                <h3 class="font-semibold text-slate-700">Bunga Segar Setiap Hari</h3>
                <p class="text-sm text-slate-400 mt-2 leading-relaxed">Bunga dipilih langsung dari supplier terpercaya,
                    dijamin segar dan tahan lama.</p>
            </div>
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-14 h-14 mx-auto bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">🎨
                </div>
                <h3 class="font-semibold text-slate-700">Desain Custom</h3>
                <p class="text-sm text-slate-400 mt-2 leading-relaxed">Pilih warna, jenis bunga, dan gaya wrapping sesuai
                    keinginanmu.</p>
            </div>
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-14 h-14 mx-auto bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">🚀
                </div>
                <h3 class="font-semibold text-slate-700">Pengiriman Cepat & Aman</h3>
                <p class="text-sm text-slate-400 mt-2 leading-relaxed">Buket dikemas khusus agar tetap cantik sampai di
                    tangan penerima.</p>
            </div>
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 text-center shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="w-14 h-14 mx-auto bg-rose-50 rounded-2xl flex items-center justify-center text-2xl mb-4">💬
                </div>
                <h3 class="font-semibold text-slate-700">Pelayanan Ramah</h3>
                <p class="text-sm text-slate-400 mt-2 leading-relaxed">Tim kami siap membantu dan memberikan rekomendasi
                    terbaik untukmu.</p>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 5: CARA PEMESANAN ===== --}}
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Cara Pemesanan</h2>
            <p class="mt-2 text-slate-500">Empat langkah mudah untuk miliki buket impianmu</p>
        </div>
        <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-8 sm:p-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative">
                {{-- Connector line (desktop) --}}
                <div
                    class="hidden lg:block absolute top-10 left-[12%] right-[12%] h-0.5 bg-gradient-to-r from-rose-200 via-pink-200 to-rose-200">
                </div>

                <div class="relative text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-gradient-to-br from-rose-400 to-pink-500 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-rose-200 relative z-10">
                        1</div>
                    <h3 class="mt-4 font-semibold text-slate-700">Pilih Buket</h3>
                    <p class="text-sm text-slate-400 mt-1.5">Jelajahi katalog dan temukan buket favoritmu.</p>
                </div>
                <div class="relative text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-gradient-to-br from-rose-400 to-pink-500 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-rose-200 relative z-10">
                        2</div>
                    <h3 class="mt-4 font-semibold text-slate-700">Isi Detail Pesanan</h3>
                    <p class="text-sm text-slate-400 mt-1.5">Masukkan nama penerima, alamat, dan catatan khusus.</p>
                </div>
                <div class="relative text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-gradient-to-br from-rose-400 to-pink-500 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-rose-200 relative z-10">
                        3</div>
                    <h3 class="mt-4 font-semibold text-slate-700">Bayar & Konfirmasi</h3>
                    <p class="text-sm text-slate-400 mt-1.5">Lakukan pembayaran dan kirim bukti transfer.</p>
                </div>
                <div class="relative text-center">
                    <div
                        class="w-16 h-16 mx-auto bg-gradient-to-br from-rose-400 to-pink-500 text-white rounded-2xl flex items-center justify-center text-xl font-bold shadow-lg shadow-rose-200 relative z-10">
                        4</div>
                    <h3 class="mt-4 font-semibold text-slate-700">Buket Dikirim</h3>
                    <p class="text-sm text-slate-400 mt-1.5">Buket cantikmu siap dikirim ke tujuan!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 6: TESTIMONI ===== --}}
    <section class="mb-16">
        <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-800">Kata Mereka Tentang Kami</h2>
            <p class="mt-2 text-slate-500">Review dari pelanggan yang puas</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-1 text-amber-400 mb-3">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic">"Buketnya cantik banget! Pacar saya senang sekali.
                    Bunganya segar dan packingnya juga rapi. Pasti order lagi下次!"</p>
                <div class="mt-4 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-300 to-pink-400 flex items-center justify-center text-white text-sm font-bold">
                        A</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Andini</p>
                        <p class="text-xs text-slate-400">Jakarta Selatan</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-1 text-amber-400 mb-3">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic">"Pesan buket wisuda untuk sahabat, hasilnya lebih
                    bagus dari foto! Admin juga ramah dan fast response. Terima kasih BuketBunga!"</p>
                <div class="mt-4 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-300 to-pink-400 flex items-center justify-center text-white text-sm font-bold">
                        R</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Raka</p>
                        <p class="text-xs text-slate-400">Bandung</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-white rounded-3xl border border-rose-100 p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center gap-1 text-amber-400 mb-3">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="text-slate-600 text-sm leading-relaxed italic">"Sudah 3x pesen buket ulang tahun di sini.
                    Kualitas konsisten dan harganya sangat reasonable. Recommended banget!"</p>
                <div class="mt-4 flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-gradient-to-br from-rose-300 to-pink-400 flex items-center justify-center text-white text-sm font-bold">
                        M</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Mutiara</p>
                        <p class="text-xs text-slate-400">Surabaya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 7: CUSTOM ORDER ===== --}}
    <section id="custom-order" class="mb-16 scroll-mt-24">
        <div
            class="bg-gradient-to-br from-rose-50 via-pink-50 to-white rounded-3xl border border-rose-100 shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <div class="p-8 sm:p-10 lg:p-12">
                    <span class="inline-block bg-rose-100 text-rose-600 text-xs font-bold px-3 py-1 rounded-full mb-4">✨
                        CUSTOM ORDER</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 leading-tight">Buat Buket Impianmu Sendiri
                    </h2>
                    <p class="mt-3 text-slate-500 leading-relaxed">
                        Punya ide sendiri untuk buketmu? Ceritakan pada kami! Pilih jenis bunga, warna kertas wrap, ukuran,
                        dan sentuhan personal lainnya.
                    </p>
                    <ul class="mt-5 space-y-2.5 text-sm text-slate-600">
                        <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">✓</span> Pilih jenis & warna
                            bunga</li>
                        <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">✓</span> Tentukan warna &
                            model wrapping</li>
                        <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">✓</span> Tambah kartu ucapan
                            personal</li>
                        <li class="flex items-start gap-2"><span class="text-rose-400 mt-0.5">✓</span> Konsultasi budget &
                            rekomendasi</li>
                    </ul>
                </div>
                <div class="bg-white p-8 sm:p-10 lg:p-12 border-t lg:border-t-0 lg:border-l border-rose-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-5">Request Custom Buket</h3>
                    <form id="custom-order-form" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nama
                                Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama kamu"
                                class="w-full border border-rose-200 rounded-2xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nomor
                                WhatsApp</label>
                            <input type="tel" name="phone" required placeholder="08XXXXXXXXXX"
                                pattern="08[0-9]{8,13}"
                                class="w-full border border-rose-200 rounded-2xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Budget
                                (Rupiah)</label>
                            <input type="number" name="budget" min="0" step="50000"
                                placeholder="Contoh: 350000"
                                class="w-full border border-rose-200 rounded-2xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400">
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Catatan
                                Custom</label>
                            <textarea name="notes" rows="3" placeholder="Jenis bunga, warna wrapping, ukuran, pesan khusus..."
                                class="w-full border border-rose-200 rounded-2xl py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 placeholder-slate-400 resize-none"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-3 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Kirim via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTION 8: FOOTER (inline, replacing layout footer for landing) ===== --}}
    {{-- We use layout's footer, so no separate footer needed here. --}}
@endsection

@push('scripts')
    <script>
        document.getElementById('custom-order-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const name = form.name.value.trim();
            const phone = form.phone.value.trim();
            const budget = form.budget.value;
            const notes = form.notes.value.trim();
            const waNumber = '{{ config('app.wa_admin_number', '6281234567890') }}';

            let msg = 'Halo Admin, saya ingin request Custom Buket 🌸\n\n';
            msg += '*Nama:* ' + name + '\n';
            msg += '*WhatsApp:* ' + phone + '\n';
            if (budget) msg += '*Budget:* Rp ' + Number(budget).toLocaleString('id-ID') + '\n';
            if (notes) msg += '*Catatan:* ' + notes + '\n';
            msg += '\nTerima kasih!';

            const url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(msg);
            window.open(url, '_blank');
        });
    </script>
@endpush
