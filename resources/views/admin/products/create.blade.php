@extends('layouts.app')

@section('title','Create product')

@section('content')
  <h1 class="text-2xl font-bold mb-4">Create product</h1>

  @if($errors->any())
    <div class="mb-3">
      <ul class="text-red-600">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.products.store') }}" method="post">
    @csrf
    @include('admin.products._form', ['product' => null])
    <div><button class="px-3 py-1 border rounded">Create</button></div>
  </form>
@endsection
