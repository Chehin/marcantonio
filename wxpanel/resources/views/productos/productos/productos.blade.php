@extends('layouts.base')


@section('main_container')

		
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
				<h1 class="page-title txt-color-blueDark">
						<i class="fa fa-cog"></i> 
								{{ $aViewData['resourceLabel'] }}
				</h1>
		</div>
		
		
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                       {{--  @if(Sentinel::hasAccess('exportar' . '.update'))
                        <a href="{{route('exportar')}}" id="exportar" class="btn btn-sm btn-warning"> 
                                <i class="fa fa-plus"></i> Exportar Categorias MELI
                        </a>
                        @endif --}}
                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                        <button data-href="codigosFlexxus" class="btn btn-sm btn-warning" data-toggle="modal-custom" data-remote="true"> 
                                <i class="fa fa-share"></i> Sincronizacion Flexxus
                        </button>
                        @endif
                        
                        @if(Sentinel::hasAccess($aViewData['resource'] . '.update'))
                        <button data-href="updateLoteMeli" class="btn btn-sm btn-primary" data-toggle="modal-custom" data-remote="true"> 
                                <i class="fa fa-plus"></i> Actualizar Mercado Libre
                        </button>
                        @endif

			@if(Sentinel::hasAccess($aViewData['resource'] . '.create'))
				<button id="resourceAdd" data-href="" class="btn btn-sm btn-success" data-toggle="modal-custom" data-remote="true"> 
					<i class="fa fa-plus"></i> Agregar {{ $aViewData['resourceLabel'] }}
				</button>
				
			@endif
                </div>	
		
	</div>


	<!-- widget grid -->
	<section id="widget-grid">

        <!-- row -->
        <div class="row">

                <!-- NEW WIDGET START -->
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        <!-- Widget ID (each widget will need unique ID)-->
                        <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-2" data-widget-editbutton="false" data-widget-deletebutton="true">
                                <header>
                                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                                        <h2></h2>
                                    <button class="btn btn-sm btn-danger pull-right" onclick="onSetEtiquetas()">Agregar etiquetas</button>
                                </header>

                                <!-- widget div-->
                                <div>

                                        <!-- widget edit box -->
                                        <div class="jarviswidget-editbox">
                                                <!-- This area used as dropdown edit box -->

                                        </div>
                                        <!-- end widget edit box -->

                                        <!-- widget content -->
                                        <div class="widget-body no-padding">
                                                <div class="widget-body-toolbar">

                                                </div>
                                                <table id="{{$aViewData['resource']}}_datatable_tabletools" style="width:100% !important;" class="table table-striped table-hover tableCustom">
													<thead>
														<tr>
                                                            <th><input type="checkbox" id="select_all"/></th>
                                                             <th>Codigo</th>
															<th></th>
															<th>Nombre</th>
                                                            <th>Rubro</th>
                                                            <th>Precio</th>
                                                            <th>Orden</th>
															<th>Habilitado</th>
                                                            <th>Destacado</th>
                                                            <th>Oferta</th>
                                                            <th>Relacionar</th>
                                                            <th>Imagenes</th>
                                                            <th>Comentarios</th>
                                                            <th>Mercado Libre</th>
                                                            <th>Preguntas</th>
                                                            <th></th>
														</tr>
													</thead>
                                                </table>

                                        </div>
                                        <!-- end widget content -->

                                </div>
                                <!-- end widget div -->

                        </div>
                        <!-- end widget -->


                </article>
                <!-- WIDGET END -->

        </div>

        <!-- end row -->

</section>
<!-- end widget grid -->
@stop

@section('custom_scripts_container')
	<script src="js/appCustom_{{ $aViewData['resource'] }}.js" ></script>
    @include('productos.'.$aViewData['resource'].'.'.$aViewData['resource'].'Scripts')
    <script src="js/plugin/cropit/jquery.cropit.js"></script>
@stop
