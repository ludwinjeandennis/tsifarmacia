@extends("layouts.app")

@section("title","agregar farmacia")

@section("style")
<link rel="stylesheet" href="{{asset("plugins/daterangepicker/daterangepicker.css")}}">
@endsection

@section("header","Agregar farmacia")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("pharmacies.index")}}">farmacias</a></li>
    <li class="breadcrumb-item">agregar farmacia</li>

@endsection

@section("content")
    <div class="card card-primary">
                <!-- /.card-header -->
                <!-- Inicio del formulario -->
                <form method="POST" action="{{route("pharmacies.store")}}" class="needs-validation">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputName1">Nombre de la Farmacia</label>
                            <input type="text" name="pharmacy_name" class="form-control @error('pharmacy_name') is-invalid @enderror" id="exampleInputName1" placeholder="Ingrese el nombre de la farmacia" value="{{old("pharmacy_name")}}">
                            @error('pharmacy_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="exampleInputName1">Tu Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="exampleInputName1" placeholder="Ingrese el nombre" value="{{old("name")}}">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Correo electrónico</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="exampleInputEmail1" placeholder="Ingrese el correo electrónico" value="{{old("email")}}">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Contraseña</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" placeholder="Contraseña">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleInputNationalId1">Identificación Nacional</label>
                            <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" id="exampleInputNationalId1" placeholder="Ingrese la identificación nacional" value="{{old("national_id")}}">
                            @error('national_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone1">Teléfono</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="exampleInputPhone1" placeholder="Ingrese el teléfono" value="{{old("phone")}}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone1">Género</label>
                            <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                                <option value="1" {{old("gender") === "1" ? "selected" : ""}}>masculino</option>
                                <option value="2" {{old("gender") === "2" ? "selected" : ""}} >femenino</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Fecha de nacimiento</label>
                            <div class="input-group">
                            <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="date" name="date_of_birth"  class="form-control @error('date_of_birth') is-invalid @enderror" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                            </div>
                            @error('date_of_birth')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="exampleInputAreaId1">Área</label>
                            <select name="area_id" class="form-select @error('area_id') is-invalid @enderror">
                                @foreach($areas as $area)
                                    <option value="{{$area->id}}" {{old("area_id") === $area->id ? "selected" : ""}}>{{$area->name}}</option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPriority1">Prioridad</label>
                            <input type="text" name="priority" class="form-control @error('priority') is-invalid @enderror" id="exampleInputPriority1" placeholder="Ingrese la prioridad" value="{{old("priority")}}">
                            @error('priority')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Imagen de perfil</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="avatar_image" class="custom-file-input @error('avatar_image') is-invalid @enderror" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Seleccionar archivo</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Subir</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-dark w-100">Enviar</button>
                    </div>
                </form>
    </div>
@endsection



@section("script")
//Selector de fecha

    <script>
    $('#reservationdate').datetimepicker({
            format: 'L'
        });
    $('[data-mask]').inputmask()
    </script>

@endsection