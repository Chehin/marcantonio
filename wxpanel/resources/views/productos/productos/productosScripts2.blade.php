<script type="text/javascript">

$(function(){

	var $wp = $(".wrapperPage_{{$aViewData['resource']}}");
	var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';

	$("#sync").click(function() {		
		$.ajax({
				dataType: 'json', 
				type: "GET",
				url: "{{ \route('sync') }}",
				data: {},
				beforeSend: function(){
					$('#sync', $wp).hide();
					$('#syncPreloader', $wp).show();
				},
				success: function(response) {
					
					if (0 == response.status) {
						appCustom.smallBox('ok','');
						$('#' + resourceTableId).dataTable().fnStandingRedraw();
					}

				},
				complete: function(jqXHR,textStatus){
					$('#sync', $wp).show();
					$('#syncPreloader', $wp).hide();

				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('Se ha producido un error');
				}
		});

	});

	var syncCheck;
		(syncCheck = function(){
			
			$.ajax({
				dataType: 'json', 
				type: "GET",
				url: "{{ \route('syncCheck') }}",
				data: {},
				beforeSend: function(){

				},
				success: function(response) {
					if (!$.isEmptyObject(response)) {
						var msg = '';
						
						if (!response.done) {
							if (response.lastSyncOk) {
								$('#sync', $wp).hide();
								$('#syncPreloader', $wp).show();
								$('#syncStartedAt', $wp).html(moment(response.last_start, "YYYY-MM-DD HH:mm").format("DD/MM/YYYY HH:mm"));
								
							}/*  else {
								msg = '. El último intento de sincronización del '+ moment(response.last_start, "YYYY-MM-DD HH:mm").format("DD/MM/YYYY HH:mm") +' no terminó correctamente.';
								msg = '<span style="color:red;">'+msg+'</span>';
							} */
							
						} else {
							$('#sync', $wp).show();
							$('#syncPreloader', $wp).hide();
						}
						var syncLastDate;
						if (response.date_up) {
							syncLastDate = moment(response.date_up, "YYYY-MM-DD HH:mm").format("DD/MM/YYYY HH:mm");
						}else{
							syncLastDate = 'No hay registro de sincronización aún';
						}
						
						$('#syncLast').html(syncLastDate + msg);
						
					} else {
						$('#syncLast').html('No hay registro de sincronización aún');
					}
					
				},
				complete: function(jqXHR,textStatus){


				},
				error: function(jqXHR, textStatus, errorThrown) {
					clearInterval(interval);
					console.log('syncCheck: Se ha producido un error');
				}
			})
			;
			
		})();
		
		var interval = setInterval(syncCheck, 5000);

});
</script>


