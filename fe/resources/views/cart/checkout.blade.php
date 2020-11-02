@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Carrito</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->

<!-- Main Container -->
<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
        	<div class="bg-{!!$estado_color!!} text-center" style="width: 44px;margin: 0 auto;height: 44px;border-radius: 30px;padding: 11px 0;border:1px solid #ccc;">
			<i class="fa fa-{!!$estado_ico!!} fa-2x"></i>
			</div>
			<h1 class="title mb15 text-center" style="font-size: 40px;">{!!$estado!!}</h1>
			<h3 class=" mb15 text-center text-uppercasse">{!!$estado_detalle!!}</h3>
			<div class="text-center">
				<a href="{{route('cuenta')}}" class="btn btn-success">Mi cuenta</a>
			</div>
        </div>
    </div>
</section>


<section class="main-container col1-layout">
    <div class="main container">
        <div class="page-content">
        	
        </div>
    </div>
</section>

@stop

@section('javascript')
    <script>
@if (isset($carritoPago))
        @if(isset($carritoPago['id_pedido']))
        gtag('event', 'purchase', {
            "transaction_id": {!! $carritoPago['id_pedido']!!},
            "affiliation": "Marcantonio Deportes",
            "value":{!!  $carritoPago['precio_venta']!!} ,
            "currency": "ARS",
            "shipping": {!!  $carritoPago['costo_envio']!!},
            "items": [
                    @if (isset($carritoPago['carrito']))
                    @foreach($carritoPago['carrito'] as $Producto)
                {
                    "id": {!! $Producto['id_producto']!!} ,
                    "name": {!! "'" .$Producto['titulo']."'" !!},
                    "list_name": "Checkout",
                    "variant": {!! "'" .$Producto['color'][0]['nombre']."'" !!},
                    "quantity": {!! $Producto['cantidad']!!} ,
                    "price": {!! $Producto['precio_prod']!!} ,
                },
                    @endforeach
                    @endif
            ]
        });
        @endif
@endif
    </script>
@stop

