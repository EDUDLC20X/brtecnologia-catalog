@props(['products' => []])

<div class="container-fluid px-0">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center text-white">
                        <h1 class="display-4 fw-bold mb-3">¡Bienvenido a nuestro Catálogo!</h1>
                        <p class="lead mb-4">Encuentra los mejores productos tecnológicos</p>
                        <a href="{{ route('catalog') }}" class="btn btn-light btn-lg">Explorar Catálogo</a>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center text-white">
                        <h2 class="display-4 fw-bold mb-3">Ofertas Especiales</h2>
                        <p class="lead mb-4">Descuentos de hasta 50% en productos seleccionados</p>
                        <a href="{{ route('catalog', ['discount' => true]) }}" class="btn btn-light btn-lg">Ver Ofertas</a>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); height: 400px; display: flex; align-items: center; justify-content: center;">
                    <div class="text-center text-white">
                        <h2 class="display-4 fw-bold mb-3">Productos Nuevos</h2>
                        <p class="lead mb-4">Descubre las últimas novedades en tecnología</p>
                        <a href="{{ route('catalog', ['sort' => 'newest']) }}" class="btn btn-light btn-lg">Ver Novedades</a>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>
