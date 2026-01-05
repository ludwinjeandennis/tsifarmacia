@extends("layouts.app")

@section("title","Agregar Dirección")

@section("style")

@endsection

@section("header","Agregar Dirección")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("addresses.index")}}">Direcciones</a></li>
    <li class="breadcrumb-item"><a href="#">agregar dirección</a></li>

@endsection

@section("content")

    <div class="card card-primary">
        <!-- /.card-header -->
        <!-- Inicio del formulario -->
        <form action="{{route("addresses.store")}}" method="post">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="street-name">Nombre de la calle</label>
                    <input type="text" value="{{old("street_name")}}" class="form-control @error('street_name') is-invalid @enderror" name="street_name" id="street-name"
                           placeholder="Ingrese el nombre de la calle">

                    @error('street_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="building-number">Número del edificio</label>
                    <input type="text" value="{{old("building_number")}}" class="form-control @error('building_number') is-invalid @enderror" name="building_number" id="building-number"
                           placeholder="Ingrese el número del edificio">

                    @error('building_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="floor-number">Número del piso</label>
                    <input type="text" value="{{old("floor_number")}}" class="form-control @error('floor_number') is-invalid @enderror" name="floor_number" id="floor-number"
                           placeholder="Ingrese el número del piso">

                    @error('floor_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="flat_number">Número del departamento</label>
                    <input type="text" value="{{old("flat_number")}}" class="form-control @error('flat_number') is-invalid @enderror" name="flat_number" id="flat-number"
                           placeholder="Ingrese el número del departamento">

                    @error('flat_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="is_main">¿Es esta tu dirección principal?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_main" id="flexRadioDefault1" value="1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Sí
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_main" id="flexRadioDefault2" value="0">
                        <label class="form-check-label" for="flexRadioDefault2">
                            No
                        </label>
                    </div>

                    @error('is_main')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="area" class="form-label">Área</label>

                    <select class="js-example-basic-multiple select2 @error('area') is-invalid @enderror" name="area"  style="width: 100%;" >
                        @foreach($areas as $area)
                            <option value="{{$area->id}}">{{$area->name}}</option>
                        @endforeach
                    </select>

                    @error('area')
                    <span class="invalid-feedback fs-6" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="user" class="form-label">Usuario</label>

                    <select class="js-example-basic-multiple select2 @error('user') is-invalid @enderror" name="user"  style="width: 100%;" >
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>

                    @error('user')
                    <span class="invalid-feedback fs-6" role="alert">
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