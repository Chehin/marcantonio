{{-- @extends('master')
@section('content')

    <!-- HEADER Fixed navbar start -->
    @include('partials.header')
    <!-- /.Fixed navbar  -->
    <div class="error-page headerOffset">
    <div class="gap"></div>
    <div class="container">
		<div class="error_pagenotfound"> 
			<strong>U<span id="animate-arrow">P</span>S !</strong> <br>
			<b>Página no encontrada</b> 
            <em>Lo sentimos, la página no se pudo encontrar aquí.</em>
			<br>
		</div>
		<!-- end error page notfound --> 
		
	</div>
</div>
    <div class="container-fluid main-container">

        <!--  OFERTAS -->
        @include('home.ofertas')
        <!--/.featuredPostContainer-->
    </div>
    <!-- /main container -->

    <div class="container-fluid main-container">
        <!--  DESTACADOS-->
        @include('home.destacado',['productos' => $productos_destacados])
        <!--/.featuredPostContainer-->

        <!-- MARCAS -->
        @include('home.marcas',['marcas' => $marcas])
        <!--/.section-block-->
    </div>
    <!--main-container-->

    <!-- BANNER 2-->
    @include('home.banner2')
    <!--/.parallax-section-->

@stop --}}