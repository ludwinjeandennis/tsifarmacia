@extends("layouts.app")

@section("title","Editar Dirección")

@section("style")

@endsection

@section("header","Editar Dirección")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("addresses.index")}}">Direcciones</a></li>
    <li class="breadcrumb-item"><a href="#">editar dirección</a></li>

@endsection

@section("content")

    <div class="card card-primary">
        <!-- /.card-header -->
        <!-- Inicio del formulario -->
        <form action="{{route("addresses.update",$address->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="street-name">Nombre de la calle</label>
                    <input type="text" value="{{$address->street_name}}" class="form-control @error('street_name') is-invalid @enderror" name="street_name" id="street-name"
                           placeholder="Ingrese el nombre de la calle">

                    @error('street_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="building-number">Número del edificio</label>
                    <input type="text" value="{{$address->building_number}}" class="form-control @error('building_number') is-invalid @enderror" name="building_number" id="building-number"
                           placeholder="Ingrese el número del edificio">

                    @error('building_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="floor-number">Número del piso</label>
                    <input type="text" value="{{$address->floor_number}}" class="form-control @error('floor_number') is-invalid @enderror" name="floor_number" id="floor-number"
                           placeholder="Ingrese el número del piso">

                    @error('floor_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="flat_number">Número del departamento</label>
                    <input type="text" value="{{$address->flat_number}}" class="form-control @error('flat_number') is-invalid @enderror" name="flat_number" id="flat-number"
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
                        <input class="form-check-input @error('is_main') is-invalid @enderror" type="radio" name="is_main" id="flexRadioDefault1"
                               value="1" {{$address->is_main === "Yes" ? 'checked="checked"' : '' }}>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Sí
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input @error('is_main') is-invalid @enderror" type="radio" name="is_main" id="flexRadioDefault2"
                               value="0" {{$address->is_main === "No" ? 'checked="checked"' : '' }}>
                        <label class="form-check-label" for="flexRadioDefault2">
                            No
                        </label>
                    </div>

                    @error('is_main')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="area" class="form-label">Área</label>

                    <select class="js-example-basic-multiple select2 @error('area') is-invalid @enderror" name="area"  style="width: 100%;" >
                        @foreach($areas as $area)
                            <option value="{{$area->id}}" {{ $area->id === $address->area_id ? 'selected' : '' }}>{{$area->name}}</option>
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
                            <option value="{{$user->id}}" {{ $address->user_id === $user->id ? 'selected' : '' }}>{{$user->name}}</option>
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
                <button type="submit" class="btn btn-dark w-100">Actualizar</button>
            </div>
        </form>
    </div>

@endsection