@extends("layouts.app")

@section("title","Ingresos")

@section("style")

@endsection

@section("header","Ingresos")

@section("breadcrumb")


    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item">Ingresos</li>

@endsection

@section("content")

    {{ $dataTable->table() }}

@endsection


@section('scripts')
{{ $dataTable->scripts() }}


@endsection