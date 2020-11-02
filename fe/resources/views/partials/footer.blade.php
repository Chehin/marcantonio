<style>
    .img-cace {
        max-width: 100%;
        height: auto;
    }
    .img-afip {
        max-width: 35%;
        height: auto;
    }
    .logos{
        margin-left: -88px !important;
    }
</style>
@if(isset($menu_footer))
<footer class="bg-light  pt-5 border-top">
    <div class="container">
        <div class="row py-4">
            <div class="col-md-3">
                <div class="py-2 px-5">
                    <a href="index.html"><img class="img-fluid rounded-circle" src="img/logo.jpg" alt="logo"></a>
                </div>
                <ul class="nav justify-content-center">
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.linkedin.com/company/marcantonio-deportes-srl/" target="_blank" ><i class="fab fa-linkedin"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://www.instagram.com/marcantoniodeportes/" target="_blank"><i class="fab fa-instagram"></i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="https://www.facebook.com/marcantoniodeportes/" target="_blank"><i class="fab fa-facebook"></i></a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        @if($menu_footer['informacion'])
                        <div class="card-title">
                            <h5>{{$menu_footer['informacion'][0]['seccion']}}</h5>
                        </div>
                        <ul class="nav flex-column">
                            @foreach($menu_footer['informacion'] as $informacion)
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('nota',['id' => $informacion['id_nota'],'name' => str_slug($informacion['titulo'])])}}"></i> {{$informacion['titulo']}}</a>
                            </li>
                              @endforeach
                        </ul>
                         @endif
                    </div>
                    <div class="col-md-4">
                        @if($menu_footer['ayuda'])
                        <div class="card-title">
                            <h5>{{$menu_footer['ayuda'][0]['seccion']}}</h5>
                        </div>
                        <ul class="nav flex-column">
                            @foreach($menu_footer['ayuda'] as $ayuda)
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('nota',['id' => $ayuda['id_nota'],'name' => str_slug($ayuda['titulo'])])}}"></i> {{$ayuda['titulo']}}</a>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if($menu_footer['sucursales'])
                        <div class="card-title">
                            <h5>{{$menu_footer['sucursales'][0]['seccion']}}</h5>
                        </div>
                        <ul class="nav flex-column">
                            @foreach($menu_footer['sucursales'] as $sucursal)
                            <li class="nav-item">
                                <a class="nav-link " href="{{route('nota',['id' => $sucursal['id_nota'],'name' => str_slug($sucursal['titulo'])])}}"></i>{{$sucursal['titulo']}}</a>
                            </li>
                            @endforeach
                        </ul>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border-top border-secondary">
        <div class="container">
            <div class="row pt-4 pb-3">
                <div class="col-md-6">
                    <li style="float:left; width:35%;"> <a href="https://qr.afip.gob.ar/?qr=X3CZePJxn2TIh0PafkNvFA,," target="_F960AFIPInfo"><img class="img-afip"src="https://www.afip.gob.ar/images/f960/DATAWEB.jpg" border="0"></a>
                    </li>
                    <li class="logos" style="float:left; width:50%;"><a class="nav-link" href="https://www.cace.org.ar/" target="_blank" title="Cámara Argentina de Comercio Electrónico">
                        <img class="img-cace" src="img/cace.png" alt="Cámara Argentina de Comercio Electrónico">
                    </a></li>
                </div>

                <div class="col-md-6">
                    <div class="float-right text-right">
                        <p>©<span>Marcantonio Deportes</span> 2020 | Todos los derechos reservados</p>
                    </div>
                    <div class="webexport float-right text-right">
                        <p>Desarrollado por <a href="https://webexport.com.ar/" class="text-info">WebExport</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="whatsapp-globo">
        <div class="shopping-cart-trigger">
            <a href="https://api.whatsapp.com/send?phone=5493816070555" target="_blank" class="cart-icon" title="Contacto por Whatsapp">
                <img src="img/whatsapp_logo.png" width="30" height="30" />
            </a>
        </div>
    </div>
</footer>

@endif