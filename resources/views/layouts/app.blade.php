<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'B&R Tecnología')) | B&R Tecnología</title>

        {{-- SEO Meta Tags --}}
        @hasSection('seo')
            @yield('seo')
        @else
            <x-seo-meta />
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Inter font for UI -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Page styles -->
        @yield('styles')
        <!-- Site styles (cards, hero) -->
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
        <!-- Client panel styles -->
        <link rel="stylesheet" href="{{ asset('css/client.css') }}">
        <!-- Accessibility & Legibility improvements -->
        <link rel="stylesheet" href="{{ asset('css/accessibility.css') }}">

        <!-- jQuery for Select2 and plugins (no SRI to avoid integrity mismatch in some environments). -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Fallback: if jQuery didn't load, try an alternative CDN
            if (typeof jQuery === 'undefined') {
                var s = document.createElement('script');
                s.src = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js';
                document.head.appendChild(s);
            }
        </script>
    </head>
    <body class="d-flex flex-column" style="min-height: 100vh;">
        @include('layouts.navigation')

        <main class="flex-grow-1">
            <div class="container my-4">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="mb-4">
                        <div class="py-2">
                            {{ $header }}
                        </div>
                    </header>
                @elseif(View::hasSection('header'))
                    <header class="mb-4">
                        <div class="py-2">
                            @yield('header')
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                @include('layouts.alerts')
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </main>

        @include('layouts.footer')
        
        {{-- Toast global y funciones de cotización --}}
        <script>
        // Mostrar toast de notificación
        function showToast(message, type) {
            type = type || 'success';
            // Remover toast anterior si existe
            var existingToast = document.getElementById('globalToast');
            if (existingToast) existingToast.remove();
            
            var bgClass = type === 'success' ? 'bg-success' : (type === 'error' ? 'bg-danger' : 'bg-warning');
            var icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            
            var toastHtml = '<div id="globalToast" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">' +
                '<div class="toast show ' + bgClass + ' text-white" role="alert">' +
                '<div class="toast-body d-flex align-items-center">' +
                '<i class="bi ' + icon + ' me-2 fs-5"></i>' +
                '<span>' + message + '</span>' +
                '<button type="button" class="btn-close btn-close-white ms-auto" onclick="this.closest(\'.toast-container\').remove()"></button>' +
                '</div></div></div>';
            document.body.insertAdjacentHTML('beforeend', toastHtml);
            
            setTimeout(function() {
                var toast = document.getElementById('globalToast');
                if (toast) toast.remove();
            }, 3000);
        }
        
        // Agregar a cotización via AJAX (global)
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.add-to-quote-btn');
            if (!btn) return;
            
            e.preventDefault();
            var url = btn.getAttribute('data-url');
            if (!url) return;
            
            var originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ quantity: 1 })
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                if (data.success) {
                    showToast(data.message || 'Producto agregado a la cotización', 'success');
                    if (typeof updateQuoteBadge === 'function') updateQuoteBadge();
                } else {
                    showToast(data.message || 'Error al agregar', 'error');
                }
            })
            .catch(function(error) {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showToast('Error de conexión', 'error');
            });
        });
        </script>
        
        @stack('scripts')
    </body>
</html>
