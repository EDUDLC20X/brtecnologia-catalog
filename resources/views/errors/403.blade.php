@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-6 text-danger">403</h1>
                    <h3 class="mb-3">Acceso denegado</h3>
                    <p class="text-muted">No tienes permiso para acceder a esta sección.</p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary me-2">Volver al inicio</a>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
