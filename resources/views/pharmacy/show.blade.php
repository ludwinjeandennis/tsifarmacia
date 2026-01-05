@extends("layouts.app")

@section("title","Farmacia")

@section("style")

@endsection

@section("header","Farmacia")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("pharmacies.index")}}">farmacias</a></li>
    <li class="breadcrumb-item">ver farmacia</li>

@endsection

@section("content")

    <div class="card card-primary">
<div class="card-body">
<strong><i class="fas fa-book mr-1"></i> Nombre de la Farmacia</strong>
<p class="text-muted">
{{$pharmacy->name}}
</p>
<strong><i class="fas fa-solid fa-user"></i> Nombre del Propietario</strong>
<p class="text-muted">
{{$pharmacy->owner->name}}
</p>
<strong><i class="fas fa-solid fa-envelope"></i> Correo Electrónico</strong>
<p class="text-muted">
{{$pharmacy->owner->email}}
</p>
<strong><i class="fas fa-phone mr-1"></i> Teléfono</strong>
<p class="text-muted">
{{$pharmacy->owner->phone}}
</p>
<hr>
<strong><i class="fas fa-map-marker-alt mr-1"></i> Nombre del Área</strong>
<p class="text-muted">{{$pharmacy->area->name}}</p>
<hr>
<strong><i class="fas fa-solid fa-id-card"></i> Identificación Nacional</strong>
<p class="text-muted">
{{$pharmacy->owner->national_id}}

</p>
<hr>
    @hasanyrole("admin|pharmacy")
    <a href="{{route("pharmacies.edit",$pharmacy->id)}}" class="btn btn-dark w-100">Editar Farmacia</a>
    @endhasanyrole
</div>

    </div>



@endsection