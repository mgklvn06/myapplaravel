<x-app-layout title="My Account">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Welcome back</div>
      <div class="text-xl font-bold">{{ auth()->user()->name }}</div>
      <div class="mt-2 text-sm text-gray-600">Email: {{ auth()->user()->email }}</div>
    </div>

    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Orders</div>
      @if(class_exists(\App\Models\Order::class))
        <div class="text-xl font-bold">{{ auth()->user()->orders()->count() }}</div>
      @else
        <div class="text-xl font-bold">—</div>
        <div class="text-sm text-gray-600 mt-1">No orders yet</div>
      @endif
    </div>

    <div class="p-4 bg-white rounded shadow">
      <div class="text-sm text-gray-500">Actions</div>
      <div class="mt-2 flex flex-col gap-2">
        <a href="{{ route('profile.edit') }}" class="px-3 py-2 border rounded">Edit Profile</a>
        <a href="{{ route('account.orders.index') }}" class="px-3 py-2 border rounded">My Orders</a>
      </div>
    </div>
  </div>

  <div class="bg-white rounded shadow p-4">
    <h3 class="font-semibold mb-3">Recently Viewed / Recommended</h3>

    @php
      // Fall back to latest products if there's no personalized history
      $items = method_exists(auth()->user(), 'recentlyViewedProducts')
               ? auth()->user()->recentlyViewedProducts()
               : \App\Models\Product::latest()->take(6)->get();
    @endphp

    @include('products._grid', ['products' => $items])
  </div>
</x-app-layout>
