<?php
namespace App\AppCustom\Models;

class SyncFlexxus extends ModelCustomBase
{
	
    protected $table = 'sync_flexxus';
    
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
		'date_up',
		'last_start',
        'created_at',
        'updated_at',
    ];
	
}