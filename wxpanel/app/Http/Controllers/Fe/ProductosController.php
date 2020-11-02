<?php

namespace App\Http\Controllers\fe;

use App\AppCustom\Cart;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\PreciosProductos;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\CodigoStock;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\EtiquetasRubros;
use App\AppCustom\Models\ProductosEtiquetas;
use App\AppCustom\Models\ProductosDeportes;
use App\AppCustom\Models\Deportes;
use App\AppCustom\Models\ProductStatistic;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\Genero;
use Carbon\Carbon;

class ProductosController extends Controller
{
	public function __construct(Request $request)
    {
		parent::__construct($request);
		
        $this->resource = $request->input('edicion');
		$this->filterNote = \config('appCustom.'.$request->input('id_edicion'));
		
		$this->fotos = ($request->input('fotos')?$request->input('fotos'):'all');		
		$this->orden = $request->input('orden');
		$this->iDisplayLength = $request->input('iDisplayLength');
		$this->iDisplayStart = $request->input('iDisplayStart');
		$this->limit = $request->input('limit');
		$this->id_relacion = $request->input('id_relacion');
		$this->forzar = $request->input('forzar');
		$this->id_moneda = $request->input('id_moneda');
		$this->filtros = $request->input('filtros');
		$this->tag = $request->input('tag');
		$this->IdDeporte = $request->input('IdDeporte');
		$this->search = $request->input('search');
		$this->IdMarca = $request->input('IdMarca');
		$this->MasVistos = $request->input('MasVistos');
		$this->idProducto = $request->input('idProducto');
        $this->MasVendidos = $request->input('MasVendidos');
        $this->Destacados = $request->input('Destacados');
        $this->precios = $request->input('precios');
        $this->Ofertas = $request->input('Ofertas');
    }
	public function rubros(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
			$rubros = Util::getRubros('array', true);
			foreach($rubros as $rubro){
				$subrubros = Util::getSubRubros($rubro['id'],'array', true);
				$array_subrubro = array();
				foreach($subrubros as $subrubro){
					$data1 = array(
						'id' => $subrubro['id'],
						'text' => $subrubro['text'],
						'cantidad' => $subrubro['cantidad'],
						'subsubrubros' => Util::getSubSubRubros($subrubro['id'],'array', true)
					);
					array_push($array_subrubro,$data1);
				}
				$data = array(
					'id' => $rubro['id'],
					'text' => $rubro['text'],
					'cantidad' => $rubro['cantidad'],
					'subrubros' => $array_subrubro
				);
				array_push($aResult['data'],$data);
			}
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
    }

    public function productosHome(Request $request){
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            if($this->Ofertas) {
                $ListaProductos = Productos::GetOfertas()->limit(10)->get();
            }

            if($this->MasVendidos) {
                $ListaProductos = Productos::GetMasVendidos()->limit(10)->get();
            }
            if ($this->Destacados){
                $ListaProductos = Productos::select('id','nombre')
                    ->where('destacado',1)
                        ->where('habilitado',1)
                            ->get();
            }
            if ($this->MasVistos) {
                $ListaProductos = Productos::GetMasVistos()->limit(10)->get();
            //    \Log::info(print_r($ListaProductos,true));
            }
             $ArrayProductos = Productos::SetImagenPrecios($ListaProductos,$this->id_moneda);
        //\Log::debug('Mas vendidos: '.$this->MasVistos.' '.print_r($ArrayProductos,true));
      //  \Log::info(print_r($ArrayProductos,true));

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
       // $data['productos']=;
        $aResult['data']=$ArrayProductos;
        return response()->json($aResult);
    }

