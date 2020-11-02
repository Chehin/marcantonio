<?php 
	$mode = $aViewData['mode'];
	$item = (isset($aViewData['item'])) ? $aViewData['item'] : null;
?>

<div class="modal-dialog modal-lg">
    
    <div class="modal-content">
        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="btnClose">
				&times;
			</button>
            <h6 class="modal-title" id="myModalLabel">
                <i class="fa fa-cog fa-fw "></i> {{ ('edit' == $aViewData['mode']) ? 'Editar' : 'Agregar' }} {{$aViewData['resourceLabel']}}   
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
						<!--        <p>
                            Tabs inside well and pulled right
                            <code>
							.tabs-pull-right
                            </code>
                            (Bordered Tabs)
						</p> -->
						<hr class="simple">

						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
							<li>
									   <a href="#l2" data-toggle="tab">Categorización Meli</a>
							</li>
   
							  <li class="pull-rigth active">
									   <a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
							   </li>
					   </ul>
						
{{-- 						<ul id="myTab3" class="nav nav-tabs tabs-pull-right bordered">
                            <li class="pull-right active">
								<a href="#l1" data-toggle="tab">Datos de {{$aViewData['resourceLabel']}}</a>
							</li>
						</ul> --}}
						
						<div id="myTabContent3" class="tab-content padding-10">
							<div class="tab-pane fade" id="l2">
							   @include('productos.productos.productosContentMeli')
							</div>
							<div class="tab-pane fade active in" id="l1">
							   @include('productos.productos.productosContent')
							</div>
	
						   <!-- Buttons inside Form!!-->
							<div class="row pull-right" style="margin-top:22px;margin-bottom: 13px;">
								<div style="padding:0;" class="col-md-12">
									<button type="button" id="save" class="btn btn-primary"><i class="fa fa-save"></i> Guardar </button>
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
		</article>
        
	</div>
