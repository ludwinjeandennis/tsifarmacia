@extends("layouts.app")

@section("title","Editar perfil")

@section("style")

@endsection

@section("header","Editar perfil")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="#">agregar usuario</a></li>

@endsection

@section("content")

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Editar perfil</h3>
        </div>
        <!-- /.card-header -->
        <!-- Inicio del formulario -->
        <form action="{{route("profiles.update",auth()->user()->id)}}" method="post" enctype="multipart/form-data">
            <div class="card-body">
                @csrf
                @method("put")
                <div class="form-group">
                    <label for="user-name">Nombre de usuario</label>
                    <input type="text" value="{{auth()->user()->name}}" class="form-control @error('name') is-invalid @enderror"
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
                    <input type="email" value="{{auth()->user()->email}}"
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
                    <input type="text" value="{{auth()->user()->national_id}}"
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
                        <option value="1" {{auth()->user()->gender === "1" ? "selected" : ""}}>masculino</option>
                        <option value="2" {{auth()->user()->gender === "2" ? "selected" : ""}} >femenino</option>
                    </select>

                    @error('gender')
                    <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" value="{{auth()->user()->phone}}"
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
                    <input type="date" value="{{auth()->user()->date_of_birth}}"
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
                    <label for="avatar-image">Archivo de imagen</label>
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
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>

        </form>
    </div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // Actualizar el nombre del archivo en el input al seleccionar uno
    $('#avatar-image').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endsection