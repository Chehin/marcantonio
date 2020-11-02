@if(isset($Servicios))

<section class="bg-custom">
    <div class="container py-5">
        <div class="row">
            @foreach($Servicios as $Servicio)
            <div class="col-md-4 py-5">
                <a class="text-decoration-none" href="">
                    <div class="card text-center">
                        <div class="position-relative m-auto icon-circle-card">
                            <div class="rounded-circle bg-dark text-light p-3">
                                <i class="fa {{$Servicio['icono']}} fa-2x"></i>
                            </div>
                        </div>
                        <div class="card-body pt-0 hidden-xs">
                            <h5 class="card-title ">{{$Servicio['titulo']}}</h5>
                            <p class="text-muted">{{$Servicio['texto']}}</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endif
<section class="parallax py-5" style="background-image: url(./img/uploads/parallax.jpg);">
    <div class="container">
        <div class="row py-5">
            <div class="col-sm-6 offset-sm-6 col-md-5 offset-md-7">
                <div class="card bg-none border-0">
                    <div class="card-body text-right text-light">
                        <h3 class="title">Los Mejores precios</h3>
                        <p class="">Comprá hoy zapatillas, ropa, indumentaria y conocé las nuevas colecciones de adidas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>