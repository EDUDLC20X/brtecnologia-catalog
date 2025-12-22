@extends('layouts.app')

@section('title', 'Mi Perfil - ' . config('app.name'))

@section('styles')
<style>
    :root {
        --admin-primary: #0f2744;
        --admin-secondary: #1e3a5f;
        --admin-accent: #3b82f6;
        --admin-light: #f8fafc;
        --admin-border: #e2e8f0;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-secondary) 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
    }

    .profile-header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .profile-header p {
        margin: 0.5rem 0 0;
        opacity: 0.85;
        font-size: 0.9rem;
    }

    .profile-card {
        background: white;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .profile-card-header {
        background: var(--admin-light);
        border-bottom: 1px solid var(--admin-border);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .profile-card-header i {
        color: var(--admin-accent);
        font-size: 1.25rem;
    }

    .profile-card-header h2 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--admin-primary);
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: var(--admin-primary);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 2px solid var(--admin-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--admin-accent);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-text {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .btn-primary-profile {
        background: var(--admin-primary);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-primary-profile:hover {
        background: var(--admin-secondary);
        transform: translateY(-1px);
    }

    .btn-danger-profile {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-danger-profile:hover {
        background: #dc2626;
    }

    .alert-success-profile {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-danger-profile {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-info-profile {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .alert-info-profile i {
        font-size: 1.25rem;
        margin-top: 2px;
    }

    .current-email-box {
        background: var(--admin-light);
        border: 1px solid var(--admin-border);
        border-radius: 8px;
        padding: 1rem;
    }

    .current-email-box .form-label {
        margin-bottom: 0.25rem;
        font-size: 0.8rem;
        color: #64748b;
    }

    .email-display {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--admin-primary);
    }

    .pending-change-box {
        background: #fefce8;
        border: 1px solid #fef08a;
        border-radius: 8px;
        padding: 1rem;
    }

    .pending-change-box h6 {
        color: #854d0e;
        font-weight: 600;
    }

    .pending-change-box p {
        color: #713f12;
    }

    .delete-section {
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
        padding: 1.25rem;
    }

    .delete-section h3 {
        color: #991b1b;
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 0.5rem;
    }

    .delete-section p {
        color: #7f1d1d;
        font-size: 0.875rem;
        margin: 0 0 1rem;
    }

    /* Modal */
    .modal-content {
        border-radius: 12px;
        border: none;
    }

    .modal-header {
        background: #fef2f2;
        border-bottom: 1px solid #fecaca;
        padding: 1.25rem;
    }

    .modal-header .modal-title {
        color: #991b1b;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid var(--admin-border);
        padding: 1rem 1.5rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="profile-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white bg-opacity-25 p-3">
                        <i class="bi bi-person-circle" style="font-size: 2rem;"></i>
                    </div>
                    <div>
                        <h1>Mi Perfil</h1>
                        <p>Administra tu información personal y seguridad</p>
                    </div>
                </div>
            </div>

            @if (session('status') === 'profile-updated')
                <div class="alert-success-profile">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Perfil actualizado correctamente.</span>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div class="alert-success-profile">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Contraseña actualizada correctamente.</span>
                </div>
            @endif

            @if (session('status') === 'email-verification-sent')
                <div class="alert-info-profile">
                    <i class="bi bi-envelope-check"></i>
                    <div>
                        <strong>Verificación enviada</strong><br>
                        <span>Hemos enviado un enlace de verificación a <strong>{{ session('pending_email') }}</strong>. Revisa tu bandeja de entrada.</span>
                    </div>
                </div>
            @endif

            @if (session('status') === 'email-changed')
                <div class="alert-success-profile">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>¡Correo electrónico actualizado correctamente!</span>
                </div>
            @endif

            @if (session('status') === 'email-change-cancelled')
                <div class="alert-success-profile">
                    <i class="bi bi-x-circle"></i>
                    <span>Solicitud de cambio de correo cancelada.</span>
                </div>
            @endif

            @if ($errors->has('delete'))
                <div class="alert-danger-profile">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $errors->first('delete') }}</span>
                </div>
            @endif

            <!-- Información del Perfil (Solo Nombre) -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-person"></i>
                    <h2>Información Personal</h2>
                </div>
                <div class="profile-card-body">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <label for="name" class="form-label">Nombre Completo</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary-profile">
                            <i class="bi bi-check-lg"></i>
                            Guardar Nombre
                        </button>
                    </form>
                </div>
            </div>

            <!-- Cambiar Correo Electrónico (con verificación) -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-envelope"></i>
                    <h2>Correo Electrónico</h2>
                </div>
                <div class="profile-card-body">
                    <div class="current-email-box mb-4">
                        <label class="form-label">Correo actual</label>
                        <div class="d-flex align-items-center gap-2">
                            <span class="email-display">{{ $user->email }}</span>
                            @if($user->email_verified_at)
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verificado</span>
                            @endif
                        </div>
                    </div>

                    @if($user->hasPendingEmailChange())
                        <!-- Hay un cambio pendiente -->
                        <div class="pending-change-box">
                            <div class="d-flex align-items-start gap-3">
                                <i class="bi bi-hourglass-split text-warning" style="font-size: 1.5rem;"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Cambio de correo pendiente</h6>
                                    <p class="mb-2">
                                        Has solicitado cambiar tu correo a: <strong>{{ $user->pending_email }}</strong>
                                    </p>
                                    <p class="text-muted small mb-3">
                                        <i class="bi bi-clock me-1"></i>
                                        Solicitado {{ $user->email_change_requested_at->diffForHumans() }}
                                        @if($user->emailChangeTokenExpired())
                                            <span class="text-danger ms-2"><i class="bi bi-exclamation-circle"></i> Expirado</span>
                                        @endif
                                    </p>
                                    <form method="post" action="{{ route('profile.cancel-email-change') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-x-lg me-1"></i>Cancelar solicitud
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Formulario para solicitar cambio -->
                        <form method="post" action="{{ route('profile.request-email-change') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="new_email" class="form-label">Nuevo correo electrónico</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="new_email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="nuevo@correo.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Enviaremos un enlace de verificación al nuevo correo. El cambio se aplicará cuando confirmes.
                                </div>
                            </div>

                            <button type="submit" class="btn-primary-profile">
                                <i class="bi bi-envelope-arrow-up"></i>
                                Solicitar Cambio de Correo
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-shield-lock"></i>
                    <h2>Cambiar Contraseña</h2>
                </div>
                <div class="profile-card-body">
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">
                        Asegúrate de usar una contraseña segura y única para proteger tu cuenta.
                    </p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input type="password" 
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" 
                                   name="password"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input type="password" 
                                   class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                   id="password_confirmation" 
                                   name="password_confirmation"
                                   autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-primary-profile">
                            <i class="bi bi-key"></i>
                            Actualizar Contraseña
                        </button>
                    </form>
                </div>
            </div>

            <!-- Eliminar Cuenta -->
            <div class="profile-card">
                <div class="profile-card-header">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                    <h2 class="text-danger">Zona de Peligro</h2>
                </div>
                <div class="profile-card-body">
                    <div class="delete-section">
                        <h3><i class="bi bi-trash3 me-2"></i>Eliminar Cuenta</h3>
                        <p>
                            Una vez eliminada tu cuenta, todos tus datos serán borrados permanentemente. 
                            Esta acción no se puede deshacer.
                        </p>
                        <button type="button" 
                                class="btn-danger-profile" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash3"></i>
                            Eliminar Mi Cuenta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Volver -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver al Panel de Administración
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar Cuenta -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    ¿Eliminar cuenta permanentemente?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-body">
                    <p class="mb-3">
                        Esta acción es <strong>irreversible</strong>. Se eliminarán todos tus datos del sistema.
                    </p>
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">
                            Ingresa tu contraseña para confirmar:
                        </label>
                        <input type="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" 
                               name="password" 
                               placeholder="Tu contraseña actual"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-danger-profile">
                        <i class="bi bi-trash3"></i>
                        Sí, eliminar mi cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
        modal.show();
    });
</script>
@endif
@endsection
