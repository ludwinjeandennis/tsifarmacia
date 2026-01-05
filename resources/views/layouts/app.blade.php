<!DOCTYPE html>
<!--
Esta es una página de plantilla inicial. Usa esta página para comenzar tu nuevo proyecto desde
cero. Esta página elimina todos los enlaces y proporciona solo el marcado necesario.
-->
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>

    @vite('resources/sass/app.scss')
    @vite('resources/sass/adminlte.scss')



@yield("style")

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">
    <div class="preloader flex-column justify-content-center align-items-center" style="height: 100vh; width:100%; background-color: white;">
        <img class="animation__shake" src="{{asset("dist/img/catch.png")}}" alt="Logo AdminLTE" style=" z-index:5;" width="80" height="80">
    </div>
    <script>
        document.onreadystatechange = () => {
            if (document.readyState === "complete") {
                setTimeout(()=>{
                    document.querySelector(".preloader").style.display="none";
                },2000)
            }
        };
    </script>
    <!-- Barra de navegación -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Enlaces de la barra de navegación izquierda -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{route("index")}}" class="nav-link">Inicio</a>
            </li>
        </ul>

    </nav>
    <!-- /.navbar -->

    <!-- Contenedor principal de la barra lateral -->
    @include("partials.sidebar")
    <!-- Contenedor del contenido. Contiene el contenido de la página -->
    <div class="content-wrapper">
        <!-- Encabezado del contenido (encabezado de página) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield("header")</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield("breadcrumb")
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Contenido principal -->
        <div class="content pb-3">
            <div class="container-fluid ">
                @yield("content")
             </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Barra lateral de control -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- El contenido de la barra lateral de control va aquí -->
        <div class="p-3">
            <h5>Título</h5>
            <p>Contenido de la barra lateral</p>
        </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Pie de página principal -->
    <footer class="main-footer">
        <!-- A la derecha -->
        <div class="float-right d-none d-sm-inline">
          <strong>TSI</strong>
        </div>
        <!-- Por defecto a la izquierda -->
        <strong>Copyright &copy; 2025- {{ now()->format('Y') }} <a href="{{route("index")}}">TSI</a>.</strong> Todos los derechos reservados.
    </footer>
</div>
<!-- ./wrapper -->

<!-- SCRIPTS REQUERIDOS -->
@vite('resources/js/app.js')

@yield("extra")
@yield('scripts')
@include("partials._toast")
</body>
</html>