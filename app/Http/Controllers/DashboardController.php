<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function overview()
    {
        // Total products
        $totalProducts = Product::count();

        // Total inventory value
        $inventoryValue = Product::sum(DB::raw('price * stock'));

        // Today's sales
        $today = Carbon::today();
        $todaysSales = ProductHistory::whereDate('checked_out_at', $today)
            ->sum(DB::raw('price * quantity'));

        // Low stock products (stock <= 5)
        $lowStock = Product::where('stock', '<=', 50)->orderBy('stock')->get(['name', 'stock']);

        // Recent transactions (last 5)
        $recentTransactions = ProductHistory::orderByDesc('checked_out_at')->limit(5)->get();

        // Sales growth (today vs yesterday)
        $yesterday = Carbon::yesterday();
        $yesterdaySales = ProductHistory::whereDate('checked_out_at', $yesterday)
            ->sum(DB::raw('price * quantity'));
        $growth = $yesterdaySales > 0
            ? (($todaysSales - $yesterdaySales) / $yesterdaySales) * 100
            : null;

        return response()->json([
            'totalProducts' => $totalProducts,
            'inventoryValue' => $inventoryValue,
            'todaysSales' => $todaysSales,
            'growth' => $growth,
            'lowStock' => $lowStock,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
