@props(['title' => null])

@php
    $pageTitle = $title ?? trim(View::yieldContent('title')) ?: config('app.name', 'My Shop');
@endphp

@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
    @if(isset($header))
        <div class="mb-4">
            {{ $header }}
        </div>
    @endif

    {{ $slot }}
@endsection
