@if($products->isEmpty())
  <div class="text-gray-600">No products to show.</div>
@else
  <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
    @foreach($products as $product)
      <article class="bg-white rounded-lg shadow overflow-hidden">
        <a href="{{ route('products.show', $product->slug ?? $product->id) }}" class="block">
          <div class="h-40 bg-gray-100 flex items-center justify-center">
            @if(!empty($product->image_url))
              <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
              <div class="text-sm text-gray-400">No image</div>
            @endif
          </div>

          <div class="p-3">
            <h4 class="text-sm font-semibold text-gray-900">{{ $product->name }}</h4>
            <div class="text-xs text-gray-600 mt-1">${{ number_format($product->price,2) }}</div>
          </div>
        </a>
      </article>
    @endforeach
  </div>
@endif
