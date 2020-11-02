<!-- widget content -->
<div class="widget-body">
    {{ Form::open(array('id' => $aViewData['resource'] . 'Form', 'name' => $aViewData['resource'] . 'Form')) }}
        <fieldset class="smart-form">
            <section>
                <div class="row">
                    <label class="label col col-2">Rubro *:</label>
                    <div class="col col-10">
                        <label class="select">
                            <?php $toDropDown1 = $aViewData['aCustomViewData']['aRubros']->prepend('Seleccione Rubro', ''); ?>
                            {{ Form::select(
                            'id_rubro',
                            $toDropDown1,
                            ("edit" == $mode) ? $item->id_rubro : '',
                            ['class' => 'col col-md-12', 'required' => '', 'id' => 'id_rubro']
                            )
                            }}
                            <i></i>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">SubRubro:</label>
                    <div class="col col-10">
                        <label class="select">
                            {{ Form::select(
                            'id_subrubro', 
                            $aViewData['aSubRubros'], 
                            ("edit" == $mode) ? $item->id_subrubro : 0, 
                            ['class' => 'col col-md-12','id'=> 'subrubros']
                            ) 
                            }}
                            <i></i>
                        </label>
                    </div>
                </div>
            </section>									
            <section>
                <div class="row">
                    <label class="label col col-2">Nombre *:</label>
                    <div class="col col-10">
                        <label class="input">
                            <input type="text" id="nombre" name="nombre" required="" value="{{ ('edit' == $mode) ? $item->nombre : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Nombre Merc. Libre*:</label>
                    <div class="col col-10">
                        <label class="input">
                            <input type="text" id="nombremeli" name="nombremeli" required="" value="{{ ('edit' == $mode) ? $item->nombremeli : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Categoría <br/>Mercadolibre:</label>
                    <div class="col col-10">
                        <div id="cat_meli" style="font-weight:bold;"></div>

                        <a class="btn btn-primary btn-sm" href="javascript:void(0);" role="button" id="edit_cat_meli" onclick="edit_cat_meli();">Editar</a>

                        <a class="btn btn-primary btn-sm" id="buscar_cat_meli" href="javascript:void(0);" role="button" onclick="buscar_cat_meli()">Buscar Categoría</a>
                        
                        <input type="hidden" name="categoria_meli" id="categoria_meli" value="{{ ('edit' == $mode) ? $item->categoria_meli : '' }}" />
                        <input type="hidden" name="categoria_variations" id="categoria_variations" value="{{ ('edit' == $mode) ? $item->categoria_variations : '' }}" />
                    </div>
                </div>
            </section>									
            <section>
                <div class="row">
                    <label class="label col col-2">Genero:</label>
                    <div class="col col-10">
                        <label class="select">
                            <?php $toDropDown4 = $aViewData['aCustomViewData']['aGeneros']->prepend('Seleccione Género', ''); ?>
                            {{ Form::select(
                            'id_genero', 
                            $toDropDown4,
                            ("edit" == $mode) ? $item->id_genero : 0, 
                            ['class' => 'col col-md-12']
                            ) 
                            }}
                            <i></i>
                        </label>
                    </div>
                </div>
            </section>									
            
            <section>
                <div class="row">
                    <label class="label col col-2">Marca:</label>
                    <div class="col col-4">
                        <label class="select">
                            <?php $toDropDown2 = $aViewData['aCustomViewData']['aMarcas']->prepend('Seleccione Marca', ''); ?>
                            {{ Form::select(
                            'id_marca',
                            $toDropDown2,
                            ("edit" == $mode) ? $item->id_marca : '',
                            ['class' => 'col col-md-12', 'id' => 'id_marca']
                            )
                            }}
                            <i></i>
                        </label>
                    </div>
                    <label class="label col col-2">Origen:</label>
                    <div class="col col-4">
                        <label class="select">
                            <?php $toDropDown3 = $aViewData['aCustomViewData']['aPaises'] ?>
                            {{ Form::select(
                            'id_origen',
                            $toDropDown3,
                            ("edit" == $mode) ? $item->id_origen : '1',
                            ['class' => 'col col-md-12', 'id' => 'id_origen']
                            )
                            }}
                            <i></i>
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Modelo:</label>
                    <div class="col col-10">
                        <label class="input">
                            <input type="text" name="modelo" value="{{ ('edit' == $mode) ? $item->modelo : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Estado:</label>
                    <div class="col col-10">
                        <div class="inline-group">
                            <label class="radio">
                                <input type="radio" name="estado" value="Nuevo" {{("edit" == $mode) ? $item->estado=='Nuevo'?'checked=""':'' : 'checked=""'}}>
                                <i></i>Nuevo
                            </label>
                            <label class="radio">
                                <input type="radio" name="estado" value="Usado" {{("edit" == $mode) ? $item->estado=='Usado'?'checked=""':'' : ''}}>
                                <i></i>Usado
                            </label>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Video Youtube:</label>
                    <div class="col col-10">
                        https://www.youtube.com/watch?v=
                        <label>
                            <input type="text" name="id_video" value="{{ ('edit' == $mode) ? $item->id_video : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Etiquetas:</label>
                    <div class="col col-10">
                        <label class="select"> 
                            <select multiple style="width: 100%" class="select2" name="etiquetasIds[]" id="etiquetasIds">
                            </select>
                        </label>
                    </div>
                </div>
            </section>                              
            <section>
                <div class="row">
                    <label class="label col col-2">Deportes:</label>
                    <div class="col col-10">
                        <label class="select"> 
                            <select multiple style="width: 100%" class="select2" name="deportesIds[]" id="deportesIds">
                            </select>
                        </label>
                    </div>
                </div>
            </section>                              
            <section>
                <div class="row">
                    <label class="label col col-2">Alto (cm) *:</label>
                    <div class="col col-4">
                        <label class="input">
                            <input required="required" type="text" name="alto" value="{{ ('edit' == $mode) ? $item->alto : '' }}" />
                        </label>
                    </div>
                    <label class="label col col-2">Ancho (cm) *:</label>
                    <div class="col col-4">
                        <label class="input">
                            <input required="required" type="text" name="ancho" value="{{ ('edit' == $mode) ? $item->ancho : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Largo (cm) *:</label>
                    <div class="col col-4">
                        <label class="input">
                            <input required="required" type="text" name="largo" value="{{ ('edit' == $mode) ? $item->largo : '' }}" />
                        </label>
                    </div>
                    <label class="label col col-2">Peso (gr) *:</label>
                    <div class="col col-4">
                        <label class="input">
                            <input required="required" type="text" name="peso" value="{{ ('edit' == $mode) ? $item->peso : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
            <section>
                <div class="row">
                    <label class="label col col-2">Orden:</label>
                    <div class="col col-10">
                        <label class="input">
                            <input type="text" name="orden" value="{{ ('edit' == $mode) ? $item->orden : '' }}" />
                        </label>
                    </div>
                </div>
            </section>
        
            <section class="section-textarea">
                <div class="row">
                    <label class="label col col-2">Sumario:</label>
                    <div class="col col-10">
                        <label class="textarea">
                            <textarea row="3" name="sumario">{{ ('edit' == $mode) ? $item->sumario : '' }}</textarea>
                        </label>
                    </div>
                </div>
            </section>
        </fieldset>	
            <section class="row">
                <label class="col col-md-2">Texto:</label>
                <label class="textarea  col col-md-10 row">
                    <input type="hidden" name="texto" id="texto" value="" />
                    <div style="border:1px solid #929292" class="no-padding" style="margin:0 5px 5px 0;">
                        <div id="textoBox">{!! ('edit' == $mode) ? $item->texto : '' !!}</div>	
                    </div>
                </label>
            </section>
            <section>
                <label class="label"><b>ASIGNAR Color, Stock y Código</b></label>
                <table class="table table-bordered">
                    <thead>
                        <tr  style="color: #222;">
                            <th>Color</th>
                            <th>Talle</th>
                            <th>Stock</th>
                            <th>Código</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="lista-cod-stock">
                        <tr>
                            <td>
                                <div class="col-12">
                                    <label class="select">
                                        <?php $toDropDownColor = $aViewData['aCustomViewData']['aColores']->prepend('Seleccione Color', ''); ?>
                                        {{ Form::select(
                                        'id_color',
                                        $toDropDownColor,
                                        ("edit" == $mode) ? $item->id_color : '',
                                        ['class' => 'col col-md-12', 'id' => 'id_color']
                                        )
                                        }}
                                        <i></i>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="col-12">
                                    <label class="select">
                                        <?php $toDropDownTalle = $aViewData['aCustomViewData']['aTalles']->prepend('Seleccione Talle', ''); ?>
                                        {{ Form::select(
                                        'id_talle',
                                        $toDropDownTalle,
                                        ("edit" == $mode) ? $item->id_talle : '',
                                        ['class' => 'col col-md-12', 'id' => 'id_talle']
                                        )
                                        }}
                                        <i></i>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div id="stock">
                                    @foreach($aViewData['aCustomViewData']['aScursales'] as $sucursal)
                                    <div class="row">
                                        <div class="col-sm-8">
                                            {{ $sucursal['titulo'] }}
                                        </div>
                                        <div class="col-sm-4">
                                            <input name="stock[]" data-sucursal="{{$sucursal['id']}}" data-sucursaln="{{$sucursal['titulo']}}" type="number" placeholder="0" style="max-width:100%;" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <!--<div class="col-12">
                                    <label class="input">
                                        <input id="stock" name="stock" type="number" placeholder="0" style="max-width:78px;" min="0" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                                    </label>
                                </div>-->
                            </td>
                            <td>
                                <div class="col-12">
                                    <label class="input">
                                        <input id="codigo" name="codigo" type="text" style="max-width:78px;">
                                    </label>
                                </div>
                            </td>
                            <td>
                                <input type="button" class="btn btn-primary" id="agregar-cod-stock" value="Agregar">
                                <input type="button" class="btn btn-default" id="editarFila" onclick="guardarFila(this)" value="Editar" style="display: none;" data-index="">
                                &nbsp;&nbsp;
                                <a href="javascript:;" id="editarFilaCancel" onclick="editarFilaCancel()" style="display: none;"><i class="fa fa-times" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" id="stockColor" name="stockColor" value="" />
                
            </section>
        </fieldset>
    {{ Form::close() }}
    <div id="selectCatMeli" class="hide">
        <div class="close_edit">&times;</div>
        <h3>Seleccionar categoría</h3>
        <div class="cat_meli"></div>
        <table class="table table-bordered">
            <thead>
                <tr style="color: #222;">
                    <th>Nombre</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- end widget content -->