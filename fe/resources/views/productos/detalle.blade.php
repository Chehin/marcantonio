@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'producos', 'page' => 1])}}">Productos</a></li>
		@if(isset($categoria))
			@if($categoria['rubro'])
			<li class="breadcrumb-item"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => 0, 'name' => $categoria['rubro']['rubro'], 'page' => 1])}}">{{ucfirst(strtolower($categoria['rubro']['rubro']))}}</a></li>
			@endif
			@if($categoria['subrubro'])
			<li class="breadcrumb-item"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => $categoria['subrubro']['id'], 'name' => $categoria['subrubro']['subrubro'], 'page' => 1])}}">{{ucfirst(strtolower($categoria['subrubro']['subrubro']))}}</a></li>
			@endif
		@endif
			@if(isset($producto))
			<li class="breadcrumb-item"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => (isset($categoria['rubro']['id']))?$categoria['rubro']['id']:0 , 'id_subrubro' => (isset($categoria['subrubro']['id']))?$categoria['subrubro']['id']:0, 'name' => $producto['marca'], 'page' => 1])}}?IdMarca={{$producto['id_marca']}}">{{ucfirst(strtolower($producto['marca']))}}</a></li>
			<li class="breadcrumb-item active" aria-current="page">{{ucfirst($producto['nombre'])}}</li>
			@endif
		</ol>
	</nav>

	<div class="page-loader dark" style="display: none;">
		<div class="spinner">
			<div class="bounce1"></div>
			<div class="bounce2"></div>
			<div class="bounce3"></div>
		</div>
	</div>
