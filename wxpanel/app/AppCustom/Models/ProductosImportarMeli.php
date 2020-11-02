<?php

namespace App\AppCustom\Models;

class ProductosImportarMeli extends ModelCustomBase
{
    protected $table = 'inv_productos_importar_meli';
    
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
        'deleted_at',
    ];
}