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
					<h4>Restablecer contraseña</h4>
					<form method="POST">
						<?php if($_error_reset){ ?>
							<div class="alert alert-danger alert-dismissable">
								<span class="alert-icon"><i class="fa fa-warning"></i></span>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>¡Atención!</strong>
								<?php if(is_array($_error_reset)){ ?>
									<ul class="list">
										<?php foreach($_error_reset as $errores){ ?>
											<?php foreach($errores as $valor){ ?>
												<li><?=$valor?></li>
											<?php } ?>
										<?php } ?>
									</ul>
									<?php }else{ ?>
									<?=$_error_reset;?>
								<?php } ?>
							</div>
						<?php } ?>
						<?php if($_success_reset){ ?>
							<div class="alert alert-success alert-dismissable">
								<span class="alert-icon"><i class="fa fa-warning"></i></span>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<?=$_success_reset?>
							</div>
						<?php } ?>
						<?php if(!$_no_reset){ ?>
						<div class="form-group">
							<label>Nueva Contraseña</label>
							<input type="password" class="form-control" name="password" placeholder="Contraseña" required>
						</div><!-- End .from-group -->
						<div class="form-group">
							<label>Confirmar Nueva Contraseña</label>
							<input type="password" class="form-control" name="repassword" placeholder="Repetir Contraseña" required>
						</div><!-- End .from-group -->					
						
						<button class="btn btn-custom min-width">Restablecer</button>
					<?php }elseif($_success_reset){ ?>
						<a href="{{ route('login') }}" class="btn btn-danger" role="button">
							<i class="fa fa-lock"></i> Ingresar
						</a>
					<?php } ?>
					</form>
				</div>
				<div class="box-authentication pull-right">
					<h4>Registrarse</h4>
					<a href="{{ route('registro') }}" class="btn btn-danger" role="button">
						<i class="fa fa-lock"></i> Registro
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Main Container End --> 

@stop
