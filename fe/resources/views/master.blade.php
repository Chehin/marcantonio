<!DOCTYPE html>
<html lang="es">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-74676350-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('set', {'currency': 'ARS'});
  gtag('config', 'UA-74676350-1');
</script>

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
					new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-PDLMQDZ');</script>
	<!-- End Google Tag Manager -->

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('pageTitle', env('SITE_NAME'))</title>
	<meta name="description" content="Tienda Online - Marcantonio Deportes">
	<base href="{{ route('home') }}/" />
	<meta name="description" content="">
	<meta name="keywords" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/jpg" href="img/favicon.jpg">

	<!-- styles -->

	{!!Html::style('vendor/bootstrap/css/bootstrap.min.css')!!}
	{!!Html::style('vendor/owlCarousel2/assets/owl.carousel.min.css')!!}
	{!!Html::style('vendor/owlCarousel2/assets/owl.theme.default.css')!!}
	{!!Html::style('css/fontawesome/css/all.min.css')!!}
	{!!Html::style('css/style.css')!!}
	{!!Html::style('https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css')!!}
	{!!Html::style('https://fonts.googleapis.com/css?family=Open+Sans|Roboto&display=swap')!!}
{{--	{!!Html::style('css/animate.min.css')!!}--}}
{{--	{!!Html::style('css/bootstrap-dropdownhover.min.css')!!}--}}

</head>


<body>

	<div class="wrapper">
		@include('partials.header')
		@yield('content')
		@include('partials.footer')
	</div>
	@include('partials.script')

	@yield('javascript')


</body>
</html>
