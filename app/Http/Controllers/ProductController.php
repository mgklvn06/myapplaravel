<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->where('is_active', true)->with('category');

        // Search (name, description, sku)
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        // Category filter: accept slug or id
        if ($category = $request->query('category')) {
            // try numeric id first, else try slug
            if (is_numeric($category)) {
                $query->where('category_id', (int)$category);
            } else {
                $cat = Category::where('slug', $category)->first();
                if ($cat) {
                    $query->where('category_id', $cat->id);
                } else {
                    // also allow filtering by category name
                    $catByName = Category::where('name', 'LIKE', $category)->first();
                    if ($catByName) {
                        $query->where('category_id', $catByName->id);
                    }
                }
            }
        }

        // Price range
        if ($min = $request->query('min_price')) {
            $query->where('price', '>=', (float)$min);
        }
        if ($max = $request->query('max_price')) {
            $query->where('price', '<=', (float)$max);
        }

        // Sorting
        $sort = $request->query('sort', 'newest'); // default
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'popular':
                // assume sold_count indicates popularity
                $query->orderBy('sold_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination (preserve query string)
        $perPage = (int) $request->query('per_page', 12);
    $products = $query->paginate($perPage);

        // If this is an API return JSON; otherwise return view
        if ($request->wantsJson()) {
            return response()->json($products);
        }

        return view('products.index', [
            'products' => $products,
            // optionally pass current filters back to view
            'filters' => $request->only(['q', 'category', 'min_price', 'max_price', 'sort', 'per_page']),
        ]);
    }

    /**
     * Show a single product, record view and preserve recently viewed list.
     */
    public function show(Request $request, Product $product)
    {
        // increment lightweight view counter
        try {
            // use DB increment to avoid model method visibility issues
            DB::table('products')->where('id', $product->id)->increment('view_count');
        } catch (\Exception $e) {
            // ignore increment failures
        }

        // Record to DB for authenticated users if table exists
        if (auth()->check() && Schema::hasTable('product_user_views')) {
            try {
                DB::table('product_user_views')->insertOrIgnore([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                // swallow DB errors
            }
        }

        // Session-based recently viewed (front-end friendly)
        if (function_exists('session')) {
            $ids = session('recently_viewed', []);
            array_unshift($ids, $product->id);
            $ids = array_values(array_unique($ids));
            $ids = array_slice($ids, 0, 20);
            session(['recently_viewed' => $ids]);
        }

        // Prepare recently viewed collection for the view (limit 6)
        $recentlyViewed = collect();
        if (auth()->check()) {
            // prefer DB-backed product_user_views when available
            if (Schema::hasTable('product_user_views')) {
                $ids = DB::table('product_user_views')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->pluck('product_id')
                    ->unique()
                    ->take(6)
                    ->toArray();

                if (count($ids)) {
                    $prods = Product::whereIn('id', $ids)->get()->keyBy('id');
                    $recentlyViewed = collect($ids)->map(fn($id) => $prods->get($id))->filter();
                }
            } else {
                // fallback to session for authenticated users as well
                $ids = session('recently_viewed', []);
                $ids = array_filter($ids, fn($id) => $id !== $product->id);
                if (count($ids)) {
                    $products = Product::whereIn('id', $ids)->get()->keyBy('id');
                    $ordered = collect(array_slice($ids, 0, 6))->map(fn($id) => $products->get($id))->filter();
                    $recentlyViewed = $ordered;
                }
            }
        } else {
            $ids = session('recently_viewed', []);
            $ids = array_filter($ids, fn($id) => $id !== $product->id);
            if (count($ids)) {
                $products = Product::whereIn('id', $ids)->get()->keyBy('id');
                $ordered = collect(array_slice($ids, 0, 6))->map(fn($id) => $products->get($id))->filter();
                $recentlyViewed = $ordered;
            }
        }

        return view('products.show', compact('product', 'recentlyViewed'));
    }

}
