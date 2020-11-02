<script>
  
   $(function() {
        //DOM Settings
        var resourceDOM = {};
        var resourceDOM1 = {};
        var formHTMLId = '{{$aViewData['resource'] . 'Form'}}';
        var formHTMLId1 = '{{$aViewData['resource'] . 'Form1'}}';
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
		var redrawTableAfterSend = $('#' + formHTMLId + ' #param_redrawTableAfterSend').val();
		var redrawTableAfterSendDiferentTable = $('#' + formHTMLId + ' #param_redrawTableAfterSendDiferentTable').val();
		
		
        //Requests Settings
        var resourceReq = {};
        resourceReq.store = {};
        resourceReq.update = {};
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.STORE.verb;
       
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.UPDATE.url(id);
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.UPDATE.verb;
        // end settings

        resourceDOM.$form = $("form#" + formHTMLId);
        resourceDOM.formValidate = resourceDOM.$form.validate();
        //segundo form categ meli
        resourceDOM1.$form = $("form#" + formHTMLId1);
        resourceDOM1.formValidate = resourceDOM1.$form.validate();
        
        $("button#save").click(function(e){
            if (resourceDOM.formValidate.form()) {
                var data = '';
				//summernote
                $('#texto', resourceDOM.$form).val($('#textoBox').code());
				
				if (!$("#stockColor").val() ||
					!JSON.parse($("#stockColor").val())[0]
				) {
					appCustom.smallBox(
						'nok',
						'Debe asignar Color, Stock y talle',
						null, 
						'NO_TIME_OUT'
					)	;
			
					return false;
				}
				
                
                data += resourceDOM.$form.serialize() + '&';
                data += resourceDOM1.$form.serialize() + '&';
				
                appCustom.ajaxRest(
                    @if("add" === $mode)     
                            resourceReq.store.url,
                            resourceReq.store.verb,
                    @else
                            resourceReq.update.url( '{{ $item->id }}' ),
                            resourceReq.update.verb,
                    @endif
                    data,
                    function(response){
                        if (0 == response.status) {
                            appCustom.smallBox('ok','');
                            appCustom.hideModal();
							
							if (!redrawTableAfterSend || 'true' === redrawTableAfterSend) {
								
								var tableToRedraw = resourceTableId;
								if (redrawTableAfterSendDiferentTable) {
									tableToRedraw = redrawTableAfterSendDiferentTable;
								}
								
								$('#' + tableToRedraw).dataTable().fnStandingRedraw();
							}
                            
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
                    }
                );
            }

        });
		
		//summernote
		$('#textoBox', resourceDOM.$form).summernote({
			height: 200,
			focus: false,
			tabsize: 2
		});
		//prevent default submitt on enter
		$('input', resourceDOM.$form).keydown(function(e){
			if(13 === e.keyCode)
			{
				e.preventDefault();
				e.stopPropagation();
				
				$("button#save", resourceDOM.$form).trigger('click');
				
			}
		});
        
   });
   
   
	
   
</script> 