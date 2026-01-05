@extends("layouts.app")





@section("content")

    <!-- Contenido principal -->
    <section class="content">
        <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> ¡Ups! Página no encontrada.</h3>

                <p>
                    No pudimos encontrar la página que estabas buscando.
                    Mientras tanto, puedes <a href="{{route("index")}}">volver a la página de inicio</a>.
                </p>

            </div>
            <!-- /.error-content -->
        </div>
        <!-- /.error-page -->
    </section>


@endsection