@php
    // Palet status diselaraskan dengan tema Mint Julep (bukan warna Tailwind default),
    // dengan fallback netral kalau ada status_color yang belum terdaftar di sini.
    $statusPalette = [
        'yellow' => ['bg' => '#FBF3DE', 'text' => '#9C7A1C'],
        'orange' => ['bg' => '#FBEEDD', 'text' => '#B36A1D'],
        'blue' => ['bg' => '#E7EEF6', 'text' => '#3C6A96'],
        'indigo' => ['bg' => '#EAEDF6', 'text' => '#4C5C9C'],
        'purple' => ['bg' => '#F1EAF6', 'text' => '#7A4C9C'],
        'green' => ['bg' => '#EAF3E8', 'text' => '#457359'],
        'red' => ['bg' => '#FBEAEE', 'text' => '#B33F5C'],
        'gray' => ['bg' => '#F1F0EA', 'text' => '#6E8577'],
    ];
@endphp

@if ($orders->isEmpty())
    <div class="border border-dashed border-[#E7E4DC] p-16 text-center">
        <p class="text-[#6E8577] text-lg">Belum ada pesanan.</p>
        <a href="{{ route('customer.catalog') }}"
            class="inline-block mt-4 border border-[#D37897] hover:bg-[#D37897] hover:text-white text-[#33413A] text-sm tracking-wide px-6 py-2.5 transition-colors duration-200">
            Mulai Belanja
        </a>
    </div>
@else
    <div class="space-y-px">
        @foreach ($orders as $order)
            @php $status = $statusPalette[$order->status_color] ?? $statusPalette['gray']; @endphp
            <div class="border border-[#E7E4DC] p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Kode Pesanan</p>
                        <p class="font-display text-xl text-[#33413A] mt-0.5">{{ $order->order_code }}</p>
                    </div>
                    <span class="self-start px-3.5 py-1 text-[11px] tracking-[0.1em] uppercase font-medium"
                        style="background-color: {{ $status['bg'] }}; color: {{ $status['text'] }};">
                        {{ $order->status_label }}
                    </span>
                </div>

                <div class="space-y-2 text-sm text-[#6E8577] pb-4 mb-4 border-b border-[#E7E4DC]">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div
                                    class="flex items-start gap-1{{ $item->custom_options ? ' cursor-pointer' : '' }}"{!! $item->custom_options
                                        ? ' onclick="this.nextElementSibling.classList.toggle(\'hidden\');this.querySelector(\'.cv\').classList.toggle(\'-rotate-180\')"'
                                        : '' !!}>
                                    <span class="truncate text-[#33413A]">{{ $item->product_name_snapshot }} <span
                                            class="text-[#6E8577]">× {{ $item->quantity }}</span></span>
                                    @if ($item->custom_options)
                                        <svg class="cv w-2.5 h-2.5 mt-0.5 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    @endif
                                </div>
                                @if ($item->custom_options)
                                    <div class="hidden mt-1.5 space-y-1 pl-2.5 border-l border-[#E7E4DC]">
                                        @foreach ($item->custom_options as $label => $value)
                                            <div>
                                                <p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577]">
                                                    {{ $label }}</p>
                                                @php $isFile = is_custom_option_file($value); @endphp
                                                @if ($isFile)
                                                    <a href="{{ Storage::url(get_custom_option_file_path($value)) }}"
                                                        target="_blank" class="inline-block mt-1">
                                                        <img src="{{ Storage::url(get_custom_option_file_path($value)) }}"
                                                            alt="Referensi"
                                                            class="w-16 h-16 object-cover border border-[#E7E4DC] hover:opacity-80 transition">
                                                    </a>
                                                @else
                                                    <p class="text-xs text-[#33413A]">
                                                        {{ get_custom_option_display($value) }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span
                                class="font-medium text-[#33413A] whitespace-nowrap flex-shrink-0">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <span
                        class="text-xs text-[#C9A9B4]">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        WIB</span>
                    <div class="flex items-center gap-3">
                        <span class="font-medium text-[#33413A]">{{ $order->formatted_total }}</span>
                        @if ($order->canBeCancelledByCustomer())
                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $order->order_code }}?')">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-1.5 border border-[#E7E4DC] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897] text-xs tracking-wide font-medium transition-colors duration-200">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('customer.orders.show', $order) }}"
                            class="px-4 py-1.5 border border-[#D37897] text-[#D37897] hover:bg-[#D37897] hover:text-white text-xs tracking-wide font-medium transition-colors duration-200">
                            Lacak →
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div
        class="mt-8 [&_.rounded-md]:rounded-none [&_a]:text-[#6E8577] [&_span[aria-current]]:bg-[#D37897] [&_span[aria-current]]:border-[#D37897]">
        {{ $orders->links() }}
    </div>
@endif
