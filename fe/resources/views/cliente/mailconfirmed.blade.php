@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Confirmar Cuenta</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
			
            
            <div class="{!!$data['class']!!}">
                <span class="alert-icon"><i class="fa fa-warning"></i></span>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{!!$data['noti']!!}</strong>{!! $data['msg'] !!}
            </div>
			
			@if($data['status']==0)
                <a href="{{ route('login') }}" class="btn btn-danger" role="button">
                                <i class="fa fa-lock"></i> Ingresar
                </a>
            @endif
			
        </div>
    </div>
</section>
<!-- Main Container End -->
@stop
