@php
  // Accepts $product
  $img = $product->first_image ?? 'https://via.placeholder.com/400';
@endphp

<div class="border rounded p-3 bg-white hover:shadow">
  <a href="{{ route('products.show', $product->slug) }}" class="block">
    <div class="h-48 w-full overflow-hidden rounded mb-3">
      <img src="{{ $img }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
    </div>

    <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
    <p class="text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>

    <div class="mt-3 font-bold">${{ number_format($product->sale_price ?? $product->price, 2) }}</div>
  </a>
</div>
