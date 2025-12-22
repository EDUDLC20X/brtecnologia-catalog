@extends('layouts.app')

@section('title', 'Crear Producto')

@section('content')

    @include('admin.products._form', [
        'formAction' => route('products.store'),
        'formMethod' => 'POST',
        'categories' => $categories,
    ])

@endsection