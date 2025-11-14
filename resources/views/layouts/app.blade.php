<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'My Shop')</title>
  {{-- Vite (or replace with your compiled CSS/JS) --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
  @include('partials.nav')

  <div class="container mx-auto px-4 py-6 flex-1">
    @if(session('success'))
      <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded">
        {{ session('success') }}
      </div>
    @endif

    @yield('content')
  </div>

  <footer class="bg-white border-t py-4">
    <div class="container mx-auto px-4 text-center text-sm text-gray-600">
      &copy; {{ date('Y') }} My Shop — Built with ♥
    </div>
  </footer>
</body>
</html>
