<?php

namespace App\AppCustom\Models;

class Note extends ModelCustomBase
{
    protected $table = 'editorial_notas';
    
    
    protected $primaryKey = 'id_nota';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['id_edicion', 'id_seccion', 'titulo', 'sumario','texto','categoria','antetitulo','ciudad','pais','keyword','_url','orden'];
    
    protected $guarded = [];
    
    public $timestamps = false;

    public static function GetNotas($seccion){

    return \DB::select('SELECT id_nota,n.id_seccion,seccion,n.titulo,n.texto,n.sumario FROM editorial_notas n JOIN editorial_secciones s ON (n.id_seccion=s.id_seccion)
WHERE n.habilitado=1 AND n.id_seccion=:seccion;',['seccion'=>$seccion] );

    }
}
