@extends('layouts.app')

@section('title', 'Editar Producto: '.$product->name)

@section('content')

    @include('admin.products._form', [
        'formAction' => route('products.update', $product),
        'formMethod' => 'PUT',
        'product' => $product,
        'categories' => $categories,
    ])

@endsection