
{{--@php \Log::debug(print_r($etiquetas_rubros,true)) @endphp--}}
@if(isset($menu_web))
<header class="sticky-top pt-2 bg-custom2">
    <a class="my-auto ml-3 position-absolute d-none d-lg-block" style="top:10px; left:10px;z-index:100;" href="{{route('home')}}">
        <img class="rounded-circle" src="img/logo.jpg" height="100" alt="logo Marcantonio Deportes">
    </a>
    <div class="row">
        <div class="col-12 p-lg-0">
            <nav class="navbar navbar-expand-lg navbar-dark">

                <a class="navbar-brand d-block d-lg-none" href="{{route('home')}}">
                    <img class="rounded-circle" src="img/logo.jpg" height="80" alt="logo Marcantonio Deportes">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">

                    <ul class="navbar-nav">
                        @if(isset($etiquetas_rubros))
                            @foreach($etiquetas_rubros as $etiqueta)
                                <li class="nav-item dropdown pb-1">
                                    <a class="nav-link text-light text-uppercase" href="{{route('productos',['id_etiqueta' => $etiqueta['id_etiqueta'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['etiqueta']), 'page' => 1])}}">{{$etiqueta['etiqueta']}}</a>
                                    <div class="dropdown-mega-menu dropdown-menu border-0 bg-light" aria-labelledby="navbarDropdown">
                                        <div class="container">
                                            <div class="row w-100">
                                                <div class="col-md-12 offset-md-1">
                                                    <div class="row" style="margin-left: 60px">
                                                    @if($etiqueta['rubros'])
                                                        @foreach($etiqueta['rubros'] as $rubro)
                                                                <div class="dropdown" id="headerRubros" style="border-radius: 5px; border-style: solid; border-width: 2px; border-color: white;">

                                                                    <a class="nav-link text-uppercase" href="{{route('productos',['id_etiqueta' => $etiqueta['id_etiqueta'], 'id_rubro' => $rubro['id'] , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['etiqueta']), 'page' => 1])}}" style="color: #3a4246; font-weight: bold; background: #e9ecef;">{{$rubro['nombre']}}</a>
                                                                    <ul style="width: 250px">
                                                                        <div class="container">
                                                                            <div class="row w-100">
                                                                                <div class="col-md-12 offset-md-1" style="left: -55px;">
                                                                                @if (isset($rubro['subrubros']))
                                                                                    @foreach($rubro['subrubros'] as $subrubro)
                                                                                    <div class="py-1">
                                                                                        <a href="{{route('productos',['id_etiqueta' => $etiqueta['id_etiqueta'], 'id_rubro' => $rubro['id'] , 'id_subrubro' => $subrubro['id'], 'name' => str_slug($subrubro['nombre']), 'page' => 1])}}">{{$subrubro['nombre']}}</a>
                                                                                    </div>
                                                                                    @endforeach
                                                                                @endif
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </ul>
                                                                </div>
                                                        @endforeach
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @endif

                       @if(isset($menu_web['rubros']))
                            @foreach($menu_web['rubros'] as $rubro)
                        <li class="nav-item dropdown pb-1">
                            <a class="nav-link text-light text-uppercase" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $rubro['id'] , 'id_subrubro' => 0, 'name' => str_slug($rubro['nombre']), 'page' => 1])}}">{{$rubro['nombre']}}</a>
                            <div class="dropdown-mega-menu dropdown-menu border-0 bg-light" aria-labelledby="navbarDropdown">
                                <div class="container">
                                    <div class="row w-100">
                                        <div class="col-md-10 offset-md-1">
                                            <div class="row ">
                                                @foreach($rubro['subrubros'] as $subrubro)
                                                    <div class="col-md-3 py-2">
                                                        <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => $rubro['id'] , 'id_subrubro' => $subrubro['id'], 'name' => str_slug($subrubro['nombre']), 'page' => 1])}}">{{$subrubro['nombre']}}</a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                            @endforeach
                        @endif
{{--                         -- MARCAS--}}
                        <li class="nav-item dropdown pb-1">
                            <a class="nav-link text-light text-uppercase" href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}">Marcas</a>
                            <div class="dropdown-mega-menu dropdown-menu border-0 bg-light">
                                <div class="container">
                                    <div class="row w-100">
                                        <div class="col-md-8 offset-md-2">
                                            <div class="row">
                                                @if(isset($menu_web['marcas']))
                                                    @foreach($menu_web['marcas'] as $marca)
                                                    <div class="col-md-2 py-2">
                                                        <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($marca['nombre']), 'page' => 1])}}?IdMarca={{ $marca['id'] }}">{{$marca['nombre']}}<img src="{{(isset($marca['imagen_file']))? env('URL_BASE').'uploads/marcas/'.$marca['imagen_file']:env('URL_BASE').'img/logo.png'}}" alt="{{$marca['imagen']}}"
                                                                                class="img-thumbnail"></a>
                                                    </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <div class="dropdown-divider"></div>
                            @if (isset($_SESSION['email']))
                                <li class="nav-item d-block d-lg-none">
                                    <a href="{{ route('cuenta') }}" class="nav-link text-light"><i class="fa fa-user"></i> Mi Cuenta</a>
                                </li>
                                <li class="nav-item d-block d-lg-none">
                                    <a href="{{ route('logout') }}" class="nav-link text-light"><i class="fas fa-sign-out-alt"></i> Cerrar Session</a>
                                </li>
                           @else
                                <li class="nav-item d-block d-lg-none">
                                    <a href="{{ route('login') }}" class="nav-link text-light"><i class="fa fa-user"></i> Ingresar</a>
                                </li>
                                <li class="nav-item d-block d-lg-none">
                                    <a href="{{ route('registro') }}" class="nav-link text-light"><i class="fas fa-sign-in-alt"></i> Registrarse</a>
                                </li>
                           @endif

                    </ul>

                </div>
                <form class="form-inline m-2 my-lg-0 buscarMovil" action="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug('productos'), 'page' => 1])}}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="q"  placeholder="¿Que estas buscando?"
                               aria-label="search" aria-describedby="btn-search" value="{{isset($search)?($search?urldecode($search):''):''}}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-light" type="submit" id="btn-search2">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <div class="cartMovil"> {{--carrito movil--}}
                <li class="nav-item dropdown d-lg-block">
                    <a class="nav-link text-light cart-icon2" href="{{ route('cart') }}"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-shopping-cart"></i><sup class="badge badge-danger rounded-circle"><span class="cart-items">0</span></sup>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="width: max-content; max-width: 90vw;">
                        <div class="py-2 px-3">

                            @include('partials.carrito')

                        </div>
                    </div>
                </li>
                </div>
            </nav>
        </div>

        <div class="col-10 offset-2 d-none d-lg-block">
            <nav class="navbar navbar-expand-lg navbar-dark">
                <ul class="navbar-nav">
                    <div class="btn-group">
                        @foreach($menu_web['etiquetas'] as $etiqueta)
                        <a class="nav-link btn btn-{{$etiqueta['color']}} text-light" href="{{route('productos',['id_etiqueta' => $etiqueta['id'], 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug($etiqueta['nombre']), 'page' => 1])}}">{{$etiqueta['nombre']}}</a>
                        @endforeach
                    </div>
                </ul>

                <ul class="navbar-nav mr-0 ml-auto">
                    <form class="form-inline m-2 my-lg-0" action="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug('productos'), 'page' => 1])}}">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" id="q" placeholder="¿Que estas buscando?"
                                   aria-label="search" aria-describedby="btn-search" value="{{isset($search)?($search?urldecode($search):''):''}}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-light" type="submit" id="btn-search">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <li class="nav-item dropdown d-none d-lg-block">
                        <a class="nav-link text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        @if (isset($_SESSION['email']))
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('cuenta') }}">Mi cuenta</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}">Cerrar Session</a>
                        </div>
                            @else
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('login') }}">Ingresar</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('registro') }}">Registrarse</a>
                            </div>
                            @endif
                    </li>
                    <li class="nav-item dropdown d-none d-lg-block">
                        <a class="nav-link text-light cart-icon" href="{{ route('cart') }}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-shopping-cart"></i><sup class="badge badge-danger rounded-circle"><span class="cart-items">0</span></sup>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="width: max-content; max-width: 90vw;">
                            <div class="py-2 px-3">

                                    @include('partials.carrito')

                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="cart-movil d-block d-lg-none position-absolute p-1" style="right: 0;">
                <div class="nav-item dropdown bg-danger rounded-circle py-1">
                    <div class="">
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger rounded-circle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-shopping-cart"></i><sup class="badge badge-secondary rounded-circle"><span class="cart-items">0</span></sup>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item" type="button">Action</button>
                                <button class="dropdown-item" type="button">Another action</button>
                                <button class="dropdown-item" type="button">Something else here</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
    @endif
