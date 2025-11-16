<x-app-layout title="Cart">
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Your Cart</h1>

            @if(empty($cart))
                <div class="bg-white rounded-lg shadow p-6 text-center">
                    <p class="text-gray-600 mb-4">Your cart is empty.</p>
                    <a href="{{ route('products.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Continue Shopping</a>
                </div>
            @else
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-4">
                            @php $total = 0; @endphp
                            @foreach($cart as $id => $item)
                                @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                                <div class="flex items-center justify-between border-b pb-4">
                                    <div class="flex items-center space-x-4">
                                        @if($item['image'])
                                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-500">No Image</div>
                                        @endif
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $item['name'] }}</h3>
                                            <p class="text-gray-600">${{ number_format($item['price'], 2) }} each</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border rounded px-2 py-1">
                                            <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Update</button>
                                        </form>
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                        </form>
                                        <div class="text-lg font-semibold">${{ number_format($subtotal, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6 flex justify-between items-center">
                            <div class="text-xl font-bold">Total: ${{ number_format($total, 2) }}</div>
                            <div class="space-x-4">
                                <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Continue Shopping</a>
                                <a href="{{ route('checkout.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
