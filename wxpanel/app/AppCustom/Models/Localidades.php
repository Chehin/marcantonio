<?php
namespace App\AppCustom\Models;


class Localidades extends ModelCustomBase
{
	
    protected $table = 'localidad';
    
    public $timestamps = false;
    
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
	   
}