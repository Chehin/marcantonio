@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
	<!-- Breadcrumbs -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">¿Olvidó su contraseña?</li>
		</ol>
	</nav>
	<!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
	<div class="main container">
		<div class="page-content">
			<div class="account-login">
				<div class="box-authentication pull-left">
					<h4>¿Olvidó su contraseña?</h4>
					@if ($_SESSION['status']==2 )
						<div class="alert alert-danger alert-dismissable col-xs-11">
								<span class="alert-icon"><i class="fa fa-warning"></i></span>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>¡Atención!</strong> {!!'ESTIMADO CLIENTE MODIFICAMOS NUESTRA WEB PARA UN MEJOR SERVICIO, POR FAVOR DEBE REESTABLECER SU CONTRASEÑA INGRESE SU EMAIL PARA PODER RECUPERARLA O GENERAR UNA NUEVA'!!}
						</div>
					@endif
					
					<form method="POST">
						@if ($_SESSION['status']==1 )
							<?php if($_error_forgot!=''){ ?>
								<div class="alert alert-danger alert-dismissable">
									<span class="alert-icon"><i class="fa fa-warning"></i></span>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>¡Atención!</strong> <?=$_error_forgot?>
								</div>
								<?php } ?>
									
								<?php if($_success_forgot!=''){ ?>
								<div class="alert alert-success alert-dismissable">
									<span class="alert-icon"><i class="fa fa-warning"></i></span>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<?=$_success_forgot?>
								</div>
								<?php } ?>					
						@endif
					
						<label for="emmail_login"> E-mail <span class="required">*</span></label>
						<input id="emmail_login" name="email" type="email" class="form-control" required="required">

						<button class="btn btn-custom"><i class="fa fa-lock"></i>&nbsp; <span>Recuperar</span></button>
					</form>
				</div>
				<div class="box-authentication pull-right">
					<h4>Registrarse</h4>
					<a href="registro" class="btn btn-custom" role="button">
						<i class="fa fa-lock"></i> Registro
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Main Container End --> 

@stop
