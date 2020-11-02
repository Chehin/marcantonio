<aside class="mb-3">
    <div class="py-2">
        @if($title['antetitulo'])
            <h5 class="font-weight-light text-muted mb-0">{{ $title['antetitulo'] }}</h5>
        @endif
        @if($title['categoria'])
            <h3 class="font-weight-normal mb-0">{{ $title['categoria'] }}</h3>
        @endif
        @if($title['marca'])
            <h3 class="font-weight-normal mb-0">{{ $title['marca'] }}</h3>
        @endif
        @if($title['etiqueta'])
            <h3 class="font-weight-normal mb-0">{{ $title['etiqueta'] }}</h3>
        @endif
        <p>{{$total_reg}} resultados</p>
    </div>

    @if(count($tags_filtros)>0)
        <div class="py-2">
            @foreach($tags_filtros as $tag_filtro)
                <a href="{{ route( 'productos', $tag_filtro['route']['url']) }}{{ $tag_filtro['route']['getData']?'?'.http_build_query($tag_filtro['route']['getData']):'' }}" class="btn-outline-custom p-1 rounded">
                    <span>{{ $tag_filtro['text'] }} <span aria-hidden="true">&times;</span></span>
                </a>
            @endforeach
        </div>
    @endif

    <div class="py-3 d-block d-lg-none">
        <button class="btn btn-outline-secondary" type="button" data-toggle="collapse" data-target="#collapseFiltros" aria-expanded="false" aria-controls="collapseFiltros">
            <i class="fa fa-filter"></i> Filtros
        </button>
    </div>
    <div class="collapse" id="collapseFiltros">
        <div class="py-2">
            <form action="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'id_subsubrubro' => $url['id_subsubrubro'], 'name' => $url['name'], 'page' => 1])}}">
                <input type="hidden" name="q" value="{{ $url['search'] }}">
                <input type="hidden" name="marca" value="{{ $url['id_marca'] }}">
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Ordenar publicaciones</label>
                    <select class="form-control" name="orden" onchange="submit()">
                        <option value="masrelevantes" {{ ($url['orden']=='masrelevantes')? 'selected':'' }}>M&aacute;s relevantes</option>
                        <option value="destacados" {{ ($url['orden']=='destacados')? 'selected':'' }}>destacados</option>
                        <option value="nombreasc" {{ ($url['orden']=='nombreasc')? 'selected':'' }}>nombre ascendente</option>
                        <option value="nombredesc" {{ ($url['orden']=='nombredesc')? 'selected':'' }}>nombre descendente</option>
                        <option value="menorPrecio" {{ ($url['orden']=='menorPrecio')? 'selected':'' }}>Menor precio</option>
                        <option value="mayorPrecio" {{ ($url['orden']=='mayorPrecio')? 'selected':'' }}>Mayor precio</option>
                        <option value="oferta" {{ ($url['orden']=='oferta')? 'selected':'' }}>Oferta</option>
                    </select>
                </div>
            </form>
        </div>
        @if( (isset($filtros['rubros'])) || (isset($filtros['subrubros'])) || (isset($filtros['subsubrubros'])) )
            <div class="py-2">
                <label for="" class="font-weight-bold">Categorías</label>
                <ul class="nav flex-column">
                    @if(isset($filtros['rubros']))
                        @foreach($filtros['rubros'] as $filtro)
                            <li class="nav-item">
                                <a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $filtro['id'] , 'id_subrubro' => 0, 'id_subsubrubro' => 0, 'name' => str_slug($filtro['text']), 'page' => 1])}}{{ ($url['search'])?'?q='.$url['search']:''}}">
                                    <span>{{ $filtro['text'] }}</span>
                                    <small>({{ $filtro['cantidad'] }})</small>
                                </a>
                            </li>
                        @endforeach
                    @endif
                    @if(isset($filtros['subrubros']))
                        @foreach($filtros['subrubros'] as $filtro)
                            <li class="nav-item">
                                <a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $filtro['id'], 'id_subsubrubro' => 0, 'name' => str_slug($filtro['text']), 'page' => 1])}}{{ ($url['search'])?'?q='.$url['search']:''}}">
                                    <span>{{ $filtro['text'] }}</span>
                                    <small>({{ $filtro['cantidad'] }})</small>
                                </a>
                            </li>
                        @endforeach
                    @endif
                    @if(isset($filtros['subsubrubros']))
                        @foreach($filtros['subsubrubros'] as $filtro)
                            <li class="nav-item">
                                <a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'id_subsubrubro' => $filtro['id'], 'name' => str_slug($filtro['text']), 'page' => 1])}}{{ ($url['search'])?'?q='.$url['search']:''}}">
                                    <span>{{ $filtro['text'] }}</span>
                                    <small>({{ $filtro['cantidad'] }})</small>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endif
        @if(isset($filtros['marcas']))
            @if(isset($filtros['marcas'][0]))
                <div class="py-2">
                    <label for="" class="font-weight-bold">Marcas</label>
                    <ul class="nav flex-column">
                        @foreach($filtros['marcas'] as $marca)
                            <li class="nav-item">
                                <a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'id_subsubrubro' => $url['id_subsubrubro'], 'name' => str_slug($marca['text']), 'page' => 1])}}?marca={{$marca['id']}}{{ ($url['search'])?'&q='.$url['search']:''}}">
                                    <span>{{ $marca['text'] }}</span>
                                    <small>({{ $marca['cantidad'] }})</small>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
        @if(isset($filtros['etiquetas']))
            @if(isset($filtros['etiquetas'][0]))
                <div class="py-2">
                    <label for="" class="font-weight-bold">Etiquetas</label>
                    <ul class="nav flex-column">
                        @foreach($filtros['etiquetas'] as $tag)
                            <li class="nav-item">
                                <a class="nav-link font-weight-light" href="{{route('productos',['id_etiqueta' => $tag['id'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'id_subsubrubro' => $url['id_subsubrubro'], 'name' => str_slug($tag['text']), 'page' => 1])}}">
                                    <span>{{ $tag['text'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        @if(isset($filtros['precios']))
            <div class="card my-2">
                <div class="card-header bg-light" id="headingOne">
                    <label for="" class="font-weight-bold">Precio</label>
                </div>
                <div class="card-block">
                    <form class="form" action="{{route('productos',['id_etiqueta' => $url['id_etiqueta'], 'id_rubro' => $url['id_rubro'] , 'id_subrubro' => $url['id_subrubro'], 'id_subsubrubro' => $url['id_subsubrubro'], 'name' => $url['name'], 'page' => 1])}}">
                        <div class="row">
                            <input type="hidden" name="q" value="{{ $url['search'] }}">
                            <input type="hidden" name="marca" value="{{ $url['id_marca'] }}">
                            <div class="col">
                                <input type="text" name="preciomin" class="form-control" placeholder="Minimo">
                            </div>
                            <div class="col">
                                <input type="text" name="preciomax" class="form-control" placeholder="Maximo">
                            </div>
                        </div>
                        <div class="my-2">
                            <button class="btn btn-custom mr-0 float-left">Filtrar</button>
                            <!--<button class="btn btn-custom2 mr-0 float-right">Limpiar</button>-->
                        </div>
                    </form>
                </div>
            </div>
        @endif

    </div>
</aside>