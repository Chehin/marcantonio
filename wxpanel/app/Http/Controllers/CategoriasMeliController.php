<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);
ini_set('memory_limit', '512M');
use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Schema\Blueprint;
use App\AppCustom\Util;
use App\AppCustom\Models\CategoriasMeli;
use App\AppCustom\Models\CategoriasMeliValues;
use App\AppCustom\Models\ProductosCategMeli;

class CategoriasMeliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            //traigo todas las categorias
            $cat = Util::categoriasMeliUnique();
            
            if(count($cat)>0){
                foreach ($cat as $c) {
                    $clientPost = new Client([
                        'base_uri' => 'https://api.mercadolibre.com/',
                    ]);
                    
                    
                     $response = $clientPost->request('GET', '/categories/'.$c->categoria_meli.'/attributes');
                     $responsePost=$response->getBody()->getContents();
                     $verificationId=json_decode($responsePost,true);
                     
                     //nombre de cateogrias
                     $responseN = $clientPost->request('GET', '/categories/'.$c->categoria_meli);
                     $responsePostN=$responseN->getBody()->getContents();
                     $verificationIdN=json_decode($responsePostN,true);
                     
                     if($verificationId){
                        foreach ($verificationId as $item) {                   
                           
                            if(isset($item['name']) and $item['name']!='Color' and $item['name']!='Color principal' and $item['name']!='Talle' and $item['relevance']==1){

                                /* $categ = new CategoriasMeli();  
                                $categ_val = new CategoriasMeliValues();   */
                                
                                CategoriasMeli::updateOrCreate( 
                                            ['id_meli_categoria' => $c->categoria_meli, 'categoria' => Util::eliminar_tildes($item['name']) ],
                                            ['nombreCategoria' => $verificationIdN['name'],
                                            'id_meli' => $item['id'],
                                            'values' => (isset($item['values'])) ? 1 : 0,
                                            'allow_variations' => (isset($item['tags']['allow_variations'])) ? 1 : 0,
                                            'defines_picture' => (isset($item['tags']['defines_picture'])) ? 1 : 0,
                                            'fixed' => (isset($item['tags']['fixed'])) ? 1 : 0,
                                            'hidden' => (isset($item['tags']['hidden'])) ? 1 : 0,
                                            'inferred' => (isset($item['tags']['inferred'])) ? 1 : 0,
                                            'multivalued' => (isset($item['tags']['multivalued'])) ? 1 : 0,
                                            'others' => (isset($item['tags']['others'])) ? 1 : 0,
                                            'product_pk' => (isset($item['tags']['product_pk'])) ? 1 : 0,
                                            'read_only' => (isset($item['tags']['read_only'])) ? 1 : 0,
                                            'required' => (isset($item['tags']['required'])) ? 1 : 0,
                                            'restricted_values' => (isset($item['tags']['restricted_values'])) ? 1 : 0,
                                            'variation_attribute' => (isset($item['tags']['variation_attribute'])) ? 1 : 0]
                                );
                               
                                //$categ->updateOrCreate($array);

                                /* ProductosCategMeli::updateOrCreate(
                                    ['id_producto' => $idProd],
                                    ['id_categoria_meli' => $idCat, 'titulo' =>  $titulo]
                                ); */

                                //guardo los valores de las categorias
                                if(isset($item['values'])){
                                    foreach ($item['values'] as $i) {
                                        CategoriasMeliValues::updateOrCreate(
                                             ['id_categoria' => $c->categoria_meli,'id' => $i['id']],
                                             ['categoria' => Util::eliminar_tildes($item['name']),
                                              'name' => $i['name'],
                                              'metadata' => (isset($i['metadata']))? json_encode($i['metadata']) : ''
                                            ]
                                        );

                                        //$categ_val->updateOrCreate($arrayval);
                                    }
                                }

                            }
                        }//
                    }
                }
                return $verificationId;
            }else{
                return 'no hay categorias cargadas';
            }
            	 			
        } catch (ClientException  $e) {
            return $e->getResponse()->getBody()->getContents();
        } 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function test()
    {

        $categoria = CategoriasMeli::where('id_meli_categoria','MLA109027')->get();
        $dataprodcateg = ProductosCategMeli::where('idproducto',42)->first();

        foreach ($categoria as $k) {
            if($dataprodcateg[$k->categoria]!=''){
                echo $k->id_meli.' '.$dataprodcateg[$k->categoria];
                echo '<br>';
            }
        }

    }
}
