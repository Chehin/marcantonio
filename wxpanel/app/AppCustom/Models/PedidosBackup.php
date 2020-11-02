<?php
namespace App\AppCustom\Models;

class PedidosBackup extends ModelCustomBase
{
	
    protected $table = 'pedidos_pedidos_backup';
	protected $primaryKey = 'id_pedido';
    
    
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