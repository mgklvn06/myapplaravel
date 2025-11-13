<div class="grid gap-3">
  <label>
    <div class="text-sm">Name</div>
    <input name="name" value="{{ old('name', $product->name ?? '') }}" class="border w-full p-2">
  </label>

  <label>
    <div class="text-sm">Slug</div>
    <input name="slug" value="{{ old('slug', $product->slug ?? '') }}" class="border w-full p-2">
  </label>

  <label>
    <div>Category</div>
    <select name="category_id" class="border w-full p-2">
      <option value="">-- none --</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
      @endforeach
    </select>
  </label>

  <label>
    <div>Price</div>
    <input name="price" value="{{ old('price', $product->price ?? '') }}" class="border w-full p-2">
  </label>

  <label>
    <div>Description</div>
    <textarea name="description" class="border w-full p-2" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
  </label>

  <label>
    <div>Stock quantity</div>
    <input name="stock_quantity" type="number" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" class="border w-full p-2">
  </label>

  <label class="flex items-center gap-2">
    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
    <span>Active</span>
  </label>
</div>
