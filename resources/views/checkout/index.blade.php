<x-app-layout title="Checkout">
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Checkout</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    <div class="space-y-4">
                        @foreach($products as $product)
                            @php $qty = $cart[$product->id]['quantity']; @endphp
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
                    <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                            <textarea id="shipping_address" name="shipping_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="city" name="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <div class="mb-6">
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
