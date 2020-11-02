@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
	<style>
		.eye-icon {
			float: right;
			margin-left: -25px;
			margin-top: -25px;
			position: relative;
			z-index: 2;
		}
	</style>
	<!-- Breadcrumbs -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item active" aria-current="page">Registrarse</li>
		</ol>
	</nav>
	<!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
	<div class="main container">
		<div class="page-content">
			<div class="account-login">
				<div class="col-md-10 col-lg-10 col-xs-10">
                    <form method="POST">
					
                        @if($data!='')
                        <div class="{!!$data['class']!!} col-md-9">
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
							<div class="row" style="width: 75%;">
								@if($data!='')
									<div class="col-md-12">
										@if(isset($data['msg2']))
											@if($data['msg2']!='')
												<div class="alert alert-warning alert-dismissible fade show" role="alert">
													<span class="alert-icon"><i class="fa fa-info-circle"></i></span>{{ $data['msg2'] }}
													<button type="button" class="close" data-dismiss="alert" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
											@endif
										@endif
									</div>
								@endif
								{{-- <div class="col-xs-12">
                                    <div class="check-title">
                                        <h4>Registrarse</h4>
                                    </div>
                                </div> --}}

								<div class="gap"></div>
								<div class="col-12">
									<label>Nombre:</label>
									<div class="input-text">
										<input type="text" name="nombre" class="form-control" value="{{ isset($nombre) ? $nombre : '' }}" required>
									</div>
								</div>
								<div class="col-12">
									<label>Apellido:</label>
									<div class="input-text">
										<input type="text" name="apellido" class="form-control" value="{{ isset($apellido) ? $apellido : '' }}" required>
									</div>
								</div>
								<div class="col-12">
									<label>E-mail:</label>
									<div class="input-text">
										<input type="email" name="email" class="form-control" value="{{ isset($email) ? $email : '' }}" required>
									</div>
								</div>
								<div class="col-12">
									<label>Contraseña:</label>
									<div class="input-text">
										<input id="input-password" type="password" name="password" class="form-control" required value="{{ isset($password) ? $password : '' }}">
										<span id="pass-eye-icon" class="fa fa-fw fa-eye eye-icon"></span>
									</div>
								</div>
								<div class="col-12" >
									<label></label>
									<div class="g-recaptcha" id="captcha_form" data-sitekey="6Lc6m6UZAAAAAI9sdjqy5nXkwBI8UrmYxg1aNhU-"></div>
								</div>
								<div class="col-12 mt-1">
									<div class="billing-checkbox">
										<label class="inline" for="politicas">
											<input type="checkbox" value="yes" id="politicas" name="politicas" required {{ isset($politicas) ? 'checked' : '' }}>
											He leído y acepto los <a href="{{route('nota',['id' => 18,'name' => 'terminos-y-condiciones' ])}}" target="_blank">Terminos y condiciones</a> y la <a href="{{route('nota',['id' => 7,'name' => 'politicas-de-privacidad' ])}}" target="_blank">Política de Privacidad</a>.
										</label>
									</div>
									<div class="billing-checkbox">
										<label class="inline" for="newsletter">
											<input type="checkbox" value="yes" id="newsletter" name="newsletter">
											Deseo recibir ofertas y novedades.
										</label>
									</div>
									<div class="submit-text">
										<button class="button btn btn-block btn-custom btn-sm"><i class="fa fa-user"></i>&nbsp; <span>Registrarse</span></button>
									</div>
								</div>
							</div>
						</div>
                    </form>
                    
                  {{--   <div class="box-authentication">				
						
                        <div class="">
                            <div class="interna-title"> <br>
                                <h4>Registrarse con</h4>
                            </div>
                        </div>
                        
                        <a  class="btn btn-facebook" href="{{ route('auth',['provider'=>'facebook']) }}"><span class="fa fa-facebook"></span> Ingresar con Facebook</a><br><br>
                
                        <div style="margin-left: 79px;">- O -</div>	<br>	
                        
                        <a  class="btn btn-google" href="{{ route('auth',['provider'=>'google']) }}"> <span class="fa fa-google"></span> Ingresar con Google</a> <br> <br>
                        
                        
                </div> --}}
				</div>
			</div>

			
		</div>
	</div>
</section>
	@stop
	@section('javascript')
<!-- Main Container End -->
	<!-- Recaptcha google  -->
{{--	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
		$('#form-registro').submit(function() {
			var $form		= $(this);
			var $dataStatus	= $form.find('.data-status');

			var response = grecaptcha.getResponse();
			if(response.length == 0){
				$dataStatus.show().html('<div class="alert alert-danger"><strong>Por favor verifique que no es un robot</strong></div>');
				return false;
			}
		});
	</script>--}}
	<script>
		//mostrar/ocultar password
		$(document).ready(function(){
			$("#pass-eye-icon").click(function() {
				$(this).toggleClass("fa-eye fa-eye-slash");
				var input = $("#input-password");
				if(input.attr("type") == "password") {
					input.attr("type", "text");
				}else{
					input.attr("type", "password");
				}
			});
		});
	</script>
@stop
