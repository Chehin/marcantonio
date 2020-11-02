<?php

namespace App\AppCustom\Models;

class CategoriasMeli extends ModelCustomBase
{
    protected $table = 'categorias_meli';
    
    
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    protected $guarded = [];
    
    public $timestamps = false;
}
