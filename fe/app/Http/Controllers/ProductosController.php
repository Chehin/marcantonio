<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class ProductosController extends Controller
{

    public function viewProducto(Request $request, $id, Api $api){ 
       
        $pageTitle = env('SITE_NAME');
        $this->view_ready($api);
        $array_send = array(
            'id' => $id,
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_idioma' => 1,
            'id_moneda' => env('ID_MONEDA')
        );
 
        $rest=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET','producto?'.$post);
            $data = $res['data'];
            if(!isset($data['producto'])){
                return redirect()->route('home');
            }
            $producto = $data['producto'];
            $categoria = $data['categoria'];
            $etiquetas = $data['etiquetas'];
            $precios = $data['precios'];
            $fotos = $data['fotos'];
            $stock = $data['stock'];
            $stockColor = $data['stockColor'];
           // \Log::debug('STOCK: '.print_r($categoria,true));
           // $subRubroGeneroMarca = $data['subrubrogeneromarca'];
            $pageTitle.= isset($categoria['rubro']['rubro'])?' - '.$producto['nombre'].' - '.$categoria['rubro']['rubro'] : $producto['nombre'];
            $pageTitle.= isset($categoria['subrubro']['subrubro'])?' - '.$categoria['subrubro']['subrubro']:'';
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
      //  if (!isset($stockColor[0]['stock_total'])) $stockColor[0]['stock_total']=0;
        //relacionados
        $array_send_p = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_relacion' => $id,
            'id_moneda' => env('ID_MONEDA'),
            'fotos' => 1,
            'limit' => 8,
            'forzar' => true,
            'orden' => array(
                'col' => env('ORDEN_COL'),
                'dir' => env('ORDEN_DIR')
            ),
            'iDisplayLength' => 99, //registros por pagina
            'iDisplayStart' => 0, //registro inicial (dinamico)
        );

        $res=Util::aResult();
        $rel = array();
        $relacionados = array();
        try {
            $post = http_build_query($array_send_p);
            $res = $api->client->resJson('GET', 'listadoProductosRelacionados?'.$post)['data'];
            $rel = $res;
            $ProductosRelacionados['productos'] = $rel['productos'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

/*
        //relacionados colores
        $array_send_color = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_relacion' => $id,
            'id_moneda' => env('ID_MONEDA'),
            'fotos' => 1,
            'limit' => 8,
            'forzar' => true,
            'orden' => array(
                'col' => env('ORDEN_COL'),
                'dir' => env('ORDEN_DIR')
            ),
            'iDisplayLength' => 99, //registros por pagina
            'iDisplayStart' => 0, //registro inicial (dinamico)
        );

        $res=Util::aResult();
        $rel = array();
        $relacionadosColor = array();
        try {
            $post = http_build_query($array_send_color);
            $res = $api->client->resJson('GET', 'listadoProductosRelacionadosColor?'.$post)['data'];
            $rel = $res;
            $relacionadosColor['productos'] = $rel['productos'];
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
*/
      // \Log::debug(print_r($stockColor,true));
       // return $stockColor;
        return view('productos.detalle', compact('etiquetas','producto','categoria','fotos','precios', 'stockColor','subRubroGeneroMarca','stock','ProductosRelacionados','relacionadosColor','pageTitle'));
    }  

    public function listproductos(Request $request, $id_etiqueta = 0, $id_rubro = 0, $id_subrubro = 0, $name = 0, $page = 1, Api $api){

        $pageTitle = env('SITE_NAME') . " - Productos ";
        $this->view_ready($api);
        $q = $request->input('q')?$request->input('q'):0;
        $IdMarca = $request->input('IdMarca')?$request->input('IdMarca'):'';
        $IdDeporte = $request->input('IdDeporte')?$request->input('IdDeporte'):'';
        $precios = array($request->input('preciomin')?$request->input('preciomin'):0, $request->input('preciomax')?$request->input('preciomax'):0);
        $header = false;

        switch($request->input('sortList')){
            case 'nombre':
                $sort = 'inv_productos.nombre';
                $dir = 'asc';
            break;
            case 'MenorPrecio':
                $sort = 'inv_precios.precio_venta';
                $dir = 'asc';
            break;
            case 'MayorPrecio':
                $sort = 'inv_precios.precio_venta';
                $dir = 'desc';
            break;
            case 'Destacados':
                $sort = 'inv_productos.destacado';
                $dir = 'desc';
            break;
            case 'MasVistos':
                $sort = 'inv_productos_estadisticas.visitas';
                $dir = 'desc';
            break;
            case 'MasVendidos':
                $sort = 'MasVendidos';
                $dir = 'desc';
                break;
            default:
                $sort = (string)env('ORDEN_COL');
                $dir = (string)env('ORDEN_DIR');
            break;
        }
      //  \Log::debug('Sort: :'.$request->input('sortList'));
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'fotos' => 1,
            'id_moneda' => (string)env('ID_MONEDA'),
            'orden' => array(
                'col' => $sort,
                'dir' => $dir
            ),
            'iDisplayLength' => (int)env('REGISTROS_PAGINA'), //registros por pagina
            'iDisplayStart' => ((int)$page-1)*(int)env('REGISTROS_PAGINA') //registro inicial (dinamico)
        );

        if($id_rubro){
            $array_send['filtros']['id_rubro'] = $id_rubro;
        }
        if($id_subrubro){
            $array_send['filtros']['id_subrubro'] = $id_subrubro;
        }
        if($id_etiqueta){
            $array_send['tag'] = $id_etiqueta;
        }
        if($IdMarca){
            $array_send['IdMarca'] = $IdMarca;
        }
        if($IdDeporte){
            $array_send['IdDeporte'] = $IdDeporte;
        }
        if($precios){
            $array_send['precios'] = $precios;
        }
        if($q){
			$array_send['search'] = $q;
        }

        $res=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);
            $res= $api->client->resJson('GET', 'listadoProductos?'.$post)['data'];
            $data = $res;
            $productos_array = $data['productos'];
            $etiqueta_array = $data['etiqueta'];
            $rubros_array = $data['rubro'];
            $deporte_array = $data['deporte'];
            $marca_array = $data['marca'];
            $search = $q;
            $total_reg = $data['total'];
            $TotalPaginas = ceil($total_reg/ (int)env('REGISTROS_PAGINA'));
        //    if($total_reg==1){
                //redirigir al producto
         //       return redirect()->route('producto',['id' => $productos_array[0]['id'],'name' => str_slug($productos_array[0]['titulo'])]);
		//	}
          //  \Log::debug(print_r($categorias_array,true));
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

/*
        if(isset($categorias_array['rubro']['header']['0'])){
            $header = $categorias_array['rubro']['header'];
        }elseif(isset($etiqueta_array['header']['0'])){
            $header = $etiqueta_array['header'];
        }
      */
        $search = $q;
        $extraParams = array(
            'getData' => $request->all(), // Parametros en la URL
            'url' => array(
                'id_etiqueta' => $id_etiqueta,
                'id_rubro' => $id_rubro,
                'id_subrubro' => $id_subrubro,
                'name' => str_slug($name),
                'page' => $page
            )
        );
      //  \Log::debug(print_r($extraParams,true));

        //get filtros
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'search' => $search,
            'id_rubro' => $id_rubro,
            'id_subrubro' => $id_subrubro,
            'id_marca' => $IdMarca,
            'id_etiqueta' => $id_etiqueta,
            'id_deporte' => $IdDeporte,
            'precios' => $precios
        );

        $res=Util::aResult();
        $data_filtros = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET','filtros?'.$post);
            $data_filtros = $res['data'];
            $data_rubros = $data_filtros['rubros'];
            $data_marcas = $data_filtros['marcas'];
            $data_subrubros = $data_filtros['subrubros'];
            $data_deportes = $data_filtros['deportes'];
            $data_etiquetas = $data_filtros['etiquetas'];

		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        $head=array();
         $head['actual']='Productos';

        if ($search) $head['actual'] = $search;
        if (isset($rubros_array['subrubro'])) $head['actual']= $rubros_array['subrubro'];
        if (isset($rubros_array['nombre']) && is_null($rubros_array['subrubro'])) $head['actual']= $rubros_array['nombre'];
        if (isset($marca_array['nombre'])) {
            $head['actual']= $marca_array['nombre'];
            $head['etiqueta_marca'] = $marca_array['nombre'];
        }

        if (isset($etiqueta_array['nombre'])) {
            $head['etiqueta']= $etiqueta_array['nombre'];
            $head['actual']= $etiqueta_array['nombre'];
        };
        if (isset($deporte_array['nombre'])) {
            $head['actual']= $deporte_array['nombre'];
            $head['etiqueta_deporte'] = $deporte_array['nombre'];

        }

        $url['id_etiqueta'] = ($id_etiqueta)?$id_etiqueta:0;
        $url['id_rubro'] = ($id_rubro)?$id_rubro:0;
        $url['id_subrubro'] = ($id_subrubro)?$id_subrubro:0;
        $url['id_marca'] = ($IdMarca)?$IdMarca:0;
        $url['search'] = $q;

        $filtros = array(
            'rubros' => $data_rubros,
            'subrubros' => $data_subrubros,
            'marcas' => $data_marcas,
            'etiquetas' => $data_etiquetas,
            'deportes' => $data_deportes,
         //   'precios' => $data_precios
        );

        //para ver en el title page
        if(isset($head['etiqueta'])&&($head['etiqueta'] != '')&&($head['etiqueta'] != 'Productos')){
            $t2 = ' - '.$head['etiqueta'];
        }else{
            $t2 = '';
        }
        if(isset($head['etiqueta_marca'])&&($head['etiqueta_marca'] != '')){
            $t3 = ' - '.$head['etiqueta_marca'];
        }else{
            $t3 = '';
        }
        if(isset($head['etiqueta_deporte'])&&($head['etiqueta_deporte'] != '')){
            $t4 = ' - '.$head['etiqueta_deporte'];
        }else{
            $t4 = '';
        }

        
        $pageTitle = env('SITE_NAME') . $t2 . $t3 . $t4 ." - Productos ";

        return view('productos.listado', compact('filtros', 'extraParams', 'page', 'total_reg', 'TotalPaginas', 'etiqueta_array','marca_array', 'rubros_array', 'productos_array', 'search','pageTitle','head','url'));
    }

    public function filtrproductos(Request $request , Api $api){
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'categorias' => (sizeof($request->input('categorias')>0))?$request->input('categorias'):null,
            'rubros' => (sizeof($request->input('rubros')>0))?$request->input('rubros'):null,
            'deportes' => (sizeof($request->input('deportes')>0))?$request->input('deportes'):null,
            'marcas' => (sizeof($request->input('marcas')>0))?$request->input('marcas'):null,
            'precios' => (sizeof($request->input('precios')>0))?$request->input('precios'):null,
            'sortlist' => $request->input('sortlist')?$request->input('sortlist'):null,
            'page' =>  $request->input('page')?$request->input('page'):(int)1
        );
        $res=Util::aResult();
        $data = array();
        $post = http_build_query($array_send);

        $res = $api->client->resJson('GET', 'filtrop?'.$post);
        $data = $res['data'];

        return $data;

        // try {
        //     $post = http_build_query($array_send);
        //     $res = $api->client->resJson('GET', 'filtrop?'.$post);
        //     $data = $res['data'];
        //     \Log::info($data);
        //     return $data;
		// } catch (RequestException $e) {
		// 	Log::error(Psr7\str($e->getRequest()));
		// 	if ($e->hasResponse()) {
		// 		Log::error($e->getMessage());
		// 	}
        // }
    }
    

    public function cambioColor(Request $request, Api $api){
        //traer fotos, talles, codigo y stock de ese color

        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'id_producto' => $request->id_producto,
            'id_color' => $request->id_color,
        );

        $res=Util::aResult();
        $data = array();
        try {
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET', 'cambioColor?'.$post);
            $data = $res['data'];
            return $data;
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        
    }

}
