@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

<body>


<nav aria-label="breadcrumb">
	<ol class="breadcrumb mb-0">
		<li class="breadcrumb-item menu-home"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item menu-productos"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}">Productos</a></li>
		@if(isset($etiqueta_array))
		<li class="breadcrumb-item menu-etiqueta"><a href="{{route('productos',['id_etiqueta' => $etiqueta_array['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta_array['nombre']), 'page' => 1])}}">{{ucfirst(strtolower($etiqueta_array['nombre']))}}</a></li>
		@endif
		@if(isset($rubros_array))
		<li class="breadcrumb-item menu-rubro"><a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $rubros_array['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubros_array['nombre']), 'page' => 1])}}">{{ucfirst(strtolower($rubros_array['nombre']))}}</a></li>
		@endif
		@if($search)
			<li class="breadcrumb-item menu-search"><a href="javascript:void(0);">{{$search}}</a></li>
		@endif
		@if(isset($rubros_array['subrubro']))
		<li class="breadcrumb-item menu-subrubro">{{ucfirst(strtolower($rubros_array['subrubro']))}}</li>
		@endif
	</ol>
</nav>
<div class="bg-light">
	<div class="container py-3">
		<div class="row">
			<div class="col-lg-3">
				<div class="py-2">
					<h2>{{$head['actual']}}</h2>
					<p>{{$total_reg}} resutados</p>
				</div>
				@if(isset($head['etiqueta']))
					<div class="py-2">

							<a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}?IdMarca={{$url['id_marca']}}" class="btn-outline-custom p-1 rounded">
								<span>{{ $head['etiqueta'] }} <span aria-hidden="true">&times;</span></span>
							</a>

					</div>
				@endif
				@if(isset($head['etiqueta_marca']))
					<div class="py-2">

						<a href="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}" class="btn-outline-custom p-1 rounded">
							<span>{{ $head['etiqueta_marca'] }} <span aria-hidden="true">&times;</span></span>
						</a>

					</div>
				@endif
				@if(isset($head['etiqueta_deporte']))
					<div class="py-2">

						<a href="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}" class="btn-outline-custom p-1 rounded">
							<span>{{ $head['etiqueta_deporte'] }} <span aria-hidden="true">&times;</span></span>
						</a>

					</div>
				@endif
				<div class="py-3 d-block d-lg-none">
					<button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapseFiltros" aria-expanded="true" aria-controls="collapseFiltros" id="btnFiltros">
						<i class="fa fa-filter"></i> Filtros
					</button>
				</div>
				<div class="collapse show" id="collapseFiltros">
					<div class="py-2">
						<form action="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}">
							<input type="hidden" name="q" value="{{ $url['search'] }}">
							<input type="hidden" name="IdMarca" value="{{ $url['id_marca'] }}">
							<div class="form-group">
								<label for="exampleFormControlSelect1">Ordenar publicaciones</label>
								<select class="form-control" name="sortList" onchange="submit()">
									<option value="MasVistos" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='MasVistos'?'selected':''):'' }}>Más vistos</option>
									<option value="MasVendidos" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='MasVendidos'?'selected':''):'' }}>Más vendidos</option>
									<option value="MenorPrecio" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='MenorPrecio'?'selected':''):'' }}>Menor precio</option>
									<option value="MayorPrecio" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='MayorPrecio'?'selected':''):'' }}>Mayor precio</option>
									<option value="Destacados" {{ isset($extraParams['getData']['sortList'])?($extraParams['getData']['sortList']=='Destacados'?'selected':''):'' }}>Destacados</option>
								</select>
							</div>
						</form>
					</div>
					@if($filtros['etiquetas'])
					<div class="py-2">
						<label for="">Etiquetas</label>
						<ul class="nav flex-column">
							@foreach($filtros['etiquetas'] as $etiqueta)
							<li class="nav-item">
								<a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => $etiqueta['Id'], 'id_rubro' => $etiqueta['id_rubro'] , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}">{{$etiqueta['Nombre']}} <small>({{$etiqueta['Cantidad']}})</small></a>
							</li>
							@endforeach
						</ul>
					</div>
					@endif
					@if($filtros['subrubros'] && !$rubros_array['subrubro'])
						<div class="py-2">
							<label for="">Subcategorías</label>
							<ul class="nav flex-column">
								@foreach($filtros['subrubros'] as $subrubro)
									<li class="nav-item">
										<a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $subrubro['IdRubro'] , 'id_subrubro' =>  $subrubro['Id'], 'name' => 'productos', 'page' => 1])}}">{{$subrubro['Nombre']}} <small>({{$subrubro['Cantidad']}})</small></a>
									</li>
								@endforeach
							</ul>
						</div>
					@endif
					@if($filtros['rubros'] && !$filtros['subrubros'])
					<div class="py-2">
						<label for="">Categorías</label>
						<ul class="nav flex-column">
							@foreach($filtros['rubros'] as $rubro)
							<li class="nav-item">
								<a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $rubro['Id'] , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}{{ ($url['search'])?'?q='.$url['search']:''}}">{{$rubro['Nombre']}} <small>({{$rubro['Cantidad']}})</small></a>
							</li>
							@endforeach
						</ul>
					</div>
					@endif
					@if($filtros['deportes'])
					<div class="py-2">
						<label for="">Deportes</label>
						<ul class="nav flex-column">
							@foreach($filtros['deportes'] as $deporte)
							<li class="nav-item">
								<a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$deporte['Id']}}">{{$deporte['Nombre']}} <small>({{$deporte['Cantidad']}})</small></a>
							</li>
							@endforeach
						</ul>
					</div>
					@endif
					@if($filtros['marcas'] && !$url['id_marca'])
					<div class="py-2">
						<label for="">Marcas</label>
						<ul class="nav flex-column">
							@foreach($filtros['marcas'] as $marca)
							<li class="nav-item">
								<a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $marca['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos', 'page' => 1])}}?IdMarca={{$marca['Id']}}{{ ($url['search'])?'&q='.$url['search']:''}}">{{$marca['Nombre']}} <small>({{$marca['Cantidad']}})</small></a>
							</li>
							@endforeach
						</ul>
					</div>
					@endif
{{--					filtro precios--}}
					<div class="card my-2">
						<div class="card-header" id="headingOne">
							<h2 class="mb-0">
								<button class="btn btn-link text-dark" type="button" data-toggle="collapse" href="#precios" aria-expanded="true" aria-controls="precios">
									Precio
								</button>
							</h2>
						</div>

						<div id="precios" class="collapse show">
							<div class="card-body">
								<form class="form" action="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'name' => 'productos-precios', 'page' => 1])}}">
									<div class="row">
										<input type="hidden" name="q" value="{{ $url['search'] }}">
										<input type="hidden" name="IdMarca" value="{{ $url['id_marca'] }}">
										<div class="col">
											<input type="text"  name="preciomin" class="form-control" placeholder="Minimo">
										</div>
										<div class="col">
											<input type="text" name="preciomax"  class="form-control" placeholder="Maximo">
										</div>
									</div>
									<div class="my-2">
										<button class="btn btn-custom">Filtrar</button>
{{--										<button class="btn btn-custom2">Limpiar</button>--}}
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
{{--			Productos--}}
			<div class="col-lg-9 bg-white">
				<div class="row">
					@if (isset($productos_array))
						@foreach($productos_array as $Producto)
							<div class="col-md-4 my-2">
							@include('productos.itemProducto',['Producto'=> $Producto])
							</div>
						@endforeach
					@endif
				</div>
					<div class="row">
					<div class="col">
						@include('partials.paginado', ['nameRoute'=> 'productos', 'params'=>$extraParams['url']])
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

