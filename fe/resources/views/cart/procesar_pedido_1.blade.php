@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')

    <style>
        .headerOffset{
            margin-top: 10px;
        }


        /****************************************
        33.1 Procesar Pedido
        *****************************************/

        .no-border{
            border: none !important;
        }

        .a-left {
            text-align: left !important
        }
        .a-center {
            text-align: center !important
        }
        .a-right {
            text-align: right !important
        }

        #procesar-pedido-container{
            border-left: none;
            border-right: none;
            margin-top: 0;
            margin-bottom: 0;
            padding: 0;
        }

        .camino_compra{
            margin-top: 20px;
        }

        .camino_compra .pasos{
            padding-left: 30px;
        }

        .data-compra{
            margin-bottom: 20px
        }

        .resumen_compra{
            background: #f5f5f5;
            padding-top: 50px;
        }

        #procesar-pedido-container .camino_compra .disabled .box_paso{
            background: #0B0B3B;
        }

        #btn_tp{
            background: #CF3341;
            border-color: #CF3341;
            color: #FFF;
        }

        #btn_tp:hover{
            background: #222;
            border-color: #222;
            color: #FFF;
        }

        .disabled #btn_tp{
            background: #e6c8e4;
            border-color: #e6c8e4;
        }

        .disabled #btn_tp:hover{
            background: #e6c8e4;
            border-color: #e6c8e4;
        }

        #btn_mp{
            background: #009ee3;
            border-color: #009ee3;
            color: #FFF;
        }

        #btn_mp:hover{
            background: #222;
            border-color: #222;
            color: #FFF;
        }

        .disabled #btn_mp{
            background: #c8cae6;
            border-color: #c8cae6;
        }

        .disabled #btn_mp:hover{
            background: #c8cae6;
            border-color: #c8cae6;
        }

        .box_paso{
            background: #065091;
            color: #fff;
            float: left;
            font-size: 21px;
            width: 40px;
            height: 40px;
            display: flex;
            margin-bottom: 10px;
        }

        .box_paso p{
            margin: auto;
            color: #fff;
        }

        .text_paso{
            float: left;
        }

        .shopping-cart-table-total{
            border:0;
        }

        .detalle_pedido{
            margin-top: 20px;
        }

        .divmercado_pago{
            padding: 0;
            margin-bottom: 10px;
        }
        /*********** Fin PROCESAR PEDIDO **********/
    </style>

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Carrito</li>
        </ol>
    </nav>
    <!-- Breadcrumbs End -->

        <section class="row col1-layout">
            <div class="main container">
                @if($_SESSION['carrito']==NULL)
                    <div class="alert alert-danger alert-dismissable">
                        <span class="alert-icon"><i class="fa fa-warning"></i></span>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>¡Atención!</strong>
                    </div>
                @endif
                <div class="col-main">
                    <div class="cart cart_box">
                        <div class="row">
                            <div class="page-title">
                                <h2>Confirmar compra</h2>
                            </div>
                        </div>


                        <div id="procesar-pedido-container" class="page-content page-order">

                            <div class="row">

                                <div class="col-sm-12" id="alerta_envio"></div>
                                <div class="col-sm-9">
                                    <div class="camino_compra hidden-xs">
                                        <div class="row">
                                            <div class="col-sm-4 pasos paso1">
                                                <div class="row">
                                                    <div class="box_paso">
                                                        <p>1</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <p class="text_paso">Envío</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pasos paso2 disabled">
                                                <div class="row">
                                                    <div class="box_paso">
                                                        <p>2</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <p class="text_paso">Facturación</p>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 pasos paso3 disabled">
                                                <div class="row">
                                                    <div class="box_paso">
                                                        <p>3</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <p class="text_paso">Pago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="data-compra">
                                        <div class="row">
                                            <div class="col-sm-4 pasos paso1">
                                                <div class="row">
                                                    <div class="cart-collaterals">
                                                        <div class="col-sm-12">
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="country" class="required">Dirección de envío</label>
                                                                    <div class="input-box">
                                                                        <select class="form-control" id="direccion_envio" name="direccion_envio" required >
                                                                            <option value="">Seleccionar</option>
                                                                            <option value="-1">Retiro GRATIS de sucursal de Marcantonio Deportes</option>
                                                                            @foreach($getDireccionEnvio as $cp=>$nombre_direccion)
                                                                                <option value="{!!$cp!!}">{!!$nombre_direccion!!}</option>
                                                                            @endforeach
                                                                            <option value="nueva">Agregar nueva dirección</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="divsucursal" class="col-sm-12" style="margin-top: 20px; display:none"><!--hidden-->
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="country" class="required">Seleccionar sucursal</label>
                                                                    <div class="input-box">
                                                                        <select class="form-control" id="sucursal" name="sucursal" required disabled>
                                                                            <option value="">Seleccionar</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="hidden" id="sucursal_envio"></div>
                                                        </div>
                                                        <div id="divtipo_envio" class="col-sm-12" style="margin-top: 20px;"><!--hidden-->
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="country" class="required">Tipo de envío</label>
                                                                    <div class="input-box">
                                                                        <select class="form-control" id="tipo_envio" name="tipo_envio" required>
                                                                            <option value="">Seleccionar</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="hidden" id="costos_envio"></div>
                                                            <div class="hidden" id="costos_envio_andreani"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 pasos pasos2">
                                                <div class="row">
                                                    <div class="cart-collaterals">
                                                        <div id="dni_add" class="col-sm-12" style="">
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="cuit" class="required">DNI *</label>
                                                                    <div class="input-box">
                                                                        <input id="dni" name="dni" type="number" onkeypress="validate(event)" class="form-control" value="{{ (isset($_SESSION['dni']))? $_SESSION['dni'] : '' }}" required disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="telefono_add" class="col-sm-12" style="margin-top: 20px;">
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="telefono" class="required">Teléfono *</label>
                                                                    <div class="input-box">
                                                                        <input id="telefono" name="telefono" type="number" onkeypress="validate(event)" class="form-control" value="{{ (isset($_SESSION['telefono']))? $_SESSION['telefono'] : '' }}" required disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="divnombre" class="col-sm-12" style="margin-top: 20px;"><!--hidden-->
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="cuit" class="required">Nombre* <small style="">de la persona que retira o recibe</small></label>
                                                                    <div class="input-box">
                                                                        <input id="nombre_retira" name="nombre" type="text" class="form-control" value="" required disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-sm-12">
                                                            <input type="hidden" id="datos_errores" name="datos_errores" value="0">
                                                            <div id="alerta_datos" class="alert alert-danger alert-dismissable" style="margin-top:20px; display:none;">
                                                                <span class="alert-icon"><i class="fa fa-warning"></i></span>
                                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                                                                <strong>Datos Invalidos</strong>
                                                            </div>
                                                        </div>

                                                        {{-- <div class="col-sm-12" style="margin-top: 20px;">
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="country" class="required">Dirección de facturación</label>
                                                                    <div class="input-box">
                                                                        <select class="form-control" id="direccion_fact" name="direccion_fact" required disabled>
                                                                            <option value="">Seleccionar</option>
                                                                            @foreach($getDireccionEnvio as $cp=>$nombre_direccion)
                                                                                <option value="{!!$cp!!}">{!!$nombre_direccion!!}</option>
                                                                            @endforeach
                                                                            <option value="nueva">Agregar nueva dirección</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        {{-- <div id="divtipo_facturacion" class="col-sm-12" style="margin-top: 20px;">
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="tipo_facturacion" class="required">Tipo de facturación</label>
                                                                    <div class="input-box">
                                                                        <select class="form-control" id="tipo_facturacion" name="tipo_facturacion" required disabled>
                                                                            <option value="">Seleccionar</option>
                                                                            <option value="Consumidor Final">Consumidor Final</option>
                                                                            <option value="Responsable Inscripto">Responsable Inscripto</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        {{-- <div id="cuit_add" class="col-sm-12" style="margin-top: 20px;"><!--hidden-->
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="cuit" class="required">Cuit/Cuil (solo números)</label>
                                                                    <div class="input-box">
                                                                        <input id="cuit" name="cuit" type="text" class="form-control" value="" required disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                        {{-- <div id="razon_social_add" class="col-sm-12" style="margin-top: 20px;" ><!--hidden-->
                                                            <div class="shipping">
                                                                <div class="shipping-form">
                                                                    <label for="cuit" class="required">Razón Social</label>
                                                                    <div class="input-box">
                                                                        <input id="razon_social" name="razon_social" type="text" class="form-control" value="" required disabled>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 pasos pasos3">
                                                <div class="row">
                                                    <div class="cart-collaterals">
                                                        <div class="col-sm-12">
                                                            <div id="medios_pago" class="disabled">
                                                                <label class="required" for="medios_pago">Pagar con</label>
                                                                <div class="">
                                                                    <div class="divmercado_pago text-center col-xs-6 col-sm-12">
                                                                        <button onclick="checkoutmp()" target="_blank" id="btn_mp" href="" name="MP-Checkout" class="btn button" mp-mode="redirect" disabled>Mercado Pago</button>

                                                                        <img src="https://imgmp.mlstatic.com/org-img/banners/ar/medios/online/468X60.jpg" class="img-fluid"  title="Mercado Pago - Medios de pago" alt="Mercado Pago - Medios de pago"/>
                                                                    </div>
{{--                                                                    @if(isset($_COOKIE['CookieRestApiWeb_marcantoniodep']))--}}
                                                                    <div class="actions text-center col-xs-6 col-sm-12 mt-10">
                                                                        <button href="" id="btn_tp" class="btn button boton-rosa" disabled>Todo Pago</button>

                                                                        <img src="https://todopago.com.ar/sites/todopago.com.ar/files/banner_cuotas-4087x1062.png" alt="" class="img-fluid"/>
                                                                    </div>
{{--                                                                    @endif--}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-sm-3 resumen_compra">
                                    <div class="page-title">
                                        <h2>RESUMEN</h2>
                                    </div>
                                    <form id="procesar_compra_form" class="container" method="POST">
                                        <div class="cart-collaterals">

                                            <input type="hidden" name="procesar_pedido" value="1" />
                                            <input type="hidden" id="cost_andreani" name="cost_andreani">
                                            <input type="hidden" id="precio_db" value="{!! $_SESSION['carrito']['subtotal']['precio_db'] !!}" />
                                            <input type="hidden" id="envio_db" name="envio_db" value=" @if(isset($_SESSION['carrito']['envio']['costo_envio'])) {!!$_SESSION['carrito']['envio']['costo_envio']!!} @else 0 @endif" />
                                            <input type="hidden" name="id_tipo_envio" id="id_tipo_envio" value=" @if(isset($_SESSION['carrito']['envio']['id_tipo_envio']) ) {!! $_SESSION['carrito']['envio']['id_tipo_envio'] !!} @endif " />
                                            <input type="hidden" name="id_sucursal" id="id_sucursal" value=" @if(isset($_SESSION['carrito']['envio']['id_sucursal']) ) {!! $_SESSION['carrito']['envio']['id_sucursal'] !!} @endif " />
                                            <input type="hidden" name="fecha_sucursal" id="fecha_sucursal" value=" @if(isset($_SESSION['carrito']['envio']['fecha_sucursal']) ) {!! $_SESSION['carrito']['envio']['fecha_sucursal'] !!} @endif " />
                                            <input type="hidden" name="id_direccion_envio" id="id_direccion_envio" value="" />
                                            <input type="hidden" name="id_direccion_fact" id="id_direccion_fact" value="" />
                                            <input type="hidden" name="dni_data" id="dni_data" value="@if(isset($_SESSION['dni']) ) {!! $_SESSION['dni'] !!} @endif " />
                                            <input type="hidden" name="telefono_data" id="telefono_data" value="@if(isset($_SESSION['telefono']) ) {!! $_SESSION['telefono'] !!} @endif " />
                                            <input type="hidden" name="nombre_data" id="nombre_data" value="@if(isset($_SESSION['nombre']) ) {!! $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] !!} @endif "/>
                                            <input type="hidden" name="razon_social_data" id="razon_social_data" value="" />
                                            <input type="hidden" name="tipo_facturacion_data" id="tipo_facturacion_data" value="" />
                                            <div class="totals box_prices" style="display: none;">
                                                <div class="inner">
                                                    <table id="shopping-cart-totals-table" class="table shopping-cart-table-total cart_summary">
                                                        <tbody>
                                                        <tr class="cart-total">
                                                            <td style="" class="a-left" colspan="1"> Subtotal </td>
                                                            <td style="" class="a-right subtotal-t"><span class="subtotal"></span></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="" class="a-left" colspan="1"> Envío </td>
                                                            <td style="" class="a-right envio-col"><span class="price">$</span></td>
                                                        </tr>
                                                        <tr class="cart-total">
                                                            <td style="" class="a-left" colspan="1"> <b>Total</b> </td>
                                                            <td style="" class="a-right total-row"><span class="total"></span></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--inner-->
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row detalle_pedido">
                            <div class="col-sm-12">
                                <div class="page-title">
                                    <div class="row">
                                        <h2>DETALLE DE PEDIDO</h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <tr>
                                                    <th colspan="3" class="cart_product">Productos agregados</th>
                                                </tr>
                                                </thead>
                                                <tbody id="cart-product-list">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
        @stop
        @section('javascript')

            <script>

                $(document).ready(function(){
                    $("select.form-control#direccion_envio").change(function(){
                        var dirEnvio = $(this).children("option:selected").val();
                        if (dirEnvio == -1){

                            gtag('event', 'set_checkout_option', {
                                "checkout_step": 1,
                                "checkout_option": "Envio",
                                "value": "Retiro en Sucursal"
                            });

                        }else{
                            gtag('event', 'set_checkout_option', {
                                "checkout_step": 1,
                                "checkout_option": "Direccion de envio",
                                "value": "Envio a domicilio"
                            });
                        }

                    });
                });

/*                function gtagtipoenvio() {
                    $("select.form-control#tipo_envio").change(function(){
                        var selectedCountry = $(this).children("option:selected").text();
                        alert("You have selected the country - " + selectedCountry);
                    });
                }*/

                function validate(evt) {
                var theEvent = evt || window.event;

                // Handle paste
                if (theEvent.type === 'paste') {
                    key = event.clipboardData.getData('text/plain');
                } else {
                    // Handle key press
                    var key = theEvent.keyCode || theEvent.which;
                    key = String.fromCharCode(key);
                }
                var regex = /[0-9]|\./;
                if( !regex.test(key) ) {
                    theEvent.returnValue = false;
                    if(theEvent.preventDefault) theEvent.preventDefault();
                }
            }

                @if($_SESSION['carrito']!=NULL)

                gtag('event', 'begin_checkout', {
                    "items": [
                            @foreach($_SESSION['carrito']['carrito'] as $Producto)
                        {
                            "id": {!! $Producto['id_producto']!!} ,
                            "name": {!! "'" .$Producto['titulo']."'" !!},

                            "quantity": {!!  $Producto['cantidad']!!} ,
                            "price": {!!(isset($Producto['precio']['precio_lista']) && $Producto['precio']['precio_lista']<$Producto['precio']['precio'] && $Producto['precio']['precio_lista']>0)?$Producto['precio']['precio_lista']:$Producto['precio']['precio']!!},
                        },
                        @endforeach
                    ],
                    "coupon": ""
                });
                @endif

                function checkoutmp() {

                    gtag('event', 'checkout_progress', {
                        "items": [
                                @foreach($_SESSION['carrito']['carrito'] as $Producto)
                            {
                                "id": {!! $Producto['id_producto']!!} ,
                                "name": {!! "'" .$Producto['titulo']."'" !!},

                                "quantity": {!!  $Producto['cantidad']!!} ,
                                "price": {!!(isset($Producto['precio']['precio_lista']) && $Producto['precio']['precio_lista']<$Producto['precio']['precio'] && $Producto['precio']['precio_lista']>0)?$Producto['precio']['precio_lista']:$Producto['precio']['precio']!!},
                            },
                            @endforeach
                        ],
                    });
                }

            </script>

    @stop
