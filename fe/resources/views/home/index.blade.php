@extends('master')
@section('content')

 @include('home.slider',['slider' => $slider])
 @include('home.marcas',['marcas' => $marcas])
 @include('home.generos',['etiquetas_rubros' => $etiquetas_rubros])
 @include('home.destacados',['ProductosDestacados' => $ProductosDestacados])
 @include('home.etiquetas',['etiquetas' => $etiquetas])
 @include('home.ofertas',['ProductosOfertas' => $ProductosOfertas])
 @include('home.deportes',['Deportes' => $Deportes])
 @include('home.mas_vendidos',['ProductosMasVendidos' => $ProductosMasVendidos])
 @include('home.servicios',['Servicios' => $Servicios])
 @include('home.mas_vistos',['ProductosMasVistos' => $ProductosMasVistos])

@stop
@section('javascript')


@stop

