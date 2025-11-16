<x-app-layout title="Order Details">
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Order #{{ $order->id }}</h1>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Order Information</h2>
                            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                            <p><strong>Ordered on:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold mb-2">Shipping Address</h2>
                            <p>{{ $order->shipping_address['address'] }}</p>
                            <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['postal_code'] }}</p>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between border-b pb-4">
                                <div class="flex items-center space-x-4">
                                    @if($item->product->image)
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-500">No Image</div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                                        <p class="text-sm text-gray-600">${{ number_format($item->unit_price, 2) }} each</p>
                                    </div>
                                </div>
                                <div class="text-lg font-semibold">${{ number_format($item->unit_price * $item->quantity, 2) }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('account.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">← Back to Dashboard</a>
                        <div class="text-xl font-bold">Total: ${{ number_format($order->total, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
