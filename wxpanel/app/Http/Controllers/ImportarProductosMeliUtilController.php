<?php

namespace App\Http\Controllers;

class ImportarProductosMeliUtilController extends Controller
{
    public function __construct(ImportarProductosMeliController $res) {
		
		parent::__construct();
		
		$this->resource = $res->resource;
		$this->resourceLabel = $res->resourceLabel;
		$this->viewPrefix = $res->viewPrefix;
		$this->user = $res->user;
	}
	
	public function showMainView() {

		if ($this->user->hasAccess($this->resource . '.view')) {
			$aViewData = array();
			$aViewData['resource'] = $this->resource;
			$aViewData['resourceLabel'] = $this->resourceLabel;
			$aViewData['lastUpdate'] = static::getLastUpdate();
					
			return 
				\View::make($this->viewPrefix . '.' . $this->resource . '.' . $this->resource)
					->with('aViewData', $aViewData)
				;
		} else {
				return \View::make('errors.unauthorized');
		}
	}
	
	static function getLastUpdate() {
		return  
				\App\AppCustom\Models\ProductosImportarMeli::
					select('inv_productos_importar_meli.created_at','a.first_name','a.last_name')
					->orderBy('inv_productos_importar_meli.created_at', 'desc')
					->join('users as a', 'a.id','=','inv_productos_importar_meli.id_usuario')
					->first()
				;
	}
}
