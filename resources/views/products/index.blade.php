<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- header / actions -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-semibold text-gray-900">All Products</h3>
                <div class="text-sm text-gray-600">
                    Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                </div>
            </div>

            <!-- grid -->
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($products as $product)
                    <article class="bg-white rounded-2xl shadow hover:shadow-lg overflow-hidden transition">
                        <a href="{{ route('products.show', $product->id) }}" class="block">
                            <div class="h-48 bg-gray-100 overflow-hidden">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        No image
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $product->name }}</h4>
                                <p class="text-sm text-gray-500 mb-3">
                                    {{ \Illuminate\Support\Str::limit($product->excerpt ?? $product->description, 100) }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="text-lg font-bold text-indigo-600">${{ number_format($product->price, 2) }}</div>
                                    <div>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
