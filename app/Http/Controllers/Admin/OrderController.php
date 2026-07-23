<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TrackingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public const STATUS_ORDER = [
        'menunggu_konfirmasi',
        'dikonfirmasi',
        'diproses',
        'dikirim',
        'selesai',
    ];

    public const STATUS_LABELS = [
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_code', 'like', '%' . $request->search . '%')
                  ->orWhere('orderer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('orderer_phone', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(15);

        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'trackingLogs.changedByUser']);

        return view('admin.orders.show', compact('order'));
    }

    public function verifyPayment(Order $order)
    {
        $order->update(['payment_verified' => true]);

        return back()->with('success', 'Pembayaran untuk pesanan ' . $order->order_code . ' berhasil diverifikasi.');
    }

    public function updateNote(Request $request, Order $order)
    {
        $validated = $request->validate([
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update(['admin_note' => $validated['admin_note'] ?? null]);

        return back()->with('success', 'Catatan admin berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:dikonfirmasi,diproses,dikirim,selesai,dibatalkan'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $newStatus = $validated['status'];
        $currentStatus = $order->status;

        if ($newStatus === $currentStatus) {
            return back()->with('error', 'Status pesanan sudah sama.');
        }

        if ($newStatus !== 'dibatalkan') {
            $currentIndex = array_search($currentStatus, self::STATUS_ORDER);
            $newIndex = array_search($newStatus, self::STATUS_ORDER);

            if ($currentIndex === false || $newIndex === false) {
                return back()->with('error', 'Status tidak valid.');
            }

            if ($newIndex !== $currentIndex + 1) {
                return back()->with('error', 'Perubahan status harus berurutan. Status saat ini: ' . self::STATUS_LABELS[$currentStatus] . '. Status yang diizinkan berikutnya: ' . (self::STATUS_ORDER[$currentIndex + 1] ?? '—') . '.');
            }
        }

        DB::beginTransaction();

        try {
            $order->update(['status' => $newStatus]);

            TrackingLog::create([
                'order_id' => $order->id,
                'previous_status' => $currentStatus,
                'new_status' => $newStatus,
                'changed_by' => Auth::id(),
                'note' => $validated['note'] ?? null,
            ]);

            DB::commit();

            return back()->with('success', 'Status pesanan berhasil diubah menjadi: ' . self::STATUS_LABELS[$newStatus]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengubah status: ' . $e->getMessage());
        }
    }
}
