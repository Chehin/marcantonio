<!-- Etiquetas -->
@if (isset($etiquetas))
<section id="etiquetas" class="bg-light">
    <div class="container-fluid py-5">
        <div class="row">
            @foreach($etiquetas as $etiqueta)
            <div class="col-6 col-md-3 p-0">
                <a href="{{route('productos',['id_etiqueta' => $etiqueta['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['nombre']), 'page' => 1])}}">
                    <div class="card border-0">
                        <img class="img-fluid" src="{{(isset($etiqueta['foto'][0]['imagen_file']))? env('URL_BASE').'uploads/etiquetas/'.$etiqueta['foto'][0]['imagen_file']:env('URL_BASE').'img/logo.png'}}" alt="{{$etiqueta['nombre']}}">
                        <h5 class="bg-{{$etiqueta['color']}}">{{$etiqueta['nombre']}}</h5>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif