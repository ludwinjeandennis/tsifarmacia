@extends('layouts.app')

@section('title', 'Editar Proveedor')

@section('header', 'Editar Proveedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('index') }}">Inicio</a></li>
    <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Proveedores</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">Editar: {{ $supplier->name }}</h3>
                </div>
                <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $supplier->name) }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $supplier->email) }}" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="phone">Teléfono</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}">
                        </div>
                        <div class="form-group">
                            <label for="address">Dirección</label>
                            <textarea name="address" class="form-control" rows="3">{{ old('address', $supplier->address) }}</textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
