@extends('layouts.app')

{{--
    ============================================================
    PALET WARNA: Mint Julep Mood (Pink & Green)
    - Rose        : #D37897  (aksen utama — tombol, ikon, link)
    - Rose Muda   : #E09FB3  (aksen sekunder, border rose)
    - Pink Pucat  : #F9DEE5  (tint background pink)
    - Sage        : #799F76  (aksen hijau sekunder — ikon, dekor)
    - Forest      : #457359  (aksen hijau gelap — hover, badge, teks judul)
    - Teks utama  : #33413A  (dark neutral, sedikit hijau)
    - Teks muted  : #6E8577  (sage-gray, untuk subtext)
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
    @php
        $catFields = ($categories ?? collect())->keyBy('id')->map(function($c) {
            return $c->fields->map(function($field) {
                return [
                    'id' => $field->id,
                    'label' => $field->label,
                    'type' => $field->type,
                    'is_required' => $field->is_required,
                    'options' => $field->options,
                    'field_options' => $field->fieldOptions->map(function($o) {
                        return ['id' => $o->id, 'name' => $o->name, 'price' => $o->price];
                    })->toArray(),
                ];
            });
        });
    @endphp
    <script>
        window.categoryFields = @json($catFields);
    </script>
    <div class="font-body text-[#33413A] bg-[#FFFDFC]">

        {{-- ===== SECTION 1: HERO ===== --}}
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
                            class="inline-flex items-center gap-2 bg-white text-[#33413A] hover:bg-white/90 text-sm font-medium tracking-wide px-8 py-3 rounded-full transition-colors duration-200">
                            Lihat Katalog Buket
                        </a>
                        <a href="#custom-order"
                            class="inline-flex items-center gap-2 border border-white text-white hover:bg-white hover:text-[#D37897] text-sm font-medium tracking-wide px-8 py-3 rounded-full transition-colors duration-200">
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
                        class="absolute -top-44 -right-24 inset-0 flex items-center justify-center font-display text-2xl sm:text-3xl lg:text-4xl font-medium text-[#457359]">
                        ABOUT US
                    </h2>
                </div>
                <div class="pr-32 pl-20 pt-20">
                    <p class="text-[#5C6F5E] font-display leading-relaxed text-base sm:text-lg">
                        Sejak 2021, Ngabuket Bandung hadir untuk membantu merayakan setiap momen berharga melalui rangkaian
                        buket dan hadiah yang dibuat dengan penuh perhatian. Setiap pesanan dapat disesuaikan dengan
                        kebutuhan dan tema yang diinginkan, sehingga memberikan kesan yang lebih personal.
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
                    'color' => '#D37897',
                ],
                (object) [
                    'slug' => 'artificial-flower',
                    'name' => 'Artificial Flower',
                    'icon' =>
                        '<path d="M12 4v12"/><path d="M8 8c-2 0-4 2-4 4s2 4 4 4"/><path d="M16 8c2 0 4 2 4 4s-2 4-4 4"/><path d="M12 16a4 4 0 0 1-4 4"/><path d="M12 16a4 4 0 0 0 4 4"/>',
                    'subtitle' => 'Tak pernah layu, selalu indah',
                    'color' => '#799F76',
                ],
                (object) [
                    'slug' => 'thumbelina-bouquet',
                    'name' => 'Thumbelina Bouquet',
                    'icon' =>
                        '<circle cx="12" cy="7" r="3"/><path d="M5 21c0-3.9 3.1-7 7-7s7 3.1 7 7"/><path d="M9 21c0-1.7 1.3-3 3-3s3 1.3 3 3"/>',
                    'subtitle' => 'Mungil dan menggemaskan',
                    'color' => '#D37897',
                ],
                (object) [
                    'slug' => 'buket-uang',
                    'name' => 'Buket Uang',
                    'icon' => '<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 000 7h2.5a3.5 3.5 0 010 7H7"/>',
                    'subtitle' => 'Hadiah berkesan bernilai',
                    'color' => '#457359',
                ],
            ];
        @endphp
        <section class="mb-20">
            <div class="text-center mb-10">
                <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Kategori</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Buket Untuk Setiap Momen</h2>
            </div>
            <div
                class="grid grid-cols-2 lg:grid-cols-4 border border-[#EFD3DE] divide-x divide-y sm:divide-y-0 divide-[#EFD3DE]">
                @foreach ($coreCategories as $cat)
                    <a href="{{ route('customer.catalog', ['category' => $cat->slug]) }}"
                        class="group flex flex-col items-center justify-center gap-3 px-4 py-10 hover:bg-[#F9DEE5]/40 transition-colors duration-200">
                        <svg class="w-6 h-6 transition-colors" style="color: {{ $cat->color }}" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                            {!! $cat->icon !!}
                        </svg>
                        <h3
                            class="font-display text-sm text-[#33413A] group-hover:text-[#D37897] transition-colors text-center">
                            {{ $cat->name }}</h3>
                        <p class="text-xs text-[#6E8577] text-center">{{ $cat->subtitle }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- ===== SECTION 3: BEST SELLER ===== --}}
        <section class="mb-20">
            <div class="text-center mb-10">
                <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Favorit Pelanggan</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Best Seller</h2>
            </div>
            @if ($bestSellers->isEmpty())
                <div class="text-center py-16 border border-dashed border-[#EFD3DE]">
                    <p class="text-[#6E8577] text-sm">Best seller belum tersedia.</p>
                </div>
            @else
                @php $swatches = ['#F9DEE5', '#D6E5D3', '#E09FB3', '#C7D9C4', '#F0C9D9', '#A9C4A5']; @endphp
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
                                    class="absolute top-4 left-4 text-[10px] tracking-[0.2em] uppercase bg-white/90 text-[#D37897] px-3 py-1.5">
                                    Best Seller
                                </span>
                            </a>
                            <div class="mt-4">
                                <h3 class="font-display text-lg text-[#33413A] line-clamp-1">{{ $product->name }}</h3>
                                <p class="mt-1 text-[#D37897] font-medium">{{ $product->formatted_price }}</p>
                                @if ($product->stock > 0)
                                    @php $catId = $product->productCategory?->id; @endphp
                                    <button type="button" data-pid="{{ $product->id }}" data-name="{{ $product->name }}"
                                        data-price="{{ $product->price }}"
                                        data-image="{{ $product->primaryImage ? Storage::url($product->primaryImage->image_url) : '' }}"
                                        data-catid="{{ $catId ?? '' }}" onclick="openQuickAdd(this)"
                                        class="mt-4 w-full border border-[#D37897] hover:bg-[#D37897] hover:border-[#D37897] hover:text-white text-[#33413A] text-sm tracking-wide py-2.5 transition-colors duration-200">
                                        Tambah ke Keranjang
                                    </button>
                                @else
                                    <button disabled
                                        class="mt-4 w-full border border-[#EFD3DE] text-[#C9A9B4] text-sm py-2.5 cursor-not-allowed">
                                        Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12 text-center">
                    <a href="{{ route('customer.catalog') }}"
                        class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200">
                        Lihat Semua Produk
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </section>

        {{-- ===== SECTION 4: KENAPA PILIH KAMI (tint hijau pucat, full-bleed) ===== --}}
        <section class="py-20 -mx-4 sm:-mx-6 lg:-mx-8 bg-[#E9F1E7] mb-20">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Kenapa Pilih Kami</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Kualitas yang Bisa Kamu
                        Percaya</h2>
                </div>
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 border-t border-b border-[#D6E5D3] divide-y sm:divide-y-0 sm:divide-x divide-[#D6E5D3]">
                    <div class="px-6 py-10 text-center">
                        <svg class="w-6 h-6 mx-auto text-[#457359]" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <circle cx="12" cy="7" r="2" fill="none" />
                            <circle cx="12" cy="17" r="2" fill="none" />
                            <circle cx="7" cy="12" r="2" fill="none" />
                            <circle cx="17" cy="12" r="2" fill="none" />
                            <circle cx="12" cy="12" r="2.2" fill="currentColor" stroke="none" />
                        </svg>
                        <h3 class="font-display text-base mt-4 text-[#33413A]">Bunga Segar Setiap Hari</h3>
                        <p class="text-sm text-[#6E8577] mt-2 leading-relaxed">Bunga dipilih langsung dari supplier
                            terpercaya, dijamin segar dan tahan lama.</p>
                    </div>
                    <div class="px-6 py-10 text-center">
                        <svg class="w-6 h-6 mx-auto text-[#D37897]" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 20l4-1 10-10-3-3L5 16l-1 4z" />
                            <path d="M14 6l3 3" />
                        </svg>
                        <h3 class="font-display text-base mt-4 text-[#33413A]">Desain Custom</h3>
                        <p class="text-sm text-[#6E8577] mt-2 leading-relaxed">Pilih warna, jenis bunga, dan gaya wrapping
                            sesuai keinginanmu.</p>
                    </div>
                    <div class="px-6 py-10 text-center">
                        <svg class="w-6 h-6 mx-auto text-[#457359]" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 2L11 13" />
                            <path d="M22 2l-7 20-4-9-9-4 20-7z" />
                        </svg>
                        <h3 class="font-display text-base mt-4 text-[#33413A]">Pengiriman Cepat &amp; Aman</h3>
                        <p class="text-sm text-[#6E8577] mt-2 leading-relaxed">Buket dikemas khusus agar tetap cantik
                            sampai di tangan penerima.</p>
                    </div>
                    <div class="px-6 py-10 text-center">
                        <svg class="w-6 h-6 mx-auto text-[#D37897]" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12a8 8 0 01-11.5 7.2L4 21l1.8-5.5A8 8 0 1121 12z" />
                        </svg>
                        <h3 class="font-display text-base mt-4 text-[#33413A]">Pelayanan Ramah</h3>
                        <p class="text-sm text-[#6E8577] mt-2 leading-relaxed">Tim kami siap membantu dan memberikan
                            rekomendasi terbaik untukmu.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 5: CARA PEMESANAN (interactive step demo) ===== --}}
        <section class="mb-20">
            <div class="text-center mb-16">
                <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Panduan</span>
                <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Cara Pemesanan</h2>
                <p class="text-sm text-[#6E8577] mt-2">Klik tombol di bawah untuk lihat simulasi alur pesanmu</p>
            </div>

            <style>
                .order-slide-content {
                    transition: opacity 0.35s ease, transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
                }

                .order-slide-exit {
                    opacity: 0;
                    transform: translateY(-24px);
                }

                .order-slide-enter {
                    opacity: 0;
                    transform: translateY(24px);
                }

                .order-illustration-icon {
                    animation: petalDrift 2.6s ease-in-out infinite;
                }

                @keyframes petalDrift {

                    0%,
                    100% {
                        transform: translateY(0) rotate(0deg);
                    }

                    50% {
                        transform: translateY(-5px) rotate(3deg);
                    }
                }

                .order-step-row {
                    transition: background-color 0.4s ease;
                }

                .order-step-num {
                    transition: background-color 0.4s ease, border-color 0.4s ease, color 0.4s ease, transform 0.4s ease;
                }

                .order-step-num.is-done {
                    background-color: #D37897;
                    border-color: #D37897;
                    color: #FFFDFC;
                    transform: scale(1.08);
                }

                .order-step-row.is-done .order-step-title {
                    color: #D37897;
                }
            </style>

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_1.2fr] gap-10 lg:gap-16 items-stretch">

                <div class="flex flex-col justify-center gap-3">
                    @foreach ([['1', 'Pilih Buket', 'Jelajahi katalog dan temukan buket favoritmu.'], ['2', 'Isi Detail Pesanan', 'Masukkan nama penerima, alamat, dan catatan khusus.'], ['3', 'Bayar & Konfirmasi', 'Lakukan pembayaran dan kirim bukti transfer.'], ['4', 'Buket Dikirim', 'Buket cantikmu siap dikirim ke tujuan!']] as $i => [$num, $title, $desc])
                        <div data-order-row="{{ $i }}"
                            class="order-step-row flex items-center gap-4 px-5 py-4 border border-[#EFD3DE]">
                            <span
                                class="order-step-num shrink-0 w-10 h-10 rounded-full border border-[#EFD3DE] text-[#6E8577] flex items-center justify-center font-display text-base">
                                {{ $num }}
                            </span>
                            <span>
                                <span
                                    class="order-step-title block font-display text-base text-[#33413A] transition-colors duration-400">{{ $title }}</span>
                                <span class="block text-xs text-[#6E8577] mt-0.5">{{ $desc }}</span>
                            </span>
                        </div>
                    @endforeach
                </div>

                <div
                    class="relative bg-[#F9DEE5] border border-[#EFD3DE] flex flex-col items-center justify-center text-center p-10 lg:p-14 min-h-[360px] overflow-hidden">
                    <div id="order-slide-content" class="order-slide-content flex flex-col items-center">
                        <div id="order-icon-wrap"
                            class="order-illustration-icon w-24 h-24 rounded-full bg-white border border-[#D37897] flex items-center justify-center mb-6">
                            <svg id="order-icon" class="w-11 h-11 text-[#D37897]" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M12 6c-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2C2 6 1 8 2 10c1 2 4 4 10 6 6-2 9-4 10-6 1-2 0-4-2-4-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2z" />
                            </svg>
                        </div>
                        <h3 id="order-illustration-title" class="font-display text-xl text-[#33413A]">Pilih Buket</h3>
                        <p id="order-illustration-desc" class="text-sm text-[#6E8577] mt-2 max-w-xs">
                            Jelajahi katalog dan temukan buket favoritmu.
                        </p>
                    </div>

                    <button type="button" id="order-next-btn"
                        class="mt-8 inline-flex items-center gap-2 bg-[#D37897] hover:bg-[#C06A85] text-white text-sm font-medium tracking-wide px-7 py-3 rounded-full transition-colors duration-200">
                        Lanjut ke Langkah Berikutnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 6: TESTIMONI (tint pink pucat, full-bleed) ===== --}}
        <section class="py-20 -mx-4 sm:-mx-6 lg:-mx-8 bg-[#F9DEE5]/50 mb-20">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Testimoni</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Kata Mereka Tentang Kami
                    </h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="bg-white border border-[#EFD3DE] p-8">
                        <div class="flex items-center gap-1 text-[#D37897] mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="font-display italic text-[#33413A] text-base leading-relaxed">"Buketnya cantik banget!
                            Pacar saya senang sekali. Bunganya segar dan packingnya juga rapi. Pasti order lagi!"</p>
                        <div class="mt-6 pt-4 border-t border-[#EFD3DE] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-[#D37897] text-white flex items-center justify-center text-sm font-display">
                                A</div>
                            <div>
                                <p class="text-sm font-medium text-[#33413A]">Andini</p>
                                <p class="text-xs text-[#6E8577]">Jakarta Selatan</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-[#EFD3DE] p-8">
                        <div class="flex items-center gap-1 text-[#D37897] mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="font-display italic text-[#33413A] text-base leading-relaxed">"Pesan buket wisuda untuk
                            sahabat, hasilnya lebih bagus dari foto! Admin juga ramah dan fast response. Terima kasih!"</p>
                        <div class="mt-6 pt-4 border-t border-[#EFD3DE] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-[#457359] text-white flex items-center justify-center text-sm font-display">
                                R</div>
                            <div>
                                <p class="text-sm font-medium text-[#33413A]">Raka</p>
                                <p class="text-xs text-[#6E8577]">Bandung</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white border border-[#EFD3DE] p-8">
                        <div class="flex items-center gap-1 text-[#D37897] mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="font-display italic text-[#33413A] text-base leading-relaxed">"Sudah 3x pesan buket ulang
                            tahun di sini. Kualitas konsisten dan harganya sangat reasonable. Recommended banget!"</p>
                        <div class="mt-6 pt-4 border-t border-[#EFD3DE] flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-[#799F76] text-white flex items-center justify-center text-sm font-display">
                                M</div>
                            <div>
                                <p class="text-sm font-medium text-[#33413A]">Mutiara</p>
                                <p class="text-xs text-[#6E8577]">Surabaya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ===== SECTION 7: CUSTOM ORDER ===== --}}
        <section id="custom-order" class="mb-4 scroll-mt-24">
            <div class="grid grid-cols-1 lg:grid-cols-2 border border-[#EFD3DE]">
                <div class="p-8 sm:p-12 lg:p-16 bg-[#F9DEE5] flex flex-col justify-center">
                    <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Custom Order</span>
                    <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-3 leading-tight">
                        Buat Buket Impianmu Sendiri
                    </h2>
                    <p class="mt-4 text-[#5C6F5E] leading-relaxed">
                        Punya ide sendiri untuk buketmu? Ceritakan pada kami! Pilih jenis bunga, warna kertas wrap,
                        ukuran, dan sentuhan personal lainnya.
                    </p>
                    <ul class="mt-6 space-y-3 text-sm text-[#33413A]">
                        <li class="flex items-start gap-3"><span class="text-[#D37897] mt-0.5">—</span> Pilih jenis &amp;
                            warna bunga</li>
                        <li class="flex items-start gap-3"><span class="text-[#799F76] mt-0.5">—</span> Tentukan warna
                            &amp; model wrapping</li>
                        <li class="flex items-start gap-3"><span class="text-[#D37897] mt-0.5">—</span> Tambah kartu
                            ucapan personal</li>
                        <li class="flex items-start gap-3"><span class="text-[#799F76] mt-0.5">—</span> Konsultasi budget
                            &amp; rekomendasi</li>
                    </ul>
                </div>
                <div class="p-8 sm:p-12 lg:p-16 border-t lg:border-t-0 lg:border-l border-[#EFD3DE]">
                    <h3 class="font-display text-lg text-[#33413A] mb-6">Request Custom Buket</h3>
                    <form id="custom-order-form" class="space-y-5">
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nama
                                Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama kamu"
                                class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nomor
                                WhatsApp</label>
                            <input type="tel" name="phone" required placeholder="08XXXXXXXXXX"
                                pattern="08[0-9]{8,13}"
                                class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Budget
                                (Rupiah)</label>
                            <input type="number" name="budget" min="0" step="50000"
                                placeholder="Contoh: 350000"
                                class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Catatan
                                Custom</label>
                            <textarea name="notes" rows="3" placeholder="Jenis bunga, warna wrapping, ukuran, pesan khusus..."
                                class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors resize-none"></textarea>
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

    {{-- Quick Add Modal --}}
    <div id="quick-add-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 border border-[#EFD3DE]">
            <div class="flex items-center justify-between mb-5">
                <h2 id="quick-add-title" class="font-display text-lg text-[#33413A]">Tambah ke Keranjang</h2>
                <button type="button" onclick="closeQuickAdd()"
                    class="p-2 text-[#C9A9B4] hover:text-[#D37897] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="quick-add-form" onsubmit="return submitQuickAdd(event)">
                <div id="quick-add-fields"></div>

                <div id="quick-add-price-summary" class="flex justify-between items-center py-3 px-4 bg-[#F9DEE5] mt-4 hidden">
                    <span class="text-sm text-[#33413A] font-medium">Total Harga</span>
                    <span id="quick-add-total-price" class="text-lg font-medium text-[#D37897]"></span>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="button" onclick="closeQuickAdd()"
                        class="flex-1 px-4 py-3 border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#D37897] text-white hover:bg-[#C06A85] text-sm tracking-wide transition-colors duration-200">
                        Tambah ke Keranjang
                    </button>
                </div>
            </form>
        </div>
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

        (function() {
            const steps = [{
                    title: 'Pilih Buket',
                    desc: 'Jelajahi katalog dan temukan buket favoritmu.',
                    icon: '<path d="M12 6c-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2C2 6 1 8 2 10c1 2 4 4 10 6 6-2 9-4 10-6 1-2 0-4-2-4-1.5 0-3 .9-4 2-1-1.1-2.5-2-4-2z"/>',
                },
                {
                    title: 'Isi Detail Pesanan',
                    desc: 'Masukkan nama penerima, alamat, dan catatan khusus.',
                    icon: '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h10"/>',
                },
                {
                    title: 'Bayar & Konfirmasi',
                    desc: 'Lakukan pembayaran dan kirim bukti transfer.',
                    icon: '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18"/>',
                },
                {
                    title: 'Buket Dikirim',
                    desc: 'Buket cantikmu siap dikirim ke tujuan!',
                    icon: '<path d="M3 7h11v9H3z"/><path d="M14 10h4l3 3v3h-7z"/><circle cx="7" cy="18" r="1.5"/><circle cx="17.5" cy="18" r="1.5"/>',
                },
            ];

            let current = 0;
            let doneUpTo = -1;
            let transitioning = false;

            const slideContent = document.getElementById('order-slide-content');
            const iconEl = document.getElementById('order-icon');
            const titleEl = document.getElementById('order-illustration-title');
            const descEl = document.getElementById('order-illustration-desc');
            const nextBtn = document.getElementById('order-next-btn');
            const rows = document.querySelectorAll('.order-step-row');

            function slideTo(stepIndex) {
                const data = steps[stepIndex];

                slideContent.classList.add('order-slide-exit');
                slideContent.classList.remove('order-slide-enter');

                setTimeout(function() {
                    iconEl.innerHTML = data.icon;
                    titleEl.textContent = data.title;
                    descEl.textContent = data.desc;

                    slideContent.classList.remove('order-slide-exit');
                    slideContent.classList.add('order-slide-enter');

                    requestAnimationFrame(function() {
                        slideContent.classList.remove('order-slide-enter');
                    });

                    transitioning = false;
                }, 380);
            }

            function renderRows() {
                rows.forEach(function(row, i) {
                    var num = row.querySelector('.order-step-num');
                    if (i <= doneUpTo) {
                        row.classList.add('is-done');
                        num.classList.add('is-done');
                    } else {
                        row.classList.remove('is-done');
                        num.classList.remove('is-done');
                    }
                });
            }

            function renderButton() {
                if (doneUpTo >= steps.length - 1) {
                    nextBtn.innerHTML =
                        'Mulai dari Awal <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4 9a9 9 0 0114-7M20 15a9 9 0 01-14 7"/></svg>';
                } else {
                    nextBtn.innerHTML =
                        'Lanjut ke Langkah Berikutnya <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>';
                }
            }

            nextBtn.addEventListener('click', function() {
                if (transitioning) return;
                transitioning = true;

                if (doneUpTo >= steps.length - 1) {
                    doneUpTo = -1;
                    current = 0;
                    slideTo(current);
                    renderRows();
                    renderButton();
                    return;
                }

                doneUpTo++;
                current = Math.min(doneUpTo + 1, steps.length - 1);
                slideTo(current);
                renderRows();
                renderButton();
            });

            slideTo(current);
            renderRows();
            renderButton();
        })();

        var quickAddProduct = null;

        function escapeHtml(str) {
            if (typeof str !== 'string') return '';
            return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function getFieldOptions(field) {
            if (field.field_options && field.field_options.length > 0) {
                return field.field_options;
            }
            return (field.options || '').split(',').map(function(o) {
                return { name: o.trim(), price: 0 };
            }).filter(function(o) { return o.name; });
        }

        function openQuickAdd(btn) {
            quickAddProduct = {
                id: parseInt(btn.getAttribute('data-pid')) || 0,
                name: btn.getAttribute('data-name'),
                price: parseInt(btn.getAttribute('data-price')) || 0,
                image: btn.getAttribute('data-image'),
                catId: btn.getAttribute('data-catid') || null,
            };

            document.getElementById('quick-add-title').textContent = 'Kustomisasi ' + quickAddProduct.name;

            var container = document.getElementById('quick-add-fields');
            container.innerHTML = '';

            var fields = quickAddProduct.catId ? (window.categoryFields[quickAddProduct.catId] || []) : [];
            var summary = document.getElementById('quick-add-price-summary');
            if (fields.length === 0) {
                container.innerHTML =
                    '<p class="text-sm text-[#6E8577]">Tidak ada opsi kustomisasi. Produk akan langsung ditambahkan.</p>';
                summary.classList.add('hidden');
            } else {
                summary.classList.remove('hidden');
                fields.forEach(function(field) {
                    var reqAttr = field.is_required ? 'required' : '';
                    var reqStar = field.is_required ? ' <span class="text-[#D37897]">*</span>' : '';
                    var html = '<div class="mb-4">';
                    html += '<label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">' +
                        escapeHtml(field.label) + reqStar + '</label>';

                    if (field.type === 'text') {
                        html += '<input type="text" name="custom_options[' + field.label + ']" ' + reqAttr +
                            ' class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors" placeholder="' +
                            escapeHtml(field.label) + '">';
                    } else if (field.type === 'select') {
                        var opts = getFieldOptions(field);
                        html += '<select name="custom_options[' + field.label + ']" ' + reqAttr +
                            ' class="quick-add-select block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">';
                        html += '<option value="">Pilih ' + escapeHtml(field.label) + '</option>';
                        opts.forEach(function(o) {
                            html += '<option value="' + escapeHtml(o.name || o) + '" data-option-id="' + (o.id || '') +
                                '" data-price="' + (o.price || 0) + '">' + escapeHtml(o.name || o);
                            if (o.price > 0) html += ' (+Rp ' + o.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ')';
                            html += '</option>';
                        });
                        html += '</select>';
                    } else if (field.type === 'checkbox') {
                        var opts = getFieldOptions(field);
                        html += '<div class="space-y-2">';
                        opts.forEach(function(o) {
                            html +=
                                '<label class="flex items-center space-x-3 p-2.5 border border-[#EFD3DE] cursor-pointer hover:bg-[#F9DEE5] transition">';
                            html += '<input type="checkbox" name="custom_options[' + field.label +
                                '][]" value="' + escapeHtml(o.name || o) + '" data-option-id="' + (o.id || '') +
                                '" data-price="' + (o.price || 0) +
                                '" class="quick-add-checkbox text-[#D37897] focus:ring-[#D37897] rounded">';
                            html += '<span class="text-sm text-[#33413A]">' + escapeHtml(o.name || o);
                            if (o.price > 0) html += ' <span class="text-[#D37897]">(+Rp ' + o.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ')</span>';
                            html += '</span></label>';
                        });
                        html += '</div>';
                    } else if (field.type === 'file') {
                        html += '<input type="file" accept="image/jpg,image/jpeg,image/png,image/webp" data-label="' + escapeHtml(field.label) + '" data-required="' + field.is_required + '" class="field-file-input block w-full text-sm text-[#33413A] file:border file:border-[#EFD3DE] file:px-4 file:py-2 file:text-sm file:tracking-wide file:bg-transparent file:text-[#33413A] hover:file:bg-[#F9DEE5] file:transition-colors file:cursor-pointer file:mr-4 transition-colors"' + (field.is_required ? ' required' : '') + '>';
                        html += '<input type="hidden" name="custom_options[' + field.label + ']" value="" class="file-uploaded-path">';
                        html += '<div class="file-preview mt-2 hidden"><div class="flex items-center gap-2"><img src="" class="w-14 h-14 object-cover border border-[#EFD3DE]"><span class="text-xs text-[#5C6F5E]">Terupload</span></div></div>';
                        html += '<p class="text-xs text-[#C9A9B4] mt-1">Upload gambar referensi (jpg/png/webp, maks 5MB)</p>';
                    }

                    html += '</div>';
                    container.insertAdjacentHTML('beforeend', html);
                });
            }

            document.getElementById('quick-add-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            updateQuickAddPrice();
        }

        function getQuickAddOptionsPrice() {
            var total = 0;
            document.querySelectorAll('#quick-add-fields .quick-add-select').forEach(function(sel) {
                if (sel.value && sel.selectedOptions[0]?.dataset?.price) {
                    total += parseInt(sel.selectedOptions[0].dataset.price) || 0;
                }
            });
            document.querySelectorAll('#quick-add-fields .quick-add-checkbox:checked').forEach(function(cb) {
                total += parseInt(cb.dataset.price) || 0;
            });
            return total;
        }

        function updateQuickAddPrice() {
            if (!quickAddProduct) return;
            var total = quickAddProduct.price + getQuickAddOptionsPrice();
            document.getElementById('quick-add-total-price').textContent = formatRupiah(total);
        }

        document.getElementById('quick-add-fields').addEventListener('change', function(e) {
            if (e.target.matches('.quick-add-select, .quick-add-checkbox')) {
                updateQuickAddPrice();
            }
        });

        const UPLOAD_URL = '{{ route('customer.cart.upload-temp') }}';

        async function uploadFile(file) {
            const fd = new FormData();
            fd.append('file', file);
            const resp = await fetch(UPLOAD_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            });
            if (!resp.ok) throw new Error('Upload gagal');
            return (await resp.json()).path;
        }

        document.getElementById('quick-add-fields').addEventListener('change', async function(e) {
            var input = e.target.closest('.field-file-input');
            if (!input) return;
            var file = input.files[0];
            if (!file) return;
            var container = input.closest('.mb-4');
            var hiddenInput = container.querySelector('.file-uploaded-path');
            var preview = container.querySelector('.file-preview');
            try {
                var path = await uploadFile(file);
                hiddenInput.value = path;
                preview.querySelector('img').src = '/storage/' + path;
                preview.classList.remove('hidden');
            } catch (err) {
                alert('Gagal mengupload gambar. Silakan coba lagi.');
                input.value = '';
            }
        });

        function closeQuickAdd() {
            document.getElementById('quick-add-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            quickAddProduct = null;
        }

        document.getElementById('quick-add-modal')?.addEventListener('click', function(e) {
            if (e.target === this) closeQuickAdd();
        });

        function buildStructuredCustomOptions() {
            var customOptions = {};
            document.querySelectorAll('#quick-add-fields .mb-4').forEach(function(container) {
                var labelEl = container.querySelector('label.block');
                if (!labelEl) return;
                var fieldLabel = labelEl.textContent.replace('*', '').trim();

                var select = container.querySelector('select.quick-add-select');
                if (select) {
                    var opt = select.selectedOptions[0];
                    if (opt && opt.value) {
                        customOptions[fieldLabel] = {
                            value: opt.value,
                            option_id: parseInt(opt.dataset.optionId) || null,
                            price: parseInt(opt.dataset.price) || 0
                        };
                    } else {
                        customOptions[fieldLabel] = '';
                    }
                    return;
                }

                var checkboxes = container.querySelectorAll('input.quick-add-checkbox');
                if (checkboxes.length > 0) {
                    var checked = [];
                    checkboxes.forEach(function(cb) {
                        if (cb.checked) {
                            checked.push({
                                value: cb.value,
                                option_id: parseInt(cb.dataset.optionId) || null,
                                price: parseInt(cb.dataset.price) || 0
                            });
                        }
                    });
                    customOptions[fieldLabel] = checked.length > 0 ? checked : [];
                    return;
                }

                var textInput = container.querySelector('input[type="text"]');
                if (textInput) {
                    customOptions[fieldLabel] = textInput.value;
                    return;
                }

                var fileHidden = container.querySelector('.file-uploaded-path');
                if (fileHidden) {
                    customOptions[fieldLabel] = fileHidden.value;
                }
            });
            return customOptions;
        }

        function submitQuickAdd(event) {
            event.preventDefault();
            if (!quickAddProduct) return false;

            var isValid = true;
            document.querySelectorAll('#quick-add-fields .field-file-input[required]').forEach(function(input) {
                var container = input.closest('.mb-4');
                var path = container.querySelector('.file-uploaded-path').value;
                if (!path) {
                    isValid = false;
                    var msg = container.querySelector('.file-error-msg');
                    if (!msg) {
                        msg = document.createElement('p');
                        msg.className = 'file-error-msg text-xs text-[#D37897] mt-1';
                        container.appendChild(msg);
                    }
                    msg.textContent = 'Harap upload gambar.';
                }
            });
            if (!isValid) return false;

            var customOptions = buildStructuredCustomOptions();
            var unitPrice = quickAddProduct.price + getQuickAddOptionsPrice();

            CartStorage.addItem({
                id: quickAddProduct.id,
                name: quickAddProduct.name,
                price: unitPrice,
                image: quickAddProduct.image,
                qty: 1,
                custom_options: Object.keys(customOptions).length > 0 ? customOptions : null
            });

            closeQuickAdd();

            var fb = document.createElement('p');
            fb.className =
                'fixed bottom-6 right-6 z-50 bg-[#457359] text-white text-sm px-5 py-3 border border-[#D6E5D3] transition-opacity duration-300';
            fb.textContent = '✓ ' + quickAddProduct.name + ' ditambahkan ke keranjang!';
            document.body.appendChild(fb);
            setTimeout(function() {
                fb.classList.add('opacity-0');
                setTimeout(function() {
                    fb.remove();
                }, 300);
            }, 2500);

            return false;
        }
    </script>
@endpush
