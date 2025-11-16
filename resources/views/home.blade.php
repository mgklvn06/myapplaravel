<x-app-layout title="Welcome to My Shop">
  <section class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
      <div class="md:col-span-2">
        <h1 class="text-3xl font-bold mb-2">Welcome to My Shop</h1>
        <p class="text-gray-700 mb-4">Discover curated products, great deals, and fast checkout. Browse featured items or search the catalog.</p>

        <div class="flex gap-3">
          <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Shop All Products</a>
          @auth
            @if(auth()->user()->isCustomer())
              <a href="{{ route('account.dashboard') }}" class="px-4 py-2 border rounded">My Account</a>
            @endif
            @if(auth()->user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border rounded">Admin Dashboard</a>
            @endif
          @endauth
        </div>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Top Categories</h3>
        <ul class="space-y-2">
          @foreach(\App\Models\Category::limit(6)->get() as $c)
            <li><a href="{{ route('products.index', ['category' => $c->slug]) }}" class="text-sm text-gray-700 hover:underline">{{ $c->name }}</a></li>
          @endforeach
        </ul>
      </div>
    </div>
  </section>

  <section id="featured" class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Featured Products</h2>
    @php
      $featured = \App\Models\Product::where('is_featured', true)->take(6)->get();
    @endphp

    @if($featured->isEmpty())
      <div class="p-6 bg-white rounded text-gray-600">No featured products yet. <a href="{{ route('products.index') }}" class="text-blue-600">Browse products</a>.</div>
    @else
      @include('products._grid', ['products' => $featured])
    @endif
  </section>

  <section id="collections" class="mb-8">
    <h2 class="text-2xl font-bold mb-4">Collections</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @foreach(\App\Models\Category::limit(6)->get() as $c)
        <div class="bg-white rounded shadow p-4">
          <h3 class="font-semibold">{{ $c->name }}</h3>
          <p class="text-sm text-gray-600 mt-2">Explore products in {{ $c->name }}.</p>
          <a href="{{ route('products.index', ['category' => $c->slug]) }}" class="mt-3 inline-block text-blue-600">View collection →</a>
        </div>
      @endforeach
    </div>
  </section>

  <section>
    <h2 class="text-2xl font-bold mb-4">Latest Products</h2>
    @php
      $latest = \App\Models\Product::latest()->take(9)->get();
    @endphp
    @include('products._grid', ['products' => $latest])
  </section>
</x-app-layout>
