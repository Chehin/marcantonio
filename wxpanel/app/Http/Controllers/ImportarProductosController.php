<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Maatwebsite\Excel\Facades\Excel;

use App\AppCustom\Models\ProductosImportar;

use App\AppCustom\Models\Productos;
use App\AppCustom\Models\ProductosCodigoStock;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\Deportes;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\SucursalesStock;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\PreciosProductos;

class ImportarProductosController extends Controller
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
		
        $this->resource = 'importarProductos';
		$this->resourceLabel = 'Importar/Sincronizar Productos';
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
					$productosActualizados = 0;
					$data = Excel::load($request->file('file'), function ($reader) {})->toArray();
					if (!empty($data) && count($data) > 0) {
						//pongo update_import en 0
						$update_import = Productos::where('update_import', 1)
						->update(array('update_import' => 0));
						$rowNum = 0;
						foreach ($data as $row) {
							$rowNum++;
							unset($talle);
							unset($cod_producto);
							unset($cod_color);
							unset($rubro);
							unset($subrubro);
							unset($marca);
							unset($genero);
							unset($color);
							unset($talle);

							$codigo_imp = $row['codigo']; //requerido
							$articulo_imp = $row['articulo']; //requerido
							$talle_imp = $row['talle'];
							$color_imp = $row['color'];
							$precio_de_venta_imp = $row['precio_de_venta']; //requerido
							$precio_de_lista_imp = $row['precio_de_lista'];
							$genero_imp = $row['genero'];
							$rubro_imp = $row['rubro']; //requerido
							$subrubro_imp = $row['subrubro'];
							$marca_imp = $row['marca'];
							$origen_imp = $row['origen'];

							$stock_web_imp = $row['stock_web']; //requerido
							//$stock_ecommerce_imp = $row['stk_ecommerce']; //requerido
							//$stock_colon_imp = $row['stk_colon']; //requerido
							//$stock_yerbabuena_imp = $row['stk_yerbabuena']; //requerido
							//$stock_plazoleta_imp = $row['stk_plazoleta']; //requerido

							$descripcion_imp = $row['descripcion'];
							$ean_imp = $row['ean'];
							$sku_imp = $row['sku'];
							$alto_imp = $row['alto'];
							$ancho_imp = $row['ancho'];
							$largo_imp = $row['largo'];
							$peso_imp = $row['peso'];
							
							//if ($codigo_imp && $articulo_imp && $precio_de_venta_imp && $rubro_imp && $stock_ecommerce_imp>=0 && $stock_colon_imp>=0 && $stock_yerbabuena_imp>=0 && $stock_plazoleta_imp>=0) {
							if ($codigo_imp && $articulo_imp && $precio_de_venta_imp && $rubro_imp && $stock_web_imp>=0) {
								//formateo el codigo
								//$codigo_form = explode('.', $codigo_imp);
								//if(isset($codigo_form[1])){
								if($codigo_imp){
									//$talle= $codigo_form[1]; //extraigo el codigo de talle
									//extraer el codigo de color y y del producto 
									//(3 ultimos digitos color)
									//$cod_producto = substr($codigo_form[0],0,-3);
									$cod_producto = $codigo_imp;
									$codigo_imp = $codigo_imp.($talle_imp?'-'.$talle_imp:'');
									//$cod_color = str_replace($cod_producto, '', $codigo_form[0]);
									
									//busco si el producto existe
									/*$item = ProductosCodigoStock::select('id_producto')
									->where('codigo', 'like', $cod_producto.'%')->first();*/
									$item = ProductosCodigoStock::select('id_producto')
									->where('codigo', 'like', $cod_producto.'%')->first();

									//empiezo a crear o actualizar los productos
									
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
									if (isset($origen_imp)) {
										// Verifico si la pais existe
										$origen = Pais::select('id_pais')->where('pais','=',$origen_imp)->first();
										if (!$origen) {
											// Si no existe se debe crear la pais
											$origen = new Pais;
											$origen->pais = $origen_imp;
											$origen->save();
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
											}
										}
									}else{
										$genero_imp = "Unisex";
									}
									// Verifico si el genero existe
									$genero = Genero::select('id')->where('genero','=',$genero_imp)->first();
									if(!$genero) {
										// Si no existe se debe crear la genero 
										$genero = new Genero;
										$genero->genero = $genero_imp;
										$genero->save();
									}
									if (isset($color_imp)) {
										//color
										$color = Colores::select('id')->where('nombre', $color_imp)->where('habilitado', 1)->first();
										if(!$color){
											$color = new Colores;
											$color->nombre = $color_imp;
											$color->habilitado = 1;
											$color->save();
										}
									}
									if (isset($talle_imp)) {
										$talle_equi = Util::equivalencia_talle($genero_imp,$marca_imp,$talle_imp,$rubro->id);
										$talle_equi = $talle_equi?$talle_equi:$talle_imp;
										//talle
										$talle = Talles::select('id')->where('nombre', $talle_equi)->where('habilitado', 1)->first();
										if(!$talle){
											$talle = new Talles;
											$talle->nombre = $talle_equi;
											$talle->habilitado = 1;
											$talle->save();
										}
									}
									$alto_imp = $alto_imp?$alto_imp:10;
									$ancho_imp = $ancho_imp?$ancho_imp:10;
									$largo_imp = $largo_imp?$largo_imp:10;
									$peso_imp = $peso_imp?$peso_imp:100;

									$stock_sucursal_imp = array(
										'sucursal_web' => $stock_web_imp,
										//'stk_ecommerce' => $stock_ecommerce_imp,
										//'stk_colon' => $stock_colon_imp,
										//'stk_yerbabuena' => $stock_yerbabuena_imp,
										//'stk_plazoleta' => $stock_plazoleta_imp
									);
									//$stock_total = $stock_colon_imp+$stock_yerbabuena_imp+$stock_plazoleta_imp;
									$stock_total = $stock_web_imp;
									if(!$item){
										$articulo_imp = ucwords(strtolower(($articulo_imp)));
										$descripcion_imp = ucwords(strtolower(($descripcion_imp)));

										$array_send = array(
											'nombre' => $articulo_imp,
											'ean' => $ean_imp,
											'sku' => $sku_imp,
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

										if (isset($origen)){
											$array_send['id_origen'] = $origen->id_pais;
										} else {
											$array_send['id_origen'] = '';
										}
										
										$request->request->add($array_send);
										$aResult = app('App\Http\Controllers\ProductosController')->store($request);
										$aResult = json_decode($aResult->getContent(),true);
										
										if ($aResult['status'] == 1) {
											$aWarns[] = "El producto no se pudo crear fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
										}else{
											$id_producto = $aResult['id_producto'];
											$aWarns[] = "El producto de la fila {$rowNum} Fue Creado.";

											//CARGAR color, talle y stock
											if(isset($color->id)){
												$colores = Colores::where('id',$color->id)->first();
												if ($colores) {
													$colores->productos()
													->attach($id_producto,['id_talle' => isset($talle)?$talle->id:0,'stock' => $stock_total,'codigo' => $codigo_imp]);

													$att_id = ProductosCodigoStock::select('id')
													->where([
														'id_talle' => isset($talle)?$talle->id:0,
														'stock' => $stock_total,
														'codigo' => $codigo_imp,
														'id_producto' => $id_producto
													])->first();
													//creo el stock por cada sucursal
													foreach ($stock_sucursal_imp as $k => $v) {
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
													}
												}
											}

											//CARGAR PRECIO
											// obtengo la moneda por default
											$moneda_default = Util::getMonedaDefault();
											$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
					
											// Array para guardar el precio del producto
											$array_precio = array(
												'resource_id' => $id_producto,
												'id_moneda' => $id_moneda,
												'precio_venta' => $precio_de_venta_imp,
												'precio_lista' => isset($precio_de_lista_imp)?$precio_de_lista_imp:null
											);											
											$request->request->add($array_precio);
											$aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);					
											$aResult = json_decode($aResult->getContent(),true);
											if ($aResult['status'] == 1) {
												$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
											}
											//update_import
											$update_import = Productos::find($id_producto);
											$update_import->habilitado = 0;
											$update_import->update_import = 1;
											$update_import->save();

										}
									}else{
										$id_producto = $item->id_producto;
										$item = Productos::find($id_producto);
										
										$articulo_imp = ucwords(strtolower(($articulo_imp)));
										$descripcion_imp = ucwords(strtolower(($descripcion_imp)));
										// Se debe actualizar el producto
										$array_send = array(
											'ean' => $ean_imp,
											'sku' => $sku_imp,
											'nombre' => $articulo_imp,											
											'id_rubro' => $item->id_rubro,
											'id_subrubro' => $item->id_subrubro,
											'id_marca' => $item->id_marca,
											'id_genero' => $item->id_genero,
											'alto' => $item->alto,
											'ancho' => $item->ancho,
											'largo' => $item->largo,
											'peso' => $item->peso,
											'orden' => $item->orden,
											'texto' => $item->texto,
											'habilitado' => $item->habilitado
										);
										if($descripcion_imp!=''){
											$array_send['sumario'] = $descripcion_imp;
										}else{
											$array_send['sumario'] = $item->sumario;
										}
										
										$request->request->add($array_send);
										$aResult = app('App\Http\Controllers\ProductosController')->update($request,$item->id);
										$aResult = json_decode($aResult->getContent(),true);
										if ($aResult['status'] == 1) {
											$aWarns[] = "El producto no se pudo actualizar fila {$rowNum}. No Importado";
										}else{
											$aWarns[] = "El producto de la fila {$rowNum} Fue Actualizado.";

											//CARGAR color, talle y stock
											$colores = Colores::where('id',$color->id)->first();
											if ($colores) {
												if(isset($color->id)){
													$att_id = ProductosCodigoStock::select('id')
													->where([
														'id_color' => $color->id,
														'id_talle' => isset($talle)?$talle->id:0,
														'codigo' => $codigo_imp,
														'id_producto' => $item->id
													])->first();
													if($att_id){
														//actualizo el stock
														$att_id->stock = $stock_total;
														$att_id->save();
													}else{
														$colores->productos()
														->attach($item->id,['id_talle' => isset($talle)?$talle->id:0,'stock' => $stock_total,'codigo' => $codigo_imp]);
														$att_id = ProductosCodigoStock::select('id')
														->where([
															'id_color' => $color->id,
															'id_talle' => isset($talle)?$talle->id:0,
															'codigo' => $codigo_imp,
															'id_producto' => $item->id
														])->first();
													}
													$stock_sucursalD = SucursalesStock::
													where('id_codigo_stock', $att_id->id)
													->delete();
													foreach ($stock_sucursal_imp as $k => $v) {
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
													}
												}
											}

											//Actualizar PRECIO
											// obtengo la moneda por default
											$moneda_default = Util::getMonedaDefault();
											$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
					
											// Array para guardar el precio del producto
											$array_precio = array(
												'resource_id' => $id_producto,
												'id_moneda' => $id_moneda,
												'precio_venta' => $precio_de_venta_imp,
												'precio_lista' => isset($precio_de_lista_imp)?$precio_de_lista_imp:null
											);	
											// Obtengo el id del registro en la tabla inv_precios
											$id_precio = PreciosProductos::
											select('id')
											->where('id_moneda','=',$id_moneda)
											->where('id_producto','=',$item->id)
											->first();
											$request->request->add($array_precio);
											if ($id_precio) {
												$id_precio = $id_precio->id;
												// Si tiene un precio cargado actualizo el valor
												$aResult = app('App\Http\Controllers\PreciosRelatedController')->update($request,$id_precio);
											} else {
												// Si no tiene un precio cargado lo creo
												$aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);
											}
											$aResult = json_decode($aResult->getContent(),true);
											if ($aResult['status'] == 1) {
												$aWarns[] = "El precio no se pudo actualizar para la fila {$rowNum}. No Importado";
											}
										}
										//update_import
										$item->update_import = 1;
										$item->save();
									}
									$productosActualizados++;
								}else{
									$aWarns[] = "El codigo está vacía en la fila {$rowNum}. No Importado";	
								}
							} elseif(!$codigo_imp){
								$aWarns[] = "El codigo está vacía en la fila {$rowNum}. No Importado";
							} elseif(!$articulo_imp) {
								$aWarns[] = "El articulo está vacío en la fila {$rowNum}. No Importado";
							} elseif(!$precio_de_venta_imp) {
								$aWarns[] = "La precio_de_venta está vacía en la fila {$rowNum}. No Importado";
							} elseif(!$rubro_imp) {
								$aWarns[] = "El precio está vacío en la fila {$rowNum}. No Importado";
							} elseif(!$stock_colon_imp || !$stock_yerbabuena_imp || !$stock_plazoleta_imp) {
								$aWarns[] = "El stock está vacío en la fila {$rowNum}. No Importado";
							}
						}
						if ($productosActualizados > 0) {
							$usuarioProducto = new ProductosImportar;
							$usuarioProducto->id_usuario = \Sentinel::getUser()->id;
							$usuarioProducto->save();
							//elimino stock
							/*$noImport = Productos::select('id')->where('update_import', 0)->get();
							foreach($noImport as $item){
								$color_stock = ProductosCodigoStock::select('id')->where('id_producto', $item->id)->get();
								foreach($color_stock as $it1){
									$su_del = SucursalesStock::where('id_codigo_stock', $it1->id)
									->update(array('stock' => 0));
									$stock_del = ProductosCodigoStock::where('id', $it1->id)
									->update(array('stock' => 0));
								}
							}*/
						} else {
							$aResult['status'] = 1;
							$aResult['msg'] = 'No se ha podido actualizar. Verifique los datos de la planilla o el tipo de archivo';
						}
					}			
				} catch (\Exception $e) {
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
			'lastUpdate' => ImportarProductosUtilController::getLastUpdate(), 
			'aResult' => $aResult
		];
		
		return response()
            ->view('productos.importarProductos.importarProductos', ['aViewData' => $aViewData])
			;
    }
}
