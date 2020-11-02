<?php

namespace App\AppCustom\Models;

class SyncMeli extends ModelCustomBase
{
    protected $table = 'syncmeli';
    
    
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
