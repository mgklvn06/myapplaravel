<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- hero + header / actions -->
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900">Shop our collection</h1>
                        <p class="mt-1 text-gray-600">Hand-picked products, great prices. Browse by category or search.</p>
                    </div>
                    <div class="text-sm text-gray-600">
                        Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="bg-white rounded-xl shadow p-6 mb-6">
                <form method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Product name, description, or SKU" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\Category::all() as $cat)
                                <option value="{{ $cat->slug }}" {{ ($filters['category'] ?? '') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Min Price -->
                    <div>
                        <label for="min_price" class="block text-sm font-medium text-gray-700">Min Price</label>
                        <input type="number" id="min_price" name="min_price" value="{{ $filters['min_price'] ?? '' }}" step="0.01" placeholder="0.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label for="max_price" class="block text-sm font-medium text-gray-700">Max Price</label>
                        <input type="number" id="max_price" name="max_price" value="{{ $filters['max_price'] ?? '' }}" step="0.01" placeholder="0.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Sort -->
                    <div class="md:col-span-2">
                        <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select id="sort" name="sort" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="newest" {{ ($filters['sort'] ?? 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ ($filters['sort'] ?? '') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700">Items per Page</label>
                        <select id="per_page" name="per_page" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="12" {{ ($filters['per_page'] ?? 12) == 12 ? 'selected' : '' }}>12</option>
                            <option value="24" {{ ($filters['per_page'] ?? '') == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ ($filters['per_page'] ?? '') == 48 ? 'selected' : '' }}>48</option>
                        </select>
                    </div>

                    <!-- Submit Button -->
                    <div class="md:col-span-4 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Apply Filters</button>
                        <a href="{{ route('products.index') }}" class="ml-2 px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Clear</a>
                    </div>
                </form>
            </div>

            <!-- product grid (uses partial) -->
            <div>
                @include('products._grid', ['products' => $products])
            </div>

            <!-- pagination -->
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
