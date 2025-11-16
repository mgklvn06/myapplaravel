<x-app-layout title="Edit product">
  <h1 class="text-2xl font-bold mb-4">Edit product</h1>

  <form action="{{ route('admin.products.update', $product) }}" method="post">
    @csrf @method('PUT')
    @include('admin.products.form', ['product' => $product])
    <div><button class="px-3 py-1 border rounded">Update</button></div>
  </form>
</x-app-layout>
