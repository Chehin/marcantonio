<!-- Mas Vistos -->
@if(isset($ProductosMasVistos))
    <section class="border-top py-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="title">Los Más Vistos</h2>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => 'productos', 'page' => 1])}}"><i class="fa fa-list"></i> Ver más productos</a>
                </div>
            </div>
            <div class="owl-carousel owl-theme owl-loaded productos-carousel">
                @foreach($ProductosMasVistos as $Producto)
                    @if($Producto['Stock']>0)
                        @include('productos.itemProducto',['Producto'=> $Producto])
                    @endif
                @endforeach
            </div>
        </div>
        </div>
    </section>
@endif