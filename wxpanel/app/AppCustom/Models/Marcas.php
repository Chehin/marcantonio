<?php
namespace App\AppCustom\Models;

class Marcas extends ModelCustomBase
{
	
    protected $table = 'conf_marcas';
    
    
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

	public static function getMenuItems(){
	    return Marcas::select('conf_marcas.id','conf_marcas.nombre','img.imagen','img.imagen_file','img.orden')
            ->leftJoin('img','conf_marcas.id','=','img.resource_id')
            ->where('img.resource','marcas')
            ->where('destacado',1)
            ->orderBy('conf_marcas.nombre','ASC')
            ->get();


/*	   return \DB::select('SELECT m.id,m.nombre,i.imagen,i.imagen_file,i.orden FROM conf_marcas m LEFT JOIN img i ON (m.id=i.resource_id)
            WHERE m.habilitado=1 AND m.destacado=1 AND (i.resource=\'marcas\' OR i.resource is null) AND (i.orden=0 OR i.orden is null) ORDER BY m.nombre ASC' );*/
    }
}