<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\Etiquetas;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\ItemRelated;
use App\AppCustom\Models\ProductosEtiquetas;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Image;
use DB;

class SliderController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		$this->id_idioma = $request->input('idioma');
    }
	public function slider(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$array_slider = array(); 
			//slider news
			$slider_slider = Note::select('id_nota','titulo','antetitulo','sumario','slider_texto')
			->where('id_edicion',$this->filterNote)
			->where('habilitado',1)
			->orderBy('orden','asc')
			->get();

			foreach($slider_slider as $item){
                //Primer Item relacionado al Slider
                $itemRelated = ItemRelated::where('parent_id', $item->id_nota)->orderBy('related_resource')->first();


				$aOItems = FeUtilController::getImages($item->id_nota,1, 'slider');
				if($aOItems){
					$set_array = array(
						'id' => $item->id_nota,
						'titulo' => $item->titulo,
						'antetitulo' => $item->antetitulo,
						'sumario' => $item->sumario,
						'foto' => $aOItems,
						'slider_texto' => $item->slider_texto,
                        'relacionado' => $itemRelated
					);
					array_push($array_slider, $set_array);
				}

			}			
			$aResult['data'] = $array_slider;
			return response()->json($aResult);
		}
	}
    public function sliderEtiquetas(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            $array_slider = array();
            //slider news
            $slider_slider = Etiquetas::select('id','nombre','color')
                ->where('habilitado',1)->where('menu',1)
                ->orderBy('orden','asc')
                ->get();

            foreach($slider_slider as $item){
                $aOItems = FeUtilController::getImages($item->id,1, 'etiquetas');
                if($aOItems){
                    $set_array = array(
                        'id' => $item->id,
                        'nombre' => $item->nombre,
                        'color' => $item->color,
                        'foto' => $aOItems,

                    );
                    array_push($array_slider, $set_array);
                }

            }
        //    \Log::info(print_r( $array_slider,true));
            $aResult['data'] = $array_slider;
            return response()->json($aResult);
        }
    }
	public function destacadosSlider(Request $request) {
        $aResult = Util::getDefaultArrayResult();

        $idNota = $request->input('id_nota');
        $idMoneda = $request->input('id_moneda');

        $slider = Note::find($idNota);

        $items =
            ItemRelated::
            where('parent_id', $idNota)
                ->orderBy('related_resource')
                ->get()

        ;

        $aData = [
            'slider' => $slider,
            'news' => [],
            'productos' => []
        ];
/*        foreach ($items	as $item) {
            $producto = null;
            switch ($item->related_resource) {
                case 'news':
                    $nota = Note::find($item->related_id);
                    if($nota){
                        $data = array(
                            'id' => $item->related_id,
                            'id_seccion' => $nota->id_seccion,
                            'titulo' => $nota->titulo,
                            'sumario' => $nota->sumario,
                            'resource' => 'news'
                        );

                        array_push(
                            $aData['news'],
                            array_merge(
                                $data,
                                ['fotos' =>
                                    Image::where('resource','like','news')
                                        ->where('resource_id', $item->related_id)
                                        ->get()
                                        ->toArray()
                                ]

                            )
                        );
                    }
                    break;
                case 'etiquetas':

                    $prods =
                        ProductosEtiquetas::where('id_etiqueta',$item->related_id)
                            ->select('id_producto')
                            ->get()
                    ;

                    foreach ($prods as &$prod) {

                        if (!Util::in_array($aData['productos'], 'id', $item->related_id)) {

                            $request->request->add([
                                'idProducto' => $prod->id_producto,
                                'id_edicion' => 'companyDefaultId',
                                'edicion' => 'productos',
                                'iDisplayLength' => 10,
                                'orden' => ['col' =>  'id','dir' =>  'asc'],
                            ]);

                            $producto = (new ProductosController($request))->listado($request);
                            $data = json_decode($producto->getContent(),true);

                            if(isset($data['data']['productos'][0])){
                                $producto = $data['data']['productos'][0];
                                array_push(
                                    $aData['productos'],
                                    $producto
                                );
                            }
                        }
                    }

                    break;
                case 'productos':
                    if (!Util::in_array($aData['productos'], 'id', $item->related_id)) {
                        $request->request->add([
                            'idProducto' => $item->related_id,
                            'id_edicion' => 'companyDefaultId',
                            'edicion' => 'productos',
                            'iDisplayLength' => 10,
                            'orden' => ['col' =>  'id','dir' =>  'asc'],
                        ]);

                        $producto = (new ProductosController($request))->listado($request);
                        $data = json_decode($producto->getContent(),true);
                        if(isset($data['data']['productos'][0])){
                            $producto = $data['data']['productos'][0];

                            array_push(
                                $aData['productos'],
                                $producto
                            );
                        }
                    }
                    break;
            }
        }*/
        $aResult['data'] = $aData;

        return response()->json($aResult);
	}
}
