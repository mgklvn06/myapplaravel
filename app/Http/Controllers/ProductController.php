<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

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
    public function category()
{
    return $this->belongsTo(Category::class);
}

}
