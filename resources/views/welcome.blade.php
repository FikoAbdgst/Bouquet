@extends('layouts.app')

{{--
    ============================================================
    DESAIN BARU — mengikuti arah desain pada contoh yang dilampirkan
    (tipografi serif editorial, palet wine/olive/slate yang tenang,
    garis tipis pengganti shadow/gradient, tanpa emoji).

    Font: pastikan @stack('styles') tersedia di <head> layouts.app,
    karena Google Fonts di-push lewat @push('styles') di bawah.
    Jika belum ada, tambahkan {{ '@stack(\'styles\')' }} sebelum </head>.
    ============================================================
--}}

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap"
        rel="stylesheet">
    <style>
        .font-display {
            font-family: "Cormorant Garamond", serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }


        .font-body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
@endpush

@section('content')
    <div class="font-body text-[#2A2724]">

        {{-- ===== SECTION 1: HERO (full-width, full-height, no padding) ===== --}}
        <section class="relative -mx-4 sm:-mx-6 lg:-mx-8 -mt-20">
            <div class="relative min-h-[80vh] lg:min-h-screen overflow-hidden"
                style="width: 100vw; margin-left: calc(-50vw + 50%);">
                <img src="{{ asset('images/hero.png') }}" alt="Buket Bunga"
                    class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/35"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center pt-40 px-6">
                    <span class="text-[11px] sm:text-xs tracking-[0.35em] uppercase text-white/80 mb-4">
                        Ngabuket Bandung
                    </span>
                    <h1
                        class="font-display text-4xl sm:text-6xl lg:text-7xl font-medium text-white leading-[0.95] tracking-tight">
                        BOUQUET
                    </h1>
                    <p class="mt-3 font-display italic text-lg sm:text-xl text-white/90">
                        Rangkai Momen Spesial Bersama Ngabuket Bandung
                    </p>

                    <div class="mt-9 flex flex-col sm:flex-row items-center gap-4">
                        <a href="{{ route('customer.catalog') }}"
                            class="inline-flex items-center gap-2 bg-white text-[#2A2724] hover:bg-white/90 text-sm font-medium tracking-wide px-8 py-3 rounded-full transition-colors duration-200">
                            Lihat Katalog Buket
                        </a>
                        <a href="#custom-order"
                            class="inline-flex items-center gap-2 border border-white text-white hover:bg-white hover:text-[#2A2724] text-sm font-medium tracking-wide px-8 py-3 rounded-full transition-colors duration-200">
                            Pesan Custom Buket
                        </a>
                    </div>
                </div>
            </div>
        </section>
        {{-- ===== SECTION: ABOUT US ===== --}}
        <section class="py-20 -mx-4 sm:-mx-6 lg:-mx-8 overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-[3fr_7fr] items-center">
                <div class="relative overflow-hidden rounded-lg">
                    <img src="{{ asset('images/about-bouquet.png') }}" alt="Buket Bunga Ngabuket Bandung"
                        class="object-cover">
                    <h2
                        class="absolute -top-44 -right-24 inset-0 flex items-center justify-center font-display text-2xl sm:text-3xl lg:text-4xl font-medium text-black">
                        ABOUT US
                    </h2>
                </div>
                <div class="pr-32 pl-20 pt-20">
                    <p class="text-[#6B6459] font-display leading-relaxed text-base sm:text-lg">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Eum, id. Minima ipsa hic nihil animi
                        eveniet iusto sequi fugit nemo blanditiis, eligendi temporibus illo voluptatem ex voluptatum unde
                        impedit velit? Dolores voluptas, at vel minima, est temporibus itaque harum aut eveniet, iusto dicta
                        tenetur corporis illo autem cupiditate a officiis.
                    </p>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 2: KATEGORI INTI ===== --}}
        @php
            $coreCategories = [
                (object) [
                    'slug' => 'fresh-flower',
                    'name' => 'Fresh Flower',
                    'icon' =>
                        '<path d="M12 6c-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2C2 6 1 8 2 10c1 2 4 4 10 6 6-2 9-4 10-6 1-2 0-4-2-4-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2z"/><path d="M12 16v4"/><path d="M9 20h6"/>',
                    'subtitle' => 'Segar dan harum alami',
                ],
                (object) [
                    'slug' => 'artificial-flower',
                    'name' => 'Artificial Flower',
                    'icon' =>
                        '<path d="M12 4v12"/><path d="M8 8c-2 0-4 2-4 4s2 4 4 4"/><path d="M16 8c2 0 4 2 4 4s-2 4-4 4"/><path d="M12 16a4 4 0 0 1-4 4"/><path d="M12 16a4 4 0 0 0 4 4"/>',
                    'subtitle' => 'Tak pernah layu, selalu indah',
                ],
                (object) [
                    'slug' => 'thumbelina-bouquet',
                    'name' => 'Thumbelina Bouquet',
                    'icon' =>
                        '<circle cx="12" cy="7" r="3"/><path d="M5 21c0-3.9 3.1-7 7-7s7 3.1 7 7"/><path d="M9 21c0-1.7 1.3-3 3-3s3 1.3 3 3"/>',
                    'subtitle' => 'Mungil dan menggemaskan',
                ],
                (object) [
                    'slug' => 'buket-uang',
                    'name' => 'Buket Uang',
                    'icon' => '<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h2.5a3.5 3.5 0 010 7H7"/>',
                    'subtitle' => 'Hadiah berkesan bernilai',
                ],
            ];
        @endphp
        <section class="mb-20">
            <div class="text-center mb-10">
                <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Kategori</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-2">Buket Untuk Setiap
                    Momen</h2>
            </div>
            <div
                class="grid grid-cols-2 lg:grid-cols-4 border border-[#E7DDD1] divide-x divide-y sm:divide-y-0 divide-[#E7DDD1]">
                @foreach ($coreCategories as $cat)
                    <a href="{{ route('customer.catalog', ['category' => $cat->slug]) }}"
                        class="group flex flex-col items-center justify-center gap-3 px-4 py-10 hover:bg-[#FBF7F2] transition-colors duration-200">
                        <svg class="w-6 h-6 text-[#6B2737]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            {!! $cat->icon !!}
                        </svg>
                        <h3
                            class="font-display text-sm text-[#2A2724] group-hover:text-[#6B2737] transition-colors text-center">
                            {{ $cat->name }}</h3>
                        <p class="text-xs text-[#8C8579] text-center">
                            {{ $cat->subtitle }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ===== SECTION 3: BEST SELLER ===== --}}
        <section class="mb-20">
            <div class="text-center mb-10">
                <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Favorit Pelanggan</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-2">Best Seller</h2>
            </div>
            @if ($bestSellers->isEmpty())
                <div class="text-center py-16 border border-dashed border-[#E7DDD1]">
                    <p class="text-[#8C8579] text-sm">Best seller belum tersedia.</p>
                </div>
            @else
                @php $swatches = ['#DCD6C9', '#8E9A7C', '#B9C3C6', '#D9C2B4', '#A8AC98', '#C9B4B4']; @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    @foreach ($bestSellers as $index => $product)
                        <div class="group">
                            <a href="{{ route('customer.catalog.show', $product) }}"
                                class="block aspect-[3/4] overflow-hidden relative"
                                style="background-color: {{ $swatches[$index % count($swatches)] }}">
                                @if ($product->primaryImage)
                                    <img src="{{ Storage::url($product->primaryImage->image_url) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                                @endif
                                <span
                                    class="absolute top-4 left-4 text-[10px] tracking-[0.2em] uppercase bg-white/90 text-[#6B2737] px-3 py-1.5">
                                    Best Seller
                                </span>
                            </a>
                            <div class="mt-4">
                                <h3 class="font-display text-lg text-[#2A2724] line-clamp-1">{{ $product->name }}
                                </h3>
                                <p class="mt-1 text-[#6B2737] font-medium">{{ $product->formatted_price }}</p>
                                @if ($product->stock > 0)
                                    <button type="button"
                                        onclick="CartStorage.addItem({{ json_encode(['id' => $product->id, 'name' => $product->name, 'price' => $product->price, 'image' => $product->primaryImage ? Storage::url($product->primaryImage->image_url) : '', 'qty' => 1]) }})"
                                        class="mt-4 w-full border border-[#2A2724] hover:bg-[#2A2724] hover:text-white text-[#2A2724] text-sm tracking-wide py-2.5 transition-colors duration-200">
                                        Tambah ke Keranjang
                                    </button>
                                @else
                                    <button disabled
                                        class="mt-4 w-full border border-[#E7DDD1] text-[#B8AFA2] text-sm py-2.5 cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12 text-center">
                    <a href="{{ route('customer.catalog') }}"
                        class="inline-flex items-center gap-2 text-sm tracking-wide text-[#6B2737] border-b border-[#6B2737] pb-0.5 hover:gap-3 transition-all duration-200">
                        Lihat Semua Produk
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </section>

        {{-- ===== SECTION 4: KENAPA PILIH KAMI ===== --}}
        <section class="mb-20">
            <div class="text-center mb-12">
                <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Kenapa Pilih Kami</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-2">Kualitas yang Bisa
                    Kamu
                    Percaya</h2>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 border-t border-b border-[#E7DDD1] divide-y sm:divide-y-0 sm:divide-x divide-[#E7DDD1]">
                <div class="px-6 py-10 text-center">
                    <svg class="w-6 h-6 mx-auto text-[#6B2737]" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <circle cx="12" cy="7" r="2" fill="none" />
                        <circle cx="12" cy="17" r="2" fill="none" />
                        <circle cx="7" cy="12" r="2" fill="none" />
                        <circle cx="17" cy="12" r="2" fill="none" />
                        <circle cx="12" cy="12" r="2.2" fill="currentColor" stroke="none" />
                    </svg>
                    <h3 class="font-display text-base mt-4 text-[#2A2724]">Bunga Segar Setiap Hari</h3>
                    <p class="text-sm text-[#8C8579] mt-2 leading-relaxed">Bunga dipilih langsung dari supplier
                        terpercaya,
                        dijamin segar dan tahan lama.</p>
                </div>
                <div class="px-6 py-10 text-center">
                    <svg class="w-6 h-6 mx-auto text-[#6B2737]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 20l4-1 10-10-3-3L5 16l-1 4z" />
                        <path d="M14 6l3 3" />
                    </svg>
                    <h3 class="font-display text-base mt-4 text-[#2A2724]">Desain Custom</h3>
                    <p class="text-sm text-[#8C8579] mt-2 leading-relaxed">Pilih warna, jenis bunga, dan gaya
                        wrapping
                        sesuai keinginanmu.</p>
                </div>
                <div class="px-6 py-10 text-center">
                    <svg class="w-6 h-6 mx-auto text-[#6B2737]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 2L11 13" />
                        <path d="M22 2l-7 20-4-9-9-4 20-7z" />
                    </svg>
                    <h3 class="font-display text-base mt-4 text-[#2A2724]">Pengiriman Cepat &amp; Aman</h3>
                    <p class="text-sm text-[#8C8579] mt-2 leading-relaxed">Buket dikemas khusus agar tetap cantik
                        sampai di
                        tangan penerima.</p>
                </div>
                <div class="px-6 py-10 text-center">
                    <svg class="w-6 h-6 mx-auto text-[#6B2737]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12a8 8 0 01-11.5 7.2L4 21l1.8-5.5A8 8 0 1121 12z" />
                    </svg>
                    <h3 class="font-display text-base mt-4 text-[#2A2724]">Pelayanan Ramah</h3>
                    <p class="text-sm text-[#8C8579] mt-2 leading-relaxed">Tim kami siap membantu dan memberikan
                        rekomendasi terbaik untukmu.</p>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 5: CARA PEMESANAN ===== --}}
        <section class="mb-20">
            <div class="text-center mb-12">
                <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Panduan</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-2">Cara Pemesanan</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-10">
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full border border-[#6B2737] flex items-center justify-center font-display text-lg text-[#6B2737]">
                        1</div>
                    <h3 class="mt-4 font-display text-base text-[#2A2724]">Pilih Buket</h3>
                    <p class="text-sm text-[#8C8579] mt-1.5">Jelajahi katalog dan temukan buket favoritmu.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full border border-[#6B2737] flex items-center justify-center font-display text-lg text-[#6B2737]">
                        2</div>
                    <h3 class="mt-4 font-display text-base text-[#2A2724]">Isi Detail Pesanan</h3>
                    <p class="text-sm text-[#8C8579] mt-1.5">Masukkan nama penerima, alamat, dan catatan khusus.
                    </p>
                </div>
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full border border-[#6B2737] flex items-center justify-center font-display text-lg text-[#6B2737]">
                        3</div>
                    <h3 class="mt-4 font-display text-base text-[#2A2724]">Bayar &amp; Konfirmasi</h3>
                    <p class="text-sm text-[#8C8579] mt-1.5">Lakukan pembayaran dan kirim bukti transfer.</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-14 h-14 mx-auto rounded-full border border-[#6B2737] flex items-center justify-center font-display text-lg text-[#6B2737]">
                        4</div>
                    <h3 class="mt-4 font-display text-base text-[#2A2724]">Buket Dikirim</h3>
                    <p class="text-sm text-[#8C8579] mt-1.5">Buket cantikmu siap dikirim ke tujuan!</p>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 6: TESTIMONI ===== --}}
        <section class="mb-20">
            <div class="text-center mb-12">
                <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Testimoni</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-2">Kata Mereka Tentang
                    Kami</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="border border-[#E7DDD1] p-8">
                    <div class="flex items-center gap-1 text-[#6B2737] mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-display italic text-[#2A2724] text-base leading-relaxed">"Buketnya cantik
                        banget! Pacar
                        saya senang sekali. Bunganya segar dan packingnya juga rapi. Pasti order lagi!"</p>
                    <div class="mt-6 pt-4 border-t border-[#E7DDD1] flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-[#6B2737] text-white flex items-center justify-center text-sm font-display">
                            A</div>
                        <div>
                            <p class="text-sm font-medium text-[#2A2724]">Andini</p>
                            <p class="text-xs text-[#8C8579]">Jakarta Selatan</p>
                        </div>
                    </div>
                </div>
                <div class="border border-[#E7DDD1] p-8">
                    <div class="flex items-center gap-1 text-[#6B2737] mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-display italic text-[#2A2724] text-base leading-relaxed">"Pesan buket wisuda
                        untuk
                        sahabat, hasilnya lebih bagus dari foto! Admin juga ramah dan fast response. Terima kasih!"
                    </p>
                    <div class="mt-6 pt-4 border-t border-[#E7DDD1] flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-[#6B2737] text-white flex items-center justify-center text-sm font-display">
                            R</div>
                        <div>
                            <p class="text-sm font-medium text-[#2A2724]">Raka</p>
                            <p class="text-xs text-[#8C8579]">Bandung</p>
                        </div>
                    </div>
                </div>
                <div class="border border-[#E7DDD1] p-8">
                    <div class="flex items-center gap-1 text-[#6B2737] mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="font-display italic text-[#2A2724] text-base leading-relaxed">"Sudah 3x pesan buket
                        ulang
                        tahun di sini. Kualitas konsisten dan harganya sangat reasonable. Recommended banget!"</p>
                    <div class="mt-6 pt-4 border-t border-[#E7DDD1] flex items-center gap-3">
                        <div
                            class="w-9 h-9 rounded-full bg-[#6B2737] text-white flex items-center justify-center text-sm font-display">
                            M</div>
                        <div>
                            <p class="text-sm font-medium text-[#2A2724]">Mutiara</p>
                            <p class="text-xs text-[#8C8579]">Surabaya</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 7: CUSTOM ORDER ===== --}}
        <section id="custom-order" class="mb-4 scroll-mt-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 border border-[#E7DDD1]">
                <div class="p-8 sm:p-12 lg:p-16 bg-[#FBF7F2] flex flex-col justify-center">
                    <span class="text-xs tracking-[0.3em] uppercase text-[#8C8579]">Custom Order</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#2A2724] mt-3 leading-tight">
                        Buat Buket Impianmu Sendiri
                    </h2>
                    <p class="mt-4 text-[#6B6459] leading-relaxed">
                        Punya ide sendiri untuk buketmu? Ceritakan pada kami! Pilih jenis bunga, warna kertas wrap,
                        ukuran, dan sentuhan personal lainnya.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-[#2A2724]">
                        <li class="flex items-start gap-3"><span class="text-[#6B2737] mt-0.5">—</span> Pilih
                            jenis &amp;
                            warna bunga</li>
                        <li class="flex items-start gap-3"><span class="text-[#6B2737] mt-0.5">—</span> Tentukan
                            warna
                            &amp; model wrapping</li>
                        <li class="flex items-start gap-3"><span class="text-[#6B2737] mt-0.5">—</span> Tambah
                            kartu
                            ucapan personal</li>
                        <li class="flex items-start gap-3"><span class="text-[#6B2737] mt-0.5">—</span> Konsultasi
                            budget
                            &amp; rekomendasi</li>
                    </ul>
                </div>
                <div class="p-8 sm:p-12 lg:p-16 border-t lg:border-t-0 lg:border-l border-[#E7DDD1]">
                    <h3 class="font-display text-lg text-[#2A2724] mb-6">Request Custom Buket</h3>
                    <form id="custom-order-form" class="space-y-5">
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#8C8579] mb-2">Nama
                                Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama kamu"
                                class="w-full border-0 border-b border-[#D9CFC3] focus:border-[#6B2737] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#B8AFA2] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#8C8579] mb-2">Nomor
                                WhatsApp</label>
                            <input type="tel" name="phone" required placeholder="08XXXXXXXXXX"
                                pattern="08[0-9]{8,13}"
                                class="w-full border-0 border-b border-[#D9CFC3] focus:border-[#6B2737] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#B8AFA2] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#8C8579] mb-2">Budget
                                (Rupiah)</label>
                            <input type="number" name="budget" min="0" step="50000"
                                placeholder="Contoh: 350000"
                                class="w-full border-0 border-b border-[#D9CFC3] focus:border-[#6B2737] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#B8AFA2] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#8C8579] mb-2">Catatan
                                Custom</label>
                            <textarea name="notes" rows="3" placeholder="Jenis bunga, warna wrapping, ukuran, pesan khusus..."
                                class="w-full border-0 border-b border-[#D9CFC3] focus:border-[#6B2737] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#B8AFA2] outline-none transition-colors resize-none"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-[#25D366] hover:bg-[#1FBE5C] text-white font-medium tracking-wide py-3 rounded-full transition-colors duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Kirim via WhatsApp
                        </button>
                    </form>
                </div>
            </div>
        </section>

    </div>
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

            let msg = 'Halo Admin, saya ingin request Custom Buket\n\n';
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