<!-- Breadcrumbs End -->

	<!-- Main Container -->
	<div class="main-container col1-layout" id="produto_detalle">
	<section>
		<div class="container border-secondary py-3">
			<div class="row item-producto">
				<div class="col-md-5">
					<div class="product-media">
						<div class='zoom' id='ex1'>
                            @if(isset($fotos['0']['imagen_file']))
							<img class="img-fluid" src="{{env('URL_BASE').'uploads/productos/'.$fotos[0]['imagen_file']}}" alt="{{$fotos[0]['epigrafe']}}">
                            @else
                                <img  class="img-fluid" src="{{env('URL_BASE').'uploads/productos/prodnodisponible.png'}}" />
                            @endif
						</div>

						<div class="owl-carousel owl-theme owl-loaded img-carousel">
							<div class="owl-stage-outer">
								<div class="owl-stage">
									@if(isset($fotos['1']['imagen_file']))
										@foreach($fotos as $foto)
										<div class="owl-item ">
											<a class="itemFoto" href="javascript:void(0)"><img src="{{env('URL_BASE').'uploads/productos/'.$foto['imagen_file']}}" class="img-thumbnail" alt="prod img"></a>
										</div>
										@endforeach
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="product-detalles py-2">
						<h2 class="product-name">
							<span>{{ (isset($producto))?$producto['nombre']:'' }}</span>
						</h2>
						<hr class="w-100">
						<h5>
							@if(isset($precios['precio']))
								@if($precios['precio']>0)
							<del class="text-danger small">{{($precios['precio_lista']>0)?$precios['precio_lista']:''}}</del> ${{$precios['precio']}}
								@endif
							@endif
						</h5>
						@if(isset($stockColor[0]['stock_total']))
							@if($stockColor[0]['stock_total']>0)
							<span class="badge badge-success">Con Stock</span>
							@else
							<p class="availability in-stock out-of-stock pull-right"><span>Sin Stock</span></p>
							@endif
						@endif
					</div>
					<div class="ratings">


					</div>
					@if(isset($stockColor[0]['stock_total']))
						@if(count($stockColor)>0)
							<div class="product-color-size-area">
								<div class="color-area">
									<h2 class="saider-bar-title">Colores</h2>
									<div class="color">
										<ul id="coloresProducto">
											@php $i=0 @endphp
											@foreach($stockColor as $color)
												@php $i++ @endphp
												<li data-color="{{ $color['id_color'] }}">
													@if($i==1)
														<a href="javascript:void(0);" class="active">
															@else
																<a href="javascript:void(0);">
																	@endif
																	<img src="{{ isset($color['foto'])? env('URL_BASE_UPLOADS').'productos/'.$color['foto'] : 'uploads/productos/logo.ico'}}" alt="{{isset($color['foto'][0]['epigrafe'])?$color['foto'][0]['epigrafe']:''}}" width="75" height="75" title="{{$color['nombreColor']}}"/>
																</a>
												</li>
											@endforeach
										</ul>
									</div>
								</div>
								@if(isset($stockColor[0]['talles']))
									<div class="size-area">
										<h2 class="saider-bar-title">Talles</h2>
										<div class="size" id="talles_div">
											<ul>
												@php $i=0 @endphp
												@foreach($stockColor[0]['talles'] as $talle)
													@if ($talle['stock']>0)
													@php $i++ @endphp
													<li data-talle="{{ $talle['id_talle'] }}" data-stock="{{ $talle['stock'] }}" data-codigo="{{ $talle['codigo'] }}"
														@if($i==1)
														class="active"
														@endif																																										
													>
														<a href="javascript:void(0);" 	data-toggle="tooltip" data-placement="top" title="{{$talle['nombre']}}">{{$talle['nombre']}}</a>														
													</li>
													@endif
												@endforeach
											</ul>
										</div>
									</div>
							</div>
						@endif
					@endif
					@endif


					<form class="my-2" method="post" enctype="multipart/form-data" action="javascript:void(0)">
						<div class="quantity">
							<div class="form-row">
								<div class="col-6 col-lg-3">
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<button type="button" class="btn btn-sm btn-secondary dec qtybutton"><i class="fa fa-minus"></i></button>
										</div>
										<input  type="text" id="qty" name="cantidad" class="form-control text-center qty" title="Cantidad" placeholder="1">
										<div class="input-group-append">
											<button  type="button" class="btn btn-sm btn-secondary inc qtybutton"><i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="stock" value="{{ isset($stockColor[0]['talles']['0']['stock'])?$stockColor[0]['talles']['0']['stock']:$stockColor[0]['stock_total'] }}" class="stock" />

						<input type="hidden" name="id" value="{{ (isset($producto['id']))?$producto['id']:0 }}"/>
						<input type="hidden" name="idProd" value="{{ (isset($producto['id']))?$producto['id']:0 }}"/>
						<input type="hidden" name="id_marca" value="{{ (isset($producto['id_marca']))?$producto['id_marca']:0 }}"/>
						<input type="hidden" name="id_genero" value="{{ (isset($producto['id_genero']))?$producto['id_genero']:0 }}"/>
						<input type="hidden" name="id_color" value="{{ (isset($stock['talles'][0]['colores'][0]['id_color']))?$stock['talles'][0]['colores'][0]['id_color']:0 }}" class="color_prod" />
						<input type="hidden" name="id_talle" value="{{ (isset($stock['talles'][0]['id_talle']))?$stock['talles'][0]['id_talle']:0 }}" class="talle_prod" />
						<input type="hidden" name="nombre_producto" value="{{ (isset($producto['nombre']))?$producto['nombre']:'' }}" />
						@if (isset($stock['total']) & $stock['total']>0)
								<button type="button" class="btn btn-custom product-btn add-to-cart" title="Agregar al carrito" onclick="addcart()">
									<i class="fa fa-shopping-cart"></i>
								</button>
							<a class="btn btn-custom2 product-btn add-to-cart" href="{{ route('procesar_pedido',['id' => 1 ]) }}">
								<i class="fa fa-check"></i>
								<span>Comprar</span>
							</a>
							@else
								<button type="button" disabled class="btn btn-custom product-btn add-to-cart">
									<i class="fa fa-shopping-cart"></i>
								</button>
								<button type="submit" disabled class="btn btn-custom2 btn-md" data-title="Add to wishlist" data-location="top">
									<i class="fa fa-check"></i> Comprar
								</button>
						@endif
					</form>

					<div class="my-2">
						<div class="card">
							<div class="card-body bg-light">
								<ul class="nav flex-column">
									<li class="nav-item">
										@if (isset($stock))
										<span>Codigo: </span><span id="codigo_prod"> {{ isset($stockColor[0]['talles'][0]['codigo'])? $stockColor[0]['talles'][0]['codigo'] : $stockColor[0]['codigo']}}</span>
										@endif
									</li>

									@if(isset($producto))
									<li class="nav-item">
										<span>Marca: <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdMarca={{$producto['id_marca']}}" rel="tag">{{$producto['marca']}}</a></span>
									</li>
									@endif
									<li class="nav-item">
                                            <span class="posted_in">Categoria:
											@if (isset($categoria))
												@if ($categoria['rubro'])
													<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => 0, 'name' => $categoria['rubro']['rubro'], 'page' => 1])}}">{{($categoria['rubro']['rubro'])?$categoria['rubro']['rubro']:''}}</a>,
												@endif
												@if ($categoria['subrubro'])
													<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $categoria['rubro']['id'] , 'id_subrubro' => $categoria['subrubro']['id'], 'name' => $categoria['subrubro']['subrubro'], 'page' => 1])}}">{{(isset($categoria['subrubro']['subrubro']))?$categoria['subrubro']['subrubro']:''}}</a>.
												@endif
											@endif
                                            </span>
									</li>
									<li class="nav-item"><span>
                                            Etiquetas:
											@if (isset($etiquetas))

												@foreach($etiquetas as $etiqueta)
                                            		<a href="{{route('productos',['id_etiqueta' => $etiqueta['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['text']), 'page' => 1])}}">{{$etiqueta['text']}}</a>,
												@endforeach
												@endif
                                            </span>
									</li>
								</ul>
							</div>
						</div>
					</div>

					<div class="clearfix my-2">
						<nav>
							<div class="nav nav-tabs" id="nav-tab" role="tablist">
								<a class="nav-item nav-link active" id="nav-descrip-tab" data-toggle="tab" href="#nav-descrip" role="tab" aria-controls="nav-descrip" aria-selected="true">Descripcion</a>
{{--								<a class="nav-item nav-link" id="nav-info-tab" data-toggle="tab" href="#nav-info" role="tab" aria-controls="nav-info" aria-selected="false">Más Informacion</a>--}}
							</div>
						</nav>
						<div class="tab-content my-3" id="nav-tabContent">
							<div class="tab-pane fade show active" id="nav-descrip" role="tabpanel" aria-labelledby="nav-descrip-tab">
								<p style="font-weight: 300;">{{ (isset($producto))?$producto['sumario']:'' }}
								</p>
							</div>
							<div class="tab-pane fade" id="nav-info" role="tabpanel" aria-labelledby="nav-info-tab">
								<div class="table-responsive-sm">
									<table class="table table-striped">
										<thead>
										<tr>
											<th>Color</th>
											<th>Talle</th>
											<th>Codigo</th>
										</tr>
										</thead>
										<tbody>
										@if (isset($stock))
											@foreach($stock['talles'] as $item)
												@foreach($item['colores'] as $color)
												<tr>
													<td>{{$color['color']}}</td>
													<td>{{$item['talle']}}</td>
													<td>{{$color['codigo']}}</td>
												</tr>
												@endforeach
											@endforeach
										@endif
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section>
	</div>
	@include('partials.relacionados',['ProductosRelacionados' => $ProductosRelacionados,'stock'=>$stock])
