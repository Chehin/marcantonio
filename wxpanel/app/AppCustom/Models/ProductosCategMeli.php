<?php

namespace App\AppCustom\Models;

class ProductosCategMeli extends ModelCustomBase
{
    protected $table = 'inv_productos_categ_meli';
    
    
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
