<?php

namespace App\AppCustom\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoCodigoFlexxus extends Model
{
    protected $table = 'tc_flexxus';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = [];
    //$guarded property should contain an array of attributes that you do not want to be mass assignable
    protected $guarded = [];
    
    public $timestamps = false;
}