    public function deportes(Request $request){
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            //marcas
            $ListaDeportes = Deportes::select('id','nombre')->where('menu',1)->where('habilitado',1)->get();
            $ArrayDeportes = array();
            foreach ($ListaDeportes as $Deporte){

               // $Imagen = FeUtilController::getImages($Deporte->id,1,'deporte');
                $ArrayAux = array(
                    'Id' => $Deporte->id,
                    'Deporte' => $Deporte->nombre,
                 //   'Imagen' => $Imagen[0]['imagen'],
                   // 'ArchivoImagen' =>$Imagen[0]['imagen_file'],
                );
                array_push($ArrayDeportes,$ArrayAux);
            }
      //      \Log::info(print_r($ArrayDeportes,true));

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
      //  $data['deportes']=$ArrayDeportes;
        $aResult['data']=$ArrayDeportes;
        return response()->json($aResult);
    }
    public function servicios(Request $request){
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            //marcas
            $ListaServicios = Note::select('id_nota','titulo','texto','icono')->where('id_seccion',4)->where('habilitado',1)->get()->toArray();
         //   $ArrayServicios = array();

         //   \Log::info(print_r($ListaServicios,true));

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        $aResult['data']=$ListaServicios;
        return response()->json($aResult);
    }
	public function listado(Request $request)
    {

        \Log::debug('Inicio Listado: '.date('H:i:s', time()));
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$aResult['data']['productos'] = array();
			$aResult['data']['total'] = array();
			$pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $limit = $this->limit;
            $currentPage = ($offset / $pageSize) + 1;
			$sort = $this->orden;
			$rand = false;
            $marca_array['nombre'] =null;
            $rubro_array['id_subrubro'] =null;
            $rubro_array['subrubro'] =null;
            $rubro_array['nombre'] = null;
            $rubro_array['id'] = null;
            $aResult['data']['rubro'] = $rubro_array;
            $IdRubro = (isset($this->filtros['id_rubro']))?$this->filtros['id_rubro']:0;
            $IdSubrubro = (isset($this->filtros['id_subrubro']))?$this->filtros['id_subrubro']:0;
            $IdDeporte = (isset($this->filtros['IdDeporte']))?$this->filtros['IdDeporte']:0;



			if($sort=='rand'){
				$rand = true;
			}else{
				$sortDir = $sort['dir'];
				$sortCol = $sort['col'];
			}
			Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
			$etiqueta_array = array();
            $deporte_array = array();

			$aResult['data']['etiqueta'] = null;
            $search = $this->search;
            $ListaProductos = Productos::
            selectRaw("inv_productos.id, inv_productos.nombre, inv_productos.id_rubro, inv_productos.id_subrubro, inv_productos.oferta,inv_productos_estadisticas.visitas")
                ->leftJoin('inv_precios', 'inv_precios.id_producto', '=', 'inv_productos.id')
                ->leftJoin('inv_productos_estadisticas','inv_productos.id','=','inv_productos_estadisticas.id_producto')
                ->where('inv_productos.habilitado', 1)
                ->groupBy('inv_productos.id');

            if ($this->IdDeporte){
                $ListaProductos = $ListaProductos
                    ->leftJoin('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
                    ->where('inv_productos_deportes.id_deporte',$this->IdDeporte);

                $deporte = Deportes::find($this->IdDeporte);
                $deporte_array['id'] = $deporte->id;
                $deporte_array['nombre'] = $deporte->nombre;
                $aResult['data']['deporte'] = $deporte_array;
            }

            if ($this->tag){
                $etiqueta = Etiquetas::find($this->tag);
                $nombreEtiqueta=substr($etiqueta->nombre,0,-1);
                $genero = Genero::where('genero','like','%'.$nombreEtiqueta.'%')->first();


                if ($genero){
                    $ListaProductos = $ListaProductos
                        ->leftJoin('conf_generos','conf_generos.id','=','inv_productos.id_genero')
                        ->where('conf_generos.genero','like','%'.$nombreEtiqueta.'%');
                }
            else
                $ListaProductos = $ListaProductos
                        ->leftJoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
                        ->where('inv_productos_etiquetas.id_etiqueta',$this->tag);


                $etiqueta_array['id'] = $etiqueta->id;
                $etiqueta_array['nombre'] = $etiqueta->nombre;
                $aResult['data']['etiqueta'] = $etiqueta_array;
                //\Log::debug('Etiqueta: '.$ListaProductos->toSql());
                }

                if ($IdSubrubro){

                $ListaProductos =  $ListaProductos
                    ->where('id_subrubro',$this->filtros['id_subrubro'])
                    ->where('id_rubro',$this->filtros['id_rubro']);

                $subrubro = SubRubros::find($IdSubrubro);
                $rubro = Rubros::find($IdRubro);
                $rubro_array['id_subrubro'] = $subrubro->id;
                $rubro_array['subrubro'] = $subrubro->nombre;
                $rubro_array['nombre'] = $rubro->nombre;
                $rubro_array['id'] = $rubro->id;

                $aResult['data']['rubro'] = $rubro_array;
            }
            elseif ($IdRubro && $IdSubrubro==0){
                $ListaProductos = $ListaProductos
                    ->where('inv_productos.id_rubro',$IdRubro);
                $rubro = Rubros::find($IdRubro);
                //\Log::debug('genero');
                $rubro_array['id'] = $rubro->id;
                $rubro_array['nombre'] = $rubro->nombre;
                $rubro_array['id_subrubro'] = null;
                $rubro_array['subrubro'] = null;
                $aResult['data']['rubro'] = $rubro_array;
            }
            if ($this->IdMarca){
                $ListaProductos = $ListaProductos
                    ->where('inv_productos.id_marca',$this->IdMarca);
                $Marca = Marcas::find($this->IdMarca);

                $marca_array['id_marca'] = $Marca->id;
                $marca_array['nombre'] = $Marca->nombre;

                $aResult['data']['marca'] = $marca_array;
            }
            if($sortCol){
                if($sortCol=='inv_productos_estadisticas.visitas'){
                        $ListaProductos = $ListaProductos
                            ->orderBy('inv_productos_estadisticas.visitas', 'desc');

                }
                if ($sortCol=='MasVendidos'){
                    $ListaProductos =  $ListaProductos
                        ->leftJoin('pedidos_productos', 'pedidos_productos.id_producto','=', 'inv_productos.id')
                        ->groupBy('inv_productos.id')
                        ->orderByRaw('SUM(pedidos_productos.cantidad) DESC');
                }
                if($sortCol=='orden'){
                    $ListaProductos = $ListaProductos->orderBy('inv_productos.orden', $sortDir);
                }
                else if($sortCol!='inv_productos_estadisticas.visitas' && $sortCol!='MasVendidos' && $sortCol!='orden'){
                  //  \Log::debug('Ordenar Por: '.$sortCol.'Dir: '.$sortDir);
                    $ListaProductos =$ListaProductos
                        ->orderBy($sortCol, $sortDir);
                }

            }
            if(($this->precios[0]>0) || ($this->precios[1]>0)){
                if($this->precios[0]>0){
                    $ListaProductos = $ListaProductos->where('inv_precios.precio_venta', '>=' , $this->precios[0]);
                }
                if($this->precios[1]>0){
                    $ListaProductos = $ListaProductos->where('inv_precios.precio_venta', '<=' , $this->precios[1]);
                }
                $ListaProductos = $ListaProductos->orderBy('inv_precios.precio_venta','asc');
            }

            if($this->search){
                $search = $this->search;
                $ListaProductos = $ListaProductos ->distinct()
                    ->leftJoin('inv_productos_etiquetas','inv_productos.id','=','inv_productos_etiquetas.id_producto')
                    ->leftJoin('inv_etiquetas','inv_etiquetas.id','=','inv_productos_etiquetas.id_etiqueta')
                    ->leftJoin('inv_rubros','inv_rubros.id','=','inv_productos.id_rubro')
                    ->leftJoin('inv_subrubros','inv_subrubros.id','=','inv_productos.id_subrubro')
                    ->leftJoin('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')

                    ->where(function ($query) use ($search){

                        $query->where('inv_productos.nombre','like', "%{$search}%")
                            ->orWhere('inv_productos.sumario','like',"%{$search}%")
                            ->orWhere('inv_productos.descripcion','like',"%{$search}%")
                            ->orWhere('inv_productos.texto','like',"%{$search}%")
                            ->orwhere('conf_marcas.nombre','like',"%{$search}%")
                            ->orwhere('inv_rubros.nombre','like',"%{$search}%")
                            ->orwhere('inv_subrubros.nombre','like',"%{$search}%")
                            ->orwhere('inv_etiquetas.nombre','like',"%{$search}%")
                        ;
                    })
                ;
            }
            $ListaProductos=$ListaProductos->paginate($pageSize);

            $ArrayProductos = Productos::SetImagenPrecios($ListaProductos,$this->id_moneda);

            $aResult['data']['marca'] = $marca_array;
            $aResult['data']['deporte'] = $deporte_array;
            $aResult['data']['total'] = $ListaProductos->total();
            $aResult['data']['productos']=$ArrayProductos;

		//	\Log::debug('Total: '.$aResult['data']['total']);
		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
        \Log::debug('Fin Listado: '.date('H:i:s', time()));
		return response()->json($aResult);
	}

	public function producto(Request $request)
    {
        Cart::enviar_mail_compra(999);
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$id = $request->input('id');

            $aItems = Productos::
			where('habilitado',1)
			->where('id', $id)
			->first();
			if($aItems){
				$coloresStock = FeUtilController::getColorTalles($aItems->id,0,$aItems->id_marca,$aItems->id_genero,$aItems->id_rubro);//Modificado para muestre imagenes de articulos sin stock

                $ArrayStockColor = array();
                foreach ($coloresStock as $item){
                    $foto = FeUtilController::getImagesByColor($id, 1, $this->resource, $item['id_color']);
                    $color = Colores::find($item['id_color']);
                    if (!$color) {
                        $color = new Colores();
                        $color->nombre='';
                    }
                    if (!$foto) {
                        $foto=array();
                        $foto[0]['imagen_file']='logo.png';
			}
                    if(!isset($item['talles'])){
                        $item['talles'][0]=array(
                            'id_talle' => 0,
                            'stock' => 0,
                            'codigo' => 0,
                            'nombre' => '-',
                        );
                    }
                    $ArrayAux = array(
                        'nombreColor'=>$color->nombre,
                        'foto' => $foto[0]['imagen_file'],
                        'id_color' => $item['id_color'],
                        'stock_total' =>$item['stock_total'],
                        'codigo' =>$item['codigo'],
                        'talles' => $item['talles']

                    );
                    array_push($ArrayStockColor,$ArrayAux);
                  //  \Log::debug('$color'.print_r($color,true));
                }
               // \Log::debug('$ArrayStockColor'.print_r($ArrayStockColor,true));
                $coloresStock=$ArrayStockColor;
                $aOItems = FeUtilController::getImages($aItems->id, 99, $this->resource);
				//imagenes
				if(!$coloresStock){
					$coloresStock = array();
					$coloresStock[0] = array(
                        'nombreColor'=>'',
                        'foto' => 'logo.png',
						'codigo' => '',
						'id_color' => 0,
                        'stock_total' =>0,
                        'talles' => array(),
					);
				}
                $stock = FeUtilController::getStockTotal($aItems->id);
				//rubro y subrubro
				$rubro = array(
					'id' => $aItems->id_rubro,
					'rubro' => Rubros::find($aItems->id_rubro)->nombre
				);
				$subrubro = array();
				if($aItems->id_subrubro){
					$subrubro = array(
						'id' => $aItems->id_subrubro,
						'subrubro' => SubRubros::find($aItems->id_subrubro)->nombre
					);
				}
				if($aItems->id_subsubrubro){
					$subsubrubro = array(
						'id' => $aItems->id_subsubrubro,
						'subsubrubro' => SubSubRubros::find($aItems->id_subsubrubro)->nombre
					);
				}else{
					$subsubrubro = array();
				}

				//precio
				$precio = FeUtilController::getPrecios($aItems->id,$this->id_moneda);

				//marca
				$marca = Marcas::find($aItems->id_marca);
				$aItems->marca = ($marca?$marca->nombre:'');
				
				//origen
				$origen = Pais::find($aItems->id_origen);
				$aItems->origen = ($origen?$origen->pais:'');

				//genero
				$genero = Genero::find($aItems->id_genero);
				$aItems->genero = ($genero?$genero->genero:'');

                $etiquetas = Productos::find($id)->etiquetas()->get();
				//registro visita al producto
				FeUtilController::newVisitorProduct($aItems->id, $aItems->nombre);
				
				$data = array(
					'producto' => $aItems,
					'categoria' => array(
						'rubro' => $rubro,
						'subrubro' => $subrubro,
						'subsubrubro' => $subsubrubro
					),
					'precios' => $precio,
                    'etiquetas' => $etiquetas,
					'stockColor' => $coloresStock,
					'fotos' => $aOItems,
                    'stock' => $stock
				);
			//	\Log::debug(print_r($stock,true));
				$aResult['data'] = $data;
			}else{
				$aResult['status'] = 1;
				$aResult['msg'] = 'Producto no encontrado';
			}
        } else {
			$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
        return response()->json($aResult);
    }
	public function relacionados(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
            $aResult['data']['productos'] = array();
            $aResult['data']['total'] = array();
            $pageSize = $this->iDisplayLength;
            $offset = $this->iDisplayStart;
            $limit = $this->limit;
            $currentPage = ($offset / $pageSize) + 1;
            $sort = $this->orden;
            $rand = false;
            if($sort=='rand'){
                $rand = true;
            }else{
                $sortDir = $sort['dir'];
                $sortCol = $sort['col'];
            }
            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });
            $aItems = Productos::
            selectRaw('inv_productos.id, inv_productos.nombre, inv_productos.id_rubro, inv_productos.id_subrubro, inv_productos.oferta, SUM(inv_producto_codigo_stock.stock) as stock')
                ->join("inv_productos_relacion as a","a.id_secundaria","=","inv_productos.id")
                ->leftJoin('inv_producto_codigo_stock','inv_producto_codigo_stock.id_producto','=','inv_productos.id')
                ->where("a.id_principal", $this->id_relacion)
                ->where('inv_productos.habilitado', 1)
                ->where('inv_productos.id','!=',$this->id_relacion)
                ->groupBy('inv_productos.id');

