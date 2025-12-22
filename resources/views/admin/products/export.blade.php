@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Exportar productos</h2>
    <p>¿Deseas exportar todos los productos? Se generará un archivo y recibirás un email con el enlace.</p>

    <form method="POST" action="{{ route('products.export') }}">
        @csrf
        <button class="btn btn-primary">Confirmar exportación</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
