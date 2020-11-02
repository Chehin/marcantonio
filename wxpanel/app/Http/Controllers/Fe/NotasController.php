<?php

namespace App\Http\Controllers\fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Note;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use Carbon\Carbon;

class NotasController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
        $this->fotos = ($request->input('fotos')?$request->input('fotos'):'all');
		$this->id_idioma = $request->input('idioma');
		
		$this->orden = $request->input('orden');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');
		$this->limit = $request->input('limit');
		$this->id_seccion = $request->input('id_seccion');
		$this->id_rel = $request->input('id_rel');
		$this->destacado = $request->input('destacado');

		
		$this->id = $request->input('id');
    }
	public function listado(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view')) {

            $ListaNotas = Note::GetNotas($this->id_seccion);
            $data=$ListaNotas;

            if ($this->fotos>0){
            $ArrayNotas = array();
            foreach ($ListaNotas as $Nota) {

                $Imagen = FeUtilController::getImages($Nota->id_nota, 1, 'news');

                $ArrayAux = array(
                    'id_nota' => $Nota->id_nota,
                    'titulo' => $Nota->titulo,
                    'id_seccion' => $Nota->id_seccion,
                    'seccion'=> $Nota->seccion,
                    'sumario'=> $Nota->sumario,
                    'texto' => $Nota->texto,
                    'imagen' => (isset($Imagen[0]))?$Imagen[0]['imagen']:null,
                    'imagen_file' => (isset($Imagen[0]))?$Imagen[0]['imagen_file']:'',
                    'epigrafe' => (isset($Imagen[0]))?$Imagen[0]['epigrafe']:'',
                );
                array_push($ArrayNotas, $ArrayAux);
            }
        //        \Log::info(print_r($ArrayNotas,true));
                $data=$ArrayNotas;
            }

            $aResult['data']=$data;
        //    \Log::debug('An informational message.'.$this->id_seccion);
        //    \Log::info(print_r($aResult['data'],true));
			return response()->json($aResult);
		} else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
	}
	public function nota(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();        
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
            $aItems = Note::
			select('id_nota')
			->where('id_edicion',$this->filterNote)
			->where('habilitado',1)
			->where('id_nota',$this->id)
			->first();
            \Log::debug('Id Seccion: '.print_r($aItems,true));
			//imagenes
			if($this->fotos){
				$aOItems = FeUtilController::getImages($aItems->id_nota,$this->fotos, $this->resource);
			}else{
				$aOItems = '';
			}
			//idioma texto
			$aItems = FeUtilController::getLenguage($aItems->id_nota, $this->id_idioma);
			
			$seccion = Util::getCategorie($aItems['id_seccion']);
			$aItems['seccion'] = $seccion?$seccion->seccion:'';

			//registro visita a la nota
			FeUtilController::newVisitor($aItems->id_nota, $aItems->titulo);
			
			$data = array(
				'nota' => $aItems,
				'fotos' => $aOItems
			);
			$aResult['data'] = $data;
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

}
