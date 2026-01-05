@extends("layouts.app")

@section("title","Direcciones")

@section("style")

@endsection

@section("header","Direcciones")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item">direcciones</li>

@endsection

@section("content")

    {{ $dataTable->table() }}

@endsection
@section("extra")
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Dirección</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar esta dirección?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" id="modalNo">no</button>
                    <button type="button" class="btn btn-danger">sí</button>
                </div>
            </div>
        </div>
    </div>

@endsection



@section('scripts')
    {{ $dataTable->scripts() }}

@endsection