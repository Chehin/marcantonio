<?php

namespace App\AppCustom\Models;

class CategoriasMeliValues extends ModelCustomBase
{
    protected $table = 'categorias_meli_values';
    
    
    protected $primaryKey = 'id_cat_val';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    protected $guarded = [];
    
    public $timestamps = false;
}
