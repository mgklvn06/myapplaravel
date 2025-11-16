<nav class="bg-white shadow-md">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-6">
      <a href="{{ route('home') }}" class="font-bold text-xl text-indigo-600">My Shop</a>
      <a href="{{ route('products.index') }}" class="text-sm text-gray-700 hover:text-indigo-600">Products</a>
      <a href="{{ route('home') }}#collections" class="text-sm text-gray-700 hover:text-indigo-600">Collections</a>
    </div>

    <div class="flex items-center gap-4">
      <form action="{{ route('products.index') }}" method="get" class="hidden sm:flex items-center">
        <label for="q" class="sr-only">Search</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
               class="border rounded-l px-3 py-1 w-56 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <button class="border rounded-r px-3 py-1 bg-indigo-50 text-indigo-600">Search</button>
      </form>

  <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" aria-label="View cart">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
          <path d="M16 11V3a1 1 0 00-1-1H5a1 1 0 00-1 1v8H1v2h1a3 3 0 006 0h6a3 3 0 006 0h1v-2h-4z" />
        </svg>
        <span class="hidden sm:inline">Cart</span>
        @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
        <span id="cart-count" class="ml-1 inline-block bg-indigo-600 text-white text-xs px-2 py-0.5 rounded" aria-live="polite">{{ $cartCount }}</span>
      </a>

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
