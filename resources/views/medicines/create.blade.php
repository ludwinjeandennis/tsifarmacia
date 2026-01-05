@extends("layouts.app")

@section("title","agregar medicamento")

@section("style")
<link rel="stylesheet" href="{{asset("plugins/daterangepicker/daterangepicker.css")}}">
@endsection

@section("header","Agregar medicamento")

@section("breadcrumb")

    <li class="breadcrumb-item"><a href="{{route("index")}}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{route("medicines.index")}}">medicamentos</a></li>
    <li class="breadcrumb-item">agregar medicamento</li>

@endsection

@section("content")

            <div class="card card-primary">
                <!-- /.card-header -->
                <!-- Inicio del formulario -->
                <form method="POST" action="{{route("medicines.store")}}" class="needs-validation">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputName1">Nombre del medicamento</label>
                            <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Ingrese el nombre del medicamento" value="{{old("name")}}">
                            @error('name')
                                <div class="invalid-feedback" @style(["display: block"])>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select class="js-example-basic-multiple select2 " name="type" style="width: 100%;" >
                                @foreach($Medicines as $medicine)
                                <option value="{{$medicine->name}}">{{$medicine->type}}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="invalid-feedback" @style(["display: block"])>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPhone1">Precio</label>
                            <input type="text" name="price" class="form-control" id="exampleInputprice1" placeholder="Ingrese el precio" value="{{old("price")}}">
                            @error('price')
                                <div class="invalid-feedback" @style(["display: block"])>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                      </div>
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