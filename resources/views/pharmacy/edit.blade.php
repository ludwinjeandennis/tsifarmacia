@extends("layouts.app")

@section("title","editar farmacia")

@section("style")

@endsection

@section("header","Editar farmacia")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("pharmacies.index")}}">farmacias</a></li>
    <li class="breadcrumb-item"><a href="#">editar farmacia</a></li>

@endsection

@section("content")
    <div class="card card-primary">
    <!-- /.card-header -->
    <!-- Inicio del formulario -->
    <form method="POST" action="{{route("pharmacies.update",$pharmacy->id)}}" class="needs-validation">
        @csrf
        @method("PUT")
        <div class="card-body">
            <div class="form-group">
                <label for="exampleInputName1">Nombre</label>
                <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Ingrese el nombre" value="{{old("name")??$pharmacy->name}}">
                @error('name')
                    <div class="invalid-feedback" @style(["display: block"])>
                        {{ $message }}
                    </div>
                @enderror

            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Correo electrónico</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Ingrese el correo electrónico" value="{{old("email")??$pharmacy->owner->email}}">
                @error('email')
                    <div class="invalid-feedback" @style(["display: block"])>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputPhone1">Teléfono</label>
                <input type="text" name="phone" class="form-control" id="exampleInputPhone1" placeholder="Ingrese el teléfono" value="{{old("phone")??$pharmacy->owner->phone}}">
                @error('phone')
                    <div class="invalid-feedback" @style(["display: block"])>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @role("admin")
            <div class="form-group">
                <label for="exampleInputAreaId1">Área</label>
                <select name="area_id" class="form-select">
                    @foreach($areas as $area)
                        <option value="{{$area->id}}">{{$area->name}}</option>
                    @endforeach
                </select>
                @error('area_id')
                    <div class="invalid-feedback" @style(["display: block"])>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputPriority1">Prioridad</label>
                <input type="text" name="priority" class="form-control" id="exampleInputPriority1" placeholder="Ingrese la prioridad" value="{{old("priority")??$pharmacy->priority}}">
                @error('priority')
                    <div class="invalid-feedback" @style(["display: block"])>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @endrole
            <div class="form-group">
                <label for="exampleInputFile">Imagen de perfil</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" name="avatar_image" class="custom-file-input" id="exampleInputFile">
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