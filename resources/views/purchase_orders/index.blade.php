@extends('layouts.app')

@section('title', 'Órdenes de Compra')

@section('header', 'Gestión de Compras')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Compras</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">Historial de Órdenes de Compra</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" id="createNewPO">
                            <i class="fas fa-plus"></i> Nueva Orden Manual
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Total ($)</th>
                                <th>Fecha Entrega</th>
                                <th width="150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Crear Orden Simple --}}
<div class="modal fade" id="poModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Generar Orden de Compra</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="poForm" name="poForm" class="form-horizontal">
                    <input type="hidden" name="id" id="po_id">
                    
                    <div class="form-group row">
                        <label for="supplier_id" class="col-sm-3 col-form-label">Proveedor</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="supplier_id" name="supplier_id" required>
                                <option value="">Seleccione un Proveedor</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Medicamento (Demo)</label>
                        <div class="col-sm-9">
                             <select class="form-control" id="medicine_id" name="medicines[0][id]" required>
                                <option value="">Seleccione Medicamento</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }} (Stock: {{ $medicine->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Cantidad</label>
                        <div class="col-sm-9">
                            <input type="number" class="form-control" id="qty" name="medicines[0][qty]" placeholder="Cantidad a pedir" min="1" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Notas</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" name="notes" placeholder="Instrucciones especiales..."></textarea>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success float-right" id="saveBtn">
                            <i class="fas fa-paper-plane"></i> Enviar Orden
                        </button>
                    </div>
                </form>
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
<!-- Bootstrap JS (necesario para modales) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">
  $(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('purchase_orders.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'supplier_name', name: 'supplier_name'},
            {data: 'status', name: 'status'},
            {data: 'total_amount', name: 'total_amount'},
            {data: 'expected_delivery_date', name: 'expected_delivery_date'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
    
    $('#createNewPO').click(function () {
        $('#poForm').trigger("reset");
        $('#modelHeading').html("Nueva Orden de Compra");
        $('#poModal').modal('show');
    });

    // Autorelleno desde Predicciones IA
    const urlParams = new URLSearchParams(window.location.search);
    if(urlParams.has('medicine_id')){
        // Pequeño retraso para asegurar que Bootstrap haya registrado el plugin modal en jQuery
        setTimeout(function() {
            $('#createNewPO').click();
            $('#medicine_id').val(urlParams.get('medicine_id'));
            $('#qty').val(urlParams.get('qty'));
            window.history.replaceState({}, document.title, window.location.pathname);
        }, 500);
    }

    // Acción para Aprobar Orden
    $('body').on('click', '.approveOrder', function () {
        var id = $(this).data("id");
        if(confirm("¿Deseas aprobar esta orden de compra?")) {
            $.ajax({
                type: "PUT",
                url: "{{ route('purchase_orders.index') }}/" + id,
                data: { status: 'Approved' },
                success: function (data) {
                    table.draw();
                    alert(data.success);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });

    // Acción para Ver Orden
    $('body').on('click', '.viewOrder', function () {
        var id = $(this).data("id");
        $.get("{{ route('purchase_orders.index') }}/" + id, function (data) {
            alert("Detalles de la Orden:\nProveedor: " + data.supplier.name + "\nEstado: " + data.status + "\nNotas: " + (data.notes || 'N/A'));
        });
    });
    
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Enviando...');
    
        $.ajax({
            data: $('#poForm').serialize(),
            url: "{{ route('purchase_orders.store') }}",
            type: "POST",
            dataType: 'json',
            success: function (data) {
                $('#poForm').trigger("reset");
                $('#poModal').modal('hide');
                table.draw();
                alert(data.success);
                $('#saveBtn').html('<i class="fas fa-paper-plane"></i> Enviar Orden');
            },
            error: function (data) {
                console.log('Error:', data);
                alert('Error al guardar la orden. Verifique los campos.');
                $('#saveBtn').html('<i class="fas fa-paper-plane"></i> Enviar Orden');
            }
        });
    });
  });
</script>
@endsection
