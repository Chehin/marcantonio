@if(isset($Producto))
    <div class="card item-producto">
        @if($Producto['Descuento']>0)
            <span class="sale">{{$Producto['Descuento'].'%'}}</span>
        @endif

        <a href="{{ route('producto',['id' => $Producto['Id'],'name' => str_slug($Producto['Producto'])]) }}"><img src="{{(isset($Producto['ArchivoImagen']))? env('URL_BASE').'uploads/productos/'.$Producto['ArchivoImagen']:env('URL_BASE').'uploads/productos/default.jpg'}}" class="card-img-top"
                                                                                                                   alt="{{$Producto['Producto']}}"></a>
        <form action="javascript:void(0)">
            <div class="card-body">
                <h5 class="card-title">{{'$'.$Producto['PrecioVenta']}} <del class="text-danger small">{{($Producto['PrecioLista']>0)?'$'.$Producto['PrecioLista']:''}}</del></h5>
                <p class="card-text"><a href="{{ route('producto',['id' => $Producto['Id'],'name' => str_slug($Producto['Producto'])]) }}" class="text-dark">{{$Producto['Producto']}}</a></p>
                <input type="hidden" name="stock" value="{{ $Producto['Stock'] }}" />
                <input type="hidden" name="id" value="{{ $Producto['Id'] }}"/>
                <input type="hidden" name="idProd" value="{{ (isset($Producto['Id']))?$Producto['Id']:0 }}"/>
                <input type="hidden" name="id_marca" value="{{ (isset($Producto['id_marca']))?$Producto['id_marca']:0 }}"/>
                <input type="hidden" name="id_genero" value="{{ (isset($Producto['id_genero']))?$Producto['id_genero']:0 }}"/>
                <input type="hidden" name="id_color" value="0" class="color_prod" />
                <input type="hidden" name="id_talle" value="0" class="talle_prod" />
                <input type="hidden" name="nombre_producto" value="{{ (isset($Producto['nombre']))?$Producto['nombre']:'' }}" />

                @if ($Producto['Stock']>0)
                    <div style="text-align: center;">
                    <a href="{{ route('producto',['id' => $Producto['Id'],'name' => str_slug($Producto['Producto'])]) }}" class="btn btn-outline-secondary m-0"><i class="fa fa-check hidden-xs"></i> Comprar</a>
                    </div>
                @else
                    <a href="{{ route('producto',['id' => $Producto['Id'],'name' => str_slug($Producto['Producto'])]) }}" class="nav-link btn btn-danger text-light">Producto Sin Stock</a>
                @endif
            </div>
        </form>
    </div>
@endif
