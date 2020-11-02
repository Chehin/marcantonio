<?php

namespace App\AppCustom\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoFlexxus extends Model
{
    protected $table = 'tc_stock_producto';
    
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
