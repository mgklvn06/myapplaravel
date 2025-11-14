<nav class="bg-white shadow">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
      <a href="{{ route('home') }}" class="font-bold text-xl">My Shop</a>
      <a href="{{ route('products.index') }}" class="text-sm text-gray-700">Products</a>
      <a href="{{ route('home') }}#collections" class="text-sm text-gray-700">Collections</a>
    </div>

    <div class="flex items-center gap-4">
      <form action="{{ route('products.index') }}" method="get" class="hidden sm:flex items-center gap-0">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
               class="border rounded-l px-3 py-1 w-56">
        <button class="border rounded-r px-3 py-1 bg-gray-50">Search</button>
      </form>

      @auth
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 border rounded text-sm">Admin</a>
        @endif

        @if(auth()->user()->isCustomer())
          <a href="{{ route('account.dashboard') }}" class="px-3 py-1 border rounded text-sm">Account</a>
        @endif

        <form action="{{ route('logout') }}" method="post" class="inline">
          @csrf
          <button type="submit" class="px-3 py-1 border rounded text-sm">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="px-3 py-1 border rounded text-sm">Login</a>
        <a href="{{ route('register') }}" class="px-3 py-1 border rounded text-sm">Register</a>
      @endauth
    </div>
  </div>
</nav>
