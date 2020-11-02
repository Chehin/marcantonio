@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cuenta') }}">Mi cuenta</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mis Direcciones</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
            <h2 class="title"><i class="fa fa-map-marker"></i> Mis Direcciones</h2>
            <div class="row">
             @if($data['data'])
                @foreach($data['data'] as $dir)
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="panel dire panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>{!! $dir['titulo'] !!}</strong></h3>
                        </div>
                        <div class="panel-body">
                            <ul>
                                <li> <span> <strong>{!! $dir['direccion'] !!} {!! $dir['numero'] !!}</strong></span></li>
                                <li> <span> <strong>Provincia</strong>: {!! $dir['provincia'] !!}</span></li>
                                <li> <span> <strong>Ciudad</strong>: {!! $dir['ciudad'] !!}</span></li>
                                <li> <span><strong>Código postal</strong>: {!! $dir['cp'] !!}</span></li>
                                <li> <span> <strong>Teléfono</strong>: {!! $dir['telefono'] !!}</span></li>
                                <li> <span> {!! $dir['informacion_adicional'] !!}</span></li>
                            </ul>
                        </div>
                        <div class="panel-footer panel-footer-address">
                            <a href="{{ route('editar_direccion',['id' => $dir['id'] ]) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-edit"> </i> Editar 
                            </a>
                            <a href="{{ route('borrar_direccion',['remove' => $dir['id'] ]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Está seguro que quiere eliminar está dirección?')">
                                <i class="fa fa-minus-circle"></i> Borrar 
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-sm-12">
                    <h4>No hay direcciones cargadas</h4>
                </div>
            @endif
               
                <div class="col-md-12">
                    <br>
                    <a class="btn btn-success" href="{{ route('agregar_direccion') }}">
                        <i class="fa fa-plus-circle"></i> Agregar Nueva Dirección
                    </a>
                </div>
            </div>
            <br>
        </div>
    </div>
</section>
@stop
