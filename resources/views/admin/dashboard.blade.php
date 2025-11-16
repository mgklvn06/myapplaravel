<x-app-layout title="Admin Dashboard">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Total Products</div>
      <div class="text-2xl font-bold">{{ \App\Models\Product::count() }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Total Categories</div>
      <div class="text-2xl font-bold">{{ \App\Models\Category::count() }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Total Users</div>
      <div class="text-2xl font-bold">{{ \App\Models\User::count() }}</div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="bg-white rounded shadow p-4">
      <h3 class="font-semibold mb-3">Recent Products</h3>
      <ul class="space-y-2">
        @foreach(\App\Models\Product::latest()->take(10)->get() as $p)
          <li class="flex justify-between items-start">
            <div>
              <a href="{{ route('admin.products.edit', $p) }}" class="font-medium">{{ $p->name }}</a>
              <div class="text-xs text-gray-500">{{ $p->category->name ?? '—' }}</div>
            </div>
            <div class="text-sm text-gray-700">${{ number_format($p->price, 2) }}</div>
          </li>
        @endforeach
      </ul>
    </div>

    <div class="bg-white rounded shadow p-4">
      <h3 class="font-semibold mb-3">Quick Actions</h3>
      <div class="flex flex-col gap-2">
        <a class="px-3 py-2 border rounded" href="{{ route('admin.products.create') }}">Create Product</a>
        <a class="px-3 py-2 border rounded" href="{{ route('admin.products.index') }}">Manage Products</a>
        <a class="px-3 py-2 border rounded" href="{{ route('admin.orders.index') }}">Manage Orders</a>
        <a class="px-3 py-2 border rounded" href="{{ route('profile.edit') }}">Profile Settings</a>
        <a class="px-3 py-2 border rounded" href="{{ route('products.index') }}">View Storefront</a>
      </div>
    </div>
  </div>
</x-app-layout>
