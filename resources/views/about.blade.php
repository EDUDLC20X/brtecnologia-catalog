@extends('layouts.app')

@php
    $content = \App\Models\SiteContent::getSection('about');
    $global = \App\Models\SiteContent::getSection('global');
@endphp

@section('title', $content['about.title'] ?? 'Acerca de Nosotros')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">{{ $content['about.title'] ?? 'Acerca de Nosotros' }}</h1>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $content['about.history.title'] ?? 'Nuestra Historia' }}</h4>
                    {!! $content['about.history.content'] ?? '<p class="lead">B&R Tecnología es una empresa dedicada a ofrecer productos de alta calidad.</p>' !!}
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $content['about.mission.title'] ?? 'Nuestra Misión' }}</h4>
                    <p>{{ $content['about.mission.content'] ?? 'Nuestra misión es ser tu proveedor de confianza, ofreciendo productos de calidad a precios accesibles.' }}</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ $content['about.values.title'] ?? 'Nuestros Valores' }}</h4>
                    <ul class="list-group list-group-flush">
                        {!! $content['about.values.content'] ?? '<li class="list-group-item">✓ <strong>Calidad:</strong> Solo ofrecemos productos de alta calidad.</li><li class="list-group-item">✓ <strong>Integridad:</strong> Transparencia en todas nuestras operaciones.</li><li class="list-group-item">✓ <strong>Servicio:</strong> Atención al cliente excepcional.</li><li class="list-group-item">✓ <strong>Innovación:</strong> Constantemente mejoramos nuestra plataforma.</li>' !!}
                    </ul>
                </div>
            </div>

            @php
                $aboutImage = $content['about.image'] ?? null;
            @endphp
            @if($aboutImage)
                <div class="card shadow-sm mb-4">
                    <img src="{{ content_image_url($aboutImage) }}" 
                         alt="{{ $global['global.company_name'] ?? 'Nuestra Empresa' }}" 
                         class="img-fluid rounded">
                </div>
            @endif
            
            <div class="text-center mt-4">
                <a href="{{ route('contact') }}" class="btn btn-primary btn-lg me-2">Contáctanos</a>
                <a href="{{ route('catalog') }}" class="btn btn-outline-primary btn-lg">Ver Catálogo</a>
            </div>
        </div>
    </div>
</div>
@endsection
