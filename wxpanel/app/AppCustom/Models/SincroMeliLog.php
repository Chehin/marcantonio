<?php
namespace App\AppCustom\Models;

use Carbon\Carbon;
use Exception;

class SincroMeliLog extends ModelCustomBase
{
	
    protected $table = 'sincro_meli_log';
    protected $primaryKey = 'id_sincro_meli';
    
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $guarded = [];
	

	/**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function alta($result,$id_producto)
    {
       // \Log::debug(print_r($result,true));
        $result = json_decode(json_encode($result));

       try {
           $sincroMeli = new SincroMeliLog();
           $sincroMeli->id_producto = $id_producto;
           $sincroMeli->estado = ($result->httpCode != 200) ? 'E' : 'O';
           $sincroMeli->mensaje = (isset($result->body->message))?$result->body->message:'-';
           $sincroMeli->error = (isset($result->body->error))?$result->body->error:'-';
           $sincroMeli->codigo_http = (isset($result->httpCode))?$result->httpCode:'-' ;
           $sincroMeli->codigo_estado = (isset($result->body->status))?$result->body->status:'-';
           $sincroMeli->save();

       }catch(Exception $e){
           \Log::debug('error al guardar el resultado de la sincronizacion: '.$e->getMessage());
       }

       if (isset($result->body->cause))
       {
           try {
               for ($i = 0; $i < count($result->body->cause); $i++) {

                   $detalleSincro = new DetalleSincroMeliLog();
                   $detalleSincro->id_sincro_meli = $sincroMeli->id_sincro_meli;
                   $detalleSincro->seccion = $result->body->cause[$i]->department;
                   $detalleSincro->id_causa = $result->body->cause[$i]->cause_id;
                   $detalleSincro->tipo = $result->body->cause[$i]->type;
                   $detalleSincro->codigo = $result->body->cause[$i]->code;
                   $detalleSincro->referencias = json_encode($result->body->cause[$i]->references);
                   $detalleSincro->mensaje = $result->body->cause[$i]->message;

                   $detalleSincro->save();
               }
           }catch(Exception $e){
                \Log::debug('error al guardar el detalle de la sincronizacion: '.$e->getMessage());
               }
       }

    }

    public static function borrarHistorial(){
        $date  = Carbon::now()->subDays( 30 );
        DetalleSincroMeliLog::where( 'created_at', '<=', $date )->delete();
        SincroMeliLog::where( 'created_at', '<=', $date )->delete();
    }
}