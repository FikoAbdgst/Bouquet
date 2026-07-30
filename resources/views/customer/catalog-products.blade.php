@if($products->count() > 0)
    @php $swatches = ['#DCD6C9', '#8E9A7C', '#B9C3C6', '#D9C2B4', '#A8AC98', '#C9B4B4']; @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">
        @foreach($products as $product)
            @php $catId = $product->productCategory?->id; @endphp
            <div class="group">
                <a href="{{ route('customer.catalog.show', $product) }}" class="block aspect-[3/4] overflow-hidden relative" style="background-color: {{ $swatches[$loop->index % count($swatches)] }}">
                    @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl text-[#C9A9B4]">—</div>
                    @endif
                    @if($product->productCategory)
                        <span class="absolute top-4 left-4 text-[10px] tracking-[0.2em] uppercase bg-white/90 text-[#D37897] px-3 py-1.5">
                            {{ $product->productCategory->name }}
                        </span>
                    @endif
                    @if($product->stock <= 5 && $product->stock > 0)
                        <span class="absolute top-4 right-4 text-[10px] tracking-[0.2em] uppercase bg-white/90 text-[#33413A] px-3 py-1.5">
                            Sisa {{ $product->stock }}
                        </span>
                    @endif
                </a>
                <div class="mt-4">
                    <h3 class="font-display text-lg text-[#33413A] line-clamp-1">{{ $product->name }}</h3>
                    <p class="mt-1 text-[#D37897] font-medium">{{ $product->formatted_price }}</p>
                    @if($product->stock > 0)
                        <button type="button"
                                data-pid="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ $product->primaryImage ? Storage::url($product->primaryImage->image_url) : '' }}"
                                data-catid="{{ $catId ?? '' }}"
                                onclick="openQuickAdd(this)"
                                class="mt-4 block w-full text-center border border-[#D37897] hover:bg-[#457359] hover:text-white text-[#33413A] text-sm tracking-wide py-2.5 transition-colors duration-200">
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

    <div class="mt-12">
        {{ $products->withQueryString()->links() }}
    </div>
@else
    <div class="border border-dashed border-[#EFD3DE] p-16 text-center">
        <p class="text-[#6E8577] text-lg">Belum ada produk yang tersedia.</p>
        <p class="text-sm text-[#C9A9B4] mt-1">Coba ubah filter pencarian Anda.</p>
    </div>
@endif
