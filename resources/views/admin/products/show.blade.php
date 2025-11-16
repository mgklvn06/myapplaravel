<x-app-layout title="View Product">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">View Product: {{ $product->name }}</h1>
    <div>
      <a href="{{ route('admin.products.edit', $product) }}" class="px-3 py-1 border rounded mr-2">Edit</a>
      <a href="{{ route('admin.products.index') }}" class="px-3 py-1 border rounded">Back to Products</a>
    </div>
  </div>

  <div class="bg-white rounded shadow p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="font-semibold mb-2">Product Details</h3>
        <table class="w-full">
          <tr><td class="font-medium p-2">ID:</td><td>{{ $product->id }}</td></tr>
          <tr><td class="font-medium p-2">Name:</td><td>{{ $product->name }}</td></tr>
          <tr><td class="font-medium p-2">Slug:</td><td>{{ $product->slug }}</td></tr>
          <tr><td class="font-medium p-2">SKU:</td><td>{{ $product->sku }}</td></tr>
          <tr><td class="font-medium p-2">Price:</td><td>${{ number_format($product->price, 2) }}</td></tr>
          <tr><td class="font-medium p-2">Stock Quantity:</td><td>{{ $product->stock_quantity }}</td></tr>
          <tr><td class="font-medium p-2">Category:</td><td>{{ $product->category->name ?? '—' }}</td></tr>
          <tr><td class="font-medium p-2">Active:</td><td>{{ $product->is_active ? 'Yes' : 'No' }}</td></tr>
          <tr><td class="font-medium p-2">Featured:</td><td>{{ $product->is_featured ? 'Yes' : 'No' }}</td></tr>
        </table>
      </div>

      <div>
        <h3 class="font-semibold mb-2">Description</h3>
        <div class="prose max-w-none">
          {!! nl2br(e($product->description)) !!}
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
