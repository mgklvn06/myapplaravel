<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    /**
     * Customer dashboard entry point.
     * Assumes 'auth' + 'role:customer' middleware on the route.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // If an Order model & relationship exists, fetch recent orders.
        $recentOrders = null;
        if (class_exists(\App\Models\Order::class) && method_exists($user, 'orders')) {
            $recentOrders = $user->orders()->with('items')->latest()->take(10)->get();
        }

        // Recently viewed or recommended products:
        // If user model has a method to get recently viewed, use it; otherwise fall back to latest products.
        $recentlyViewed = null;
        if (method_exists($user, 'recentlyViewedProducts')) {
            // developer-defined method on User model expected to return a Collection
            $recentlyViewed = $user->recentlyViewedProducts();
        } else {
            $recentlyViewed = Product::where('is_active', true)->latest()->take(6)->get();
        }

        // Basic account stats (placeholders if Orders missing)
        $ordersCount = $recentOrders ? $recentOrders->count() : 0;

        return view('account.dashboard', compact('user', 'recentOrders', 'recentlyViewed', 'ordersCount'));
    }
}
