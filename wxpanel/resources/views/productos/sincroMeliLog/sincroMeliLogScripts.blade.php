<script type="text/javascript">
		
    $(document).ready(function() {
        
        //Settings
        var resourceDOM = {};
        var resourceTableId = '{{$aViewData['resource']}}_datatable_tabletools';
        var dtWrapper = '#' + resourceTableId + '_wrapper ';
        //Requests Settings
        var resourceReq = {};
        resourceReq.index = {};
        resourceReq.create = {};
        resourceReq.store = {};
        resourceReq.update = {};
        resourceReq.edit = {};
        resourceReq.delete = {};
        resourceReq.language = {};
		var resourceReqOption1 = {};
		resourceReqOption1.edit = {};
		var resourceReqOption2 = {};
		resourceReqOption2.edit = {};
        resourceReq.detalles = {};

        resourceReq.detalles.url = function(id){
            return appCustom.{{$aViewData['resource']}}.detalles.EDIT.url(id);
        };

        resourceReq.index.url = appCustom.{{$aViewData['resource']}}.INDEX.url;
        resourceReq.index.verb = appCustom.{{$aViewData['resource']}}.INDEX.verb;
        
        resourceReq.create.url = appCustom.{{$aViewData['resource']}}.CREATE.url;
        
        resourceReq.store.url = appCustom.{{$aViewData['resource']}}.STORE.url;
        resourceReq.store.verb = appCustom.{{$aViewData['resource']}}.STORE.verb;

        resourceReq.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.EDIT.url(id);
        };
        resourceReq.edit.verb = appCustom.{{$aViewData['resource']}}.EDIT.verb;
        
        resourceReq.update.url = function(id){
            return appCustom.{{$aViewData['resource']}}.UPDATE.url(id)
        };
        resourceReq.update.verb = appCustom.{{$aViewData['resource']}}.UPDATE.verb;
        
        resourceReq.delete.url = function(id){
            return appCustom.{{$aViewData['resource']}}.DELETE.url(id);
        };
        resourceReq.delete.verb = appCustom.{{$aViewData['resource']}}.DELETE.verb;
        
        resourceReq.language.url = function(id){
            return appCustom.{{$aViewData['resource']}}.language.mainView.url(id)
        };
		
		resourceReqOption1.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.image.mainView.url(id);
        };
		
		resourceReqOption2.edit.url = function(id){
            return appCustom.{{$aViewData['resource']}}.noteRelated.mainView.url(id);
        };

		//resourceReqOption1.edit.url
        // end settings
        
        $("button#resourceAdd")
                .attr('data-href', resourceReq.create.url);


        pageSetUp();
        var resourceTable = $('#' + resourceTableId).dataTable({
            buttons: [
                {
                    extend: 'collection',
                    text: 'Exportar',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Excel',
                            filename: '{{$aViewData['resource']}}_*'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            filename: '{{$aViewData['resource']}}_*',
                            orientation: 'landscape',
                            title: 'Reporte de {{$aViewData['resource']}} - *'
                        },
                        'csvHtml5'
                    ]
                }
            ],

            "fixedHeader": true,
            "scrollX" : true,
            "stateSave": false,
            "scrollCollapse": true,
             "language": {
                "sSearch" : '<span title="Filtro" class="input-group-addon"> <i class="fa fa-search"></i> </span>'
               },
            "sDom" : "<'dt-top-row'Tl <'filtro-custom'f> <'filtro-mas filtro-all dataTables_length2'><'filtro-mas dataTables_length'><'btn-filters'><'dataTables_length btn-export'B>>r<'dt-wrapper't><'dt-row dt-bottom-row'<'row'<'col-sm-6'i><'col-sm-6 text-right'p>>>",
            "oTableTools" : {
                "aButtons" : [{
                    "sExtends" : "collection",
                    "sButtonText" : 'Exportar <span class="caret" />',
                    "aButtons" : ["csv", "xls", "pdf"]
                }],"sSwfPath" : "js/plugin/datatables/media/swf/copy_csv_xls_pdf.swf"
            },
            "initComplete": function ()
            {
                $('.filtro-custom input').attr('placeholder', 'ID MELI o Nombre Producto');

                //filter 1
                var $fecha_a = $('<input style="width:90px;margin-left:4px;" id="fecha_a" class="datepicker" placeholder="Fecha Desde" data-dateformat="dd/mm/yy" name="fecha_a" type="text" value="">');

                $(dtWrapper + '.filtro-all').append($fecha_a).append(' ');
                $fecha_a.css('height','33px');

                $fecha_a.datepicker({
                    dateFormat : $fecha_a.attr('data-dateformat') || 'dd/mm/yy',
                    language: "es",
                    prevText : '<i class="fa fa-chevron-left"></i>',
                    nextText : '<i class="fa fa-chevron-right"></i>'
                });
                $fecha_a.on('change', onChangeFiltroMas1);

                //filter 2
                var $fecha_a2 = $('<input style="width:90px;margin-left:4px;" id="fecha_a2" class="datepicker" placeholder="Fecha Hasta" data-dateformat="dd/mm/yy" name="fecha_a2" type="text" value="">');

                $(dtWrapper + '.filtro-all').append($fecha_a2);
                $fecha_a2.css('height','30px');

                $fecha_a2.datepicker({
                    dateFormat : $fecha_a2.attr('data-dateformat') || 'dd/mm/yy',
                    language: "es",
                    prevText : '<i class="fa fa-chevron-left"></i>',
                    nextText : '<i class="fa fa-chevron-right"></i>'
                });
                $fecha_a2.on('change', onChangeFiltroMas2);

                //filter 3
                        @php
                            $selectName = 'estado';
                        @endphp

                var selectName = "{{ $selectName }}";
                var select = '{{ Form::select(
					$selectName,
					[
						"E"			=> "Error",
						"O"          => "Correcto",
					],
					null,
					['style' => 'width:122px;']
					)
				}}';

                $(dtWrapper + '.filtro-all').append(select);
                $(dtWrapper + 'select[name='+ selectName +']').prepend('<option selected value="">Estado (Todos)</option>');
                $(dtWrapper + '.filtro-all select[name='+ selectName +']').on('change', onChangeFiltroMas3);

            },
            "bProcessing" : false,
            "sAjaxSource":  resourceReq.index.url,
            "bServerSide": true,
            "bPaginate": true,
            "ordering": true,
            "order": [ 0, 'asc' ],
            "fnCreatedRow": function ( row, data, index ) {

            },
            "aoColumnDefs": [
                { "mData": "fecha", "aTargets":[0], "sortable":true },
                { "mData": "nombremeli", "aTargets":[1], "sortable":false, "sClass": "right" },
                { "mData": "id_meli", "aTargets":[2], "sortable":false, "sClass": "center" },
                { "mData": "estado", "aTargets":[3], "sortable":false , "mRender": function(value, type, full){

                        var estado = ( 'E' == full.estado) ? 'ERROR' : 'CORRECTO';

                        return estado;

                    }},
                { "mData": "mensaje", "aTargets":[4], "sortable":false},

                { "mData": "error", "aTargets":[5], "sortable":false},
                { "mData": "codigo_estado", "aTargets":[6], "sortable":false},
                { "mData": "id_sincro_meli", "aTargets":[7], "sortable":false, "sClass": "center", "mRender":
                        function(value, type, full){
                            var detalles='<a href="javascript:void(0);" ><span data-href="'+resourceReq.detalles.url(full.id_sincro_meli)+'" data-toggle="modal-custom" class="fa fa-info-circle"></span></a> ';
                            return detalles;
                        }
                },
              ],
            "fnServerData":function (sSource, aoData, fnCallback){

                appCustom.ajaxRest(
                    sSource, 
                    resourceReq.index.verb,
                    aoData, 
                    fnCallback
                );
            },
			"drawCallback": function () {
				$(".dataTables_paginate li a").on('click', function(){
					$('html, body').animate({
						scrollTop: $("body").offset().top
					}, 500);
				});
			}
        });

        var auxFiltrosFecha = {fecha_a:'', fecha_a2:''};

        function auxRequestValida(param, valor) {
            if ('' == auxFiltrosFecha[param]) {
                auxFiltrosFecha[param] = valor;
            } else if (auxFiltrosFecha[param] == valor) {
                return false;
            }

            auxFiltrosFecha[param] = valor;

            return true;
        };

        var onChangeFiltroMas1 = function(){
            if (auxRequestValida('fecha_a', this.value)) {
                $('#' + resourceTableId).dataTable().fnFilter(this.value ,1);
            }
        };

        var onChangeFiltroMas2 = function(){
            if (auxRequestValida('fecha_a2', this.value)) {
                $('#' + resourceTableId).dataTable().fnFilter(this.value ,2);
            }
        };

        var onChangeFiltroMas3 = function(){
            $('#' + resourceTableId).dataTable().fnFilter($(this).val() ,3);
        };



    }); //End DOM ready
    
    
</script>

	
