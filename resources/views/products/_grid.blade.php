@if($products->isEmpty())
  <div class="text-gray-600">No products to show.</div>
@else
  <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    @foreach($products as $product)
      <article class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden flex flex-col">
        <a href="{{ route('products.show', $product) }}" class="block flex-1">
          <div class="h-44 bg-gray-100 flex items-center justify-center overflow-hidden">
            @if(!empty($product->image_url))
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
              {{-- SVG placeholder for nicer look --}}
              <svg class="w-16 h-16 text-gray-300" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect width="24" height="24" rx="4" fill="currentColor" opacity="0.06" />
                <path d="M4 7h16M4 12h10M4 17h16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.4"/>
              </svg>
            @endif
          </div>

          <div class="p-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $product->name }}</h4>
            <div class="flex items-center justify-between">
              <div class="text-sm font-bold text-indigo-600">${{ number_format($product->price,2) }}</div>
              <div class="text-xs px-2 py-1 rounded-full {{ ($product->stock_quantity ?? 0) > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ($product->stock_quantity ?? 0) > 0 ? 'In stock' : 'Out of stock' }}
              </div>
            </div>
          </div>
        </a>

        <div class="p-3 border-t bg-white">
          <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-2 js-add-to-cart" aria-label="Add {{ $product->name }} to cart">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">Add to cart</button>
            <a href="{{ route('products.show', $product) }}" class="inline-flex items-center px-3 py-2 border rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300" aria-label="View details for {{ $product->name }}">Details</a>
          </form>
        </div>
      </article>
    @endforeach
  </div>
@endif
