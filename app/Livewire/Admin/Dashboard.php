<?php

namespace App\Livewire\Admin;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $chartRange = '7days'; // 7days, 30days, year

    public function render()
    {
        $today = Carbon::today();

        // 1. Metric Cards
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todaySales = (float) Order::whereDate('created_at', $today)
            ->whereNotIn('order_status', ['cancelled', 'returned'])
            ->sum('total_amount');

        $totalSales = (float) Order::whereNotIn('order_status', ['cancelled', 'returned'])
            ->sum('total_amount');

        $totalOrders = Order::count();
        $totalCustomers = Customer::count();

        // Products with stock <= low_stock_threshold
        $lowStockCount = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();

        // 2. Recent Orders (Last 10)
        $recentOrders = Order::with(['customer', 'deliveryZone'])
            ->latest('created_at')
            ->take(10)
            ->get();

        // 3. Low Stock Products (Top 8 urgently requiring replenishment)
        $lowStockProducts = Product::with(['supplier', 'category'])
            ->where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->take(8)
            ->get();

        // 4. Daily Sales Chart Data (Last 7 days or 30 days)
        $daysCount = $this->chartRange === '30days' ? 30 : 7;
        $salesData = [];
        $labels = [];

        for ($i = $daysCount - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $sales = (float) Order::whereDate('created_at', $date)
                ->whereNotIn('order_status', ['cancelled', 'returned'])
                ->sum('total_amount');
            $salesData[] = $sales;
        }

        return view('livewire.admin.dashboard', [
            'metrics' => [
                'todaySales' => $todaySales,
                'todayOrders' => $todayOrders,
                'totalSales' => $totalSales,
                'totalOrders' => $totalOrders,
                'totalCustomers' => $totalCustomers,
                'lowStockCount' => $lowStockCount,
            ],
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'chartLabels' => $labels,
            'chartValues' => $salesData,
        ]);
    }
}
