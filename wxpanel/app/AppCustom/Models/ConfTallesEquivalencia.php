<?php
namespace App\AppCustom\Models;

class ConfTallesEquivalencia extends ModelCustomBase
{
	
    protected $table = 'conf_talles_equivalencias';
    
    
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
	
}