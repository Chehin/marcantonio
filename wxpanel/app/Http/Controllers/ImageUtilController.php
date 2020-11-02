<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUtilController extends Controller
{
	
	static function getParameters(Request $request) {
		$routeName = $request->route()->getName();
		
		$aParams = [];
		if (strpos($routeName, 'rubrosImage') !== false) {
			$res = new RubrosController($request);
			$aParams['resource'] = $res->resource;
		}

		if (strpos($routeName, 'etiquetasImage') !== false) {
			$res = new EtiquetasController($request);
			$aParams['resource'] = $res->resource;
		}

		if (strpos($routeName, 'marcasImage') !== false) {
			$res = new MarcasController($request);
			$aParams['resource'] = $res->resource;
		}  

		if (strpos($routeName, 'productosImage') !== false) {
			$res = new ProductosController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'newsImage') !== false) {
			$res = new NewsController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'coloresImage') !== false) {
			$res = new ColoresController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'listasImage') !== false) {
			$res = new ListasController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'sliderImage') !== false) {
			$res = new SliderController($request);
			$aParams['resource'] = $res->resource;
		}
		
		if (strpos($routeName, 'pedidosClientesImage') !== false) {
			$res = new PedidosClientesController($request);
			$aParams['resource'] = $res->resource;
		}
		
		return $aParams;
		
	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
}
