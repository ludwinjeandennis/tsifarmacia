@extends("layouts.app")

@section("title","Ver Dirección")

@section("style")

@endsection

@section("header","Ver Dirección")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("addresses.index")}}">Direcciones</a></li>
    <li class="breadcrumb-item"><a href="#">ver dirección</a></li>

@endsection

@section("content")

    <div class="card card-primary">
        <div class="card-body">
            <strong>Nombre del Usuario</strong>
            <p class="text-muted">
                {{ $address->user->name }}
            </p>
            <strong>Nombre del Área</strong>
            <p class="text-muted">
                {{ $address->area->name }}
            </p>
            <strong>Nombre de la Calle</strong>
            <p class="text-muted">
                {{ $address->street_name }}
            </p>
            <strong>Número del Edificio</strong>
            <p class="text-muted">
                {{ $address->building_number }}
            </p>
            <hr>
            <strong>Número del Piso</strong>
            <p class="text-muted">
                {{ $address->floor_number }}
            </p>
            <hr>
            <strong>Número del Departamento</strong>
            <p class="text-muted">
                {{ $address->flat_number }}
            </p>
            <hr>
            <strong>¿Es esta la dirección principal del usuario?</strong>
            <p class="text-muted">
                @if($address->is_main == 1)
                    <p class="text-muted">
                        Sí
                    </p>
                @else
                    <p class="text-muted">
                        No
                    </p>
                @endif
            </p>
        </div>
    </div>
@endsection