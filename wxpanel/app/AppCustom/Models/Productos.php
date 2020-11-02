<?php
namespace App\AppCustom\Models;

use App\Http\Controllers\Fe\FeUtilController;

class Productos extends ModelCustomBase
{
	
    protected $table = 'inv_productos';

    protected static $etiquetasModel = 'App\AppCustom\Models\Etiquetas';
    protected static $coloresModel = 'App\AppCustom\Models\Colores';
    protected static $tallesModel = 'App\AppCustom\Models\Talles';
    protected static $deportesModel = 'App\AppCustom\Models\Deportes';
    
    
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

    /**
     * Returns the etiquetas relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function etiquetas()
    {
        return $this->belongsToMany(static::$etiquetasModel, 'inv_productos_etiquetas', 'id_producto', 'id_etiqueta')->withTimestamps()->select(array('id', 'nombre as text'));
    }
    /**
     * Returns the deportes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function deportes()
    {
        return $this->belongsToMany(static::$deportesModel, 'inv_productos_deportes', 'id_producto', 'id_deporte')->withTimestamps()->select(array('id', 'nombre as text'));
    }
    /**
     * Returns the colores relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function colores()
    {
        return $this->belongsToMany(static::$coloresModel, 'inv_producto_codigo_stock', 'id_producto', 'id_color','id_talle','stock','codigo')->withTimestamps()
		->select(array('inv_producto_codigo_stock.id', 'nombre as nombreColor','id_color','id_talle','stock','codigo','estado_meli'));
    }
    /**
     * Returns the colores relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function talles()
    {
        return $this->belongsToMany(static::$tallesModel, 'inv_producto_codigo_stock', 'id_producto', 'id_color','id_talle','stock','codigo')->withTimestamps()
		->select(array('nombre as nombreTalle','id_color','id_talle','stock','codigo','estado_meli'));
    }

    public static function GetMasVendidos(){
        return Productos::select('inv_productos.id','inv_productos.nombre',\DB::raw('count(pedidos_productos.id_producto) as Cantidad'))
            ->join('pedidos_productos','inv_productos.id','=','pedidos_productos.id_producto')
                ->where('inv_productos.habilitado',1)
                    ->groupBy('inv_productos.id')
                        ->orderBy('Cantidad', 'desc');
       // return \DB::select('SELECT id,pr.nombre ,COUNT(id_producto) Cantidad FROM pedidos_productos pe JOIN inv_productos pr ON (pe.id_producto=pr.id) WHERE pr.habilitado=1
      //  GROUP BY id ORDER BY Cantidad DESC limit :offset,:cantidad',['cantidad'=>$Cantidad,'offset'=>$Offset] );
    }
    public static function GetMasVistos(){
        return Productos::select('inv_productos.id','inv_productos.nombre','inv_productos.id_rubro','inv_productos.id_subrubro','inv_productos.id_subsubrubro')
	->join('inv_productos_estadisticas','inv_productos.id','=','inv_productos_estadisticas.id_producto')
            ->where('inv_productos.habilitado',1)
                ->orderBy('inv_productos_estadisticas.visitas', 'desc');
        //return \DB::select('select `inv_productos`.`id`, `inv_productos`.`nombre`, `inv_productos`.`id_rubro`, `inv_productos`.`id_subrubro`, `inv_productos`.`id_subsubrubro` from `inv_productos_estadisticas` inner join `inv_productos` on `inv_productos`.`id` = `inv_productos_estadisticas`.`id_producto`  order by `inv_productos_estadisticas`.`visitas` desc LIMIT 10;' );
    }
    public static function GetOfertas(){
        return Productos::select('inv_productos.id','inv_productos.nombre','inv_productos.id_rubro','inv_productos.id_subrubro','inv_productos.id_subsubrubro')
            ->where('inv_productos.habilitado',1)
            ->where('inv_productos.oferta',1);
    }
    public static function SetImagenPrecios($ListaProductos,$IdMoneda){
     $ArrayProductos=array();

     foreach ($ListaProductos as $Producto) {


         $Precio = FeUtilController::getPrecios($Producto->id, $IdMoneda);
         $Imagen = FeUtilController::getImages($Producto->id, 1, 'productos');
         $Stock = SucursalesStock::selectRaw('SUM(inv_producto_stock_sucursal.stock) as Stock')
            ->leftJoin('inv_producto_codigo_stock AS codigo','codigo.id','=','inv_producto_stock_sucursal.id_codigo_stock')
            ->leftJoin('inv_productos AS producto','producto.id','=','codigo.id_producto')
            ->leftJoin('editorial_notas','editorial_notas.id_nota', '=', 'inv_producto_stock_sucursal.id_sucursal' )
            ->where('editorial_notas.habilitado', 1)
             ->where('inv_producto_stock_sucursal.stock','>',0)
            ->where('codigo.id_producto', $Producto->id)
            ->groupBy('codigo.id_producto')
            ->first();


         $ArrayAux = array(
             'Id' => $Producto->id,
             'Producto' => $Producto->nombre,
              'Stock' => $Stock['Stock'],
             'PrecioVenta' => (isset($Precio->precio))?$Precio->precio:null,
             'PrecioLista' => (isset($Precio->precio_lista))?$Precio->precio_lista:null,
             'Descuento' => (isset($Precio->oferta))?$Precio->oferta:null,
             'Imagen' => (isset($Imagen[0]['imagen']))?$Imagen[0]['imagen']:'IMAGEN_NO_DIPONIBLE',
             'ArchivoImagen' => (isset($Imagen[0]['imagen_file']))?$Imagen[0]['imagen_file']:env('IMG_PROD_NO_DISP'),
         );
        // \Log::debug('Producto Id:'.$Producto->id);
         array_push($ArrayProductos, $ArrayAux);
     }
     return $ArrayProductos;
 }
}