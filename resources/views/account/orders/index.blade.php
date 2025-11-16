<x-app-layout title="My Orders">
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">My Orders</h1>

            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="bg-white rounded-lg shadow overflow-hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold">Order #{{ $order->id }}</h3>
                                        <p class="text-sm text-gray-600">Placed on {{ $order->created_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-600">Status: <span class="font-medium">{{ ucfirst($order->status) }}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold">${{ number_format($order->total, 2) }}</p>
                                        <a href="{{ route('orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View Details</a>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($order->items->take(3) as $item)
                                            <div class="flex items-center space-x-3">
                                                @if($item->product->image)
                                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover rounded">
                                                @else
                                                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-500">No Image</div>
                                                @endif
                                                <div>
                                                    <p class="font-medium text-sm">{{ $item->product->name }}</p>
                                                    <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <div class="flex items-center text-sm text-gray-600">
                                                +{{ $order->items->count() - 3 }} more items
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-8 text-center">
                    <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
