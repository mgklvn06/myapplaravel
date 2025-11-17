<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order #{{ $order->id }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Order Details</h3>
                                <p class="text-gray-600 mt-1">Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                                       ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                       'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Name: <span class="font-medium">{{ $order->user->name ?? 'N/A' }}</span></p>
                                <p class="text-sm text-gray-600">Email: <span class="font-medium">{{ $order->user->email ?? 'N/A' }}</span></p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Order ID: <span class="font-medium">#{{ $order->id }}</span></p>
                                <p class="text-sm text-gray-600">Total Items: <span class="font-medium">{{ $order->items->sum('quantity') }}</span></p>
                                <p class="text-sm text-gray-600">Total Amount: <span class="font-medium text-lg">${{ number_format($order->total, 2) }}</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-8">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Order Items</h4>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($item->product)
                                                        @if($item->product->image)
                                                            <img class="h-10 w-10 rounded-lg object-cover" src="{{ $item->product->image }}" alt="{{ $item->product->name }}">
                                                        @else
                                                            <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                                                <span class="text-xs text-gray-500">No Image</span>
                                                            </div>
                                                        @endif
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                                            <div class="text-sm text-gray-500">{{ $item->product->sku ?? 'N/A' }}</div>
                                                        </div>
                                                    @else
                                                        <div class="text-sm text-gray-500">Product not found</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($item->price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Actions -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Order Actions</h4>
                        <div class="flex flex-wrap gap-3">
                            @if($order->status !== 'completed')
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        Mark as Completed
                                    </button>
                                </form>
                            @endif

                            @if($order->status === 'pending')
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Start Processing
                                    </button>
                                </form>
                            @endif

                            @if($order->status !== 'cancelled')
                                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Cancel Order
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
