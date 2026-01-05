@extends('layouts.app')

@section('title', 'Métricas del Modelo ML')

@section('header', 'Dashboard de Rendimiento del Modelo Predictivo')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('predictions.index') }}">Predicciones</a></li>
    <li class="breadcrumb-item active">Métricas del Modelo</li>
@endsection

@section('content')
<div class="container-fluid">
    
    <!-- KPIs del Modelo -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $metrics['avg_mape'] }}%</h3>
                    <p>MAPE Promedio</p>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $metrics['high_accuracy_percent'] }}%</h3>
                    <p>Predicciones de Alta Precisión</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $metrics['avg_rmse'] }}</h3>
                    <p>RMSE Promedio (unidades)</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $metrics['valid_predictions'] }}</h3>
                    <p>Predicciones Validadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-database"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos Principales -->
    <div class="row">
        <!-- Distribución de Precisión -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Distribución de Precisión del Modelo</h3>
                </div>
                <div class="card-body">
                    <canvas id="accuracyChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
                <div class="card-footer bg-white">
                    <div class="row">
                        <div class="col-sm-6 border-right">
                            <div class="description-block">
                                <h5 class="description-header text-success">{{ $metrics['high_accuracy_count'] }}</h5>
                                <span class="description-text">ALTA PRECISIÓN</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block">
                                <h5 class="description-header text-danger">{{ $metrics['low_accuracy_count'] }}</h5>
                                <span class="description-text">BAJA PRECISIÓN</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribución de Tendencias -->
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-bar"></i> Tendencias Detectadas</h3>
                </div>
                <div class="card-body">
                    <canvas id="trendChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Estacionalidad y Anomalías -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Detección de Estacionalidad</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="info-box bg-gradient-info">
                                <span class="info-box-icon"><i class="fas fa-snowflake"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Productos Estacionales</span>
                                    <span class="info-box-number">{{ $seasonalityStats['seasonal_count'] }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $seasonalityStats['seasonal_percent'] }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $seasonalityStats['seasonal_percent'] }}% del total</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="info-box bg-gradient-secondary">
                                <span class="info-box-icon"><i class="fas fa-equals"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Productos No Estacionales</span>
                                    <span class="info-box-number">{{ $seasonalityStats['non_seasonal_count'] }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ 100 - $seasonalityStats['seasonal_percent'] }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ 100 - $seasonalityStats['seasonal_percent'] }}% del total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Detección de Anomalías</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 text-center">
                            <div class="info-box bg-gradient-warning">
                                <span class="info-box-icon"><i class="fas fa-bolt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total de Anomalías</span>
                                    <span class="info-box-number">{{ $anomalyStats['total_anomalies'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 text-center">
                            <div class="info-box bg-gradient-orange">
                                <span class="info-box-icon"><i class="fas fa-pills"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Productos con Anomalías</span>
                                    <span class="info-box-number">{{ $anomalyStats['medicines_with_anomalies'] }}</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $anomalyStats['anomaly_percent'] }}%"></div>
                                    </div>
                                    <span class="progress-description">{{ $anomalyStats['anomaly_percent'] }}% del total</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top y Peor Predicciones -->
    <div class="row">
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Top 10 - Mejores Predicciones</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>MAPE</th>
                                <th>RMSE</th>
                                <th>Precisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topAccurate as $p)
                            <tr>
                                <td>{{ $p['medicine']->name }}</td>
                                <td><span class="badge badge-success">{{ $p['mape'] }}%</span></td>
                                <td>{{ $p['rmse'] }}</td>
                                <td><span class="badge badge-success">{{ $p['accuracy'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Top 10 - Predicciones a Mejorar</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Medicamento</th>
                                <th>MAPE</th>
                                <th>RMSE</th>
                                <th>Precisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leastAccurate as $p)
                            <tr>
                                <td>{{ $p['medicine']->name }}</td>
                                <td><span class="badge badge-danger">{{ $p['mape'] }}%</span></td>
                                <td>{{ $p['rmse'] }}</td>
                                <td><span class="badge badge-warning">{{ $p['accuracy'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Modelo -->
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Información del Modelo</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Algoritmo</dt>
                        <dd class="col-sm-9">Exponential Smoothing (Holt-Winters)</dd>
                        
                        <dt class="col-sm-3">Parámetros</dt>
                        <dd class="col-sm-9">α=0.3 (Nivel), β=0.1 (Tendencia), γ=0.2 (Estacionalidad)</dd>
                        
                        <dt class="col-sm-3">Horizonte Temporal</dt>
                        <dd class="col-sm-9">12 meses de datos históricos</dd>
                        
                        <dt class="col-sm-3">Validación</dt>
                        <dd class="col-sm-9">80% Entrenamiento / 20% Validación</dd>
                        
                        <dt class="col-sm-3">Intervalo de Confianza</dt>
                        <dd class="col-sm-9">95% (±1.96 desviaciones estándar)</dd>
                        
                        <dt class="col-sm-3">Rango Promedio de Confianza</dt>
                        <dd class="col-sm-9">±{{ $metrics['avg_confidence_range'] }} unidades</dd>
                        
                        <dt class="col-sm-3">Última Actualización</dt>
                        <dd class="col-sm-9">{{ now()->format('d/m/Y H:i:s') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Gráfico de Distribución de Precisión
    const accuracyCtx = document.getElementById('accuracyChart').getContext('2d');
    new Chart(accuracyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Muy Alta', 'Alta', 'Media', 'Baja', 'N/A'],
            datasets: [{
                data: [
                    {{ $accuracyDistribution['Muy Alta'] }},
                    {{ $accuracyDistribution['Alta'] }},
                    {{ $accuracyDistribution['Media'] }},
                    {{ $accuracyDistribution['Baja'] }},
                    {{ $accuracyDistribution['N/A'] }}
                ],
                backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6c757d']
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

    // Gráfico de Tendencias
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: ['En Aumento', 'Estable', 'En Descenso', 'Desconocida'],
            datasets: [{
                label: 'Cantidad de Productos',
                data: [
                    {{ $trendDistribution['En Aumento'] }},
                    {{ $trendDistribution['Estable'] }},
                    {{ $trendDistribution['En Descenso'] }},
                    {{ $trendDistribution['Desconocida'] }}
                ],
                backgroundColor: ['#28a745', '#6c757d', '#dc3545', '#ffc107']
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

});
</script>
@endsection
