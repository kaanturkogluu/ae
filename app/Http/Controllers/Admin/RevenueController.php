<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    /**
     * Gelir Tablosu ve Yönetim Dashboard
     */
    public function index()
    {
        try {
            $validOrders = Order::where('status', '!=', 'cancelled');

            $totalRevenue = (clone $validOrders)->sum('total_amount') ?? 0;

            $thisMonthRevenue = (clone $validOrders)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount') ?? 0;

            $totalOrders = (clone $validOrders)->count();

            $totalProductsSold = OrderItem::whereHas('order', function ($q) {
                $q->where('status', '!=', 'cancelled');
            })->sum('quantity') ?? 0;

            // Son 30 günlük gelir grafiği
            $revenueByDay = (clone $validOrders)
                ->where('created_at', '>=', now()->subDays(30))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->pluck('total', 'date')
                ->toArray();

            $recentOrders = Order::with('items')->latest()->take(10)->get();
        } catch (\Throwable $e) {
            $totalRevenue = 0;
            $thisMonthRevenue = 0;
            $totalOrders = 0;
            $totalProductsSold = 0;
            $revenueByDay = [];
            $recentOrders = collect();
        }

        return view('admin.revenue.index', compact(
            'totalRevenue',
            'thisMonthRevenue',
            'totalOrders',
            'totalProductsSold',
            'revenueByDay',
            'recentOrders'
        ));
    }
}
