<!-- Generos -->
@if(isset($etiquetas_rubros))

<section id="generos" class="bg-secondary">
    <div class="container py-5">
        <div class="row">
            @foreach($etiquetas_rubros as $etiqueta)
                @if(isset($etiqueta['etiqueta']))
            <div class="col-12 col-md-4 p-0">
                <div class="card border-0">
                    <img class="card-img" src="{{(isset($etiqueta['foto'][0]['imagen_file']))? env('URL_BASE').'uploads/etiquetas/'.$etiqueta['foto'][0]['imagen_file']:env('URL_BASE').'img/logo.png'}}" alt="{{$etiqueta['etiqueta']}}">
                    <div class="card-img-overlay m-3 border">
                        <div class="p-2">
                            <a class="text-decoration-none" href="{{route('productos',['id_etiqueta' => $etiqueta['id_etiqueta'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['etiqueta']), 'page' => 1])}}">
                                <h5 class="card-title text-light">{{$etiqueta['etiqueta']}}</h5>
                            </a>
                            <ul class="nav flex-column">
                                @if($etiqueta['rubros'])
                                    @foreach($etiqueta['rubros'] as $rubro)
                                    <li class="nav-item">
                                        <a class="nav-link text-light" href="{{route('productos',['id_etiqueta' => $etiqueta['id_etiqueta'], 'id_rubro' => $rubro['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubro['nombre']), 'page' => 1])}}">{{$rubro['nombre']}}</a>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="overlay"></div>
                </div>
            </div>
                @endif
           @endforeach
        </div>
    </div>
</section>
@endif