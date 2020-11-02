<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;
use App\AppCustom\Models\Rubros;
use App\AppCustom\Models\SubRubros;
use App\AppCustom\Models\SubSubRubros;
use App\AppCustom\Models\Marcas;
use App\AppCustom\Models\Etiquetas;
use App\AppCustom\Models\Pais;
use App\AppCustom\Models\Colores;
use App\AppCustom\Models\Talles;
use App\AppCustom\Models\Generos;
use App\AppCustom\Models\Deportes;
use App\AppCustom\Models\Note;
use App\AppCustom\Models\SucursalesStock;
use App\Http\Controllers\Fe\FeUtilController;
use App\AppCustom\Models\ProductosCodigoStock;
use App\AppCustom\Models\PreciosProductos;
use App\AppCustom\Models\PedidosProductos;
use App\AppCustom\Models\ProductosCategMeli;
use App\AppCustom\Models\CategoriasMeli;
use App\AppCustom\Models\CategoriasMeliValues;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\AppCustom\Models\ProductosEtiquetas;
use App\AppCustom\Models\Productos;


class ProductosController extends Controller
{
    use ResourceTraitController {
        create as protected createTrait;
        edit as protected editTrait;
        destroy as protected destroyTrait;
    }
    
    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {
        
        parent::__construct($request);
        
        $this->resource = 'productos';
        $this->resourceLabel = 'Productos';
        $this->modelName = 'App\AppCustom\Models\Productos';
        $this->viewPrefix = 'productos.';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.view')) {


            $modelName = $this->modelName;
            
            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'id';
                $sortDir = 'desc';
            } else {
                            
                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));
			
            //Search rubros
            $search1 = \trim($request->input('sSearch_1'));
            //Search subrubros
            $search2 = \trim($request->input('sSearch_2'));
            //Search subsubrubros
            $search3 = \trim($request->input('sSearch_3'));
            //Search stock
            $search4 = \trim($request->input('sSearch_4'));
            //Search marcas
            $search5 = \trim($request->input('sSearch_5'));
            //Search meli
            $search6 = \trim($request->input('sSearch_6'));
            //Search Habilitados
            //$search7 = \trim($request->input('sSearch_7'));


            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items = 
                $modelName::select(
                        'inv_productos.id',
                        'inv_producto_codigo_stock.codigo',
                        'inv_productos.nombre',
                        'inv_rubros.nombre as rubro',
                        'inv_productos.id_rubro',
                        'inv_productos.id_subrubro',
                        'inv_productos.orden',
                        'inv_productos.habilitado',
                        'inv_productos.destacado',
                        'inv_productos.oferta',
                        'inv_productos.comentarios',
                        'inv_productos.id_meli',
                        'inv_productos.estado_meli'
                    )
                    ->leftJoin('inv_rubros','inv_rubros.id','=','inv_productos.id_rubro')
					->leftjoin('inv_producto_codigo_stock','inv_producto_codigo_stock.id_producto','=','inv_productos.id')
                    ->orderBy($sortCol, $sortDir)
                    ->groupBy('inv_productos.id')
            ;
            \Log::debug('Sort Col - Sort Dir'.$sortCol.''.$sortDir);
            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('inv_productos.nombre','like',"%{$search}%")
                       /* ->orWhere('inv_productos.descripcion','like',"%{$search}%") */
                        ->orWhere('inv_producto_codigo_stock.codigo','like',"%{$search}%")
                    ;
                });
            }
			
			if ($search!='') {
                $items->where(function($query) use ($search){
                    $query
                        ->where('inv_productos.nombre','like',"%{$search}%")
                       /* ->orWhere('inv_productos.descripcion','like',"%{$search}%") */
                        ->orWhere('inv_producto_codigo_stock.codigo','like',"%{$search}%")
                    ;
                });
            }

            if ($search1!='' && $search1!='NULL') {
                $items->where(function($query) use ($search1){
                    $query
                        ->where('inv_productos.id_rubro',$search1)
                    ;
                });

                if ($search2!='NULL' && $search2 != '') {
					$items->where(function($query) use ($search2){
						$query
							->where('inv_productos.id_subrubro',$search2)
						;
					});
				}
            }
	    
	    if ($search3!='' && $search3!='NULL') {
                $items->leftJoin('inv_productos_etiquetas','inv_productos_etiquetas.id_producto','=','inv_productos.id')
                ->where(function($query) use ($search3){
                    $query
                        ->where('inv_productos_etiquetas.id_etiqueta',$search3)
                    ;
                });
            }
            
            //sin stock 
            if ($search4!='' && $search4!='NULL') {
                if($search4==0){
                    $items->havingRaw('SUM(inv_producto_codigo_stock.stock) <=  0 ');
                }elseif($search4==1){
                    $items->havingRaw('SUM(inv_producto_codigo_stock.stock) >  0 ');
                    ;
                }
            }

            if ($search5!='' && $search5!='NULL') {
                $items->leftJoin('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')
                ->where(function($query) use ($search5){
                    $query
                        ->where('inv_productos.id_marca',$search5)
                    ;
                });
            }
            if ($search6!='' && $search6!='NULL') {
                if($search6==1){
                    $items->leftJoin('img', 'img.resource_id', '=', 'inv_productos.id')
                        ->where('img.resource', '=', $this->resource)
                        ->whereNotNull('img.imagen_file');
                }elseif($search6==2){
                    $items->leftJoin('img', 'img.resource_id', '=', 'inv_productos.id')
                    ->where('img.resource', '=', $this->resource)
                    ->whereNull('img.imagen_file');
                }elseif($search6==3){
                    $items->where('inv_productos.destacado',1);
                }elseif($search6==4){
                    $items->where('inv_productos.oferta',1);
                }elseif($search6==5){
                    $items->whereNotNull('inv_productos.id_meli');
                }elseif($search6==6){
                    $items->where('inv_productos.habilitado',1);
                }elseif($search6==7){
                    $items->where('inv_productos.habilitado',0);
                }
                
            }

