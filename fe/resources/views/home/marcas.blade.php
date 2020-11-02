
@if(isset($marcas))
<!-- Marcas -->
<section class="pb-1">
	<div class="container-fluid ">
		<div class="owl-carousel owl-theme owl-loaded marcas-carousel">
			<div class="owl-stage-outer">
				<div class="owl-stage">
					@foreach($marcas as $marca)
					<div class="owl-item">
						<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => $marca['nombre'], 'page' => 1])}}?IdMarca={{$marca['id']}}"><img src="{{(isset($marca['imagen_file']))? env('URL_BASE').'uploads/marcas/'.$marca['imagen_file']:env('URL_BASE').'img/logo.png'}}" class="img-fluid" alt="marca descrip"></a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</section>
@endif

