@if(isset($slider) && count($slider)>0 )
@php $href = '#'; @endphp
<!-- Slider -->
<section class="">
	<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
		<div class="carousel-inner">
			<div class="carousel-item active">
				@if (isset($slider[0]))
					@php if (isset($slide))
					$href = '#';
                    if(isset($slider[0]['relacionado']['related_resource'])){
                       switch( $slider[0]['relacionado']['related_resource'] ) {
                          case 'etiquetas': $href = 'productos/' . $slider[0]['relacionado']['related_id'] . '/0/0/'. $slider[0]['relacionado']['related_resource'] .'/1'; break;
                          case 'productos':  $href = 'producto/' . $slider[0]['relacionado']['related_id'] .'/' .$slider[0]['relacionado']['related_resource']; break;

                       }
                    }
					@endphp

				<a href="{{$href}}">
					<img src="{{env('URL_BASE').'uploads/slider/'.$slider[0]['foto'][0]['imagen_file']}}" class="d-block w-100" alt="slider 1920x1080">
				</a>
				@endif
			</div>

			@php unset($slider[0]); @endphp
			@if (isset($slider[1]))
			@foreach($slider as $slide)
				@php if (isset($slide))
					$href = '#';
                    if(isset($slide['relacionado']['related_resource'])){
                       switch( $slide['relacionado']['related_resource'] ) {
                          case 'etiquetas': $href = 'productos/' . $slide['relacionado']['related_id'] . '/0/0/'. $slide['relacionado']['related_resource'] .'/1'; break;
                          case 'productos':  $href = 'producto/' . $slide['relacionado']['related_id'] .'/' . $slide['relacionado']['related_resource']; break;

                       }
                    }
				@endphp


			<div class="carousel-item">
				<a href="{{$href}}">
					<img src="{{env('URL_BASE').'uploads/slider/'.$slide['foto'][0]['imagen_file']}}" alt="{{$slide['foto'][0]['imagen']}}" class="d-block w-100">
				</a>
			</div>
            @endforeach
			@endif
		</div>

		<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
			<span class="carousel-control-prev-icon" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
			<span class="carousel-control-next-icon" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
	</div>
</section>
@endif