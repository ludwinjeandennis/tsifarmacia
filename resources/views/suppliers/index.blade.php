@extends('layouts.app')

@section('title', 'Gestión de Proveedores')

@section('header', 'Lista de Proveedores')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item active">Proveedores</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Proveedores Registrados</h3>
                    <div class="card-tools">
                        <a href="{{ route('suppliers.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Nuevo Proveedor
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th width="200px">Acciones</th>
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
@endsection

@section('extra')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
@endsection

@section('scripts')
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('suppliers.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        }
    });
  });
</script>
@endsection