@stop
@section('javascript')


<script>

	function cambiar_stock(content){
		var stock = parseInt($(content).find('.stock').val());
		var _this = parseInt($(content).find('#qty').val());
		if(_this>stock){
			$(content).find('#qty').val(stock);
		}else{
			if(_this<1 || !$.isNumeric(_this)){
				$(content).find('#qty').val(1);
			}else{
				$(content).find('#qty').val(_this);
			}
		}
	}
	function buscarCambioColor(id_producto, id_color){
		$('.page-loader.dark').fadeIn();
		$.ajax({
			dataType: 'json',
			type: "GET",
			url: "ajax/cambioColor",
			data: {'id_producto': id_producto, 'id_color': id_color}
		})
				.done(function( items ) {
					console.log(items);
					$('.page-loader.dark').fadeOut();
					var talles = items.talles[0].talles;
					var fotos = items.fotos;
					$('#talles_div ul').html('');
					//talles
					jQuery.each( talles, function( i, val ) {
						var li = $('<li/>')
								.data('cantidad', val['cantidad'])
								.data('talle', val['id_talle'])
								.data('stock', val['stock'])
								.data('codigo', val['codigo'])
								.appendTo('#talles_div ul');

						$('<a data-toggle="tooltip" data-placement="top" />')
								.attr('title', 'ARG ' + val['nombre'])
								.attr('href', 'javascript:void(0)')
								.text(val['nombre'])
								.appendTo(li);
					});
					//fotos
					var imagenClick = document.getElementsByTagName('base')[0].href + 'uploads/' + fotos[0]['imagen_file'];
					
				        $('#ex1').find('img').attr('src', imagenClick);
				        $('#ex1').zoom();
			/*		$('.img-carousel').hide();
					$('.previews-list.slides').html('');
					if(fotos.length == 0){
						var div = $('.large-image');
						div.find('a').attr('href','images/img_default/th_producto.jpg').find('img').attr('src','images/img_default/th_producto.jpg')

						jQuery("#magni_img").attr("data-big", 'images/img_default/th_producto.jpg');
						jQuery("#mlens_target_0").css('background-image','url(images/img_default/th_producto.jpg)');
					}
				/*	jQuery.each( fotos, function( i, val ) {
						var galimg='{{env("URL_BASE_UPLOADS")}}'+val['imagen_file'];
						var galimg_th=galimg;
						//var galimg_th='{{env("URL_BASE_UPLOADS")}}th_'+val['imagen_file'];
						if(i==0){
							var div = $('.large-image');
							div.find('a').attr('href',galimg).find('img').attr('src',galimg)

							jQuery("#magni_img").attr("data-big", galimg);
							jQuery("#mlens_target_0").css('background-image','url('+galimg+')');
						}
						if(fotos.length > 1){
							var li = $('<li/>')
									.appendTo('.previews-list.slides');

							var ali = $('<a/>')
									.attr('href', galimg)
									.attr('rel', "useZoom: 'magni_img', smallImage: '"+galimg_th+"'")
									.addClass('cloud-zoom-gallery')
									.appendTo(li);

							$('<img/>')
									.attr('src', galimg_th)
									.appendTo(ali);
						}
					});
					//jQuery(".cloud-zoom, .cloud-zoom-gallery").CloudZoom();
					//jQuery(".cloud-zoom-gallery").click(clickThumb);
					if(fotos.length > 1){
						$('.flexslider-thumb').data('flexslider').setup();
						$('.flexslider-thumb').show();
					}*/
					productoReady();
					$("#talles_div li:first").click();
				});
	}
	function productoReady(){
		//selecciona talle
		$("#talles_div li").on('click',function(){
			$('input[name=cantidad]').val(1);
			$('.stock').val($(this).data('stock'));
			$('input[name=talle_prod]').val($(this).data('talle'));
			$('input[name=id_talle]').val($(this).data('talle'));
			$('#codigo_prod').text($(this).data('codigo'));
			$("#talles_div li").removeClass('active');
			$(this).addClass('active');
		});
	}
	$(document).ready(function() {
		//selecciona color
		$("#coloresProducto li").on('click',function(e){
			var id_producto = $('input[name=id]').val();
			var id_color = $(this).data('color');
			$('input[name=cantidad]').val(1);
			$('input[name=color_prod]').val($(this).data('color'));
			$('input[name=id_color]').val($(this).data('color'));
			$("#coloresProducto li a").removeClass('active');
			$(this).find('a').addClass('active');
			buscarCambioColor(id_producto, id_color);
		});
		$('#coloresProducto li:first').click();
		productoReady();

		var windowWidth = window.screen.width < window.outerWidth ?
				window.screen.width : window.outerWidth;
		var mobile = windowWidth < 600;

		if (mobile==true){
			$('zoom').removeAttr('id').setAttribute('id');
			$('img.img-fluid').magnifik();
			console.log('Zoom Movil');
		}

	});
	function addcart() {

		gtag('event', 'add_to_cart', {
			"items": [
				{
					"id": {!!$producto['id']!!},
					"name": {!!"'" .$producto['nombre']."'"!!},
					"list_name": {!!"'" .'Detalle de Producto'."'"!!},
					"brand": {!!"'" .$producto['marca']."'"!!},
					"category":{!!isset($categoria['subrubro']['subrubro'])?"'" .$categoria['subrubro']['subrubro']."'":"'" .$categoria['rubro']['rubro']."'"!!},
					"quantity": parseInt($(document).find('#qty').val()),
					"price": {!!(isset($precios['precio_lista']) && $precios['precio_lista']<$precios['precio'] && $precios['precio_lista']>0)?$precios['precio_lista']:$precios['precio']!!},

				}
			]
		});
	}

	gtag("event",  "view_item",  {
		"items": [{
			"id": {!!$producto['id']!!},
			"name": {!!"'" .$producto['nombre']."'"!!},
			"list_name": {!!"'" .'Detalle de Producto'."'"!!},
			"brand": {!!"'" .$producto['marca']."'"!!},
			"category": {!!isset($categoria['subrubro']['subrubro'])?"'" .$categoria['subrubro']['subrubro']."'":"'" .$categoria['rubro']['rubro']."'"!!},
			"price": {!!(isset($precios['precio_lista']) && $precios['precio_lista']<$precios['precio'] && $precios['precio_lista']>0)?$precios['precio_lista']:$precios['precio']!!},

		}]
	});


</script>

@stop