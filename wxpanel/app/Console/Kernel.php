<?php

namespace App\Console;
ini_set('max_execution_time', 1860);
ini_set('memory_limit', '-1'); 
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\AppCustom\Util;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\AppCustom\Models\Sync;
use App\AppCustom\Models\ProductosImportar;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\CampaignTesting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
		\App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {            
	            
        /* sincronizacion de fotos */		
		$aResult = Util::getDefaultArrayResult();	
		/**
		 * Funcion que muestra las imagenes que hay en la ruta pasada como parametro
		 */
			$ruta = '../fe/public/uploads/importador';
			// Se comprueba que realmente sea la ruta de un directorio
			if (is_dir($ruta)){
				// Abre un gestor de directorios para la ruta indicada
				$gestor = opendir($ruta);

				// Recorre todos los archivos del directorio
				while (($archivo = readdir($gestor)) !== false)  {
					// Solo buscamos archivos sin entrar en subdirectorios
					if (is_file($ruta."/".$archivo)) {
							
						$schedule->call(function () use($archivo) {
						//	\Log::info('antes de importar fotos');
							Util::importarFotos($archivo);												
						})->everyMinute();			
						
					}else{
					//	\Log::info('pasa');
						echo "No encuentra la ruta especificada";
					}            
				}

				//guardo fecha de sync
				$sync = new Sync();
				$sync->date_up = Carbon::now()->format('Y-m-d H:i:s');
				$sync->last_start = Carbon::now()->format('Y-m-d H:i:s');
				$sync->save();

				// Cierra el gestor de directorios
				closedir($gestor);
				//return response()->json($aResult);
			} else {
				echo "No es una ruta de directorio valida<br/>";
				return response()->json($aResult);
			}
		/* fin sinc fotos **/


    }
	

}
