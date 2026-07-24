<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $ordersToday = Order::whereDate('created_at', $today)->count();

        $pendingOrders = Order::where('status', 'menunggu_konfirmasi')->count();

        $totalRevenue = Order::where('status', 'selesai')->sum('total_price');

        $recentOrders = Order::with('user', 'items')
            ->latest()
            ->limit(5)
            ->get();

        $bestSellers = Product::topSellers(3);

        return view('admin.dashboard', compact('ordersToday', 'pendingOrders', 'totalRevenue', 'recentOrders', 'bestSellers'));
    }
}
