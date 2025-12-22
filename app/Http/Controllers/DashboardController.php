<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Estadísticas principales
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalUsers = User::count();
        $lowStockProducts = Product::where('stock_available', '<=', 10)->count();
        $outOfStockProducts = Product::where('stock_available', 0)->count();

        // Productos con bajo stock
        $lowStockItems = Product::where('stock_available', '<=', 10)
            ->where('stock_available', '>', 0)
            ->with('category')
            ->orderBy('stock_available')
            ->limit(10)
            ->get();

        // Productos sin stock
        $outOfStock = Product::where('stock_available', 0)
            ->with('category')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'totalUsers',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockItems',
            'outOfStock'
        ));
    }
}

