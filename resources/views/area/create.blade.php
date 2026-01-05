@extends("layouts.app")

@section("title","Agregar Área")

@section("style")

@endsection

@section("header","Agregar Área")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("areas.index")}}">áreas</a></li>
    <li class="breadcrumb-item">agregar área</li>

@endsection

@section("content")

<div class="card card-primary">
    <!-- /.card-header -->
    <!-- Inicio del formulario -->
    <form action="{{route('areas.store')}}" method="post">
        <div class="card-body">
            @csrf

            <label for="user-name">PAÍS</label>
            <select name="country_id" class="form-control mb-2 select2" id="country">
                @foreach($countries as $country)
                <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
            </select>

            <div class="form-group">
                <label for="user-name">Nombre del Área</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="areaname" placeholder="Ingrese el nombre del área">

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror

            </div>
            <div class="form-group">
                <label for="address">Dirección</label>
                <input type="text" value="" class="form-control   @error('name') is-invalid @enderror" name="address" id="address" placeholder="Ingrese la dirección">

                @error('address')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror


            </div>

        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            <button type="submit" class="btn btn-dark w-100">Enviar</button>
        </div>
    </form>
</div>

@endsection