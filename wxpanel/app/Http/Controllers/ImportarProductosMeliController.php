<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Maatwebsite\Excel\Facades\Excel;
use App\AppCustom\Models\ProductosImportarMeli;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\ProductosCodigoStock;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\SucursalesStock;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\ProductosCategMeli;
use App\AppCustom\Models\CategoriasMeli;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Carbon\Carbon;


class ImportarProductosMeliController extends Controller
{
    //use ResourceTraitController;

	public $resource;
    public $resourceLabel;
	public $filterNote;
	public $viewPrefix = '';
	public $modelName = '';
	/**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
		
		parent::__construct($request);
		
        $this->resource = 'importarProductosMeli';
		$this->resourceLabel = 'Importar Productos Meli';
		$this->viewPrefix = 'productos';

		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '512M');
    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function procesar(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		
		if ($this->user->hasAccess($this->resource . '.create')) {
			$aWarns = [];
			if (!$request->file('file')) {
				$aResult['status'] = 1;
				$aResult['msg'] = 'Debe seleccionar un archivo';
			} else {				
				try {
					$rowNum = 1;
					$arrayids = [];
					$arrayidsmeli = [];
						
			
					$data = Excel::load($request->file('file'), 
								  function ($reader) {	
										  $reader->setSeparator(' ');
										  $reader->setHeaderRow(1); //indica en que fila empieza la cabecera
										  $reader->ignoreEmpty(); }, 'ISO-8859-1')
										  ->toArray();

					
															  
					if (!empty($data) && count($data) > 0) {
							//for ($i=0; $i <count($data) ; $i++) { //el for lo uso para procesar multiples pestañas de un excel, comienzo por la pestaña 1
							
							//primero cargo los productos
							foreach ($data[1] as $row) {
								//\Log::info($row);
								//los datos del excel contienen caracteres \n y \t se deben limpiar para que no se guarden en la base
								$alto_imp = 10;
								$ancho_imp = 10;
								$largo_imp = 10;
								$peso_imp = 100;
								$sku = (isset($row['sku']))? Util::limpiar($row['sku']) : '';
								$articulo_imp = $row['titulo ingresa solo producto marca y modelo obligatorio'];//$row['titulo maximo 60 caracteres']; //requerido
								$descripcion_imp = (isset($row['descripcion']))? $row['descripcion'] : '';
								$genero_imp = (isset($row['genero obligatorio']))? Util::limpiar($row['genero obligatorio']) : '';
								$rubro_imp = Util::limpiar($row['rubro']); //requerido
								$subrubro_imp = (isset($row['subrubro']))? Util::limpiar($row['subrubro']) : '';
								$marca_imp = (isset($row['marca']))? Util::limpiar($row['marca']) : Util::limpiar($row['marca obligatorio']);
								$condicion_imp = Util::limpiar($row['condicion obligatorio']);		
								$modelo_imp = Util::limpiar($row['modelo']);
										
								if(isset($sku)){

									$item = Productos::select('id')
									->where('nombre', '=', $articulo_imp)->first();

									// Verifico si el rubro existe
									if (isset($rubro_imp)) {
										$rubro_imp = ucwords(strtolower(($rubro_imp)));
										$rubro = Rubros::select('id')->select('id')->where('nombre','=',$rubro_imp)->first();
										if (!$rubro) {
											// Si no existe se debe crear el rubro
											$array_rubro = array(
												'nombre' => $rubro_imp
											);
											$request->request->add($array_rubro);
											$aResult = app('App\Http\Controllers\RubrosController')->store($request);
											$aResult = json_decode($aResult->getContent(),true);
											$rubro = Rubros::select('id')->where('nombre','=',$rubro_imp)->first();
											
										}	

										if (isset($subrubro_imp)) {
											$subrubro_imp = ucwords(strtolower(($subrubro_imp)));
											// Verifico si el subrubro existe
											$subrubro = SubRubros::
											select('id')
											->where('nombre','=',$subrubro_imp)
											->where('id_rubro', $rubro->id)
											->first();
						
											if (!$subrubro) {

												// Si no existe se debe crear el subrubro
												$array_subrubro = array(
													'nombre' => $subrubro_imp,
													'id_rubro' => $rubro->id,
													'orden' => 0
												);
												$request->request->add($array_subrubro);
												$aResult = app('App\Http\Controllers\SubRubrosController')->store($request);
												$aResult = json_decode($aResult->getContent(),true);
												$subrubro = SubRubros::
												select('id')->where('nombre','=',$subrubro_imp)
												->where('id_rubro', $rubro->id)
												->first();
											}
										}
																
									}

									if (isset($marca_imp)) {
										$marca_imp = ucwords(strtolower(($marca_imp)));
										// Verifico si la marca existe
										$marca = Marcas::select('id')->where('nombre','=',$marca_imp)->first();
										if (!$marca) {
											// Si no existe se debe crear la marca 
											$array_marca = array(
												'nombre' => $marca_imp
											);
											$request->request->add($array_marca);
											$aResult = app('App\Http\Controllers\MarcasController')->store($request);
											$aResult = json_decode($aResult->getContent(),true);
											$marca = Marcas::select('id')->where('nombre','=',$marca_imp)->first();
										}
										
									}

									if (isset($genero_imp)) {
										if($genero_imp=='NO TIENE'){
											$genero_imp = "Unisex";
										}else{
											$genero_imp = ucwords(strtolower(($genero_imp)));
											if($genero_imp=='Femenino'){
												$genero_imp = 'Mujer';
											}elseif($genero_imp=='Masculino'){
												$genero_imp = 'Hombre';
											}elseif($genero_imp=='Masculino'){
												$genero_imp = 'Unisex';
											}
										}
									}else{
										$genero_imp = "Unisex";
									}

									// Verifico si el genero existe
									$genero = Genero::select('id','genero')->where('genero','=',$genero_imp)->first();
									if(!$genero) {
										// Si no existe se debe crear la genero 
										$genero = new Genero;
										$genero->genero = $genero_imp;
										$genero->save();
									}									

									$array_send = array(
										'nombre' => $articulo_imp,
										'nombremeli' => $articulo_imp.' '.$genero->genero,
										'orden' => 0,
										'habilitado' => 0
									);
										
									$array_send['alto'] = $alto_imp;
									$array_send['ancho'] = $ancho_imp;
									$array_send['largo'] = $largo_imp;
									$array_send['peso'] = $peso_imp;

									if ($descripcion_imp!=''){
										$array_send['sumario'] = $descripcion_imp;
									} else {
										$array_send['sumario'] = '';
									}
									
									if (isset($rubro)){
										$array_send['id_rubro'] = $rubro->id;
									} else {
										$array_send['id_rubro'] = '';
									}

									if (isset($subrubro)){
										$array_send['id_subrubro'] = $subrubro->id;
									} else {
										$array_send['id_subrubro'] = '';
									}

									if (isset($marca)){
										$array_send['id_marca'] = $marca->id;
									} else {
										$array_send['id_marca'] = '';
									}

									if (isset($genero)){
										$array_send['id_genero'] = $genero->id;
									} else {
										$array_send['id_genero'] = '';
									}

									if (isset($condicion_imp)){
										$array_send['estado'] = $condicion_imp;
									} else {
										$array_send['estado'] = '';
									}

									if (isset($modelo_imp)){
										$array_send['modelo'] = $modelo_imp;
									} else {
										$array_send['modelo'] = '';
									}
										
									$array_send['id_api'] = 1;

								if(empty($item)){										
									$request->request->add($array_send);
									$aResult = app('App\Http\Controllers\ProductosController')->storeImport($request);
									$aResult = json_decode($aResult->getContent(),true);																				
										
									if ($aResult['status'] == 1) {
										$aWarns[] = "El producto no se pudo crear fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
									}else{
										$id_producto = $aResult['id_producto'];
										$aWarns[] = "El producto de la fila {$rowNum} Fue Creado.";

										
										//update_import
										$update_import = Productos::find($id_producto);
										$update_import->habilitado = 0;
										$update_import->update_import = 1;
										$update_import->save();
										
									}
									
								}else{									
									$request->request->add($array_send);
									$aResult = app('App\Http\Controllers\ProductosController')->update($request,$item['id']);
									$aResult = json_decode($aResult->getContent(),true);																				
									
									if ($aResult['status'] == 1) {
										$aWarns[] = "El producto no se pudo actualizar en la fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
									}else{
										$id_producto = $item['id'];
										$aWarns[] = "El producto de la fila {$rowNum} Fue Actualizado.";
										
										//update_import
										$update_import = Productos::find($id_producto);
										$update_import->habilitado = 0;
										$update_import->update_import = 1;
										$update_import->save();										
									}																	
								}//fin if (item)
								}//fin del if sku	
															
								
								//************************************************************************************* */

