jQuery(document).ready( function() {
    "use strict";

    $("form#consulta_form").validate({
		rules : {
			codigo_postal : {
				required : true
			}
		},
		messages : {
			codigo_postal : {
				required : 'Por favor ingrese el código postal'
			}
		},
		// Do not change code below
        errorPlacement : function(error, element) {
            error.insertAfter(element);
        }
	});
  
	$('#calcular_envio').on('click', function(){
		if ($("form#consulta_form").validate().form()) {
			$('#codigo_postal').attr('readonly', true);
			var codigo = $('#codigo_postal').val();
			var id = $('#id_producto').val();
			$('#opciones_envio').html('');
			$('#ajaxPreloader').show();
			$('#confirma_envio').show();
			$.ajax({
				url : 'consultarEnvio',
				data : {  id : id, codigo : codigo },
				type : 'GET',
				dataType : 'json',
				success : function(json) {
					var table = '<table class="table table-bordered cart_summary"><thead><tr><th>Método de envío</th><th>Costo</th><th>Empresa</th></tr></thead><tbody>';
					$.each(json, function (i, item) {
						table += '<tr><td><div><label>'+item.name+'</label></div></td>';
						table += '<td>$'+item.cost+'</td>';
						table += '<td>'+item.empresa+'</td></tr>';
						$('<input>').attr({
							type: 'hidden',
							id: 'e_'+item.id,
							value: item.cost,
							'data-tipo-envio' : item.id,
							'data-nombre_envio' : item.name,
							'data-id_tipo_envio' : item.id_tipo_envio
						}).appendTo('#costos_envio');
					});
					table += '</tbody></table>';
					$('#ajaxPreloader').hide();
					$('#opciones_envio').html(table);
					$('#codigo_postal').attr('readonly', false);
				},
				error : function(xhr, status) {
					$('#codigo_postal').attr('readonly', false);
					$('#ajaxPreloader').hide();
					alert('Disculpe, hubo un error inesperado');
				}
			});
		}
	});
});