<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', 860);

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Requests;
use App\AppCustom\Models\ProductosCategMeli;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\SyncMeli;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use DB;
/* use Maatwebsite\Excel\Concerns\WithMultipleSheets; */

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(Request $request)
    {
        Controller::__construct($request);
        /* $this->year = 2019; */
		
    }
    
    public function index()
    {
        //creo todas las combinaciones en la tabla inv_productos_categ_meli por primera vez
        $sync = SyncMeli::where('id',1)->first();
        $categorias = Util::categoriasMeli();         
       
        if($categorias){
            if($sync->first==0){            
            
                foreach ($categorias as $v) {
                    Util::guardarAtribCategorias($v->categoria_meli,$v->id,$v->nombre);
                }
    
                //actualizo el sync
                $sync = SyncMeli::where('id', 1)->update(['first'=> 1]);
    
                Excel::create('Listado de Categorias de MELI', function($excel){                
                    
                    $categorias = Util::categoriasMeliUnique();
    
                     foreach ($categorias as $v) {
    
                        $verificationId = Util::getNombreCategMeli($v->categoria_meli);
    
                        $excel->sheet($verificationId['name'], function($sheet) use ($v){

                           $categories = ProductosCategMeli::where('idcategoriameli',$v->categoria_meli)
                                        ->get();
                          
                           $sheet->fromArray($categories);

                        });  
    
                     } 
            
                })->export('xls');
    
            }else{
                 
                Excel::create('Listado de Categorias de MELI', function($excel){                
                    
                    $categorias = Util::categoriasMeliUnique();
    
                     foreach ($categorias as $v) { 
                       
                        $verificationId = Util::getNombreCategMeli($v->categoria_meli);
    
                        $excel->sheet($verificationId['name'], function($sheet) use ($v){
                           $categories = ProductosCategMeli::where('idcategoriameli',$v->categoria_meli)
                                        ->get();
                            
                                        /* $sheet->SetCellValue("A1", "id");
                                        $sheet->SetCellValue("A2", "S");
                                        $sheet->SetCellValue("A3", "M");
                                        $sheet->SetCellValue("A4", "L");
                                        $sheet->SetCellValue("A5", "XL");
                                        $sheet->SetCellValue("A6", "XXL");
                                        $sheet->SetCellValue("A7", "XXXL");
                                    
                                        $sheet->_parent->addNamedRange(
                                            new \PHPExcel_NamedRange(
                                            'id', $sheet, 'A3:A7'
                                            )
                                        );
                                    
                                        $objValidation = $sheet->getCell('D4')->getDataValidation();
                                        $objValidation->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                                        $objValidation->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                                        $objValidation->setAllowBlank(false);
                                        $objValidation->setShowInputMessage(true);
                                        $objValidation->setShowErrorMessage(true);
                                        $objValidation->setShowDropDown(true);
                                        $objValidation->setErrorTitle('Input error');
                                        $objValidation->setError('Value is not in list.');
                                        $objValidation->setPromptTitle('Pick from list');
                                        $objValidation->setPrompt('Please pick a value from the drop-down list.');
                                        $objValidation->setFormula1('id'); */
                                                                                
                           $sheet->fromArray($categories);
                        });  
    
                     } 
            
                })->export('xls');
                
                return response()->json(['msg'=>'exito']);
            }
        }else{
            return response()->json(['msg'=>'no se encontraron productos cargados en MELI']);
        }

        
    
    }


    public function atributosCategorias()
    {
        $categorias = Util::categoriasMeli();   
        $categoriasUnique = Util::categoriasMeliUnique();
        
        //creo las tablas por categorias    
        foreach ($categoriasUnique as $v) {
            $nombreCat = Util::getNombreCategMeli($v->categoria_meli);
            $nombreCat = Util::eliminar_espacios($nombreCat['name']);
            

            /* $sql = "CREATE TABLE "  .'meli_'.$nombreCat. " (
				`id` INT NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`id`)
			)
			COLLATE='latin1_swedish_ci'
			;";
			
			DB::statement($sql); */
        }

         

        //creo todas las combinaciones en la tabla inv_productos_categ_meli por primera vez
        $sync = SyncMeli::where('id',1)->first();
        
        
         
       
        if($categorias){
            if($sync->first==0){            
            
                foreach ($categorias as $v) {
                    Util::guardarAtribCategorias($v->categoria_meli,$v->id,$v->nombre);
                }
    
                //actualizo el sync
                $sync = SyncMeli::where('id', 1)->update(['first'=> 1]);
    
                Excel::create('Listado de Categorias de MELI', function($excel){                
                    
                    $categorias = Util::categoriasMeliUnique();
    
                     foreach ($categorias as $v) {
    
                        $verificationId = Util::getNombreCategMeli($v->categoria_meli);
    
                        $excel->sheet($verificationId['name'], function($sheet) use ($v){

                           $categories = ProductosCategMeli::where('idcategoriameli',$v->categoria_meli)
                                        ->get();
                           
                           $sheet->fromArray($categories);

                        });  
    
                     } 
            
                })->export('xls');
    
            }else{
                 
                /* Excel::create('Listado de Atributos de las Categorias MELI', function($excel){                
                    
                    $categorias = Util::categoriasMeliUnique();
    
                     foreach ($categorias as $v) { 
                       
                        $verificationId = Util::getNombreCategMeli($v->categoria_meli);
                        $heading = Util::categoriasHeading($v->categoria_meli);

                        foreach($heading as $h){
                            $heading = 
                        }
                          
                        $excel->sheet($verificationId['name'], function($sheet) use ($v,$heading){

                            foreach ($heading as $k) {
                                $data = Util::dataAtriCategorias($v->categoria_meli,$k->categoria);                       
                                $sheet->fromArray($data, null, 'A1', false, $heading);
                            }

                        });  
                        
                     } 
            
                })->export('xls'); */
                     Excel::create('Listado de Categorias de MELI', function($excel){                
                    
                        $categorias = Util::categoriasMeliUnique();

                        foreach ($categorias as $v) { 
                        
                            $heading = Util::categoriasHeading($v->categoria_meli);
    
                            foreach($heading as $h){
                                $data_array[$v->categoria_meli] = [];
                                $data[$h->categoria] = [];
    
                                array_push($data_array[$v->categoria_meli], $h->categoria);
    
                                $aux = Util::dataAtriCategorias($v->categoria_meli,$h->categoria); 
    
                                foreach ($aux as $a) {
                                    array_push($data, $a->name);
                                }
    
    
                            }
                        }
        
                         foreach ($categorias as $v) {                                                                                  

                            $verificationId = Util::getNombreCategMeli($v->categoria_meli);
        
                            $excel->sheet($verificationId['name'], function($sheet) use ($v,$data,$data_array){
                                $sheet->fromArray($data_array);
                            });  
        
                         } 
                
                    })->export('xls');

                   // \Log::info($data);
                
                return response()->json(['msg'=>'exito']);
            }
        }else{
            return response()->json(['msg'=>'no se encontraron productos cargados en MELI']);
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
}
