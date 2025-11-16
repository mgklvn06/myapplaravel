<?php

namespace App\Http\Controllers;

            use App\Http\Controllers\Controller;
            use App\Models\Order;
            use App\Models\OrderItem;
            use App\Models\Product;
            use Illuminate\Http\Request;
            use Illuminate\Support\Facades\Auth;
            use Illuminate\Support\Facades\DB;

            class CheckoutController extends Controller
            {
                /**
                 * Display checkout form
                 */
                public function index()
                {
                    $cart = session()->get('cart', []);

                    if (empty($cart)) {
                        return redirect()->route('cart.index')->with('error', 'Cart is empty');
                    }
                    $products = Product::whereIn('id', array_keys($cart))->get();
                    $total = $products->sum(function ($product) use ($cart) {
                        return $product->price * $cart[$product->id]['quantity'];
                    });

                    return view('checkout.index', compact('cart', 'products', 'total'));
                }

                /**
                 * Process checkout and create order
                 */
                public function store(Request $request)
                {
                    $this->validate($request, [
                        'shipping_address' => 'required|string|max:255',
                        'city' => 'required|string|max:100',
                        'postal_code' => 'required|string|max:20',
                    ]);

            
                    $cart = session()->get('cart', []);

                    if (empty($cart)) {
                        return redirect()->route('cart.index')->with('error', 'Cart is empty');
                    }
                    $user = Auth::user();
                    if (!$user) {
                        return redirect()->route('login')->with('error', 'Please login first');
                    }

                    try {
                        DB::beginTransaction();

                        // Fetch products and verify stock
                        $products = Product::whereIn('id', array_keys($cart))->get();
                        $total = 0;

                        foreach ($products as $product) {
                            $qty = $cart[$product->id]['quantity'];

                            // Check stock
                            if ($qty > $product->stock) {
                                DB::rollBack();
                                return back()->with('error', "Not enough stock for {$product->name}");
                            }

                            $total += $product->price * $qty;
                        }

                        // Create order
                        $shippingAddress = [
                            'address' => $request->input('shipping_address'),
                            'city' => $request->input('city'),
                            'postal_code' => $request->input('postal_code'),
                        ];

                        $order = Order::create([
                            'user_id' => $user->id,
                            'total' => $total,
                            'status' => 'pending',
                            'shipping_address' => $shippingAddress,
                        ]);

                        // Create order items and reduce stock
                        foreach ($products as $product) {
                            $qty = $cart[$product->id]['quantity'];

                            OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'quantity' => $qty,
                                'unit_price' => $product->price,
                            ]);

                            // Reduce stock
                            $product->decrement('stock', $qty);
                            $product->increment('sold_count', $qty);
                        }

                        DB::commit();

                        // Clear cart
                        session()->forget('cart');

                        return redirect()->route('order.show', $order->id)
                            ->with('success', 'Order placed successfully!');

                    } catch (\Exception $e) {
                        DB::rollBack();
                        return back()->with('error', 'An error occurred: ' . $e->getMessage());
                    }
                }
            }

       
