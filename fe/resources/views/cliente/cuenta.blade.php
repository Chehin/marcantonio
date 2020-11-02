@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mi Cuenta</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->
<!-- Main Container -->
<section class="main-container">
    <div class="main container">
        <div class="page-content">
            <h2 class="title"><i class="fa fa-unlock-alt"></i> @if ($_SESSION) {!! $_SESSION['nombre'] !!} {!! $_SESSION['apellido'] !!} @endif - Mi cuenta</h2>
            <p><strong>BIENVENIDO A SU CUENTA. AQUÍ USTED PUEDE ADMINISTRAR TODA SU INFORMACIÓN PERSONAL Y PEDIDOS.</strong></p>
            <hr />
            <ul class="myAccountList row">
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Pedidos" href="{{ route('historial') }}">
                            <i class="fa fa-calendar v-icon"></i><br> Historial de Pedidos </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Mis direcciones" href="{{ route('direcciones') }}">
                            <i class="fa fa-map-marker v-icon"></i><br> Mis direcciones</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Agregar dirección" href="{{ route('agregar_direccion') }}"> 
                            <i class="fa fa-edit v-icon"> </i><br> Agregar dirección
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 col-xs-6  text-center ">
                    <div class="thumbnail equalheight" style="height: 116px;">
                        <a title="Información persona" href="{{ route('perfil') }}">
                            <i class="fa fa-cog v-icon"></i><br> Información personal
                        </a>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</section>

@stop