/*            if ($search7!='' && $search7!='NULL'){
                if($search7==1){
                    $items->where('inv_productos.habilitado',1);
                }elseif($search7==0){
                    $items->where('inv_productos.habilitado',0);
                }
            }*/

            $items = $items
                ->paginate($pageSize)
            ;

            $aItems = $items->toArray();
			
			array_walk($aItems['data'], function(&$val,$key){
				$id_moneda = Util::getMonedaDefault();
				$simbolo = $id_moneda[0]['simbolo'];
				$precio = FeUtilController::getPrecios($val['id'], $id_moneda[0]['id']);
                $val['precio']	= $precio?$simbolo.$precio->precio:'';
                $val['codigo'] = $val['codigo']?(substr($val['codigo'],0, strpos($val['codigo'],'-'))):'';
                
                if(isset($val['id_subsubrubro'])){
                    $val['rubro'] = $val['rubro'].($val['id_subrubro']?' > '.SubRubros::find($val['id_subrubro'])->nombre:'').($val['id_subsubrubro']?' > '.SubSubRubros::find($val['id_subsubrubro'])->nombre:'');
                }
                $aOItems = FeUtilController::getImages($val['id'],1, $this->resource);
                if($aOItems){
                    $val['foto'] = \config('appCustom.PATH_UPLOADS') . $this->resource . '/' .$aOItems[0]['imagen_file'];
                }else{
                    $val['foto'] = '';
                }
				
			});
                            
            
            $total = $aItems['total'];
            $aItems = $aItems['data'];
            
            //Cuento la cantidad de Imagenes por producto
            $this->putImgCnt($aItems);

            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->orderBy('nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aMarcas'] = Marcas::select('id','nombre')->orderBy('nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aPaises'] = Pais::select('id_pais','pais')->lists('pais','id_pais');
        $this->aCustomViewData['aColores'] = Colores::select('id','nombre')->orderBy('nombre')->lists('nombre','id');
        $this->aCustomViewData['aTalles'] = Talles::select('id','nombre')->orderBy('nombre')->lists('nombre','id');
        $this->aCustomViewData['aGeneros'] = Generos::select('id','genero')->orderBy('genero')->lists('genero','id');
        $this->aCustomViewData['aScursales'] = Note::select('id_nota as id','titulo')->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))->where('habilitado', 1)->orderBy('destacado','desc')->get();
      //  $this->aCustomViewData['aCategoriasMeli'] = 
        
        
        return $this->createTrait();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'nombre'    => 'required',
                    'id_rubro'  => 'required',
                    //'id_genero' => 'required',
                    'alto'      => 'required',
                    'ancho'     => 'required',
                    'largo'     => 'required',
                    'peso'      => 'required',
                ], 
                [
                    'nombre.required'   => 'El campo Nombre es requerido',
                    'id_rubro.required' => 'El campo Rubro es requerido',
                    //'id_genero.required'=> 'El campo Género es requerido',
                    'alto.required'     => 'El campo Alto es requerido',
                    'ancho.required'    => 'El campo Ancho es requerido',
                    'largo.required'    => 'El campo Largo es requerido',
                    'peso.required'     => 'El campo Peso es requerido',
                ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
   
				if(!$request->input('id_api')){
                    $aSc = json_decode($request->input('stockColor'), true);
                    
                    if (isset($aSc[0])) {
                        foreach ($aSc[0] as $aItem) {
                            $item = ProductosCodigoStock::where('codigo', $aItem['codigo'])->first();
                            
                            if ($item) {
                                $validator->errors()->add('field', 'El código '.$aItem['codigo'].' ya existe');
                                break;
                            }
                        }
                    }
                }
				
            });

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'        => $request->input('nombre'),
                        'nombremeli'        => $request->input('nombremeli'),
                        'id_rubro'      => $request->input('id_rubro'),
                        'id_subrubro'   => $request->input('id_subrubro'),
                        'id_subsubrubro'=> $request->input('id_subsubrubro'),
                        'categoria_meli'=> $request->input('categoria_meli'),
                        'categoria_variations'=> $request->input('categoria_variations'),
                        'id_genero'     => $request->input('id_genero'),
                        'id_marca'      => $request->input('id_marca'),
                        'id_origen'     => $request->input('id_origen'),
                        'modelo'        => $request->input('modelo'),
                        'sumario'       => $request->input('sumario'),
                        'texto'         => $request->input('texto'),
                        'estado'      	=> $request->input('estado'),
                        'id_video'     	=> $request->input('id_video'),
                        'alto'          => $request->input('alto'),
                        'ancho'         => $request->input('ancho'),
                        'largo'         => $request->input('largo'),
                        'peso'          => $request->input('peso'),
                        'orden'         => $request->input('orden'),
                        'id_api'      => $request->input('id_api'),
                        'habilitado'    => 0,
                        ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                $aResult['id_producto'] = $resource->id;

                // Relaciono el producto con las etiquetas
                $aAllEtiquetas = Etiquetas::get();
                if (!$aAllEtiquetas->isEmpty()) {
                    $aOpt = $request->input('etiquetasIds');
					if($aOpt){
						array_walk($aOpt, function($value) use ($resource){
							$etiqueta = Etiquetas::where('id',$value)->first();
							if($etiqueta) {
								$etiqueta->productos()->attach($resource);
							}
						});
					}
                }
                // Relaciono el producto con los deportes
                $aAllDeportes = Deportes::get();
                if (!$aAllDeportes->isEmpty()) {
                    $aOpt = $request->input('deportesIds');
					if($aOpt){
						array_walk($aOpt, function($value) use ($resource){
							$deporte = Deportes::where('id',$value)->first();
							if($deporte) {
								$deporte->productos()->attach($resource);
							}
						});
					}
                }
                // Relaciono el producto con las color, stock, codigo
                if($request->input('stockColor')){
                    $aAllColores = Colores::get();
                    if (!$aAllColores->isEmpty()) {
                        foreach ($aAllColores as $colores) {
                            $colores->productos()->detach($resource);
                        }
                        $aOptC = json_decode($request->input('stockColor'), true);
                        if(!$aOptC){ $aOptC = array(); }else{ $aOptC = $aOptC[0]; }
                        array_walk($aOptC, function($value) use ($resource){
                            $colores = Colores::where('id',$value['id_color'])->first();
                            if ($colores) {
                                $stock = 0;
                                foreach($value['stock'] as $data){
                                    $stock = $stock+$data['stock'];
                                }
                                $colores->productos()
                                ->attach(
                                    $resource->id,
                                    ['id_talle' => $value['id_talle'],'stock' => $stock,'codigo' => $value['codigo']]
                                );
                                $att_id = ProductosCodigoStock::select('id')->where(['id_talle' => $value['id_talle'],'stock' => $stock,'codigo' => $value['codigo'],'id_producto' => $resource->id])->first();
                                foreach($value['stock'] as $data){
                                    $stock_sucursal = new SucursalesStock;
                                    $stock_sucursal->id_codigo_stock = $att_id->id;
                                    $stock_sucursal->id_sucursal = $data['id'];
                                    $stock_sucursal->stock = $data['stock'];
                                    $stock_sucursal->save();
                                }
                            }
                        });
                    }
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }  
        
        return response()->json($aResult);
    }

    public function storeImport(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.create')) {
            $modelName = $this->modelName;
            //Validation
            $validator = \Validator::make(
                $request->all(), 
                [
                    'nombre'    => 'required',
                    'id_rubro'  => 'required',
                    //'id_genero' => 'required',
                    'alto'      => 'required',
                    'ancho'     => 'required',
                    'largo'     => 'required',
                    'peso'      => 'required',
                ], 
                [
                    'nombre.required'   => 'El campo Nombre es requerido',
                    'id_rubro.required' => 'El campo Rubro es requerido',
                    //'id_genero.required'=> 'El campo Género es requerido',
                    'alto.required'     => 'El campo Alto es requerido',
                    'ancho.required'    => 'El campo Ancho es requerido',
                    'largo.required'    => 'El campo Largo es requerido',
                    'peso.required'     => 'El campo Peso es requerido',
                ]
            );
            
            $validator->after(function($validator) use ($modelName, $request) {
                /* if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                    $validator->errors()->add('field', 'El campo Nombre ya existe');
                } */
            
				if(!$request->input('id_api')){
                    $aSc = json_decode($request->input('stockColor'), true);
                    
                    if (isset($aSc[0])) {
                        foreach ($aSc[0] as $aItem) {
                            $item = ProductosCodigoStock::where('codigo', $aItem['codigo'])->first();
                            
                            if ($item) {
                                $validator->errors()->add('field', 'El código '.$aItem['codigo'].' ya existe');
                                break;
                            }
                        }
                    }
                }
				
            });

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'        => $request->input('nombre'),
                        'nombremeli'        => $request->input('nombremeli'),
                        'id_rubro'      => $request->input('id_rubro'),
                        'id_subrubro'   => $request->input('id_subrubro'),
                        'id_subsubrubro'=> $request->input('id_subsubrubro'),
                        'categoria_meli'=> $request->input('categoria_meli'),
                        'categoria_variations'=> $request->input('categoria_variations'),
                        'id_genero'     => $request->input('id_genero'),
                        'id_marca'      => $request->input('id_marca'),
                        'id_origen'     => $request->input('id_origen'),
                        'modelo'        => $request->input('modelo'),
                        'sumario'       => $request->input('sumario'),
                        'texto'         => $request->input('texto'),
                        'estado'      	=> $request->input('estado'),
                        'id_video'     	=> $request->input('id_video'),
                        'alto'          => $request->input('alto'),
                        'ancho'         => $request->input('ancho'),
                        'largo'         => $request->input('largo'),
                        'peso'          => $request->input('peso'),
                        'orden'         => $request->input('orden'),
                        'id_api'      => $request->input('id_api'),
                        'habilitado'    => 0,
                    ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

            
                $aResult['id_producto'] = $resource->id;            

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }  
        
        return response()->json($aResult);
    }

    public function storeImportKernel($request)
    {
        $aResult = Util::getDefaultArrayResult();
        

        $modelName = 'App\AppCustom\Models\Productos';
            //Validation
            $validator = \Validator::make(
                $request, 
                [
                    'nombre'    => 'required',
                    'id_rubro'  => 'required',
                    'id_genero' => 'required',
                    'alto'      => 'required',
                    'ancho'     => 'required',
                    'largo'     => 'required',
                    'peso'      => 'required',
                ], 
                [
                    'nombre.required'   => 'El campo Nombre es requerido',
                    'id_rubro.required' => 'El campo Rubro es requerido',
                    'id_genero.required'=> 'El campo Género es requerido',
                    'alto.required'     => 'El campo Alto es requerido',
                    'ancho.required'    => 'El campo Ancho es requerido',
                    'largo.required'    => 'El campo Largo es requerido',
                    'peso.required'     => 'El campo Peso es requerido',
                ]
            );
            
        

            if (!$validator->fails()) {
                $resource = new $modelName(
                    [
                        'nombre'        => $request['nombre'],
                        'id_rubro'      => $request['id_rubro'],
                        'id_subrubro'   => $request['id_subrubro'],
                        'id_genero'     => $request['id_genero'],
                        'id_marca'      => $request['id_marca'],
                        'id_origen'     => $request['id_origen'],
                        'alto'          => $request['alto'],
                        'ancho'         => $request['ancho'],
                        'largo'         => $request['largo'],
                        'peso'          => $request['peso'],
                        'habilitado'    => 0,
                        ]
                )
                ;

                if (!$resource->save()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }

                $aResult['id_producto'] = $resource->id;

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = $validator->errors()->all();
            }
        
    
        
        return response()->json($aResult);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) 
    {
        $modelName = $this->modelName;
        $prod =  $modelName::find($id);

        $this->aCustomViewData['aRubros'] = Rubros::select('id','nombre')->orderBy('nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aSubRubros'] = SubRubros::select('id','nombre')->orderBy('nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aMarcas'] = Marcas::select('id','nombre')->orderBy('nombre')->where('habilitado', '=', '1')->lists('nombre','id');
        $this->aCustomViewData['aPaises'] = Pais::select('id_pais','pais')->lists('pais','id_pais');
        $this->aCustomViewData['aEtiquetasAssigned'] = $modelName::find($id)->etiquetas()->get();
        $this->aCustomViewData['aDeportesAssigned'] = $modelName::find($id)->deportes()->get();
		
		$this->aCustomViewData['aColores'] = Colores::select('id','nombre')->orderBy('nombre')->lists('nombre','id');
        $aColores = $modelName::find($id)->colores()->get();
		
        $this->aCustomViewData['aTalles'] = Talles::select('id','nombre')->orderBy('nombre')->lists('nombre','id');
        
        $this->aCustomViewData['aGeneros'] = Generos::select('id','genero')->orderBy('genero')->lists('genero','id');

        $this->aCustomViewData['aScursales'] = Note::select('id_nota as id','titulo')->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))->where('habilitado', 1)->orderBy('destacado','desc')->get();

        if($prod->categoria_meli!=''){
            $categoria = CategoriasMeli::where('id_meli_categoria',$prod->categoria_meli)->get();
            $dataprodcateg = ProductosCategMeli::where('idproducto',$id)->first();

            $this->aCustomViewData['aCategoriasMeli'] = $categoria;
            $this->aCustomViewData['aProductosMeli'] = $dataprodcateg;

            $array = [];

            foreach ($categoria as $key) {
                
                    if($key['values']==1){
                        //es un array con los atributos que se guardaron en la base (lo que se agrego via excel o via panel)
                        $dataprodcategval = CategoriasMeliValues::select('id','name')
                                                            ->where('id_categoria',$key->id_meli_categoria)
                                                            ->where('categoria',$key->categoria)
                                                            ->where('name',$dataprodcateg[$key->categoria])
                                                            ->first();
                        //un array con todos los atributos por categorias                                                
                        $categoriaVal = CategoriasMeliValues::select('id','name')
                                                            ->where('id_categoria',$key->id_meli_categoria)
                                                            ->where('categoria',$key->categoria)
                                                            ->lists('name','id');

                        if($dataprodcategval){
                            array_push($array, ['categoria'=>$key->categoria,'name'=>$dataprodcategval['name'],'id'=>$dataprodcategval['id'] ]);                         
                        }else{
                            array_push($array, ['categoria'=>$key->categoria,'name'=>'','id'=>'' ]);                         
                        }
                    
                        $this->aCustomViewData[$key['categoria']] = $categoriaVal;
                    }
                    
            }
            
            $this->aCustomViewData['aValCategoria'] =$array;
        }else{
            $this->aCustomViewData['aCategoriasMeli'] = '';
            $this->aCustomViewData['aProductosMeli'] = '';
            $this->aCustomViewData['aValCategoria'] = '';
        }
        

        foreach($aColores as $color){    
			if($color->id_talle){
                $talle = Talles::find($color->id_talle);           
				if($talle){
					$color->nombreTalle = $talle->nombre;
                }           
            }
            //busco el stock por sucursal de este id stock color
            $array_stock = array();
            foreach($this->aCustomViewData['aScursales'] as $sucursal){
                $stock_suc = SucursalesStock::select('stock')
                ->where('id_codigo_stock', $color->id)
                ->where('id_sucursal', $sucursal->id)
                ->first();
                $data_array = array(
                    'stock' => $stock_suc?$stock_suc->stock:0,
                    'sucursal' => $sucursal->id,
                    'sucursaln' => $sucursal->titulo
                );
                array_push($array_stock, $data_array);
            }
            $color->stock = $array_stock;
        }

		$this->aCustomViewData['aColoresAssigned'] = $aColores;
        return $this->editTrait($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.update')) {
            
            $modelName = $this->modelName;
        
            $item = $modelName::find($id);

            if ($item) {
                
                //Just enable/disable resource? Habilitado
                if ('yes' === $request->input('justEnable')) {
					$item->habilitado = $request->input('enable');
					if (!$item->save()) {
						$aResult['status'] = 1;
						$aResult['msg'] = \config('appCustom.messages.dbError');                    
					}
					return response()->json($aResult);
                }

                //Just enable/disable resource? Destacado Home
                if ('yes' === $request->input('justEnable1')) {
                    $item->destacado = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }                

                //Just enable/disable resource? Lanzamiento
                if ('yes' === $request->input('justEnable2')) {
                    $item->oferta = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }                    

                    return response()->json($aResult);
                }

                //Just enable/disable resource? comentarios
                if ('yes' === $request->input('justEnable3')) {
                    $item->comentarios = $request->input('enable');
                    if (!$item->save()) {
                        $aResult['status'] = 1;
                        $aResult['msg'] = \config('appCustom.messages.dbError');                    
                        
                    }

                    return response()->json($aResult);
                }

                $validator = \Validator::make(
                    $request->all(), 
                    [
                        'nombre'    => 'required',
                        'id_rubro'  => 'required',
                        //'id_genero' => 'required',
                        'alto'      => 'required',
                        'ancho'     => 'required',
                        'largo'     => 'required',
                        'peso'      => 'required',
                    ], 
                    [
                        'nombre.required'   => 'El campo Nombre es requerido',
                        'id_rubro.required' => 'El campo Rubro es requerido',
                        //'id_genero.required'=> 'El campo Género es requerido',
                        'alto.required'     => 'El campo Alto es requerido',
                        'ancho.required'    => 'El campo Ancho es requerido',
                        'largo.required'    => 'El campo Largo es requerido',
                        'peso.required'     => 'El campo Peso es requerido',
                    ]
                )
                ;
                
                if ($item->nombre != $request->nombre) {
                    $validator->after(function($validator) use ($modelName, $request) {
                        if (!$modelName::where('nombre',$request->nombre)->get()->isEmpty()) {
                            $validator->errors()->add('field', 'El campo Nombre ya existe');
                        }
						
						
                    });
                }
				if(!$request->input('id_api')){
                    $validator->after(function($validator) use ($id, $request) {
                        $aSc = json_decode($request->input('stockColor'), true);

                        if (isset($aSc[0])) {
                            foreach ($aSc[0] as $aItem) {
                                $item = 
                                    ProductosCodigoStock::where('codigo', $aItem['codigo'])
                                        ->where('id_producto','!=',$id)
                                        ->first()
                                ;                                
                               /*  if ($item) {
                                    $validator->errors()->add('field', 'El código '.$aItem['codigo'].' ya existe');
                                    break;
                                } */
                            }
                        }
                    });
                }

                if (!$validator->fails()) {                    
                    if(!$request->input('id_api')){
                        $item->fill(
                            [
                                'nombre'        => $request->input('nombre'),
                                'nombremeli'        => $request->input('nombremeli'),
                                'id_rubro'      => $request->input('id_rubro'),
                                'id_subrubro'   => $request->input('id_subrubro'),
                                'id_subsubrubro'=> $request->input('id_subsubrubro'),
                                'id_genero'     => $request->input('id_genero'),
                                'categoria_meli'=> $request->input('categoria_meli') ? $request->input('categoria_meli') : $item->categoria_meli,
                                'categoria_variations'=> $request->input('categoria_variations') ? $request->input('categoria_variations') : $item->categoria_variations,
                                'id_marca'      => $request->input('id_marca'),
                                'id_origen'     => $request->input('id_origen'),
                                'modelo'        => $request->input('modelo'),
                                'sumario'       => $request->input('sumario'),
                                'texto'         => $request->input('texto'),
                                'estado'      	=> $request->input('estado'),
                                'id_video'     	=> $request->input('id_video'),
                                'alto'          => $request->input('alto'),
                                'ancho'         => $request->input('ancho'),
                                'largo'         => $request->input('largo'),
                                'peso'          => $request->input('peso'),
                                'orden'         => $request->input('orden'),
                                'id_meli'       => $request->input('id_meli')?$request->input('id_meli'):$item->id_meli,
                                'estado_meli'   => $request->input('estado_meli')?$request->input('estado_meli'):$item->estado_meli,
                                
                            ]
                        )
                        ;

                        if (!$item->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                    

                        // Relaciono el producto con las etiquetas
                        if($request->input('etiquetasIds')){
                            $aAllEtiquetas = Etiquetas::get();
                            if (!$aAllEtiquetas->isEmpty()) {
                                
                                foreach ($aAllEtiquetas as $etiqueta) {
                                    $etiqueta->productos()->detach($item);
                                }
                                $aOpt = $request->input('etiquetasIds');
                                if(!$aOpt){ $aOpt = array(); }
                                array_walk($aOpt, function($value) use ($item){
                                    $etiqueta = Etiquetas::where('id',$value)->first();
                                    if ($etiqueta) {
                                        $etiqueta->productos()->attach($item);
                                    }
                                });
                            }
                        }
                        // Relaciono el producto con los deportes
                        if($request->input('deportesIds')){
                            $aAllDeportes = Deportes::get();
                            if (!$aAllDeportes->isEmpty()) {
                                
                                foreach ($aAllDeportes as $deporte) {
                                    $deporte->productos()->detach($item);
                                }
                                $aOpt = $request->input('deportesIds');
                                if(!$aOpt){ $aOpt = array(); }
                                array_walk($aOpt, function($value) use ($item){
                                    $deporte = Deportes::where('id',$value)->first();
                                    if ($deporte) {
                                        $deporte->productos()->attach($item);
                                    }
                                });
                            }
                        }
                    }
					
                    // Relaciono el producto con las color, stock, codigo
                    if($request->input('stockColor')){
                        $aAllColores = Colores::get();
                        if (!$aAllColores->isEmpty()) {
                            //borro el stock por sucursal
                            $get_id_pcs = ProductosCodigoStock::select('id')->where('id_producto', $item->id)->get();
                            foreach($get_id_pcs as $data_pcs){
                                SucursalesStock::where('id_codigo_stock', $data_pcs->id)->delete();
                            }
                            foreach ($aAllColores as $colores) {
                                $colores->productos()->detach($item);
                            }
                            $aOptC = json_decode($request->input('stockColor'), true);
                            if(!$aOptC){ $aOptC = array(); }else{ $aOptC = $aOptC[0]; }
                            array_walk($aOptC, function($value) use ($item){
                                $colores = Colores::where('id',$value['id_color'])->first();
                                $stock = 0;
                                foreach($value['stock'] as $data){
                                    $stock = $stock+$data['stock'];
                                }
                                if ($colores) {
                                    $colores->productos()
                                    ->attach(
                                        $item->id,
                                        ['id_talle' => $value['id_talle'],'stock' => $stock,'codigo' => $value['codigo'],'estado_meli' => 0]
                                    );
                                    $att_id = ProductosCodigoStock::select('id')->where(['id_talle' => $value['id_talle'],'stock' => $stock,'codigo' => $value['codigo'],'id_producto' => $item->id])->first();
                                    foreach($value['stock'] as $data){
                                        $stock_sucursal = new SucursalesStock;
                                        $stock_sucursal->id_codigo_stock = $att_id->id;
                                        $stock_sucursal->id_sucursal = $data['id'];
                                        $stock_sucursal->stock = $data['stock'];
                                        $stock_sucursal->save();
                                    }
                                }
                            });
                        }
                    }

                    //CATEGORIZACION DE MELI
                    //primero creo las columnas en la tabla
                    if(!$request->input('id_api')){                        
                        if($item->categoria_meli!=''){                        
                            $categorias = CategoriasMeli::where('id_meli_categoria',$item->categoria_meli)->get();
                                            
                            foreach ($categorias as $key) {   
                                if (!Schema::hasColumn('inv_productos_categ_meli', $key['categoria']) )
                                {  
                                    //creo las columnas segun categorias
                                    $categ = $key['categoria'];
                                    Schema::table('inv_productos_categ_meli', function (Blueprint $table) use($categ) {
                                        $table->string($categ, 200)->nullable();
                                    });
                                }                             
                            }

                            //segundo guardo los valores por id de producto
                            $prodCat = ProductosCategMeli::where('idproducto',$id)->first();
                            
                            if($prodCat){
                                $prodCat->idproducto = $id;
                                $prodCat->idcategoriameli = $item->categoria_meli;
                                $prodCat->titulo = $item->nombre;
                                
                                foreach ($categorias as $key) {   
                                    $nombre = Util::eliminar_espacios($key['categoria']);//en el request lo pone con _ a los espacions en blanco
                                    $nombreOriginal = $key['categoria'];//como figura en la base con espacios                                
                                    if($key['values']==1){                                
                                        if($request[$nombre]){
                                            //obtengo el valor del atributo segun id
                                            $atr = CategoriasMeliValues::where('categoria',$key['categoria'])->where('id',$request[$nombre])->first();
                                            $prodCat->$nombreOriginal = $atr->name;
                                        }
                                    }else{
                                        $prodCat->$nombreOriginal = $request[$nombre];
                                    }
                                }

                                $prodCat->save();

                            }else{
                                $save = new ProductosCategMeli();
                                $save->idproducto = $id;
                                $save->idcategoriameli = $item->categoria_meli;
                                $save->titulo = $item->nombre;
                                
                                foreach ($categorias as $key) {   
                                    $nombre = Util::eliminar_espacios($key['categoria']);//en el request lo pone con _ a los espacions en blanco
                                    $nombreOriginal = $key['categoria'];//como figura en la base con espacios                                
                                    if($key['values']==1){                                
                                        if($request[$nombre]){
                                            //obtengo el valor del atributo segun id
                                            $atr = CategoriasMeliValues::where('categoria',$key['categoria'])->where('id',$request[$nombre])->first();
                                            $save->$nombreOriginal = $atr->name;
                                        }
                                    }else{
                                        $save->$nombreOriginal = $request[$nombre];
                                    }
                                }

                                $save->save();
                            }                     
                        }
                    }
                    
                } else {
                    $aResult['status'] = 1;
                    $aResult['msg'] = $validator->errors()->all();
                }

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
               
        return response()->json($aResult);
    }

    public function updateImportKernel($request, $id)
    {
        $aResult = Util::getDefaultArrayResult();
        
            
        $modelName = 'App\AppCustom\Models\Productos';
        
            $item = $modelName::find($id);

            if ($item) {

                $validator = \Validator::make(
                    $request, 
                    [
                        'nombre'    => 'required',
                        'id_rubro'  => 'required',
                        'id_genero' => 'required',
                        'alto'      => 'required',
                        'ancho'     => 'required',
                        'largo'     => 'required',
                        'peso'      => 'required',
                    ], 
                    [
                        'nombre.required'   => 'El campo Nombre es requerido',
                        'id_rubro.required' => 'El campo Rubro es requerido',
                        'id_genero.required'=> 'El campo Género es requerido',
                        'alto.required'     => 'El campo Alto es requerido',
                        'ancho.required'    => 'El campo Ancho es requerido',
                        'largo.required'    => 'El campo Largo es requerido',
                        'peso.required'     => 'El campo Peso es requerido',
                    ]
                )
                ;
                
             

                if (!$validator->fails()) {
                        $item->fill(
                            [
                                'nombre'        => $request['nombre'],
                                'id_rubro'      => $request['id_rubro'],
                                'id_subrubro'   => $request['id_subrubro'],
                              /*   'id_subsubrubro'=> $request['id_subsubrubro'], */
                                'id_genero'     => $request['id_genero'],
                               /*  'categoria_meli'=> $request['categoria_meli'],
                                'categoria_variations'=> $request['categoria_variations'], */
                                'id_marca'      => $request['id_marca'],
                               /*  'id_origen'     => $request['id_origen'], */
                                /* 'modelo'        => $request['modelo'],
                                'sumario'       => $request['sumario'],
                                'texto'         => $request['texto'],
                                'estado'      	=> $request['estado'],
                                'id_video'     	=> $request['id_video'], */
                                'alto'          => $request['alto'],
                                'ancho'         => $request['ancho'],
                                'largo'         => $request['largo'],
                                'peso'          => $request['peso'],
                                'orden'         => $request['orden'],
                                /* 'habilitado'    => ($request->input('habilitado')?$request->input('habilitado'):0), */
                                
                            ]
                        )
                        ;

                        if (!$item->save()) {
                            $aResult['status'] = 1;
                            $aResult['msg'] = \config('appCustom.messages.dbError');
                        }
                

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }
        } 
        
        return response()->json($aResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($this->user->hasAccess($this->resource . '.delete')) {
            $modelName = $this->modelName;
        
            $item = $modelName::find($id);

            if ($item) {
                //reviso que el producto no esté asociado a un pedido
                $pedidos = PedidosProductos::where('id_producto', $id)->first();
                if($pedidos){
                    $aResult['status'] = 1;
                    $aResult['msg'] = "El producto está asociado a uno o más pedidos. Se recomienda deshabilitar el producto.";
                }elseif (!$item->delete()) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = \config('appCustom.messages.dbError');
                }else{
                // Borro las relaciones de las Etiquetas con los productos
                $aAllEtiquetas = Etiquetas::get();
                if (!$aAllEtiquetas->isEmpty()) {
                    
                    foreach ($aAllEtiquetas as $etiqueta) {
                        $etiqueta->productos()->detach($item);
                    }
                }
                // Borro las relaciones de los Deportes con los productos
                $aAllDeportes = Deportes::get();
                if (!$aAllDeportes->isEmpty()) {
                    
                    foreach ($aAllDeportes as $deporte) {
                        $deporte->productos()->detach($item);
                    }
                }
                //elimino relacion stock color y talle
                $aAllColores = Colores::get();
                if (!$aAllColores->isEmpty()) {
                    foreach ($aAllColores as $colores) {
                        $colores->productos()->detach($item);
                    }
                }
                $precio_del = PreciosProductos::where('id_producto', $id)->delete();
                }
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            }            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
        
        return response()->json($aResult);
    }

    public function setEtiquetas(Request $request){
        $aResult = Util::getDefaultArrayResult();
        //dd($aResult);

        if ($this->user->hasAccess($this->resource . '.create')) {

            $aIds = \array_unique(json_decode($request->input('ids')));


            if ($aIds) {

                $aViewData = array(
                    'mode' => 'add',
                    'resource' => $this->resource,
                    'resourceLabel' => 'Etiquetas',

                    'parentResource' => $request->input('parentResource'),
                    'aIds' => $aIds,
                    'etiquetas' => Etiquetas::select('id','nombre')->orderBy('nombre')->get()
                );
                $aResult['html'] = \View::make($this->viewPrefix . $request->input('parentResource')  .".setEtiquetasEdit")
                    ->with('aViewData', $aViewData)
                    ->render();
            } else {
                $aResult['status'] = 2;
                $aResult['msg'] = 'Debe elegir al menos un producto';
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    public function setEtiquetasPost(Request $request){
        $aResult = Util::getDefaultArrayResult();

        if (!$this->user->hasAccess($this->resource . '.create')) {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');

            return response()->json($aResult);
        }
        $validator = \Validator::make(
            $request->all(),
            [
                'ids'    => 'required',
                'etiquetasIds'    => 'required'
            ],
            [
                'ids.required'   => 'Debe seleccionar al menos un producto.',
                'etiquetasIds.required'   => 'Debe seleccionar al menos una etiqueta.'
            ]
        );
        if (!$validator->fails()) {
            $aAllEtiquetas = Etiquetas::get();
            $aOpt = $request->input('etiquetasIds');
            if(!$aOpt){ $aOpt = array(); }

            if (!$aAllEtiquetas->isEmpty()) {
                $productos = Productos::
                select('id')
                    ->whereIn('id', \json_decode($request->ids))
                    ->get();
                foreach($productos as $producto){
                    array_walk($aOpt, function($value) use ($producto){
                        $prod = ProductosEtiquetas::where('id_producto', $producto['id'])->where('id_etiqueta', $value)->first();
                        if(!$prod){
                            $etiqueta = Etiquetas::where('id',$value)->first();
                            if ($etiqueta) {
                                $etiqueta->productos()->attach($producto);
                            }
                        }
                    });
                }
            }
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = $validator->errors()->all();
        }
        return response()->json($aResult);
    }

    public function codigosFlexxus(Request $request){
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.create')) {

            $cantError = ProductosCodigoStock::where('estado_flexxus', 0)->count();
            $itemsError = ProductosCodigoStock::where('estado_flexxus', 0)->get();
            
            $aViewData = array(
                'mode' => 'edit',
                'resource' => $this->resource,
                'resourceLabel' => 'Productos',
                'parentResource' => $request->input('parentResource'),
                'cantError' => $cantError,
                'itemsError' => $itemsError
            );

            $aResult['html'] = \View::make($this->viewPrefix ."productos/setCodigoFlexxus")
                ->with('aViewData', $aViewData)
                ->render();
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }

    public function codigosFlexxusPost(Request $request){
        $aResult = Util::getDefaultArrayResult();
        if ($this->user->hasAccess($this->resource . '.create')) {
            
            $items = $request->input('datos');
            $items = json_decode($items, true);

            if(is_array($items)){
                foreach($items as $id => $item){
                    $id = str_replace('id_','',$id);

                    $prod = ProductosCodigoStock::find($id);
                    if($prod){
                        $prod->codigo_flexxus = $item['codigo'];
                        $prod->talle_flexxus = $item['talle'];
                        $prod->save();
                    }
                }
            }else{
                $aResult['status'] = 2;
                $aResult['msg'] = 'No realizo cambios';
            }
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
	
        return response()->json($aResult);
    }
}