								$talle_imp =  (isset($row['variante talle obligatorio']))? Util::limpiar($row['variante talle obligatorio']) : 'Talle Unico';
								$color_imp = (isset($row['variante color obligatorio']))? Util::limpiar($row['variante color obligatorio']) : 'SIN COLOR';
								$precio_de_venta = $row['precio obligatorio']; //requerido								
								$stock_imp = Util::limpiar($row['cantidad obligatorio']);
								$gtin_imp = Util::limpiar($row['codigo universal de producto']);

								//hago un pre control del talle porque MELI cambia el nombre de las columnas							
								if ($talle_imp=='Talle Unico') {	
									$talle_imp =  (isset($row['variante talle']))? Util::limpiar($row['variante talle']) : 'Talle Unico';
								}

								if ($talle_imp=='Talle Unico') {	
									$talle_imp =  (isset($row['varia por talle obligatorio']))? Util::limpiar($row['varia por talle obligatorio']) : 'Talle Unico';
								}

								if ($talle_imp=='Talle Unico') {	
									$talle_imp =  (isset($row['varia por talle']))? Util::limpiar($row['varia por talle']) : 'Talle Unico';
								}
								
								if ($color_imp=='SIN COLOR') {	
									$color_imp =  (isset($row['varia por color obligatorio']))? Util::limpiar($row['varia por color obligatorio']) : 'SIN COLOR';
								}
											
