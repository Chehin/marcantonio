<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 860);

use Log;
use App\AppCustom\Util;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Models\Sync;
use App\AppCustom\Models\Image;
use App\AppCustom\Models\CodigoStock;

class FotosSyncController extends Controller
{
	const minsToDetectSyncNok = 90;
	const syncModel = 'App\AppCustom\Models\Sync';

	public function sync() {	
		
		$aResult = Util::getDefaultArrayResult();	
		/**
		 * Funcion que muestra las imagenes que hay en la ruta pasada como parametro
		 */
		$ruta = \config('appCustom.UPLOADS_FOTOS');
			// Se comprueba que realmente sea la ruta de un directorio
			if (is_dir($ruta)){
				// Abre un gestor de directorios para la ruta indicada
				$gestor = opendir($ruta);

				// Recorre todos los archivos del directorio
				while (($archivo = readdir($gestor)) !== false)  {
					
					// Solo buscamos archivos sin entrar en subdirectorios
					if (is_file($ruta."/".$archivo)) {

						$codigo = explode("-", $archivo);	
						$nombre = explode(".", $archivo);		
						
						/* \Log::info('codigo');
						\Log::info(print_r($codigo[0],true)); */
						$prod = CodigoStock::select('id_producto','id_color')->where('codigo','like',$codigo[0].'%')->first();
						
						$fileName = \time();
						$fileName .= '_' . \base64_encode($archivo);
						$fileName .= '.jpg';
					
						//guardo los datos en img
						if($prod){
							//configuro el orden segun la letra de la foto
							$letra = explode(".", $codigo[1]);
							switch ($letra[0]) {
								case 'A':
									$orden=1;
									break;

								case 'B':
									$orden=2;
									break;

								case 'C':
									$orden=3;
									break;

								case 'D':
									$orden=4;
									break;
								
								case 'E':
									$orden=5;
									break;
								
								case 'F':
									$orden=6;
									break;
								
								default:
									$orden=7;
									break;
							}

							$img = Image::where('resource_id',$prod->id_producto)->where('imagen',$nombre[0])->where('resource','productos')->first();
													
							if($img){

									$img_update = Image::where('resource_id',$prod->id_producto)
											->where('imagen',$nombre[0])
											->where('resource','productos')
											->update(['resource'=>'productos',
													'resource_id'=>$prod->id_producto,
													'imagen'=>$nombre[0],
													'imagen_file'=>$img->imagen_file,
													'orden' => $orden,
													'id_color'=>$prod->id_color,
													'habilitado' =>1]);

													if(!file_exists(\config('appCustom.UPLOADS_BE'). 'productos/' . $img->imagen_file) || !file_exists(\config('appCustom.UPLOADS_BE'). 'productos/' .'app_'.$img->imagen_file) || !file_exists(\config('appCustom.UPLOADS_BE'). 'productos/' .'300_'.$img->imagen_file)){
														$im = file_get_contents(\config('appCustom.UPLOADS_FOTOS').$archivo);
														$imdata = 'data:image/png;base64,'.base64_encode($im);
														
														$redim=Util::uploadBase64File1(
															\config('appCustom.UPLOADS_BE'). 'productos/',
															$img->imagen_file, 
															$imdata,
															0.5
														);
													}
																		
							}else{
								//redimensiono
								$im = file_get_contents(\config('appCustom.UPLOADS_FOTOS').$archivo);
								$imdata = 'data:image/png;base64,'.base64_encode($im);

								$redim=Util::uploadBase64File1(
									\config('appCustom.UPLOADS_BE'). 'productos/' ,
									$fileName, 
									$imdata,
									0.5
								)
								;	
							
								if($redim){
									//copio lo de la carpeta sync en uploads con otro nombre
									$rutaSync=\config('appCustom.UPLOADS_FOTOS').$archivo;
									$rutaUploads=\config('appCustom.UPLOADS_BE').$fileName;

									if (file_exists($rutaSync)){
										copy($rutaSync, $rutaUploads);
									}

									$img = new Image();
									$img->resource='productos';
									$img->resource_id=$prod->id_producto;
									$img->imagen=$nombre[0];
									$img->imagen_file=$fileName;
									$img->orden=$orden;
									$img->id_color=$prod->id_color;
									$img->habilitado = 1;
									$img->save();	
								}else{
									//le pongo un fondo blanco de 800px x 800px
									//cambio los valores
									$posiX = 1;
									$posiY = 1;


									//Se define el maximo ancho y alto que tendra la imagen final
									$max_ancho = 650;
									$max_alto = 800;


									$img_original = \config('appCustom.UPLOADS_BE').'base.jpg';
									$marcadeagua = \config('appCustom.UPLOADS_FOTOS').$archivo;

									
										//Crear el destino (fondo)
										if (preg_match("/\.jpe?g$/i", $img_original)) { //simplifiqué el regex
											$imgm = imagecreatefromjpeg($img_original);
										}

										if (preg_match("/\.jpe?g$/i", $marcadeagua)) { //simplifiqué el regex
											$marcadeagua = imagecreatefromjpeg($marcadeagua);
										}
										elseif (preg_match("/\.png$/i", $marcadeagua)) {
											$marcadeagua = imagecreatefrompng($marcadeagua);
										}
										
										//tomar el origen (logo)
										

										//redimensiono
										$width=ImageSx($marcadeagua);              // Original picture width is stored
										$height=ImageSy($marcadeagua);

										//Se calcula ancho y alto de la imagen final
										$x_ratio = $max_ancho / $width;
										$y_ratio = $max_alto / $height;
										
										
										//Si el ancho y el alto de la imagen no superan los maximos,
										//ancho final y alto final son los que tiene actualmente
										if( ($width <= $max_ancho) && ($height <= $max_alto) ){//Si ancho
											$x_ratio = $max_ancho / $width;
											$y_ratio = $max_alto / $height;
										}
										
		
										//img
										$a_width = $width * $x_ratio;
										$a_height = $height * $y_ratio;

										$newimage_a=imagecreatetruecolor($a_width,$a_height);                 
										imageCopyResized($newimage_a,$marcadeagua,0,0,0,0,$a_width,$a_height,$width,$height);
																			


										//las posiciones en donde ubicar - se reciben por POST (hardcoddeadas en este ejemplo)
										$xmarcaagua = $posiX;
										$ymarcaagua = $posiY;
										//se obtiene el ancho y el largo del logo
										$ximagen = imagesx($newimage_a);
										$yimagen = imagesy($newimage_a);

										//COPIAR (observar las variables que se usan)
										imagecopy($imgm,$newimage_a, 
												$xmarcaagua, $ymarcaagua,
												0, 0,
												$ximagen, $yimagen);


										//Generar el archivo
										imagejpeg($imgm, \config('appCustom.UPLOADS_FOTOS').$archivo);

										//cuando termine de dejar las imagenes proporcionales de 800x800 lo vuelvo a porcesar para th y app
										/* \Log::info(\config('appCustom.UPLOADS_FOTOS').$archivo);
										\Log::info($fileName); */
										$im = file_get_contents(\config('appCustom.UPLOADS_FOTOS').$archivo);
										$imdata = 'data:image/png;base64,'.base64_encode($im);
									/* 	\Log::info($imdata); */
										$redim=Util::uploadBase64File1(
											\config('appCustom.UPLOADS_BE'),
											$fileName, 
											$imdata,
											0.5
										)
										;

										
										imagedestroy( $imgm );

										

										$img = new Image();
										$img->resource='productos';
										$img->resource_id=$prod->id_producto;
										$img->imagen=$nombre[0];
										$img->imagen_file=$fileName;
										$img->orden=$orden;
										$img->id_color=$prod->id_color;
										$img->habilitado = 1;
										$img->save();										
								}
							}						
						}				
						
					}            
				}

				//guardo fecha de sync
				$sync = new Sync();
				$sync->date_up = Carbon::now()->format('Y-m-d H:i:s');
				$sync->last_start = Carbon::now()->format('Y-m-d H:i:s');
				$sync->save();

				// Cierra el gestor de directorios
				closedir($gestor);
				return response()->json($aResult);
			} else {
				echo "No es una ruta de directorio valida<br/>";
				return response()->json($aResult);
			}
		

	}

	protected function getLastSync() {
		
		$syncModel = static::syncModel;
		
		$this->update = $syncModel::orderBy('id', 'desc')->first();
		
		return $this->update;
	}
	
	public function getLastSyncStatus() {
		
		$lastSync = $this->getLastSync();
		
		if ($lastSync) {
			if (!$lastSync->done) {
				if ($lastSync->last_start->diffInMinutes(Carbon::now()) >= static::minsToDetectSyncNok) {
					$lastSync->lastSyncOk = 0;
				} else {
					$lastSync->lastSyncOk = 1;
				}
			}
		}
		
		
		return response()->json($lastSync);
	}
    
}
