<nav x-data="{ open: false }" class="bg-white shadow-md">
  @php $cartCount = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-4">
      <a href="{{ route('home') }}" class="font-bold text-xl text-indigo-600">My Shop</a>
  <div class="hidden md:flex items-center gap-4">
        <a href="{{ route('products.index') }}" class="text-sm text-gray-700 hover:text-indigo-600">Products</a>
        <a href="{{ route('home') }}#collections" class="text-sm text-gray-700 hover:text-indigo-600">Collections</a>
      </div>
    </div>

  <!-- desktop actions -->
  <div class="hidden md:flex items-center gap-4">
      <form action="{{ route('products.index') }}" method="get" class="flex items-center">
        <label for="q" class="sr-only">Search</label>
        <input id="q" type="text" name="q" value="{{ request('q') }}" placeholder="Search products..."
               class="border rounded-l px-3 py-1 w-56 focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <button class="border rounded-r px-3 py-1 bg-indigo-50 text-indigo-600">Search</button>
      </form>

      <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-1 border rounded text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" aria-label="View cart">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
          <path d="M16 11V3a1 1 0 00-1-1H5a1 1 0 00-1 1v8H1v2h1a3 3 0 006 0h6a3 3 0 006 0h1v-2h-4z" />
        </svg>
  <span class="hidden lg:inline">Cart</span>
        <span id="cart-count" class="ml-1 inline-block bg-indigo-600 text-white text-xs px-2 py-0.5 rounded" aria-live="polite">{{ $cartCount }}</span>
      </a>

      @auth
        @if(auth()->user()->isCustomer())
          <a href="{{ route('account.dashboard') }}" class="px-3 py-1 border rounded text-sm">Account</a>
        @endif
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="px-3 py-1 border rounded text-sm">Admin</a>
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

    <!-- mobile: menu button -->
    <div class="md:hidden flex items-center">
      <button @click="open = !open" :aria-expanded="open" aria-controls="mobile-menu" class="p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-300" aria-label="Toggle menu">
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>

  <!-- mobile menu -->
  <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden border-t" id="mobile-menu">
    <div class="px-4 py-3">
      <form action="{{ route('products.index') }}" method="get" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="flex-1 border rounded px-3 py-2">
        <button class="px-3 py-2 bg-indigo-600 text-white rounded">Search</button>
      </form>
    </div>

    <div class="px-4 pb-4 space-y-2">
      <a href="{{ route('products.index') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Products</a>
      <a href="{{ route('home') }}#collections" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Collections</a>
  <a href="{{ route('cart.index') }}" class="flex items-center gap-2 px-3 py-2 rounded text-gray-700 hover:bg-gray-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" viewBox="0 0 20 20" fill="currentColor">
          <path d="M16 11V3a1 1 0 00-1-1H5a1 1 0 00-1 1v8H1v2h1a3 3 0 006 0h6a3 3 0 006 0h1v-2h-4z" />
        </svg>
        <span>Cart</span>
  <span id="cart-count-mobile" class="ml-auto inline-block bg-indigo-600 text-white text-xs px-2 py-0.5 rounded">{{ $cartCount }}</span>
      </a>

      @auth
        @if(auth()->user()->isCustomer())
          <a href="{{ route('account.dashboard') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Account</a>
        @endif
        @if(auth()->user()->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Admin</a>
        @endif

        <form action="{{ route('logout') }}" method="post" class="mt-2">
          @csrf
          <button type="submit" class="w-full text-left px-3 py-2 rounded border">Logout</button>
        </form>
      @else
        <a href="{{ route('login') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Login</a>
        <a href="{{ route('register') }}" class="block px-3 py-2 rounded text-gray-700 hover:bg-gray-50">Register</a>
      @endauth
    </div>
  </div>
</nav>
