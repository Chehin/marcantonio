@if ('edit' == $aViewData['mode'])
<div class="widget-body">
        {{ Form::open(array('id' => $aViewData['resource'] . 'Form1', 'name' => $aViewData['resource'] . 'Form1')) }}           
            <fieldset class="smart-form">
                <?php $categorias = $aViewData['aCustomViewData']['aCategoriasMeli'];
                      $prod = $aViewData['aCustomViewData']['aProductosMeli'];
                      $atribVal = $aViewData['aCustomViewData']['aValCategoria'];
                ?>
                @if($categorias!='')
                @foreach ($categorias as $item)
                @if($item->values!=1)
                    <section>
                        <div class="row">
                            <label class="label col col-4">  {{$item->categoria}} :</label>
                            <div class="col col-8">
                                <label class="input">
                                    <input type="text" name="{{$item->categoria}}" required="" value="{{ ('edit' == $mode) ? $prod[$item->categoria] : '' }}" />
                                </label>
                            </div>
                        </div>
                    </section>

                @else
                    <section>
                        <div class="row">
                            <label class="label col col-4">Atributos de {{$item->categoria}}:</label>
                            <div class="col col-8">
                                <label class="select">
                                    <?php 
                                        $toDropDown4 = $aViewData['aCustomViewData'][$item->categoria]->prepend('Seleccione Atributo', '');
                                    ?>
                                    
                                    @if(count($atribVal)>0)
                                    @for ($i = 0; $i < count($atribVal); $i++)
                                         @if($atribVal[$i]['categoria']==$item->categoria)
                                            {{ Form::select(
                                                $item->categoria, 
                                                $toDropDown4,
                                                ("edit" == $mode) ? $atribVal[$i]['id']: 0, 
                                                ['class' => 'col col-md-12']
                                                ) 
                                                }}
                                         @endif
                                    @endfor
                                    <i></i>
                                    @else 
                                    {{ Form::select(
                                        $item->categoria, 
                                        $toDropDown4,
                                        ("edit" == $mode) ? $item->categoria : 0, 
                                        ['class' => 'col col-md-12']
                                        ) 
                                        }}
                                        <i></i>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </section>	
                @endif                     
                @endforeach
                @endif
            </fieldset>	
        {{ Form::close() }}
</div>
@endif
<!-- end widget content -->