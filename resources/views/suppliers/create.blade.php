@extends('layouts.app')

@section('title', 'Crear Proveedor')

@section('header', 'Nuevo Proveedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Proveedores</a></li>
    <li class="breadcrumb-item active">Crear</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Información del Proveedor</h3>
                </div>
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nombre de la empresa" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="proveedor@ejemplo.com" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1-555-XXX-XXXX">
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Dirección completa">{{ old('address') }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar Proveedor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
