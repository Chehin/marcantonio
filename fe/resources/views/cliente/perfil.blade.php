@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cuenta') }}">Mi cuenta</a></li>
            <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container">
    <div class="main container">
        <div class="page-content">
            <div class="account-login">
                <div class="new-customer-box Account Page ">
                    <form method="POST" action="{{ route('perfil') }}">


                        @if($data!='')
                            <div class="{!!$data['class']!!}">
                                    <span class="alert-icon"><i class="fa fa-warning"></i></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{!!$data['noti']!!}</strong> 
                                    @if(is_array( $data['msg']) )
                                        <ul class="list">
                                        @foreach($data['msg'] as $err)
                                            @foreach($err as $valor)
                                                <li>{!!$valor!!}</li>
                                            @endforeach
                                        @endforeach
                                        </ul>
                                    @else
                                        {!! $data['msg'] !!}
                                    @endif
                            </div>
                        @endif
                            <div class="row">
                            <div class="col-sm-12">
                                <div class="title">
                                    <h4>Información personal</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Su Nombre *:</label>
                                <div class="input-text">
                                    <input type="text" name="nombre" class="form-control" value="{!! $_SESSION['nombre'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Su Apellido *:</label>
                                <div class="input-text">
                                    <input type="text" name="apellido" class="form-control" value="{!! $_SESSION['apellido'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>DNI *:</label>
                                <div class="input-text">
                                    <input type="text" name="dni" class="form-control" value="{{ (isset($_SESSION['dni']))? $_SESSION['dni'] : '' }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Telefono *:</label>
                                <div class="input-text">
                                    <input type="text" name="telefono" class="form-control" value="{{ (isset($_SESSION['telefono']))? $_SESSION['telefono'] : '' }}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>E-mail *:</label>
                                <div class="input-text">
                                    <input type="text" name="email" class="form-control" value="{!! $_SESSION['email'] !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Repetir E-mail *:</label>
                                <div class="input-text">
                                    <input type="email" name="reemail" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Contraseña:</label>
                                <div class="input-text">
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Repetir Contraseña:</label>
                                <div class="input-text">
                                    <input type="password" name="repassword" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <small style="color:#ff0000;text-transform:initial;">Deje en blanco la contraseña si no desea modificarla</small>
                            </div>
                            <div class="col-xs-12">
                                <div class="submit-text">
                                    <button class="btn btn-custom"><i class="fa fa-user"></i>&nbsp; <span>Guardar</span></button>
                                </div>
                            </div>
                        </div>
                            <br>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Main Container End -->
@stop
