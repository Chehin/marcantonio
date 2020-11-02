<?php
$mode = $aViewData['mode'];
$cantError = (isset($aViewData['cantError'])) ? $aViewData['cantError'] : null;
$itemsError = (isset($aViewData['itemsError'])) ? $aViewData['itemsError'] : null;
?>

<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
                &times;
            </button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-envelope fa-fw "></i> {{ $aViewData['resourceLabel'] }}
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
                        <!--<p>
                            Tabs inside well and pulled right
                            <code>
                                    .tabs-pull-right
                            </code>
                            (Bordered Tabs)
                        </p> -->
                        <hr class="simple">

                        <ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
                                <a href="#l1" data-toggle="tab">{{$aViewData['resourceLabel']}}</a>
                            </li>
                        </ul>

                        <div id="myTabContent3" class="tab-content padding-10">
                            <div class="alert alert-info fade in">
                                <button class="close" data-dismiss="alert">
                                    ×
                                </button>
                                <i class="fa-fw fa fa-info"></i>
                                <span>{{ $cantError }} Codigos en conflicto</span>
                            </div>
                            <table id="codigos" class="table table-bordered table-striped table-condensed table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Código Flexxus</th>
					<th>Talle Flexxus</th>
                                        <th>Actualizar codigo-talle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itemsError as $item)
                                    <tr id="{{$item['id']}}">
                                        <td>{{ $item['codigo'] }}</td>
                                        <td id="codigo_{{$item['id']}}">{{ $item['codigo_flexxus'] }}</td>
					<td id="talle_{{$item['id']}}">{{ $item['talle_flexxus'] }}</td>
                                        <td>
                                            <input type="text" name="codigo_{{ $item['id'] }}" value="{{ $item['codigo_flexxus'] }}">-
					    <input type="text" name="talle_{{ $item['id'] }}" value="{{ $item['talle_flexxus'] }}" style="width: 50px;">
                                            <button class="btn btn-sm btn-default" onclick="changeCodigoFlexxus('{{ $item['id'] }}', '{{ $item['codigo'] }}')"><i class="fa fa-pencil"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach                                        
                                </tbody>
                            </table>
                            <div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">
								<div style="padding:0;" class="col-md-12">
									<button type="button" id="saveCodigos" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
									<button type="button" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-times"></i> Cancelar </button>
								</div>
							</div>
                        </div>

                    </div>

                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->
    </div>
</div>
<script>
    var cambios = {};

    function changeCodigoFlexxus(id, codigo){
	var codigoFlexxus = jQuery('#'+id+ ' input[name=codigo_'+id+']').val();
	var talleFlexxus = jQuery('#'+id+ ' input[name=talle_'+id+']').val();
        jQuery('#codigo_'+id).html(codigoFlexxus);
        jQuery('#talle_'+id).html(talleFlexxus);
        cambios['id_'+id] = {"codigo": codigoFlexxus, "talle": talleFlexxus};
    }

    jQuery(document).ready(function(){
        jQuery('#saveCodigos').click(function(){

            console.log(cambios);
            var data = JSON.stringify(cambios);

            console.log(data);
            
            $.ajaxSetup({headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content')}  });

            var parametros = {
                '_token'  : "{{ csrf_token() }}",
                'datos'  : data,
            };

            $.ajax( {
                "dataType": 'json',
                "type": 'POST',
                "url": "{{ route('codigosFlexxusPost') }}",
                "data": parametros,
                "success":  function(response){
                    if (0 == response.status) {
                        appCustom.smallBox('ok','');
                        appCustom.hideModal();
                    } else {
                        var type = 'nok';
                        if (2 == response.status) {
                            type = 'warn';
                        }
                        
                        appCustom.smallBox(
                            type,
                            response.msg,
                            null, 
                            'NO_TIME_OUT'
                        );
                    }
                },
                "error":function(xhr, status, error) {
                    //(possibly) one user starts more than one session
                    if (401 === xhr.status) {
                        window.location = 'logout';
                    } else { //another error code
                        appCustom.smallBox(
                            'nok', 
                            'Error interno. No se pudo completar la operaci&oacute;n',
                            '',
                            'NO_TIME_OUT'
                        );
                    }


                },
                "complete":function() {
                    appCustom.closeModalPreloader();
                }
            });
        });
    });
</script>