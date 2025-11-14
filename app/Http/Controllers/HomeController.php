<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Show the visitor homepage.
     */
    public function index(Request $request)
    {
        // Featured products (limit)
        $featured = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with('category')
            ->take(6)
            ->get();

        // Latest products (limit)
        $latest = Product::where('is_active', true)
            ->with('category')
            ->latest()
            ->take(9)
            ->get();

        // Categories (limit) — simple listing
        $categories = Category::orderBy('name')->take(12)->get();

        return view('home', compact('featured', 'latest', 'categories'));
    }
}
