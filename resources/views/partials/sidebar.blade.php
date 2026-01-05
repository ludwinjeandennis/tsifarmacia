<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Logo de la Marca -->
    <a href="{{route("index")}}" class="brand-link">
        <img src="{{asset("dist/img/catch.png")}}" alt="Logo AdminLTE" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight-light">TSI</span>
    </a>
    <!-- Barra Lateral -->
    <div class="sidebar">
        <!-- Panel de Usuario (opcional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset("storage/avatars/" . auth()->user()->avatar_image)}}" class="img-circle elevation-2" alt="Imagen de Usuario">
            </div>
            <div class="info">
                <a href="{{route("profiles.edit",auth()->user()->id)}}" class="d-block">{{auth()->user()->name}}</a>
            </div>
        </div>

        <!-- Formulario de Búsqueda -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Buscar">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú Lateral -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{route("index")}}" class="nav-link {{ Route::currentRouteNamed('index') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- CATEGORÍA: OPERATIVO -->
                <li class="nav-header">OPERACIONES</li>

                <li class="nav-item {{ Route::currentRouteNamed('orders.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('orders.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Ventas y Pedidos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('orders.create')}}" class="nav-link {{Route::is('orders.create') ? 'active' : '' }}">
                                <i class="fas fa-cash-register nav-icon"></i>
                                <p>Nueva Venta (POS)</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('orders.index')}}" class="nav-link {{Route::is('orders.index') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>Historial de Pedidos</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Route::currentRouteNamed('medicines.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('medicines.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>
                            Medicamentos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route("medicines.index")}}" class="nav-link {{Route::is('medicines.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Catálogo</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("medicines.create")}}" class="nav-link {{Route::is('medicines.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Producto</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- CATEGORÍA: GESTIÓN -->
                <li class="nav-header">ADMINISTRACIÓN</li>

                @hasanyrole("pharmacy|admin")
                <li class="nav-item">
                    <a href="{{route('suppliers.index')}}" class="nav-link {{ Route::currentRouteNamed('suppliers.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Proveedores</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('purchase_orders.index')}}" class="nav-link {{ Route::currentRouteNamed('purchase_orders.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Órdenes de Compra</p>
                    </a>
                </li>
                @endhasanyrole

                @hasanyrole("pharmacy|admin")
                <li class="nav-item {{ Route::currentRouteNamed('doctors.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('doctors.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-md"></i>
                        <p>
                            Doctores
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route("doctors.index")}}" class="nav-link {{Route::is('doctors.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Listado</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("doctors.create")}}" class="nav-link {{Route::is('doctors.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar Doctor</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endhasanyrole

                @role('admin')
                <li class="nav-item {{ Route::currentRouteNamed('users.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('users.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Usuarios
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route("users.index")}}" class="nav-link {{ Route::currentRouteNamed('users.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Clientes</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("users.create")}}" class="nav-link {{ Route::currentRouteNamed('users.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nuevo Cliente</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Route::currentRouteNamed('pharmacies.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('pharmacies.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-building"></i>
                        <p>
                            Farmacias
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route("pharmacies.index")}}" class="nav-link {{Route::is('pharmacies.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sucursales</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route("pharmacies.create")}}" class="nav-link {{Route::is('pharmacies.create') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nueva Surcursal</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ Route::currentRouteNamed('areas.*') || Route::currentRouteNamed('addresses.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ Route::currentRouteNamed('areas.*') || Route::currentRouteNamed('addresses.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-map-marked-alt"></i>
                        <p>
                            Ubicaciones
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route("areas.index")}}" class="nav-link {{ Route::currentRouteNamed('areas.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Áreas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('addresses.index')}}" class="nav-link {{ Route::currentRouteNamed('addresses.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Direcciones</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                <!-- CATEGORÍA: ANALÍTICA -->
                <li class="nav-header">INTELIGENCIA DE NEGOCIO</li>

                <li class="nav-item">
                    <a href="{{route('predictions.index')}}" class="nav-link {{ Route::currentRouteNamed('predictions.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-brain text-info"></i>
                        <p>Predicción de IA</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('model.metrics')}}" class="nav-link {{ Route::currentRouteNamed('model.metrics') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-purple"></i>
                        <p>Métricas del Modelo</p>
                    </a>
                </li>

                @hasanyrole("pharmacy|admin")
                <li class="nav-item">
                    <a href="{{route("revenues.index")}}" class="nav-link {{Route::is('revenues.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line text-success"></i>
                        <p>Reporte de Ingresos</p>
                    </a>
                </li>
                @endhasanyrole

                @role("admin")
                <li class="nav-item">
                    <a href="{{route('statistics.index')}}" class="nav-link {{Route::is('statistics.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-pie text-warning"></i>
                        <p>Estadísticas Globales</p>
                    </a>
                </li>
                @endrole

                <li class="nav-header">SISTEMA</li>
                
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>Cerrar Sesión</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>