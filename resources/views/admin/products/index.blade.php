@extends('layouts.app')

@section('title','Admin - Products')

@section('content')
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Products</h1>
    <a href="{{ route('admin.products.create') }}" class="px-3 py-1 border rounded">Create product</a>
  </div>

  @if(session('success')) <div class="mb-3 text-green-700">{{ session('success') }}</div> @endif

  <table class="w-full bg-white border">
    <thead>
      <tr class="text-left">
        <th class="p-2">ID</th>
        <th class="p-2">Name</th>
        <th class="p-2">Category</th>
        <th class="p-2">Price</th>
        <th class="p-2">Stock</th>
        <th class="p-2">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $p)
      <tr>
        <td class="p-2">{{ $p->id }}</td>
        <td class="p-2">{{ $p->name }}</td>
        <td class="p-2">{{ $p->category->name ?? '—' }}</td>
        <td class="p-2">${{ number_format($p->price,2) }}</td>
        <td class="p-2">{{ $p->stock_quantity }}</td>
        <td class="p-2">
          <a class="mr-2 text-blue-600" href="{{ route('admin.products.edit', $p) }}">Edit</a>

          <form action="{{ route('admin.products.destroy', $p) }}" method="post" style="display:inline" onsubmit="return confirm('Remove product?')">
            @csrf @method('DELETE')
            <button class="text-red-600">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="mt-4">
    {{ $products->links() }}
  </div>
@endsection