            if($rand){
                $aItems = $aItems->inRandomOrder();
            }else{
                $aItems = $aItems->orderBy($sortCol, $sortDir);
            }
            $aItems = $aItems->paginate($pageSize);
            if($aItems->total() > 0){
                foreach ($aItems as $item) {
                    if($this->fotos){
                        $aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
                    }else{
                        $aOItems = '';
                    }

                    //stock
                    $stock = $item->stock;
                    //precio
                    $precio = FeUtilController::getPrecios($item->id,$this->id_moneda);

                    $fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
                    //rubro y subrubro
                    $rubro = array();
                    if(isset(Rubros::find($item->id_rubro)->nombre)){
                        $rubro = array(
                            'id' => $item->id_rubro,
                            'rubro' => Rubros::find($item->id_rubro)->nombre
                        );
                    }

                    $subrubro = array();
                    if($item->id_subrubro){
                        $subrubro = array(
                            'id' => $item->id_subrubro,
                            'subrubro' => SubRubros::find($item->id_subrubro)->nombre
                        );
                    }
                    $data = array(
                        'id' => $item->id,
                        'titulo' => $item->nombre,
                        'sumario' => $item->sumario,
                        'categoria' => array(
                            'rubro' => $rubro,
                            'subrubro' => $subrubro
                        ),
                        'fotos' => $aOItems,
                        'stock' => $stock,
                        'precios' => $precio,
                        'updated_at' => $fecha,
                        'oferta' => $item->oferta
                    );
                    array_push($aResult['data']['productos'],$data);
                }
                $aResult['data']['total'] = $aItems->total();
            }else{
                $aItems = array();

                // *********************************************************************
                //obtengo los productos del mismo rubro

                $aItems = Productos::
                selectRaw('inv_productos.id, inv_productos.nombre, inv_productos.id_rubro, inv_productos.id_subrubro, inv_productos.oferta, SUM(inv_producto_codigo_stock.stock) as stock')
                    ->leftJoin('inv_producto_codigo_stock','inv_producto_codigo_stock.id_producto','=','inv_productos.id')
                    ->where('inv_productos.habilitado', 1)
                    ->where('inv_productos.id_rubro',Productos::find($this->id_relacion)->id_rubro)
                    ->where('inv_productos.id', '!=',$this->id_relacion)
                    ->groupBy('inv_productos.id');
                if($limit){
                    $aItems = $aItems->limit($limit);
                }
                if($rand){
                    $aItems = $aItems->inRandomOrder();
                }else{
                    $aItems = $aItems->orderBy($sortCol, $sortDir);
                }
                $aItems = $aItems->paginate($limit);
                foreach ($aItems as $item) {
                    if($this->fotos){
                        $aOItems = FeUtilController::getImages($item->id,$this->fotos, $this->resource);
                    }else{
                        $aOItems = '';
                    }
                    //precio
                    $precio = FeUtilController::getPrecios($item->id,$this->id_moneda);


                    $stock = $item->stock;
                    $fecha = Carbon::parse($item->updated_at)->format('d/m/Y');
                    //rubro y subrubro
                    $rubro = array();
                    if(isset(Rubros::find($item->id_rubro)->nombre)){
                        $rubro = array(
                            'id' => $item->id_rubro,
                            'rubro' => Rubros::find($item->id_rubro)->nombre
                        );
                    }

                    $subrubro = array();
                    if(isset(SubRubros::find($item->id_subrubro)->nombre)){
                        if($item->id_subrubro){
                            $subrubro = array(
                                'id' => $item->id_subrubro,
                                'subrubro' => SubRubros::find($item->id_subrubro)->nombre
                            );
                        }
                    }

                    $data = array(
                        'id' => $item->id,
                        'titulo' => $item->nombre,
                        'sumario' => $item->sumario,
                        'categoria' => array(
                            'rubro' => $rubro,
                            'subrubro' => $subrubro
                        ),
                        'fotos' => $aOItems,
                        'stock' => $stock,
                        'precios' => $precio,
                        'updated_at' => $fecha,
                        'oferta' => $item->oferta
                    );
                    array_push($aResult['data']['productos'],$data);
                }
                $aResult['data']['total'] = $aItems->total();
            }
        }else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        return response()->json($aResult);
	}

	public function etiquetasMenu(Request $request)
	{

		$aResult = Util::getDefaultArrayResult();
		
        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$etiquetas = Etiquetas::select('id','nombre')
			->where('habilitado',1)->where('menu',1)
			->orderBy('orden','asc')
			->get();
			$aResult['data'] = $etiquetas;
			return response()->json($aResult);
		}
	}

	public function menu(Request $request)
	{
		$data = array();
		$rubros = Rubros::select('id','nombre')
            ->where('destacado',1)
            ->where('habilitado',1)
            ->orderBy('orden')
            ->get()
            ->toArray();

		if ($rubros){
		    $data['rubros']=array();

        //subrubros
		foreach ($rubros as $rubro){
            $subrubros = SubRubros::select('id','nombre','id_rubro')
                ->where('id_rubro',$rubro['id'])
                ->orderBy('orden')
                ->get()
                ->toArray();
            $arrayRubros = array(
                'id'=>$rubro['id'],
                 'nombre'=>$rubro['nombre'],
                 'subrubros'=>$subrubros,
            );
            array_push($data['rubros'],$arrayRubros);
        }
        }
        //\Log::info(print_r($data['subrubros'][0]->nombre,true));

		//marcas
        $data['marcas'] = Marcas::getMenuItems();

		$etiquetas = Etiquetas::select('id','nombre','color')
		->where('habilitado',1)->where('menu',1)
		->orderBy('orden','asc')
		->get();

        if($etiquetas){
            $data['etiquetas'] = $etiquetas;
        }
		$aResult['data'] = $data;

       // \Log::info(print_r($aResult,true));
		return response()->json($aResult);
	}

    public function panelGeneros(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            $etiquetas = array();
            //slider news
            $etiquetas = Etiquetas::select('id','nombre','color')
                ->where('habilitado',1)
                ->where('slider',1)
                ->orderBy('orden','asc')
                ->get();

            $etiquetas_rubros = array();
            $rubros = array();
            foreach($etiquetas as $etiqueta){

                $nombreEtiqueta=substr($etiqueta->nombre,0,-1);
                $rubrosAux = Rubros::select('inv_rubros.id', 'inv_rubros.nombre')
                    ->Join('inv_etiquetas_rubros','inv_etiquetas_rubros.id_rubro','=','inv_rubros.id')
                    ->where('inv_rubros.habilitado',1)
                    ->where('inv_etiquetas_rubros.id_etiqueta',$etiqueta['id'])
                    ->orderBy('orden','asc')
                    ->get()->toArray();
                $aOItems = FeUtilController::getImages($etiqueta['id'],1, 'etiquetas');

                    foreach ($rubrosAux as $itemRubro){
                        $subrubros = SubRubros::select('inv_subrubros.id','inv_subrubros.nombre')
                            ->join('inv_productos','inv_productos.id_subrubro','=','inv_subrubros.id')
                            ->join('conf_generos','conf_generos.id','=','inv_productos.id_genero')
                            ->where('inv_subrubros.habilitado',1)
                            ->where('conf_generos.genero','like','%'.$nombreEtiqueta.'%')
                            ->where('inv_subrubros.id_rubro',$itemRubro['id'])
                            ->groupBy('inv_subrubros.id')
                            ->orderBy('inv_subrubros.orden','asc')
                            ->get()
                            ->toArray();
//\Log::debug(print_r($subrubros,true));
                        if ($subrubros){

                            $itemRubro['subrubros'] = $subrubros;

                        }
                        array_push($rubros, $itemRubro);
                    }

                $array_etiqueta = array(
                    'id_etiqueta' => $etiqueta['id'],
                    'etiqueta' => $etiqueta['nombre'],
                    'rubros'=>$rubros,
                    'foto'=>$aOItems
                );

                    array_push($etiquetas_rubros, $array_etiqueta);
                    $rubros = array();
                }

            }
          //  \Log::info(print_r( $etiquetas_rubros,true));
            $aResult['data'] = $etiquetas_rubros;
            return response()->json($aResult);
     }

    public function cambiarColor(Request $request){
        $aResult = Util::getDefaultArrayResult();

        $id = $request->input('id_producto');

        $aItems = Productos::
		where('habilitado',1)
		->where('id', $id)
        ->first();
        
        if($aItems){
			$id_color = $request->input('id_color');
			$id_marca = $request->input('id_marca');
			$id_genero = $request->input('id_genero');

            //taer fotos, [talles, codigos y stock]
            $coloresStock = FeUtilController::getColorTalles($id, $id_color,$id_marca,$id_genero,$aItems->id_rubro);
            $aOItems = FeUtilController::getImagesByColor($id, 'all', $this->resource, $id_color);
            $aResult['data'] = array(
                'fotos' => $aOItems,
                'talles' => $coloresStock
            );
        }

        return response()->json($aResult);
    }
	public function filtros(Request $request){
		$aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
            $search = $request->input('search');
            $id_rubro = $request->input('id_rubro');
            $id_etiqueta = $request->input('id_etiqueta');
            $id_subrubro = $request->input('id_subrubro');
            $id_marca = $request->input('id_marca');
            $id_deporte = $request->input('id_deporte');
           // $precio = $request->input('precios');

            //-----------DEPORTES---------------

            $Deportes = Deportes::select('inv_deportes.id as Id','inv_deportes.nombre as Nombre',\DB::raw('count(inv_productos_deportes.id_producto) as Cantidad'))
                ->leftjoin('inv_productos_deportes','inv_productos_deportes.id_deporte','=','inv_deportes.id')
                ->leftjoin('inv_productos', 'inv_productos_deportes.id_producto', '=', 'inv_productos.id')
                ->where('inv_deportes.habilitado',1)
                ->where('inv_productos.habilitado',1)
                ->groupBy('inv_deportes.id')
                ->orderBy('Cantidad', 'desc');
            // ------------ETIQUETAS--------------

                $etiquetas = Etiquetas::select('inv_etiquetas.id as Id', \DB::raw('0 as id_rubro'), 'inv_etiquetas.nombre as Nombre', \DB::raw('count(inv_productos_etiquetas.id_producto) as Cantidad'))
                    ->leftjoin('inv_productos_etiquetas', 'inv_etiquetas.id', '=', 'inv_productos_etiquetas.id_etiqueta')
                    ->leftjoin('inv_productos', 'inv_productos_etiquetas.id_producto', '=', 'inv_productos.id')
                    ->where('inv_etiquetas.habilitado', 1)
                    ->where('inv_etiquetas.slider', 1)
                    ->where('inv_productos.habilitado',1)
                    ->groupBy('inv_etiquetas.id')
                    ->orderBy('Cantidad', 'desc');

                //---------------RUBROS----------------------
                $Rubros = Rubros::select('inv_rubros.id as Id','inv_rubros.nombre as Nombre',\DB::raw('count(inv_productos.id) as Cantidad'))
                    ->leftjoin('inv_productos','inv_productos.id_rubro','=','inv_rubros.id')
                    ->where('inv_rubros.habilitado',1)
                    ->where('inv_productos.habilitado',1)
                    ->groupBy('inv_rubros.id')
                    ->orderBy('Cantidad', 'desc')->having('Cantidad', '>', 0);

                //---------------MARCAS----------------------
                $Marcas = Marcas::select('conf_marcas.id as Id',\DB::raw('0 as id_rubro'),'conf_marcas.nombre as Nombre',\DB::raw('count(inv_productos.id) as Cantidad'))
                    ->leftjoin('inv_productos','inv_productos.id_marca','=','conf_marcas.id')
                    ->where('conf_marcas.habilitado',1)
                    ->where('inv_productos.habilitado',1)
                    ->groupBy('conf_marcas.id')
                    ->orderBy('Cantidad', 'desc')->having('Cantidad', '>', 0); //Quita del listado marcas sin productos

            //---------------SUBRUBROS----------------------
            if ($id_rubro) {
                $Subrubros = SubRubros::select('inv_subrubros.id as Id','inv_subrubros.id_rubro as IdRubro', 'inv_subrubros.nombre as Nombre', \DB::raw('count(inv_productos.id) as Cantidad'))
                    ->leftjoin('inv_productos', 'inv_productos.id_subrubro', '=', 'inv_subrubros.id')
                    ->where('inv_subrubros.habilitado', 1)
                    ->where('inv_productos.habilitado',1)
                    ->where('inv_productos.id_rubro', $id_rubro)
                    ->groupBy('inv_subrubros.id')
                    ->orderBy('Cantidad', 'desc');


                $Marcas = $Marcas->select('conf_marcas.id as Id','inv_productos.id_rubro','conf_marcas.nombre as Nombre',\DB::raw('count(inv_productos.id) as Cantidad'))
                ->where('inv_productos.id_rubro',$id_rubro);


                $etiquetas = $etiquetas->select('inv_etiquetas.id as Id','id_rubro', 'inv_etiquetas.nombre as Nombre', \DB::raw('count(inv_productos_etiquetas.id_producto) as Cantidad'))
                    ->where('inv_productos.id_rubro', $id_rubro)
                    ->groupBy('inv_etiquetas.id')
                    ->orderBy('Cantidad', 'desc');

                $Deportes = $Deportes->select('inv_deportes.id as Id','inv_deportes.nombre as Nombre',\DB::raw('count(inv_productos_deportes.id_producto) as Cantidad'))
                    ->where('inv_productos.habilitado', 1)
                    ->where('inv_productos.id_rubro', $id_rubro)
                    ->groupBy('inv_deportes.id')
                    ->orderBy('Cantidad', 'desc');

                if($id_subrubro){
                    $Marcas = $Marcas->where('inv_productos.id_subrubro',$id_subrubro);

                }

            }
            else{
                $Subrubros=null;
            }

                if($id_etiqueta){
                    $etiqueta = Etiquetas::find($id_etiqueta);
                    $nombreEtiqueta = substr($etiqueta->nombre,0,-1);
                    $genero = Genero::where('genero','like','%'.$nombreEtiqueta.'%')->first();


                    $etiquetas = null;
                    if ($genero){
                        \Log::debug('pass');
                        $Rubros = $Rubros
                            ->leftJoin('conf_generos','conf_generos.id','=','inv_productos.id_genero')
                            ->where('conf_generos.genero','like','%'.$nombreEtiqueta.'%');

                        $Marcas = $Marcas
                            ->leftJoin('conf_generos','conf_generos.id','=','inv_productos.id_genero')
                            ->where('conf_generos.genero','like','%'.$nombreEtiqueta.'%');
                    }
                    else
                    {
                        $Rubros = $Rubros->leftjoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
                                                ->where('inv_productos_etiquetas.id_etiqueta',$id_etiqueta);

                        $Marcas = $Marcas->leftjoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
                                                ->where('inv_productos_etiquetas.id_etiqueta',$id_etiqueta);
                    }

                    if ($id_rubro){

                        if ($genero)
                            $Subrubros = $Subrubros
                                ->leftJoin('conf_generos','conf_generos.id','=','inv_productos.id_genero')
                                ->where('conf_generos.genero','like','%'.$nombreEtiqueta.'%');
                        else
                            $Subrubros = $Subrubros
                                ->leftjoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
                                ->where('inv_productos_etiquetas.id_etiqueta',$id_etiqueta);

                    }

                }
                if($id_deporte){
                    $Deportes = null;
                    $Rubros = $Rubros->leftjoin('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
                        ->where('inv_productos_deportes.id_deporte',$id_deporte);
                    $Marcas = $Marcas->leftjoin('inv_productos_deportes','inv_productos_deportes.id_producto','=','inv_productos.id')
                        ->where('inv_productos_deportes.id_deporte',$id_deporte);
                }
                if ($id_marca){
                    if ($id_rubro)  $Subrubros = $Subrubros->where('inv_productos.id_marca',$id_marca);
                    $Rubros = $Rubros->where('inv_productos.id_marca',$id_marca);

                }
            if(($this->precios[0]>0) || ($this->precios[1]>0)){
                if($this->precios[0]>0){
                    $Marcas = $Marcas->leftJoin('inv_precios','inv_productos.id','=','inv_precios.id_producto');
                    $Marcas = $Marcas->where('inv_precios.precio_venta', '>=' , $this->precios[0]);

                    $Rubros = $Rubros->leftJoin('inv_precios','inv_productos.id','=','inv_precios.id_producto');
                    $Rubros = $Rubros->where('inv_precios.precio_venta', '>=' , $this->precios[0]);
                }
                if($this->precios[1]>0){
                    $Marcas = $Marcas->where('inv_precios.precio_venta', '<=' , $this->precios[1]);

                    $Rubros = $Rubros->where('inv_precios.precio_venta', '<=' , $this->precios[1]);

                }

            }
            if($search){
                $Marcas = $Marcas->where(function ($query) use ($search){
                    $query->Where('inv_productos.nombre','like',"%{$search}%")
                    ;
                });
                $Rubros = $Rubros->where(function ($query) use ($search){
                    $query->Where('inv_productos.nombre','like',"%{$search}%")
                    ;
                });
            }
            //    \Log::debug('Precio: '.$this->precios[0].' '.$this->precios[1]);
                $aResult['data']['rubros'] = $Rubros->get();
                $aResult['data']['marcas'] = $Marcas->get();
                $aResult['data']['subrubros'] = ($Subrubros)?$Subrubros->get():null;

                $aResult['data']['etiquetas'] = ($etiquetas)?$etiquetas->get():null;
                $aResult['data']['deportes'] = ($Deportes)?$Deportes->get():null;
			//----------------------------------------------------

		}else{
			$aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
	public function search(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view') && $this->filterNote) {
			$array_send = array(
				"fotos" => 1,
				"id_moneda" => 1,
				"orden" => array(
					"col" => "inv_productos.orden",
					"dir" => "ASC"
				),
				"iDisplayLength" => 10,
				"iDisplayStart" => 0,
				"search" => $this->search
			);
			$request->request->add($array_send);
			$aResult = app('App\Http\Controllers\Fe\ProductosController')->listado($request);
			$aResult = json_decode($aResult->getContent(),true);

		}else {
			$aResult['status'] = 1;
			$aResult['msg'] = \config('appCustom.messages.unauthorized');
		}
		return response()->json($aResult);
	}
}