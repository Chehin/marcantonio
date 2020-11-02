<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\AppCustom\Api;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Log;

class HomeController extends Controller
{
    public function home(Request $request, Api $api){
        $this->view_ready($api);
        //array comun a  PRODUCTOS
        $array_send = array(
            'id_idioma' => 1,
            'id_moneda' => env('ID_MONEDA'),
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'fotos' => 1,
            'orden' => array(
                'col' => env('ORDEN_COL'),
                'dir' => env('ORDEN_DIR')
            ),
            'iDisplayLength' => 30, //registros por pagina
            'iDisplayStart' => 0, //registro inicial (dinamico)
        );

        // LISTADO DE PRODUCTOS PARA EL HOME
        //Mas Vendidos
        $ProductosMasVendidos=Util::aResult();
        try {
            $array_pv = $array_send;
            $array_pv['limit'] = 21;
            $array_pv['MasVendidos'] = 1;
            $post = http_build_query($array_pv);
            $ProductosMasVendidos = $api->client->resJson('GET', 'productosHome?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
        //Destacados
        $ProductosDestacados=Util::aResult();
        try {
            $array_pv = $array_send;
            $array_pv['limit'] = 21;
            $array_pv['Destacados'] = 1;
            $post = http_build_query($array_pv);
            $ProductosDestacados = $api->client->resJson('GET', 'productosHome?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
	//return $ProductosDestacados;
        //Mas Vistos
        $ProductosMasVistos=Util::aResult();
        try {
            $array_pv = $array_send;
            $array_pv['limit'] = 21;
            $array_pv['MasVistos'] = 1;
            $post = http_build_query($array_pv);
            $ProductosMasVistos = $api->client->resJson('GET', 'productosHome?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
        //Ofertas
        $ProductosOfertas=Util::aResult();
        try {
            $array_pv = $array_send;
            $array_pv['limit'] = 21;
            $array_pv['Ofertas'] = 1;
            $post = http_build_query($array_pv);
            $ProductosOfertas = $api->client->resJson('GET', 'productosHome?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
	      // \Log::info(print_r($ProductosMasVendidos,true));

        // SLIDER
        $array_send = array(
            'id_edicion' => 'MOD_NEWSSLIDER_FILTER',
            'edicion' => 'slider',
            'fotos' => 99
        );
        $slider=Util::aResult();
        try {
            $post = "&".http_build_query($array_send);
            $slider = $api->client->resJson('GET','slider'."?idioma=1".$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }

        // MARCAS
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'marcas',
            'fotos' => 1,
            'destacado' => 1
        );
        $marcas=Util::aResult();
        try {
            $post = http_build_query($array_send);
            $marcas = $api->client->resJson('GET', 'listadoMarcas?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }

        // ETIQUETAS
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'marcas',
            'fotos' => 1,
            'destacado' => 1
        );
        $etiquetas=Util::aResult();
        try {
            $post = http_build_query($array_send);
            $etiquetas = $api->client->resJson('GET', 'sliderEtiquetas?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }

        // GENEROS
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'marcas',
            'fotos' => 1,
            'destacado' => 1
        );
        $etiquetas_rubros=Util::aResult();
        try {
            $post = http_build_query($array_send);
            $etiquetas_rubros = $api->client->resJson('GET', 'panelGeneros?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
        //DEPORTES
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'marcas',
            'fotos' => 1,
            'destacado' => 1
        );
        $Deportes=Util::aResult();
        try {
            $post = http_build_query($array_send);
            $Deportes = $api->client->resJson('GET', 'deportes?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }

        $Servicios=Util::aResult();
        try {
            $post = http_build_query($array_send);
            $Servicios = $api->client->resJson('GET', 'servicios?'.$post)['data'];
        } catch (RequestException $e) {
            Log::error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                Log::error($e->getMessage());
            }
        }
       // return $slider;
        return view('home.index',
            compact('slider','marcas','etiquetas','etiquetas_rubros','ProductosMasVendidos','Deportes','ProductosDestacados',
            'Servicios','ProductosMasVistos','ProductosOfertas'));
    }

    public function viewBlog(Request $request, Api $api, $page = 1){
        $this->view_ready($api);
        $q = $request->input('q')?$request->input('q'):0;
        $mes = $request->input('m');
        $anio = $request->input('a');
        $tag = $request->input('tag');
        $pageTitle = 'Blog - ';

        $filtros = array();

        $extraParams = array(
            'getData' => $request->all(),
        );

        // LISTADO DE Blog
        $array_send = array(
            'id_edicion' => 'MOD_BLOG_FILTER',
            'edicion' => 'news',
            'fotos' => 1,
            'orden' => array(
                'col' => 'fecha',
                'dir' => 'desc'
            ),
            'iDisplayLength' => env('REGISTROS_PAGINA'), //registros por pagina
            'iDisplayStart' => ($page-1)*env('REGISTROS_PAGINA') //registro inicial (dinamico)
        );

        
        if($q){
			$array_send['search'] = $q;
        }
        if($tag){
			$array_send['tag'] = $tag;
        }
        if($mes && $anio){
            $array_send['filtro_archivo'] = array(
                'm' => $mes,
                'a' => $anio
            );
        }
        
        try {			
            $post = http_build_query($array_send);
            $res = $api->client->resJson('GET', 'blog?'.$post);
            $blog = array();
            if ($res['status'] == 0){
                $data = $res['data'];
                $total_reg = $res['data']['total'];
                $total_pages = ceil($total_reg/ env('REGISTROS_PAGINA'));
                $etiquetas = $res['data']['etiquetas_all'];
                $archivos = $res['data']['archivos'];
                $filtros = array(
                    'q' => $q,
                    'm' => $mes,
                    'a' => $anio,
                    'tag' => $tag
                );
            }
            
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }


        return view('blog.index', compact('pageTitle', 'data', 'etiquetas', 'archivos', 'page', 'total_reg', 'total_pages', 'extraParams', 'filtros'));
    }

    public function viewBlogNota(Api $api, Request $request, $id){
        $this->view_ready($api);
        $filtros = array(
            'q' => 0,
            'm' => 0,
            'a' => 0,
            'tag' => 0
        );

        $blog = array();
        try {			
            $res = $api->client->resJson('GET', 'blog/'.$id);
        
            if ($res['status'] == 0){
                $blog = $res['data'];
            }
            
            if(!isset($blog[0])){
                return redirect()->route('blog',['page' => 1]);
            }
    
            $nota = $blog[0];
            $etiquetas_all = $nota['etiquetas_all'];
            $etiquetas = $nota['etiquetas'];
            
            $archivos = $nota['archivos'];
            
            $pageTitle = $nota['titulo'].' - Blog - ';
    
            $relacionados = $nota['relacion'];
            

		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }

        return view('blog.nota', compact('pageTitle', 'nota', 'relacionados', 'etiquetas','etiquetas_all', 'archivos', 'filtros'));
    }


    public function autocomplete(Api $api,Request $request){

        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'fotos' => 1,
            'id_moneda' => (string)env('ID_MONEDA'),
            'q' => $request->input('term')
        );

        $data = array();
        try {
            $post = http_build_query($array_send);
            $data= $api->client->resJson('GET', 'autocomplete?'.$post);         
		} catch (RequestException $e) {
			Log::error(Psr7\str($e->getRequest()));
			if ($e->hasResponse()) {
				Log::error($e->getMessage());
			}
        }
        
        $results=array();
        foreach ($data as $k => $v) {
            $results[]=['id' =>$v['id'],
                        'label' =>$v['nombre'],
                        'value'=>$v['nombre'],
                        'img'=>$v['imagen_file']                     
            ];
        }
    
        return response()->json($results);
    }

    public function search(Api $api,Request $request)
    {
        $q = $request->input('term');
        $data = array();
        $array_send = array(
            'id_edicion' => 'MOD_PRODUCT_FILTER',
            'edicion' => 'productos',
            'fotos' => 1,
            'search' => urldecode($q)
        );
        $post = http_build_query($array_send);
        $res = $api->client->resJson('GET', 'search?'.$post);
        if ($res['status'] == 0){
            $search = $res['data'];
            foreach($search['productos'] as $result){
                $item = array(
                    'id' => $result['id'],
                    'label' => str_slug($result['titulo']),
                    'value' => $result['titulo'],
                    'img' => (isset($result['fotos'][0]['imagen_file'])?(string)env('URL_BASE_UPLOADS').'th_'.$result['fotos'][0]['imagen_file']:'images/img_default/th_producto.jpg')
                );
                array_push($data, $item);
            }
        }
    
        echo json_encode($data);
    }
   
}