</div>
<script src="js/appCustom_subRubros.js"></script>
<script src="js/appCustom_subsubRubros.js"></script>
<script>
    $(function(){
		
        appCustom.ajaxRest(
		'rest/v1/etiquetasIds',
		'GET',
		null,
		function(result){
			
			var $element = $('form#productosForm select#etiquetasIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aEtiquetasAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );        
        appCustom.ajaxRest(
		'rest/v1/deportesIds',
		'GET',
		null,
		function(result){
			
			var $element = $('form#productosForm select#deportesIds');
			
			@if ('edit' == $mode) 
			var data = JSON.parse('<?php echo $aViewData['aCustomViewData']['aDeportesAssigned']?>');
			for (var i = 0; i < result.length; i++) { 
				for (var d = 0; d < data.length; d++) {
					var item = data[d];
					if (result[i].id == item.id) {
						// Create the DOM option that is pre-selected by default
						var option = new Option(item.text, item.id, true, true);                                
						// Append it to the select
						$element.append(option);
						// Elimino los rubros seleccionados
						result.splice(i,1);
					}
				};
			}
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@else 
			for (var i = 0; i < result.length; i++) {
				// Create the DOM option that is pre-selected by default
				var option = new Option(result[i].text, result[i].id, false, false);
				// Append it to the select
				$element.append(option);
			};
			@endif                
			
			$element.select2({
				placeholder: 'Seleccionar',
				minimumInputLength: 0,
				allowClear : true,
				width : '100%'
			});
			
			// Update the selected options that are displayed
			$element.trigger('change');
		}, 
		'sync'
        );      
		@if ('edit' == $mode)   
		@if ($item->categoria_meli)   
        appCustom.ajaxRest(
		'rest/v1/categoriaMeli/{{ $item->categoria_meli }}/1',
		'GET',
		null,
		function(result){
			if (0 == result.status) {
				var data_cat = '';
				$.each(result.data.path_from_root, function(i, v) {
					if(i>0){
						data_cat = data_cat+' > ';
					}
					data_cat = data_cat+v['name'];
				});
				$('#categoria_meli').val(result.data.id);
				$('#cat_meli').html(data_cat);
				$('#edit_cat_meli').removeClass('disabled');
			} 
		}, 
		'sync'
        );    
		@endif    
		@endif    
		
		        
	});
	
    $(document).ready(function() { 
        var stockColor = {}; // El objeto que almacenará el color, código y stock
        contadorDataJSON = 0;
		@if ('edit' == $mode) 		
		var color = '{!! $aViewData['aCustomViewData']['aColoresAssigned'] !!}';
		var find = /\n/;
		var find1 = /\t/;
		color = color.replace(find,'');				
		color = color.replace(find1,'');
		console.log(color);				
		var dataColor = $.parseJSON(color);				
		for (var i = 0; i < dataColor.length; i++) {
			var stockHTML = '';
			var stock_json = [];
			var data_stock = dataColor[i]['stock'];
			for (var e = 0; e < data_stock.length; e++) {
				stockHTML = stockHTML+'<p style="margin-bottom:0;"><strong>'+data_stock[e].sucursaln+':</strong> '+data_stock[e].stock+'</p>';
				stock_json.push({'id': data_stock[e].sucursal, 'stock': data_stock[e].stock});
			};

			$("#lista-cod-stock").append(
				"<tr class='fila-" + contadorDataJSON + "'>"+
					"<td>" + dataColor[i]['nombreColor'] + "</td>"+
					"<td>" + (dataColor[i]['nombreTalle'] ? dataColor[i]['nombreTalle'] : '')  + "</td>"+
					"<td>" + stockHTML + "</td>"+
					"<td>" + dataColor[i]['codigo'] + "</td>"+
					"<td style='text-align:center;'>"+
						"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
						'&nbsp&nbsp&nbsp'+
						"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
					"</td>"+
				"</tr>");
			
			// Guardo los datos
			stockColor[contadorDataJSON] = {
				"id_color" : dataColor[i]['id_color'], 
				"id_talle" : dataColor[i]['id_talle'], 
				"stock" : stock_json, 
				"codigo" : dataColor[i]['codigo'],
				"estado_meli" : dataColor[i]['estado_meli']
			};
			$("#stockColor").val('');
			$("#stockColor").val('['+JSON.stringify(stockColor)+']');
			contadorDataJSON++;
		}
		if(contadorDataJSON>0){
			$('input[name=stock_total]').prop('disabled', true);
			$('input[name=codigo_total]').prop('disabled', true);
		}
		@endif
        $("#agregar-cod-stock").on("click", function(e){
            var color = $("#id_color").val();
            var nombreColor = $("#id_color option:selected").text();
            var talle = $("#id_talle").val();
            var nombreTalle = $("#id_talle").val() ? $("#id_talle option:selected").text() : '';
            var codigo = $("#codigo").val();
			
            if("" === color){
                appCustom.smallBox(
				'nok', 
				"Debe ingresar un color", 
				null, 
				'NO_TIME_OUT'
                );
			}else{
				var repetido_color = false;
				var repetido_codigo = false;
				if($("#stockColor").val()==''){
					stockColor = {};
				}else{
					var stockColor = JSON.parse($("#stockColor").val());
					stockColor = stockColor[0];
					//buscar si el color ya fue elegido 

					$.each(stockColor, function(i, v) {
						if (v.id_color == color && v.id_talle == talle) {
							appCustom.smallBox(
								'nok', 
								"El color y talle elegido ya está cargado", 
								null, 
								'NO_TIME_OUT'
							);
							repetido_color = true;
						}
						
						if (codigo && v.codigo == codigo) {
							appCustom.smallBox(
								'nok', 
								"El código ya está cargado", 
								null, 
								'NO_TIME_OUT'
							);
							repetido_codigo = true;
						}
						
					});
					
				}
				if(!repetido_color &&  !repetido_codigo){
					var stockHTML = '';
					var stock_json = [];
					$('#stock input[name="stock[]"]').each(function() {
						stockHTML = stockHTML+'<p style="margin-bottom:0;"><strong>'+$(this).data('sucursaln')+':</strong> '+$(this).val()+'</p>';
						stock_json.push({'id': $(this).data('sucursal'), 'stock': $(this).val()});
					});


					$("#lista-cod-stock").append(
						"<tr class='fila-" + contadorDataJSON + "'>"+
							"<td>" + nombreColor + "</td>"+
							"<td>" + nombreTalle + "</td>"+
							"<td>" + stockHTML + "</td>"+
							"<td>" + codigo + "</td>"+
							"<td style='text-align:center;'>"+
								"<a href='javascript:void(0);' onclick='quitarFila(" + contadorDataJSON + ");'><i class='fa fa-trash fa-lg'></i></a>"+
								'&nbsp&nbsp&nbsp'+
								"<a href='javascript:void(0);' onclick='editarFila(" + contadorDataJSON + ");'><i class='fa fa-pencil fa-lg'></i></a>"+
							"</td>"+
						"</tr>")
					;
					// Vuelvo el foco a la lista de productos
					$("#id_color").focus();
					
					// Vacio los formularios
					$("#id_color").val("");
					$("#id_talle").val("");
					$('#stock input[name="stock[]"]').val("");
					$("#codigo").val("");
					
					// Guardo los datos
					stockColor[contadorDataJSON] = {
						"id_color" : color, 
						"id_talle" : talle, 
						"stock" : stock_json, 
						"codigo" : codigo,
						"estado_meli" : 0
					};
					$("#stockColor").val('');
					$("#stockColor").val('['+JSON.stringify(stockColor)+']');
					contadorDataJSON++;
				}
			}
			if(contadorDataJSON>0){
				$('input[name=stock_total]').prop('disabled', true);
				$('input[name=codigo_total]').prop('disabled', true);
			}else{
				$('input[name=stock_total]').prop('disabled', false);
				$('input[name=codigo_total]').prop('disabled', false);
			}
		});
		$( "select[name=id_subrubro]" ).change(function() {
			var id_subrubro = $( this ).val();
			appCustom.ajaxRest(
            appCustom.subsubRubros.OBTENER_SUBSUBRUBROS.url, 
            appCustom.subsubRubros.OBTENER_SUBSUBRUBROS.verb,
            {id_subrubro: id_subrubro}, 
            function(result) {
                if (0 == result.status) {
                    if (result.subsubrubros) {
                        $('#subsubrubros').html('<option value="" selected="selected">Seleccione una SubSubrubro</option>');
                        for (var i = 0; i < result.subsubrubros.length ; i++) {
                            $('#subsubrubros').append('<option value="'+result.subsubrubros[i].id+'">'+result.subsubrubros[i].text+'</option>');
						}
						} else {
                        $('#subsubrubros').html('<option value="" selected="selected">No hay SubSubrubro</option>');
					};
					} else {
                    appCustom.smallBox(
					'nok', 
					result.msg, 
					null, 
					'NO_TIME_OUT'
                    )
                    ;
				}
			}
			);
		});
		$( "select[name=id_rubro]" ).change(function() {
			var id_rubro = $( this ).val();
			appCustom.ajaxRest(
            appCustom.subRubros.OBTENER_SUBRUBROS.url, 
            appCustom.subRubros.OBTENER_SUBRUBROS.verb,
            {id_rubro: id_rubro}, 
            function(result) {
                if (0 == result.status) {
                    if (result.subrubros) {
                        $('#subrubros').html('<option value="" selected="selected">Seleccione una Subrubro</option>');
                        for (var i = 0; i < result.subrubros.length ; i++) {
                            $('#subrubros').append('<option value="'+result.subrubros[i].id+'">'+result.subrubros[i].text+'</option>');
						}
						} else {
                        $('#subrubros').html('<option value="" selected="selected">No hay Subrubro</option>');
					};
					} else {
                    appCustom.smallBox(
					'nok', 
					result.msg, 
					null, 
					'NO_TIME_OUT'
                    )
                    ;
				}
			}
			);
		});
		$('.close_edit').on('click', function(){
			close_edit_cat();
		});
	});
	
	//Categoria meli
	function buscar_cat_meli(){
		var nombre = $('#nombremeli').val();
		if(nombre){
			appCustom.ajaxRest(
				'rest/v1/categoryPredict/'+nombre,
				'GET',
				null,
				function(result) {
					if (0 == result.status) {
						//console.log(result);
						var data_cat = '';
						$.each(result.data.path_from_root, function(i, v) {
							if(i>0){
								data_cat = data_cat+' > ';
							}
							data_cat = data_cat+v['name'];
						});
						$('#categoria_meli').val(result.data.id);
						$('#cat_meli').html(data_cat);
						$('#edit_cat_meli').removeClass('disabled');
						var categoria_variations = result.data.variations;
						$('#categoria_variations').val(categoria_variations?1:0);
					} else {
						appCustom.smallBox(
						'nok', 
						result.msg, 
						null, 
						'NO_TIME_OUT'
						)
						;
					}
				}
			);
		}else{
			appCustom.smallBox(
				'nok', 
				'Debe ingresar el nombre del producto', 
				null, 
				'NO_TIME_OUT'
			);
		}
	}
	function quitarFila(color){
		var stockColor = $("#stockColor").val();
		stockColor = JSON.parse(stockColor);
		delete stockColor[0][color]
		$(".fila-"+color).remove();
		$("#stockColor").val('');
		var cant = Object.keys(stockColor[0]).length;
		if(cant>0){
			$("#stockColor").val(JSON.stringify(stockColor));
		}else{
			$('input[name=stock_total]').prop('disabled', false);
			$('input[name=codigo_total]').prop('disabled', false);
		}
	}
	
	function editarFila(index){
		var arr = JSON.parse($("#stockColor").val())[0][index];
		
		$("#id_color").val(arr.id_color);
		$("#id_talle").val(arr.id_talle);
		$("#codigo").val(arr.codigo);
		$("#editarFila").data('index',index);
		$.each(arr.stock, function(i, item) {
			var data = $('#stock input[name="stock[]"]')[i];
			$(data).val(item.stock);
		});
		
		$("#id_color").focus();
		
		modeGrid('edit');
				
		//console.log(arr);
		
	}
	
	function editarFilaCancel(index){
		
				
		modeGrid('default');
				
		//console.log(arr);
		
	}
	
	function modeGrid(mode) {
		
		if ('edit' === mode) {
			$("#editarFila").show();
			$("#editarFilaCancel").show();
			$("#agregar-cod-stock").hide();
		} else {
			$("#editarFila").hide();
			$("#editarFilaCancel").hide();
			$("#agregar-cod-stock").show();
			
			$("#id_color").val("");
			$("#id_talle").val("");
			$('#stock input[name="stock[]"]').val("");
			$("#codigo").val("");
		}
		
		
	}
	
	function guardarFila(elEditar){
		var index = $(elEditar).data('index');
		
		if ("" === $("#id_color").val()) {
			appCustom.smallBox(
				'nok', 
				"Debe cargar un color", 
				null, 
				'NO_TIME_OUT'
			);
	
			return false;
		}
		
		var arr = JSON.parse($("#stockColor").val());
		repetido_color = false;
		repetido_codigo = false;
		$.each(arr[0], function(i, v) {
			
			if(index != i){
			
				if (v.id_color == $("#id_color").val() && v.id_talle == $("#id_talle").val()) {
					appCustom.smallBox(
						'nok', 
						"El color y talle elegido ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_color = true;
				}

				if ($("#codigo").val() && v.codigo == $("#codigo").val()) {
					appCustom.smallBox(
						'nok', 
						"El código ya está cargado", 
						null, 
						'NO_TIME_OUT'
					);
					repetido_codigo = true;
				}
			}

		});
		
		if (repetido_codigo || repetido_color) {
			return false;
		}
		var stockHTML = '';
		var stock_json = [];
		$('#stock input[name="stock[]"]').each(function() {
			stockHTML = stockHTML+'<p><strong>'+$(this).data('sucursaln')+':</strong> '+$(this).val()+'</p>';
			stock_json.push({'id': $(this).data('sucursal'), 'stock': $(this).val()});
		});
		
		arr[0][index].id_color = $("#id_color").val();
		arr[0][index].id_talle = $("#id_talle").val();
		arr[0][index].stock = stock_json;
		arr[0][index].codigo = $("#codigo").val();
		
		$("#stockColor").val(JSON.stringify(arr));
		
		//console.log(log);
		
		//console.log($('#lista-cod-stock .fila-' + index + ' td'));
		
		$('#lista-cod-stock .fila-' + index + ' td').eq(0).html($("#id_color option:selected").text());
		$('#lista-cod-stock .fila-' + index + ' td').eq(1).html($("#id_talle").val() ? $("#id_talle option:selected").text() : '');
		$('#lista-cod-stock .fila-' + index + ' td').eq(2).html(stockHTML);
		$('#lista-cod-stock .fila-' + index + ' td').eq(3).html($("#codigo").val());
		
		modeGrid('default');
		
		$('#lista-cod-stock .fila-' + index + ' td').animate({ 'background-color':'#3276B1'},"fast",function(){
			$(this).css('background-color','white');
		});

	}

	function edit_cat_meli(cat = false, nivel=2){
		var div = $("#selectCatMeli");
		div.find('tbody').html('');
		if(!cat){
			var cat = $('#categoria_meli').val()?$('#categoria_meli').val():-1;
		}
		appCustom.ajaxRest(
			'rest/v1/editCatMeli/'+cat+'/'+nivel,
			'GET',
			null,
			function(result) {
				div.removeClass('hide');
				var data_cat = '<a href="javascript:void(0);" onclick="edit_cat_meli(-1);">Inicio</a> >';
				if(cat!=-1){
					$.each(result.camino.path_from_root, function(i, v) {
						if(i>0){
							data_cat = data_cat+' > ';
						}
						data_cat = data_cat+'<a href="javascript:void(0);" onclick="edit_cat_meli(\''+v['id']+'\');">'+v['name']+'</a>';
					});
					div.find('.cat_meli').html(data_cat);

					var data_cat = '';
					$.each(result.categoria.children_categories, function(i, v) {
						data_cat = '<tr><td align="center">'+v['name']+'</td>';
						data_cat = data_cat+'<td align="center"><a href="javascript:void(0);" onclick="selectCategoria(\''+v['id']+'\')">Seleccionar</a></td></tr>';
						div.find('tbody').append(data_cat);
					});
				}else{
					div.find('.cat_meli').html(data_cat);
					$.each(result.camino, function(i, v) {
						data_cat = '<tr><td align="center">'+v['name']+'</td>';
						data_cat = data_cat+'<td align="center"><a href="javascript:void(0);" onclick="selectCategoria(\''+v['id']+'\')">Seleccionar</a></td></tr>';
						div.find('tbody').append(data_cat);
					});
				}
			}
		);
	}

	function close_edit_cat(){
		var div = $("#selectCatMeli");
		div.addClass('hide');
		div.find('tbody').html('');
	}

	function selectCategoria(cat){	
		close_edit_cat();
		appCustom.ajaxRest(
		'rest/v1/categoriaMeli/'+cat+'/1',
		'GET',
		null,
		function(result){
			if (0 == result.status) {
				var categoria_variations = result.data.attribute_types=='variations'?1:0;
				$('#categoria_variations').val(categoria_variations);
				if(result.data.children_categories.length>0){
					edit_cat_meli(result.data.id, 1);
				}else{
					var data_cat = '';
					$.each(result.data.path_from_root, function(i, v) {
						if(i>0){
							data_cat = data_cat+' > ';
						}
						data_cat = data_cat+v['name'];
					});
					$('#categoria_meli').val(result.data.id);
					$('#cat_meli').html(data_cat);
					$('#edit_cat_meli').removeClass('disabled');
				}
			} 
		}, 
		'sync'
        );  

		
	}

</script>
@include('pedidos.pedidos.pedidosEditScripts')
