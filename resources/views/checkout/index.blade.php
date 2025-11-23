<x-app-layout title="Checkout">
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Checkout</h1>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <!-- CSRF Token for AJAX -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        @foreach($products as $product)
                            @php 
                                $qty = $cart[$product->id]['quantity']; 
                                $stockQty = $product->stock_quantity ?? 0;
                            @endphp
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    @if($product->image)
                                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded">
                                    @else
                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-500">No Image</div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">Qty: {{ $qty }}</p>
                                        @if($stockQty < $qty)
                                            <p class="text-xs text-red-600 font-semibold">Only {{ $stockQty }} in stock ({{$qty}} in cart)</p>
                                        @elseif($stockQty <= 0)
                                            <p class="text-xs text-red-600 font-semibold">Out of stock</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-lg font-semibold">${{ number_format($product->price * $qty, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Checkout Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Shipping & Payment Information</h2>
                    <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <!-- Shipping Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Shipping Details</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                                </div>

                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                    <input type="text" id="city" name="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>

                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                    <input type="text" id="postal_code" name="postal_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-3">Payment Method</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <input id="mpesa" name="payment_method" type="radio" value="mpesa" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" checked>
                                            <label for="mpesa" class="ml-3 block text-sm font-medium text-gray-700">
                                                M-Pesa
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div id="mpesa-fields">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">M-Pesa Phone Number</label>
                                    <input type="tel" id="phone" name="phone" placeholder="254712345678" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                    <p class="mt-1 text-sm text-gray-500">Enter your M-Pesa registered phone number (e.g., 254712345678)</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="place-order-btn" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            Place Order & Pay with M-Pesa
                        </button>
                    </form>

                    <!-- Payment Status -->
                    <div id="payment-status" class="mt-4 hidden">
                        <div class="bg-blue-50 border border-blue-200 rounded p-4">
                            <p class="text-blue-800" id="payment-message">Processing payment...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
