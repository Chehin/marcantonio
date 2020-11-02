
@if($TotalPaginas > 1)
    @if(isset($extraParams['url']))
        @foreach($extraParams['url'] as $key => $value)
                @php
                    $params[$key] = $value;
                @endphp
        @endforeach
    @endif
    <nav aria-label="Paginado">
        <ul class="pagination justify-content-center">
            @if($page > 1)
            @php
                $params['page'] = $page - 1;
            @endphp
            <li class="page-item">
                <a class="page-link" href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}" tabindex="-1">Anterior</a>
            </li>
            @endif
            @for($x = 1; $x <= $TotalPaginas; $x++)
            @if(($x>=$page-3) && $x<=$page+3)
            @php $params['page'] = $x; @endphp
            <li class="page-item @if($x == $page) active @endif">
                <a class="page-link" href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}">{{$x}}</a>
            </li>
            @endif
            @endfor
            @if($page != $TotalPaginas)
            @php
            $params['page'] = $page + 1;
            @endphp
            <li class="page-item">
                <a class="page-link" href="{{ route( $nameRoute, $params) }}{{ $extraParams['getData']?'?'.http_build_query($extraParams['getData']):'' }}">Siguiente</a>
            </li>
            @endif
        </ul>
    </nav>
    </div>
</div>
@endif
