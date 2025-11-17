<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Add product to cart with stock validation
     */
    public function add(Request $request, Product $product)
    {
        // Validate product exists and is active
        if (!$product->is_active) {
            return back()->with('error', 'Product is not available');
        }

        $cart = session()->get('cart', []);
        $id = $product->id;
        $qty = (int)$request->input('quantity', 1);

        // Validate quantity
        if ($qty <= 0) {
            return back()->with('error', 'Invalid quantity');
        }

        // Check stock availability
        $currentQty = $cart[$id]['quantity'] ?? 0;
        $totalQty = $currentQty + $qty;

        if ($totalQty > $product->stock_quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'product_id' => $id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        // calculate total items in cart
        $cartCount = array_sum(array_column(session('cart', []), 'quantity'));

        // If AJAX / expects JSON, return JSON response
        if ($request->wantsJson() || $request->ajax() || str_contains($request->header('Accept', ''), 'application/json')) {
            return response()->json([
                'success' => true,
                'message' => 'Added to cart',
                'cart_count' => $cartCount,
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    /**
     * Update product quantity in cart
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return back()->with('error', 'Item not found in cart');
        }

        $qty = (int)$request->input('quantity', 1);

        // Validate quantity
        if ($qty <= 0) {
            unset($cart[$id]);
        } else {
            // Check stock availability if product exists
            $product = \App\Models\Product::find($id);
            if ($product && $qty > $product->stock_quantity) {
                return back()->with('error', 'Not enough stock available');
            }
            $cart[$id]['quantity'] = $qty;
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated');
    }

    /**
     * Remove product from cart
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('success', 'Product removed from cart');
        }
        return back()->with('error', 'Item not found in cart');
    }

    /**
     * Clear all items from cart
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared');
    }

    /**
     * Display cart contents
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }
}
