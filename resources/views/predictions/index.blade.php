@extends("layouts.app")

@section("title", "Predicciones de Inventario")

@section("style")
<style>
    .trend-up { color: #28a745; font-weight: bold; }
    .trend-down { color: #dc3545; font-weight: bold; }
    .trend-stable { color: #6c757d; font-weight: bold; }
    .prediction-card { transition: all 0.3s; }
    .prediction-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endsection

@section("header", "Predicción de Demanda (IA)")

@section("breadcrumb")
    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item active">Predicciones</li>
@endsection

@section("content")
<div class="container-fluid">
    
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-brain"></i> Modelo Predictivo Adaptativo con IA</h5>
                El sistema utiliza <strong>Exponential Smoothing (Holt-Winters)</strong> con:
                <ul class="mb-0 mt-2">
                    <li><strong>Detección Automática de Estacionalidad</strong>: Identifica patrones repetitivos</li>
                    <li><strong>Detección de Anomalías</strong>: Alerta sobre picos o caídas inusuales usando Z-Score</li>
                    <li><strong>Validación Continua</strong>: Métricas RMSE y MAPE para medir precisión</li>
                    <li><strong>Intervalos de Confianza</strong>: Rango de predicción al 95%</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Top Mover -->
    @if(count($predictions) > 0)
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-gradient-success">
            <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Mayor Demanda Esperada</span>
                <span class="info-box-number">{{ $predictions[0]['medicine']->name }}</span>
                <span class="progress-description">
                Predicción: {{ $predictions[0]['predicted_qty'] }} unidades
                </span>
            </div>
            </div>
        </div>
        <!-- /.col -->
    </div>
    @endif

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">Análisis de Demanda por Medicamento</h3>
            <div class="card-tools">
                <a href="{{ route('model.metrics') }}" class="btn btn-tool btn-sm bg-purple">
                    <i class="fas fa-chart-line"></i> Ver Métricas del Modelo
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="predictionsTable" class="table table-bordered table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Medicamento</th>
                            <th>Tendencia</th>
                            <th>Stock Actual</th>
                            <th>Predicción (IC 95%)</th>
                            <th>Déficit</th>
                            <th>Precisión (MAPE)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($predictions as $p)
                        <tr>
                            <td class="text-left">
                                <strong>{{ $p['medicine']->name }}</strong><br>
                                <small class="text-muted">{{ $p['medicine']->type }}</small>
                                
                                @if($p['has_seasonality'])
                                    <br><span class="badge badge-info badge-sm"><i class="fas fa-calendar-alt"></i> Estacional</span>
                                @endif
                                
                                @if(count($p['anomalies_detected']) > 0)
                                    <br><span class="badge badge-warning badge-sm"><i class="fas fa-exclamation-triangle"></i> {{ count($p['anomalies_detected']) }} Anomalías</span>
                                @endif
                            </td>
                            <td>
                                @if($p['trend'] == 'En Aumento')
                                    <span class="trend-up"><i class="fas fa-arrow-trend-up"></i> Aumento</span>
                                @elseif($p['trend'] == 'En Descenso')
                                    <span class="trend-down"><i class="fas fa-arrow-trend-down"></i> Descenso</span>
                                @else
                                    <span class="trend-stable"><i class="fas fa-minus"></i> Estable</span>
                                @endif
                                <br>
                                <small class="text-muted">Coef: {{ $p['coefficient'] }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $p['current_stock'] <= 5 ? 'danger' : 'secondary' }}" style="font-size: 1rem;">
                                    {{ $p['current_stock'] }}
                                </span>
                            </td>
                            <td class="bg-light">
                                <h4 class="mb-0 font-weight-bold text-primary">{{ $p['predicted_qty'] }}</h4>
                                @if(is_array($p['confidence_interval']) && isset($p['confidence_interval']['lower']))
                                    <small class="text-muted">
                                        IC: [{{ $p['confidence_interval']['lower'] }} - {{ $p['confidence_interval']['upper'] }}]
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($p['qty_to_buy'] > 0)
                                    <h5 class="text-danger font-weight-bold">+{{ $p['qty_to_buy'] }}</h5>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeColor = 'secondary';
                                    if($p['accuracy'] == 'Muy Alta') $badgeColor = 'success';
                                    elseif($p['accuracy'] == 'Alta') $badgeColor = 'info';
                                    elseif($p['accuracy'] == 'Media') $badgeColor = 'warning';
                                    elseif($p['accuracy'] == 'Baja') $badgeColor = 'danger';
                                @endphp
                                <span class="badge badge-{{ $badgeColor }}">{{ $p['accuracy'] }}</span>
                                @if($p['mape'] !== null)
                                    <br><small class="text-muted">MAPE: {{ $p['mape'] }}%</small>
                                @endif
                                @if($p['rmse'] !== null)
                                    <br><small class="text-muted">RMSE: {{ $p['rmse'] }}</small>
                                @endif
                            </td>
                            <td>
                                @if($p['action_type'] == 'Crítico' || $p['action_type'] == 'Comprar')
                                    <button class="btn btn-{{ $p['action_type'] == 'Crítico' ? 'danger' : 'warning' }} btn-sm create-order-btn" 
                                            data-id="{{ $p['medicine']->id }}" 
                                            data-name="{{ $p['medicine']->name }}" 
                                            data-qty="{{ $p['qty_to_buy'] }}">
                                        <i class="fas fa-cart-plus"></i> Generar Orden
                                    </button>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Stock Saludable</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
@endsection

@section("scripts")
<!-- DataTables & Plugins -->
<script>
  $(function () {
    $("#predictionsTable").DataTable({
      "responsive": true, 
      "lengthChange": false, 
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"],
      "order": [[ 4, "desc" ]] 
    }).buttons().container().appendTo('#predictionsTable_wrapper .col-md-6:eq(0)');

    // Handle Create Order Click
    $(document).on('click', '.create-order-btn', function() {
        var id = $(this).data('id');
        var qty = $(this).data('qty');
        // Redirect to PO creation with query params
        window.location.href = "{{ route('purchase_orders.index') }}?medicine_id=" + id + "&qty=" + qty;
    });
  });
</script>
@endsection
