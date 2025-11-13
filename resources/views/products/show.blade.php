<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $product->name }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-sm text-indigo-600 hover:underline">← Back to products</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow overflow-hidden md:flex">
                <div class="md:w-1/2">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-96 object-cover">
                    @else
                        <div class="w-full h-96 bg-gray-100 flex items-center justify-center text-gray-400">
                            No image
                        </div>
                    @endif
                </div>

                <div class="p-6 md:w-1/2">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="text-2xl font-extrabold text-indigo-600 mb-4">${{ number_format($product->price, 2) }}</div>

                    <p class="text-gray-700 mb-4">{{ $product->excerpt ?? '' }}</p>

                    <div class="prose max-w-none text-gray-700 mb-6">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <div class="flex items-center space-x-4">
                        @if($product->stock > 0)
                            <form method="POST" action="#">
                                @csrf
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                                    Add to cart
                                </button>
                            </form>
                        @else
                            <div class="text-sm text-red-600 font-semibold">Out of stock</div>
                        @endif

                        <div class="text-sm text-gray-500">SKU: {{ $product->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
