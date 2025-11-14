<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Only admin users should reach here; middleware is expected on routes.
     */
    public function index(Request $request)
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        // recent products for quick management
        $recentProducts = Product::with('category')
            ->latest()
            ->take(10)
            ->get();

        // Optionally: show low-stock items (example)
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->orderBy('stock_quantity', 'asc')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalUsers',
            'recentProducts',
            'lowStockProducts'
        ));
    }
}
