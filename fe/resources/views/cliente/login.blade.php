@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

	<div id="fb-root"></div>
	{{-- <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = 'https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v3.2&appId=1026532147526416&autoLogAppEvents=1';
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script> --}}

	<!-- Breadcrumbs -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Ingresar</li>
		</ol>
	</nav>
	<!-- Breadcrumbs End -->

		<!-- Main Container -->
			<div class="main container">
				<div class="row userInfo">
					<div class="col-xs-12 col-sm-6">
						<h2 class="title"> Ingresar </h2>
						@if($data!='')
							<div class="alert alert-danger alert-dismissable">
								<span class="alert-icon"><i class="fa fa-warning"></i></span>
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>¡Atención!</strong> {!!$data['msg']!!}
							</div>
						@endif

						<form method="POST" action="{{route('login')}}">
							<div class="form-group">
								<label >Email *</label>
								<input name="email" title="Email" type="email" class="form-control"  placeholder="Email" required >
							</div>
							<div class="form-group">
								<label>Contraseña *</label>
								<input required minlength="5" title="Contraseña" type="password" name="password" class="form-control"  placeholder="Contraseña">
							</div>
							<div class="error">
							</div>
							<p class="forgot-pass"><a href="recuperar_pass">¿Olvidó su contraseña?</a></p>
							<button class="btn btn-custom" onclick="gtagLogin()"><i class="fa fa-lock"></i>&nbsp; <span>Ingresar</span></button>
						</form>
					</div>
					<div class="col-xs-12 col-sm-6">
						<h2 class="title"><span>Registrarse</span></h2>
					{{--
                        <a  class="btn btn-facebook" href="{{ route('auth',['provider'=>'facebook']) }}">
                                <span class="fa fa-facebook"></span> Ingresar con Facebook
                        </a><br><br>--}}
					<!--
					<a  class="btn btn-google" href="{{ route('auth',['provider'=>'google']) }}" {{-- class="g-signin2" data-onsuccess="onSignIn" --}}> <span class="fa fa-google"></span> Ingresar con Google</a> <br>
				 <br>-->
						<a href="{{ route('registro') }}" class="btn btn-custom" role="button">
							<i class="fa fa-user-alt"></i> Registro
						</a>
					</div>
				<!--/row end-->
			</div>

			<div class="col-lg-3 col-md-3 col-sm-5"> </div>
		</div> <!--/row-->

		<div style="clear:both"></div>
	</div>
	<!-- /wrapper -->

	<!-- Main Container End -->

@stop
@section('javascript')
	<script>
		function gtagLogin(){
			gtag('event', 'login');
		}

	</script>
@stop