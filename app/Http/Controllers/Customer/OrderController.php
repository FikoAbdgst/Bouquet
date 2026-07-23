<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(404);
        }

        $order->load(['items', 'trackingLogs.changedByUser']);

        return view('customer.order-detail', compact('order'));
    }

    public function status(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->load(['trackingLogs.changedByUser']);

        $labels = [
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'dikonfirmasi' => 'Dikonfirmasi',
            'diproses' => 'Diproses',
            'dikirim' => 'Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        $colors = [
            'menunggu_konfirmasi' => 'yellow',
            'dikonfirmasi' => 'blue',
            'diproses' => 'indigo',
            'dikirim' => 'purple',
            'selesai' => 'green',
            'dibatalkan' => 'red',
        ];

        return response()->json([
            'status' => $order->status,
            'status_label' => $labels[$order->status] ?? $order->status,
            'status_color' => $colors[$order->status] ?? 'gray',
            'payment_verified' => $order->payment_verified,
            'tracking_logs' => $order->trackingLogs->sortByDesc('created_at')->map(function ($log) use ($labels) {
                return [
                    'previous_status' => $log->previous_status,
                    'previous_label' => $labels[$log->previous_status] ?? $log->previous_status,
                    'new_status' => $log->new_status,
                    'new_label' => $labels[$log->new_status] ?? $log->new_status,
                    'note' => $log->note,
                    'changed_by' => $log->changedByUser->name ?? 'Sistem',
                    'created_at' => $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB',
                ];
            })->values(),
        ]);
    }

    public function create(Product $product, Request $request)
    {
        $minDate = now()->addDay()->format('Y-m-d');
        $user = Auth::user();
        $reorderItem = null;

        if ($request->filled('reorder')) {
            $previousOrder = Order::where('id', $request->reorder)
                ->where('user_id', Auth::id())
                ->with('items')
                ->first();

            if ($previousOrder) {
                $previousItem = $previousOrder->items->firstWhere('product_id', $product->id);
                if ($previousItem) {
                    if (!$product->is_active) {
                        return back()->with('error', 'Produk "' . $product->name . '" sudah tidak tersedia.');
                    }
                    if ($product->stock <= 0) {
                        return back()->with('error', 'Produk "' . $product->name . '" sedang habis.');
                    }
                    $reorderItem = [
                        'quantity' => $previousItem->quantity,
                        'special_note' => $previousOrder->special_note,
                        'pickup_method' => $previousOrder->pickup_method,
                        'delivery_address' => $previousOrder->delivery_address,
                        'old_price' => $previousItem->price_snapshot,
                    ];
                    if ($previousItem->price_snapshot !== $product->price) {
                        session()->flash('warning', 'Harga produk "' . $product->name . '" telah berubah dari Rp ' . number_format($previousItem->price_snapshot, 0, ',', '.') . ' menjadi ' . $product->formatted_price . '. Harga terbaru akan digunakan.');
                    }
                }
            }
        }

        if (!$product->is_active || $product->stock <= 0) {
            abort(404);
        }

        return view('customer.order-create', compact('product', 'minDate', 'user', 'reorderItem'));
    }

    public function store(Request $request)
    {
        $leadDays = (int) env('MIN_ORDER_LEAD_DAYS', 1);

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'orderer_name' => ['required', 'string', 'max:255'],
            'orderer_phone' => ['required', 'string', 'max:20', 'regex:/^08[0-9]{8,11}$/'],
            'needed_date' => ['required', 'date', 'after:' . now()->addDays($leadDays - 1)->format('Y-m-d')],
            'pickup_method' => ['required', 'in:self_pickup,delivery'],
            'delivery_address' => ['required_if:pickup_method,delivery', 'nullable', 'string'],
            'special_note' => ['nullable', 'string', 'max:500'],
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $product = Product::lockForUpdate()->find($validated['product_id']);

        if (!$product || !$product->is_active) {
            return back()->withErrors(['product_id' => 'Produk tidak tersedia.'])->withInput();
        }

        $quantity = (int) $validated['quantity'];

        if ($product->stock < $quantity) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi. Sisa stok: ' . $product->stock])->withInput();
        }

        $priceSnapshot = $product->price;
        $subtotal = $priceSnapshot * $quantity;
        $totalPrice = $subtotal;

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $orderCode = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(3));

        DB::beginTransaction();

        try {
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(),
                'orderer_name' => $validated['orderer_name'],
                'orderer_phone' => $validated['orderer_phone'],
                'needed_date' => $validated['needed_date'],
                'pickup_method' => $validated['pickup_method'],
                'delivery_address' => $validated['pickup_method'] === 'delivery' ? $validated['delivery_address'] : null,
                'special_note' => $validated['special_note'] ?? null,
                'total_price' => $totalPrice,
                'payment_proof_url' => $path,
                'status' => 'menunggu_konfirmasi',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name_snapshot' => $product->name,
                'price_snapshot' => $priceSnapshot,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);

            $product->decrement('stock', $quantity);

            TrackingLog::create([
                'order_id' => $order->id,
                'previous_status' => null,
                'new_status' => 'menunggu_konfirmasi',
                'changed_by' => Auth::id(),
                'note' => 'Pesanan dibuat oleh pelanggan.',
            ]);

            DB::commit();

            return redirect()->route('customer.orders.index')
                ->with('success', 'Pesanan berhasil dibuat! Kode pesanan: ' . $orderCode);
        } catch (\Exception $e) {
            DB::rollBack();
            \Storage::disk('public')->delete($path);
            return back()->withInput()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}
