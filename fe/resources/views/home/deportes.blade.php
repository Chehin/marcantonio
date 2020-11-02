<!-- Deportes -->
@if($Deportes)

<section id="deportes" class="bg-light">
    <div class="container py-5 px-5">
        <div class="col-12 text-center my-3">
            <h2 class="title">¿Que Deporte Practicas?</h2>
        </div>
        <div class="row">
            <div class="col-6 col-md-3 p-0">
                @if(isset($Deportes[0]))

                <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$Deportes[0]['Id']}}">
                    <div class="card border-0">
                        <img class="img-fluid" src="img/uploads/Running.jpg" alt="">
                        <h5>{{$Deportes[0]['Deporte']}}</h5>
                    </div>
                </a>
                    @php  unset($Deportes[0]) @endphp
                @endif
            </div>
            <div class="col-6 col-md-3 p-0">
                @if(isset($Deportes[1]))

                <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$Deportes[1]['Id']}}">
                    <div class="card border-0">
                        <img class="img-fluid" src="img/uploads/Futbol.jpg" alt="">
                        <h5>{{$Deportes[1]['Deporte']}}</h5>
                    </div>
                </a>
                    @php  unset($Deportes[1]) @endphp
                    @endif
            </div>
            <div class="col-6 col-md-3 p-0">
                @if(isset($Deportes[2]))
                <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$Deportes[2]['Id']}}">
                    <div class="card border-0">
                        <img class="img-fluid" src="img/uploads/Trecking.jpg" alt="">
                        <h5>{{$Deportes[2]['Deporte']}}</h5>
                    </div>
                </a>
                    @php  unset($Deportes[2]) @endphp
                    @endif
            </div>
            <div class="col-6 col-md-3 p-0">
                @if(isset($Deportes[3]))
                <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$Deportes[3]['Id']}}">
                    <div class="card border-0">
                        <img class="img-fluid" src="img/uploads/Fitness.jpg" alt="">
                        <h5>{{$Deportes[3]['Deporte']}}</h5>
                    </div>
                </a>
                    @php  unset($Deportes[3]) @endphp
                 @endif
            </div>
        </div>
        @if(isset($Deportes[4]))
        <div class="collapse" id="masDeportes">
            <div class="row">
                @foreach($Deportes as $Deporte)
                <div class="col-6 col-md-3 p-0">
                    <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}?IdDeporte={{$Deporte['Id']}}">
                        <div class="card border-0">
                            <img class="img-fluid" src="img/uploads/Fitness.jpg" alt="">
                            <h5>{{$Deporte['Deporte']}}</h5>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center pt-5">
                <button class="btn btn-secondary" data-toggle="collapse" data-target="#masDeportes"
                        aria-expanded="false" aria-controls="masDeportes">
                    <i class="fa fa-plus"></i> Más Deportes
                </button>
            </div>
        </div>
    </div>
    @endif
</section>

@endif