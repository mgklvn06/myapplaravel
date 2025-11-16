@props(['title' => null])

@php
        $pageTitle = $title ?? trim(View::yieldContent('title')) ?: config('app.name', 'My Shop');
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-white via-gray-50 to-gray-100 text-gray-900 min-h-screen flex flex-col">
    <x-header />

    <main class="container mx-auto px-4 py-8 flex-1">
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(isset($header))
                <div class="mb-4">{{ $header }}</div>
        @endif

        {{ $slot }}
    </main>

    <footer class="bg-white border-t py-6">
        <div class="container mx-auto px-4 text-center text-sm text-gray-600">
            &copy; {{ date('Y') }} {{ config('app.name', 'My Shop') }} — Built with ♥
        </div>
    </footer>
</body>
</html>
