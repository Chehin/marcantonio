@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-0">
			<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
			<li class="breadcrumb-item"><a href="{{route('notas',['id' => $data[0]['id_seccion'],'name' => str_slug($data[0]['seccion'])])}}">{{ $data[0]['seccion'] }}</a></li>
			<li class="breadcrumb-item"><a href="{{route('nota',['id' => $data[0]['id_nota'],'name' => str_slug($data[0]['titulo'])])}}">{{ $data[0]['titulo'] }}</a></li>
		</ol>
	</nav>
<section class="blog_post">
	<div class="container"> 
		<!-- row -->
		<div class="row"> 
			<!-- Center colunm-->
			<div class="center_column col-xs-12 col-sm-12" id="center_column">
				<div class="page-title">
					<h2>{{ $data[0]['seccion'] }}</h2>
				</div>
				<ul class="blog-posts">
					@foreach($data as $nota)
						<article class="entry">
							<div class="row">
								@if(isset($nota['imagen_file']))
								<div class="col-sm-4">
									<div class="entry-thumb image-hover2">
										<a href="{{route('nota',['id' => $nota['id_nota'],'name' => str_slug($nota['titulo'])])}}">
											<figure>
												<img src="{{ env('URL_BASE').'uploads/news/'.$nota['imagen_file'] }}" alt="{{$nota['epigrafe']}}">
											</figure>
										</a>
									</div>
								</div>
								@endif
								<div class="{{ isset($nota['fotos'][0])?'col-sm-8':'col-sm-12' }}">
									<h3 class="entry-title">
										<a href="{{route('nota',['id' => $nota['id_nota'],'name' => str_slug($nota['titulo'])])}}">{{$nota['titulo']}}</a>
									</h3>
									<div class="entry-excerpt">
										<p>{{ $nota['sumario'] }}</p>
									</div>
									<div class="entry-more">
										<a href="{{route('nota',['id' => $nota['id_nota'],'name' => str_slug($nota['titulo'])])}}" class="button">
											Ver más &nbsp; 
											<i class="fa fa-angle-double-right"></i>
										</a>
									</div>
								</div>
							</div>
						</article>

					@endforeach
				</ul>
			</div>
			<!-- ./ Center colunm --> 
		</div>
		<!-- ./row--> 
	</div>
</section>
 
@stop
