@extends("layouts.app")

@section('title' , 'Estadísticas Gerenciales')

@section('style')
<style>
    @media print {
        .main-header, .main-sidebar, .card-header button, .btn-print, .breadcrumb, footer {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            background-color: white !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
    .kpi-card {
        transition: transform 0.3s;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section("header","Dashboard Gerencial de Estadísticas")

@section("breadcrumb")
    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item active">Estadísticas</li>
@endsection

@section('content')

<div class="container-fluid">
    
    <!-- Action Bar -->
    <div class="row mb-3">
        <div class="col-12 text-right">
            <button onclick="window.print()" class="btn btn-outline-secondary btn-print">
                <i class="fas fa-print"></i> Imprimir Reporte PDF
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box kpi-card bg-info">
                <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ingresos Totales (Año)</span>
                    <span class="info-box-number" style="font-size: 1.5rem;">$ {{ number_format($totalRevenue, 2) }}</span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box kpi-card bg-success">
                <span class="info-box-icon"><i class="fas fa-shopping-bag"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pedidos Completados</span>
                    <span class="info-box-number" style="font-size: 1.5rem;">{{ $totalOrders }}</span>
                     <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box kpi-card bg-warning">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Cartera de Clientes</span>
                    <span class="info-box-number" style="font-size: 1.5rem;">{{ $totalClients }}</span>
                     <div class="progress">
                        <div class="progress-bar" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Evolución de Ingresos Mensuales</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Demographics -->
        <div class="col-md-4">
            <div class="card card-danger card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-venus-mars"></i> Demografía de Clientes</h3>
                     <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
                <div class="card-footer bg-white p-0">
                    <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        Hombres
                        <span class="float-right text-info font-weight-bold">{{ isset($gender[1]) ? count($gender[1]) : 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                        Mujeres
                        <span class="float-right text-danger font-weight-bold">{{ isset($gender[2]) ? count($gender[2]) : 0 }}</span>
                        </a>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Clients -->
        <div class="col-12">
            <div class="card card-success card-outline">
                <div class="card-header border-0">
                    <h3 class="card-title"><i class="fas fa-crown"></i> Clientes Estrella (Top 10)</h3>
                     <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body">
                     <canvas id="usersChart" style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    // 1. Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [@foreach($revenue as $key => $val) {!! "'$key'," !!} @endforeach],
            datasets: [{
                label: 'Ingresos ($)',
                data: [@foreach($revenue as $key => $val) {{ $val }}, @endforeach],
                fill: true,
                backgroundColor: 'rgba(60,141,188,0.2)',
                borderColor: '#3c8dbc',
                tension: 0.3,
                pointRadius: 4,
                pointBackgroundColor: '#3c8dbc'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value; }
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) { 
                            return 'Ingresos: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // 2. Gender Donut Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Hombres', 'Mujeres'],
            datasets: [{
                data: [{{ isset($gender[1]) ? count($gender[1]) : 0 }}, {{ isset($gender[2]) ? count($gender[2]) : 0 }}],
                backgroundColor: ['#3c8dbc', '#f56954'],
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 3. Users Bar Chart
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: [@foreach($topUsers as $user) {!! "'$user->user_name'," !!} @endforeach],
            datasets: [{
                label: 'Cantidad de Pedidos',
                data: [@foreach($topUsers as $user) {{ $user->count }}, @endforeach],
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderColor: '#28a745',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Horizontal bars
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

});
</script>
@endsection