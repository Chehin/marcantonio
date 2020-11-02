<!-- Mas Vendidos -->
@if(isset($ProductosRelacionados))
	<section class="border-top py-5">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6">
					<h2 class="title">Productos Relacionados</h2>
				</div>
				<div class="col-md-6 text-right">
					<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}"><i class="fa fa-list"></i> Ver más productos</a>
				</div>
			</div>
			<div class="owl-carousel owl-theme owl-loaded productos-carousel">
				@foreach($ProductosRelacionados['productos'] as $Producto)
					<div class="card item-producto">
						@if($Producto['precios']['descuento']>0)
							<span class="sale">{{$Producto['precios']['descuento'].'%'}}</span>
						@endif
						<a href="{{ route('producto',['id' => $Producto['id'],'name' => str_slug($Producto['titulo'])]) }}"><img src="{{($Producto['fotos'])? env('URL_BASE').'uploads/productos/'.$Producto['fotos'][0]['imagen_file']:env('URL_BASE').'uploads/productos/default.jpg'}}" class="card-img-top"	alt=""></a>
						<form action="javascript:void(0)">
							<div class="card-body">
								<h5 class="card-title">{{'$'.$Producto['precios']['precio_venta']}} <del class="text-danger small">{{($Producto['precios']['precio_lista']>0)?'$'.$Producto['precios']['precio_lista']:''}}</del></h5>
								<p class="card-text"><a href="{{ route('producto',['id' => $Producto['id'],'name' => str_slug($Producto['titulo'])])}}" class="">{{$Producto['titulo']}}</a></p>
								<input type="hidden" name="stock" value="{{ (isset($stock['total']))?$stock['total']:0 }}" />
								<input type="hidden" name="id" value="{{ (isset($Producto['id']))?$Producto['id']:0 }}"/>
								<input type="hidden" name="idProd" value="{{ (isset($Producto['id']))?$Producto['id']:0 }}"/>
								<input type="hidden" name="id_marca" value="{{ (isset($Producto['id_marca']))?$Producto['id_marca']:0 }}"/>
								<input type="hidden" name="id_genero" value="{{ (isset($Producto['id_genero']))?$Producto['id_genero']:0 }}"/>
								<input type="hidden" name="id_color" value="{{ (isset($stock['talles'][0]['colores'][0]['id_color']))?$stock['talles'][0]['colores'][0]['id_color']:0 }}" class="color_prod" />
								<input type="hidden" name="id_talle" value="{{ (isset($stock['talles'][0]['id_talle']))?$stock['talles'][0]['id_talle']:0 }}" class="talle_prod" />
								<input type="hidden" name="nombre_producto" value="{{ (isset($Producto['nombre']))?$Producto['nombre']:'' }}" />

								@if (isset($stock['total']) && $stock['total']>0)
									<div style="text-align: center;">
									<a href="{{ route('producto',['id' => $Producto['id'],'name' => str_slug($Producto['titulo'])]) }}" class="btn btn-outline-secondary m-0"><i class="fa fa-check hidden-xs"></i> Comprar</a>
									</div>
									@else
									<a class="nav-link btn btn-danger text-light">Producto Sin Stock</a>
								@endif
							</div>
						</form>
					</div>
				@endforeach
			</div>
		</div>
		</div>
	</section>
@endif