								if ($color_imp=='SIN COLOR') {	
									$color_imp =  (isset($row['varia por color']))? Util::limpiar($row['varia por color']) : 'SIN COLOR';
								}
								
								if(isset($sku)){

									$item = Productos::select('id','nombre')
									->where('nombre', '=', $articulo_imp)->first();
											
								if(!empty($item)){
									
									if (isset($color_imp)) {
										//color
										$color = Colores::select('id')->where('nombre', $color_imp)->where('habilitado', 1)->first();
										if(!$color){
											$color = new Colores;
											$color->nombre = $color_imp;
											$color->habilitado = 1;
											$color->save();
										}

										//hago el control con el color bordo.
										if($color_imp=='Bordó' || $color_imp=='Bordo'){
											$color->id = 224;
										}
									}
							
									if (isset($talle_imp)) {										
										//talle										
										$talle = Talles::select('id')->where('nombre', $talle_imp)->where('habilitado', 1)->first();
										
										if(!$talle){										
											$talle = new Talles;
											$talle->nombre = $talle_imp;
											$talle->habilitado = 1;
											$talle->save();
										}
									} 
								
									$stock_sucursal_imp = array(
										'sucursal_web' => $stock_imp
									);

									$stock_total = $stock_imp;							
									
									if($articulo_imp==$item['nombre']){
										//CARGAR color, talle y stock
										if(isset($color->id)){
											$colores = Colores::where('id',$color->id)->first();
											if ($colores) {														
												$att_id = ProductosCodigoStock::select('id')
												->where([
													//'id_talle' => isset($talle)?$talle->id:0,
													//'stock' => $stock_total,
													'codigo' => $sku,
													'id_producto' => $item['id']
												])->first();

												
												if(!empty($att_id)){
													//actualizo el stock
													$att_id->stock = $stock_total;
													$att_id->save();
												}else{
													
													$colores->productos()
															->attach($item['id'],['id_talle' => isset($talle)?$talle->id:0,'stock' => $stock_total,'codigo' => $sku,'gtin'=> !empty($gtin_imp)?$gtin_imp:'']);
													
													$att_id = ProductosCodigoStock::select('id')
															->where([
																//'id_color' => $color->id,
																//'id_talle' => isset($talle)?$talle->id:0,
																'codigo' => $sku,
																'id_producto' => $item['id']
															])->first();
															
													//creo el stock por cada sucursal
													$stock_sucursal = new SucursalesStock;
													$stock_sucursal->id_codigo_stock = $att_id->id;
													$stock_sucursal->id_sucursal = 1;
													$stock_sucursal->stock = $stock_imp;
													$stock_sucursal->save(); 
													
													/* foreach ($stock_sucursal_imp as $k => $v) {
														$sucursal = Note::
														select('id_nota as id')
														->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))
														->where('antetitulo', $k)
														->first();
														if($sucursal){
															$stock_sucursal = new SucursalesStock;
															$stock_sucursal->id_codigo_stock = $att_id->id;
															$stock_sucursal->id_sucursal = $sucursal->id;
															$stock_sucursal->stock = $v;
															$stock_sucursal->save();
														}
													}  */

												}
												
											}
										}

										//CARGAR PRECIO
										// obtengo la moneda por default
										$moneda_default = Util::getMonedaDefault();
										$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
				
										// Array para guardar el precio del producto
										$array_precio = array(
											'resource_id' => $item['id'],
											'id_moneda' => $id_moneda,
											'precio_venta' => $precio_de_venta,
											'precio_lista' => null
										);					
															
										$request->request->add($array_precio);
										$aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);					
										$aResult = json_decode($aResult->getContent(),true);
										if ($aResult['status'] == 1) {
											$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
										}
									}					
								}//fin del if item
								}	


								//************************************************************************************* */
								
								  /* if(!empty($articulo_imp)){
									$item = Productos::select('id')
									->where('nombre', '=', $articulo_imp)->first();									
									if(!empty($item)){										
										if (!in_array($item['id'], $arrayids)) {
											try {
												$clientGet = new Client([
													'base_uri' => env('FE_URL'),
												]);
												$articulo_imp=preg_replace('([^A-Za-z0-9 ])', '', $articulo_imp);
												$response = $clientGet->request('GET', 'wxpanel/rest/v2/categoryPredict/'.$articulo_imp);												
												$responseGet=$response->getBody()->getContents();
												$verificationId=json_decode($responseGet,true); 	
												$ind = count($verificationId['data']['path_from_root'])-1;																										
												array_push($arrayidsmeli,$verificationId['data']['path_from_root'][$ind]['id']);
												//return $verificationId;				
											} catch (ClientException  $e) {
												return $e->getResponse()->getBody()->getContents();
											} 

											array_push($arrayids,$item['id']);
																																	
											$categorias = CategoriasMeli::where('id_meli_categoria',$verificationId['data']['path_from_root'][$ind]['id'])->get();
											$prod = ProductosCategMeli::where('idproducto',$item['id'])->first();
											
											$producto = Productos::find($item['id']);
											$producto->categoria_meli = $verificationId['data']['path_from_root'][$ind]['id'];
											$producto->save();
											
											$array_cat = [
												'idproducto' => $item['id'],
												'idcategoriameli' => $verificationId['data']['path_from_root'][$ind]['id'],
												'titulo' => $articulo_imp,
												'Marca' => $marca_imp,
											];
																						
											foreach($categorias as $cat){
												if(isset($row[strtolower($cat['categoria']) ])){													
													if($cat['categoria']!='SKU' && $cat['categoria']!='Codigo universal de producto'){
														$array_cat[ $cat['categoria'] ] = $row[strtolower($cat['categoria'])];
														//array_push($array_cat,[ '"'.$cat['categoria'].'"' => $row[strtolower($cat['categoria'])]]);
													}												
												}
											}
											
											if(empty($prod)){																							
												$save = new ProductosCategMeli();												
												$save->updateOrCreate($array_cat);	
											}else{																																																						
												$save = new ProductosCategMeli();																	
												$save->update($array_cat);																						
											}
										
										} //fin del if in_array
									}

								}  */


							}//fin 1 forearch

							
							/* //cargo los items del producto/meli
							foreach ($data[1] as $row) {													
								
							} 

							//Para cargar los datos de las variantes
							foreach ($data[1] as $row) {												
										
							} */
							
							
						}
					
						
				}catch (\Exception $e) {
					$aResult['status'] = 1;
					$aResult['msg'] = $e->getMessage();
				}
			}
		} else {
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		
		if ($aWarns) {
			$aResult['status'] = 2;
            $aResult['msg'] = \config('appCustom.messages.someWarnings');
			$aResult['data'] = $aWarns;
		}
		
		$aViewData = [
			'lastUpdate' => ImportarProductosMeliUtilController::getLastUpdate(), 
			'aResult' => $aResult
		];
		
		return response()
            ->view('productos.importarProductosMeli.importarProductosMeli', ['aViewData' => $aViewData])
			;
    }
}
