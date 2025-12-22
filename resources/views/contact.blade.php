@extends('layouts.app')

@php
    $content = \App\Models\SiteContent::getSection('contact');
    $global = \App\Models\SiteContent::getSection('global');
    $adminEmail = \App\Services\AdminService::getAdminEmail();
@endphp

@section('title', $content['contact.title'] ?? 'Contacto')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4">{{ $content['contact.title'] ?? 'Contacto' }}</h1>

            <div class="row mb-4">
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-phone me-1" aria-hidden="true"></i>Teléfono</h5>
                            <p class="card-text">{{ $content['contact.phone'] ?? '+593 98 863 3454' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-envelope me-1" aria-hidden="true"></i>Email</h5>
                            <p class="card-text"><a href="mailto:{{ $adminEmail }}">{{ $adminEmail }}</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-clock me-1" aria-hidden="true"></i>Horario</h5>
                            <p class="card-text">{{ $content['contact.hours'] ?? 'Lun - Vie: 08:00 - 18:00' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-geo-alt me-1" aria-hidden="true"></i>Dirección</h5>
                            <p class="card-text">{{ $content['contact.address'] ?? 'Machala, Ecuador' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">{{ $content['contact.form.title'] ?? 'Envía tu Mensaje' }}</h4>

                    <form method="POST" action="{{ route('contact.send') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre Completo *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Asunto *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-send me-2"></i>Enviar Mensaje
                        </button>
            </div>
        </div>
    </div>
</div>
@endsection
