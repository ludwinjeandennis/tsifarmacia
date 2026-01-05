@extends("layouts.app")

@section("title","Editar usuario")

@section("style")

@endsection

@section("header","Editar usuario")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("users.index")}}">usuarios</a></li>
    <li class="breadcrumb-item">editar usuario</li>

@endsection

@section("content")

    <div class="card card-primary">
        <!-- /.card-header -->
        <!-- Inicio del formulario -->
        <form action="{{route("users.update",$user->id)}}" method="post" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                @method("put")
                <div class="form-group">
                    <label for="user-name">Nombre de usuario</label>
                    <input type="text" value="{{$user->name}}" class="form-control @error('name') is-invalid @enderror"
                           name="name" id="user-name"
                           placeholder="Ingrese el nombre de usuario">

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" value="{{$user->email}}"
                           class="form-control @error('email') is-invalid @enderror" name="email" id="email"
                           placeholder="Ingrese el correo electrónico">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="national-id">Identificación nacional</label>
                    <input type="text" value="{{$user->national_id}}"
                           class="form-control @error('national_id') is-invalid @enderror" name="national_id"
                           id="national-id"
                           placeholder="Ingrese la identificación nacional">

                    @error('national_id')
                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                           id="password"
                           placeholder="Contraseña">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="gender">Género</label>
                    <select class="form-control @error('gender') is-invalid @enderror" name="gender" id="gender">
                        <option value="1" {{$user->gender === "1" ? "selected" : ""}}>masculino</option>
                        <option value="2" {{$user->gender === "2" ? "selected" : ""}} >femenino</option>
                    </select>

                    @error('gender')
                    <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" value="{{$user->phone}}"
                           class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"
                           placeholder="Ingrese el teléfono">

                    @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>

                <div class="form-group">
                    <label for="date-of-birth">Fecha de nacimiento</label>
                    <input type="date" value="{{$user->date_of_birth}}"
                           class="form-control @error('date_of_birth') is-invalid @enderror" name="date_of_birth"
                           id="phone"
                           placeholder="Ingrese la fecha">
                    @error('date_of_birth')
                    <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="avatar-image">Imagen de perfil</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" name="avatar_image"
                                   class="custom-file-input @error('avatar_image') is-invalid @enderror"
                                   id="avatar-image">
                            <label class="custom-file-label" for="avatar-image">Seleccionar archivo</label>

                        </div>


                        <div class="input-group-append">
                            <span class="input-group-text">Subir</span>
                        </div>


                    </div>
                    @error('avatar_image')
                    <span class="invalid-feedback d-block" role="alert">
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