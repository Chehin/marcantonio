<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AppCustom\Models\ProductoFlexxus;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\SucursalesStock;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Genero;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\SyncFlexxus;
use App\AppCustom\Models\PreciosProductos;

class ApiProductosFlexxusController extends Controller
{
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'productos';
        $this->resourceLabel = 'Productos';
        $this->modelName = 'App\AppCustom\Models\Productos';
    }

    public function store(Request $request){
    
        //Sucursales que maneja la tabla a importar
        $sucursales_array = array('STOCKTOTAL');

        $aResult = Util::getDefaultArrayResult();
        $error = array();

        if($this->user->hasAccess($this->resource . '.create')){

            $dat = $request->input('productos');
            $datos = json_decode($dat, true);

            foreach ($datos as $dato) {
                //$codigoUnico = $dato['CODIGOARTICULO'].'-'.$dato['TALLE'];
                $codigoUnico = $dato['codigo'];
                $id_producto = $dato['id'];

                $codigoStock = CodigoStock::where('codigo','=',$codigoUnico)->first();

                if($codigoStock){
                
                    //Flexxus manda solo una
                    $stock = $dato['STOCKTOTAL'];
                    $sucursal = Note::select('id_nota', 'titulo')->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))->where('id_nota', 1)->first();

                    if ($sucursal) {
                        //actualizo el stock de la sucursal
                        $stock_sucursal = SucursalesStock::where('id_codigo_stock', $codigoStock->id)->where('id_sucursal', $sucursal->id_nota)->first();
                        $stock_sucursal->stock = $stock;
                        $stock_sucursal->save();
                    }else{
                        array_push($error,'error '.$codigoUnico.': Sucursal no encontrada');
                    }
		    
                    //actualizo el stock total
                    $codigoStock->stock = $stock;
                    $codigoStock->codigo_flexxus = $dato['CODIGOARTICULO'];
                    $codigoStock->talle_flexxus = $dato['TALLE'];
                    $codigoStock->estado_flexxus = 1;
                    $codigoStock->save();

                    //ACTUALIZO PRECIO
                    if($codigoStock->id_producto){

                        // Verico si existe el producto 
                        $item = Productos::find($codigoStock->id_producto);

                        if (!$item) {
                            // Si no existe el producto se debe crear
                            // Array para crear un nuevo producto

                            //No sincroniza Productos
			    array_push($error,'error '.$codigoUnico.': El Producto'.$codigoStock->id_producto.' no existe');
                            \Log::info('El Producto'.$codigoStock->id_producto.' no existe');
                        }else{
                             // Se debe actualizar el producto

                            if($dato['LISTA1']){

                                $precio_flexxus = $dato['LISTA1']*1.21;

                                $precio_flexxus = round($precio_flexxus, 2, PHP_ROUND_HALF_UP);
                                // obtengo la moneda por default
                                $moneda_default = Util::getMonedaDefault();
                                $id_moneda = ($moneda_default?$moneda_default[0]['id']:1);

                                // Array para guardar el precio del producto
                                $array_precio = array(
                                    'resource_id' => $item->id,
                                    'id_moneda' => $id_moneda,
                                    'precio_venta' => $precio_flexxus,
                                    //'precio_lista' => $precio_flexxus,
                                    'descuento' => 0
                                );

                                // Obtengo el id del registro en la tabla inv_precios
                                $id_precio = PreciosProductos::select('id','precio_lista', 'descuento', 'precio_venta')
                                ->where('id_moneda','=',$id_moneda)
                                ->where('id_producto','=',$item->id)->first();

                                if ($id_precio) {

                                    //Si el precio es mayor se actualiza
                                    if($precio_flexxus > $id_precio->precio_venta){
                                        $array_precio['precio_lista'] = $id_precio->precio_lista;
                                        $array_precio['descuento'] = $id_precio->descuento;
                                        $request->request->add($array_precio);
                                        $id = $id_precio->id;
                                        // Si tiene un precio cargado actualizo el valor
                                        $aResult = app('App\Http\Controllers\PreciosRelatedController')->update($request,$id);
                                        $aResult = json_decode($aResult->getContent(),true);
                                    }else{
                                        //\Log::info('El precio de '. $codigoUnico .' es menor al precio WEB');
					//\Log::info($precio_flexxus);
					//array_push($error,'warning '.$codigoUnico.': El precio es menor al de la web');
                                        //$aResult = array();
                                        //$aResult['status'] = 200;
                                        //$aResult['msg'] = array('Precio No Actualizado. El precio es menor al precio WEB');
                                    }
                                } else {
                                    $request->request->add($array_precio);
                                    // Si no tiene un precio cargado lo creo
                                    $aResult = app('App\Http\Controllers\PreciosRelatedController')->store($request);
                                    $aResult = json_decode($aResult->getContent(),true);
                                }

                                if ($aResult['status'] == 1) {
                                    array_push($error, 'El precio del producto' . $item->nombre . ', id: ' . $item->id . ' no se pudo actualizar ('.$aResult['msg'][0].')');
                                }                                

                            }else{
                                // obtengo la moneda por default
                                $moneda_default = Util::getMonedaDefault();
                                $id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
                                // Elimino los precios cargados para este producto
                                $id_precio = PreciosProductos::
                                where('id_moneda','=',$id_moneda)
                                ->where('id_producto','=',$item->id)->delete();
                                \Log::info('----BORRAR PRECIO!!----');
                                \Log::info('Flexxus No Envio precio');
				array_push($error,'warning '.$codigoUnico.': se borro el precio');
                            }
                        }
                    }else{
                        \Log::info('El stock fue encontrado pero No tiene Producto Asociado');
			array_push($error,'error '.$codigoUnico.': Producto no encontrado');
                    }
                }else{
                    \Log::info('stock no encontrado');
		    array_push($error,'error '.$codigoUnico.': Codigo no encontrado ');
                }
            }

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        if($error){
            //\Log::error($error);
        }
        $aResult['data'] = $error;
        \Log::info('Sincronizado con flexxus');
        return response()->json($aResult);
    }

    public function productos(Request $request){

        $aResult = Util::getDefaultArrayResult();
        \Log::info('Flexxus news prod');

        if($this->user->hasAccess($this->resource . '.create')){

            $codigos = CodigoStock::select()->where('estado_flexxus', 0)->get();

            $data = array();
            if ($codigos) {
                foreach($codigos as $codigo){
                    $producto = Productos::select('id', 'nombre')->where('id', $codigo->id_producto)->first();
                    if ($producto) {
                        $precio = PreciosProductos::select('precio_venta')->where('id_producto', $producto->id)->first();   
                        if ($precio) {
                            $item = array(
                                'codigo' => $codigo->codigo,
                                'codigo_flexxus' => $codigo->codigo_flexxus,
                                'talle_flexxus' => $codigo->talle_flexxus,
                                'stock' => $codigo->stock,
                                'id_producto' => $producto->id,
                                'nombre' => $producto->nombre,
                                'precio_venta' => $precio->precio_venta,
                            );
        
                            array_push($data, $item);
                        }                     
                    }                    
                }
            }

            $aResult['data'] = $data;
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    public function importCodigos(Request $request){

        $aResult = Util::getDefaultArrayResult();
	$error = array();
	
        if($this->user->hasAccess($this->resource . '.create')){

            $dat = $request->input('productos');
            $datos = json_decode($dat, true);
            
            foreach ($datos as $dato) {
                $item = CodigoStock::where('codigo', $dato['codigo'])->first();
                if($item){
                    $item->codigo_flexxus = $dato['codigo_flexxus'];
		    $item->talle_flexxus = $dato['talle_flexxus'];
		    $item->estado_flexxus = 1;
                    $item->save();
                }else{
			array_push($error, 'Codigo no encontrado. '. $dato['codigo']);
		}
            }

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
	$aResult['data'] = $error;
        return response()->json($aResult);
    }
}
