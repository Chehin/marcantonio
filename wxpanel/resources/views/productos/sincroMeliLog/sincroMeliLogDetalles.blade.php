<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['aItem'])) ? $aViewData['aItem'] : null;
	$detalles = (isset($aViewData['detalles'])) ? $aViewData['detalles'] : null;
	$producto = (isset($aViewData['producto'])) ? $aViewData['producto'] : null;
?>

<div class="modal-dialog modal-lg">    
    <div class="modal-content">        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
				&times;
			</button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> Detalle de Error
			</h6>
		</div>
		<!-- NEW WIDGET START -->
		<article class="col-sm-12 col-md-12 col-lg-6" style="width: 100%;padding: 0;">
			
			<!-- Widget ID (each widget will need unique ID)-->
			<div class="jarviswidget well" id="wid-id-4" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
				<!-- widget div-->
				<div>					
					<!-- widget edit box -->
					<div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
						
					</div>
					<!-- end widget edit box -->
					
					<!-- widget content -->
					<div class="widget-body">
						
						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered" style="border-bottom: 0;"></ul>
						
						<div id="myTabContent3" class="tab-content padding-10">
							<h1> {{ env('SITE_NAME')}} - <small>Error en Sincronizaci&oacute;n Productos MELI</small></h1>
							<h4>Detalles del Producto</h4>
                            <div class="row">
                                <div class="col-xs-6"><strong>Producto:</strong> {{$producto->nombremeli}}</div>
                                <div class="col-xs-6"><strong>ID MELI:</strong> {{$producto->id_meli}}</div>
								<div class="col-xs-6"><strong>Categoria MELI:</strong> {{$producto->categoria_meli}}</div>
                            </div>
							<br>
							<br>
							@if(count($detalles)>0)
								<h4>Detalles del Error</h4>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<th>Seccion</th>
											<th>Causa</th>
											<th>Tipo</th>
											<th>Codigo</th>
											<th>Referencias</th>
											<th>Mensaje</th>
										</tr>
									</thead>
									<tbody>
										@foreach($detalles as $detalle)
										<tr>
											<td><div style="width:auto; overflow-wrap: break-word;"><p>{{$detalle['seccion']}}</p></div></td>
											<td><div style="width:auto; overflow-wrap: break-word;"><p>{{$detalle['id_causa']}}</p></div></td>
											<td><div style="width:auto; overflow-wrap: break-word;"><p>{{$detalle['tipo']}}</p></div></td>
											<td>{{$detalle['codigo']}}</td>
											<td><div style="width:150px; overflow-wrap: break-word;"><p>{{$detalle['referencias']}}</p></div></td>
											<td>{{$detalle['mensaje']}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
								
							</div>
							@else
							<h4>Sin Detalles</h4>
							@endif
						</div>
					</div>
					
					<!-- end widget content -->
					
				</div>
				<!-- end widget div -->
				
			</div>
			<!-- end widget -->
		</article>
        
	</div>
</div>
