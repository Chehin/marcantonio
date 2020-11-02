@extends('master')

@section('pageTitle') {{$pageTitle}} @stop

@section('content')
<div class="page-loader cart">
	<div class="spinner">
		<div class="dot1"></div>
		<div class="dot2"></div>
	</div>
</div>
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
		<li class="breadcrumb-item active" aria-current="page">Carrito</li>
	</ol>
</nav>
	<!-- Breadcrumbs End -->

	<div class="gap"></div>
	<!-- Main Container -->
	<section>
		<div class="main container">
			@if($_SESSION['carrito']==NULL)
			<div class="alert alert-danger alert-dismissable">
				<span class="alert-icon"><i class="fa fa-warning"></i></span>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>¡Atención!</strong> Error inesperado
			</div>
            @endif

			<div class="col-main">
				<div class="cart cart_box">
					<div class="page-content page-order row">
						<div class="col-sm-8 clearfix">
							<div class="title">
								<div class="row">
									<h2>CARRITO</h2>
								</div>
							</div>
							<div class="dropdown cart_box cartMenu" style="width:100%;">
								<div class="row cart_box">
									<div class="order-detail-content container">
										<div class="dropdown cart-dropdown dcart-products table-responsive" style="float:left; width:100%;">
											<div class="w100">
                                                <table class="cartTable table-responsive" style="width:100%">
                                                    <tr class="CartProduct cartTableHeader alternate-color">
                                                        <td style="width:10%"></td>
                                                        <td style="width:30%">Producto</td>
                                                        <!-- <td style="width:10%">Cant.</td>-->
														
														<td style="width:50%"> Descripcion</td>
                                                    </tr>
													<tbody id="cart-product-list"></tbody>
													<tfoot>
														<tr class="first last text-center alternate-color">
															<td colspan="5" class="a-right last">
																<br>
																<a title="Continuar comprando" class="btn btn-success"
																 href="{{route('productos',['id_etiqueta' => 0, 'id_rubro' => 0 , 'id_subrubro' => 0, 'name' => str_slug('Productos'), 'page' => 1])}}"><span>Continuar comprando</span></a>
															</td>
														</tr>
													</tfoot>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4 clearfix">
							<div class="title">
								<h2>RESUMEN</h2>
							</div>
							<div class="cart-collaterals">
								<div class="totals box_prices" style="display: none;">
									<div class="inner">
										<table id="shopping-cart-totals-table" class="table shopping-cart-table-total table-bordered cart_summary">
											<colgroup>
												<col>
												<col width="1">
											</colgroup>
											<tbody>
												<tr class="cart-total">
													<td style="" class="a-left" colspan="1"> Subtotal </td>
													<td style="" class="a-right"><span class="subtotal"></span></td>
												</tr>
												<tr class="cart-envio">
													<td style="" class="a-left" colspan="1"> Envío </td>
													<td style="" class="a-right"><span class="price">A calcular</span></td>
												</tr>
												<tr class="cart-total">
													<td style="" class="a-left" colspan="1"> <b>Total</b> </td>
													<td style="" class="a-right"><span class="total"></span></td>
												</tr>
											</tbody>
										</table>
										<ul class="checkout" style="list-style:none">
											<br>
											<li>
												<a title="Procesar compra" class="btn btn-custom" href="{{route('procesar_pedido',['id'=>1])}}"><span>Finalizar
														pedido</span></a>
														<button type="button" title="Calcular envío" class="btn btn-custom2"  data-toggle="modal" data-target="#modal-envio"><span><i class="fa fa-truck"></i> Calcular envío</span></button>
											</li>
											<br>

										</ul>
									</div>
									<!--inner-->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		
		
		
		
		
		<!-- Modal -->
		<div class="modal fade" id="modal-envio" tabindex="-1" role="dialog" aria-labelledby="modal-envioLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="modal-envioLabel">Consultar costo envio</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <div class="row">
				<div class="col-12">
					<form action="javascript:void(0);" class="form-inline" id="consulta_form">
					  <div class="form-group mx-sm-3 mb-2">
					    <label for="codigo_postal">Código postal:</label>
					    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" placeholder="Ej.: 4000" autofocus required>
					  </div>
					  <button type="button" value="Aceptar" id="calcular_envio" name="calcular_envio" class="btn btn-custom2 mb-2">Calcular</button>
					</form>
				</div>
		      	</div>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-custom" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	</section>

</div>
@stop

@section('javascript')
	<script src="js/jquery.validate.min.js"></script>
    <script src="js/envio.js"></script>
@stop