@stop
@section('javascript')
<script>
	$( document ).ready(function() {
		var windowWidth = window.screen.width < window.outerWidth ?
				window.screen.width : window.outerWidth;
		var mobile = windowWidth < 700;
		if (mobile==true){
			 $( "#btnFiltros" ).click();
		}
	});

	gtag('event', 'view_item_list', {
		"items": [
				@if (isset($productos_array))
				@foreach($productos_array as $Producto)
			{
				"id":  {!! $Producto['Id']!!} ,
				"name": {!! "'" .$Producto['Producto']."'" !!},
				"brand": {!! isset($head['etiqueta_marca'])?"'" .$head['etiqueta_marca']."'":"'" .'-'."'" !!},
				"category": {!! isset($head['actual'])?"'" .$head['actual']."'":"'" .'-'."'" !!},
				"list_name": {!! "'" .$head['actual']."'" !!},
				"price": {!!  $Producto['PrecioVenta']!!} ,
			},
					@endforeach
				@endif
		]
	});
	@if (isset($Producto))
	$( ".card.item-producto" ).click(function() {

		gtag('event', 'select_content', {
			"content_type": "product",
			"items": [
				{
					"id": {!!$Producto['Id'] !!},
					"name": {!! "'" .$Producto['Producto']."'" !!},
					"brand": {!! isset($head['etiqueta_marca'])?"'" .$head['etiqueta_marca']."'":"'" .'-'."'" !!},
					"category": {!! isset($head['actual'])?"'" .$head['actual']."'":"'" .'-'."'" !!},
					"list_name": {!! (isset($head['actual']))?"'" .$head['actual']."'":"'" .'Sin Nombre'."'" !!},
					"price": {!! ($Producto['PrecioLista']>0)?$Producto['PrecioLista']:$Producto['PrecioVenta']!!},
				}
			]
		});
	});
@endif
</script>
@stop

