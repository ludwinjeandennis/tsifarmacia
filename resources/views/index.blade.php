@extends("layouts.app")

@section("title","Inicio")

@section("style")

@endsection

@section("header","Dashboard Gerencial")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="#">Inicio</a></li>

@endsection

@section("content")
    <div class="container-fluid">
        @hasanyrole("admin|pharmacy")
        <!-- Small Boxes (Stat card) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{$new_orders}}</h3>
                        <p>Pedidos Nuevos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{route("orders.index")}}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{$total_orders}}</h3>
                        <p>Total de Pedidos</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-cart"></i>
                    </div>
                    <a href="{{route("orders.index")}}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>$ {{number_format($sumRevenues, 2)}}</h3>
                        <p>Ingresos Totales</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{route("revenues.index")}}" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{$clients??'0'}}</h3>
                        <p>Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="@role('admin') {{route('users.index')}} @else # @endrole" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="far fa-chart-bar"></i>
                            Ingresos Mensuales ({{date('Y')}})
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left col -->
            <div class="col-md-8">
                <!-- TABLE: LATEST ORDERS -->
                <div class="card card-info card-outline">
                    <div class="card-header border-transparent">
                        <h3 class="card-title">Últimos Pedidos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table m-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ciente</th>
                                    <th>Estado</th>
                                    <th>Monto</th>
                                    <th>Fecha</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td><a href="{{ route('orders.index') }}">{{ $order->id }}</a></td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($order->status) {
                                                'Delivered', 'Confirmed' => 'success',
                                                'New', 'Processing' => 'info',
                                                'Waiting' => 'warning',
                                                'Cancelled' => 'danger',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge badge-{{ $badgeClass }}">{{ $order->status }}</span>
                                    </td>
                                    <td>${{ number_format($order->totalPrice(), 2) }}</td>
                                    <td>{{ $order->created_at->format('d/M/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay pedidos recientes.</td>
                                </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary float-right">Ver Todos los Pedidos</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->

            <div class="col-md-4">
                <!-- PRODUCT LIST -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Top 5 Medicamentos</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <ul class="products-list product-list-in-card pl-2 pr-2">
                            @forelse($topMedicines as $med)
                            <li class="item">
                                <div class="product-info">
                                    <a href="javascript:void(0)" class="product-title">{{ $med->name }}
                                        <span class="badge badge-warning float-right">{{ $med->total_qty }} Vendidos</span>
                                    </a>
                                </div>
                            </li>
                            @empty
                            <li class="item">
                                <div class="product-info ml-2">No hay datos disponibles.</div>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer text-center">
                        <a href="{{ route('medicines.index') }}" class="uppercase">Ver Todos los Medicamentos</a>
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        @endhasanyrole

        @unlessrole('admin|pharmacy')
        <div class="d-flex justify-content-center align-items-center" style="height: 60vh;">
            <div class="text-center">
                <img class="animation__shake img-fluid w-50" src="{{asset("dist/img/catch.png")}}">
                <h2 class="mt-4">Bienvenido al Sistema de Farmacia</h2>
            </div>
        </div>
        @endunlessrole

    </div>
@endsection

@section("scripts")
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Revenue Chart
        var revenueChartCanvas = document.getElementById('revenueChart');
        if (revenueChartCanvas) {
            var ctx = revenueChartCanvas.getContext('2d');
            var revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    datasets: [{
                        label: 'Ingresos Mensuales',
                        backgroundColor: 'rgba(60,141,188,0.2)',
                        borderColor: 'rgba(60,141,188,1)',
                        pointRadius: 4,
                        pointBackgroundColor: '#3b8bba',
                        pointBorderColor: 'rgba(60,141,188,1)',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(60,141,188,1)',
                        data: @json($monthlyRevenue ?? []),
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            }
                        },
                        y: {
                            grid: {
                                borderDash: [2],
                                drawBorder: false,
                            },
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection