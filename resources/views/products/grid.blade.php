<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
  @foreach($products as $product)
    @include('products.productscard', compact('product'))
  @endforeach
</div>
