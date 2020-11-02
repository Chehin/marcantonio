<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
	
	const RESOURCE = 'permission';
	const RESOURCE_LABEL = 'Permisos';
	
	
	static $aAllPermissions = [
		'dash' => [
			'label' => 'Dash. Por Mes',
			'aPermissions' => [
				'dash.create' => '',
				'dash.update' => '',
				'dash.view' => '',
				'dash.delete' => '',
				
			]
		],
		'dash2' => [
			'label' => 'Dash. Por Producto',
			'aPermissions' => [
				'dash2.create' => '',
				'dash2.update' => '',
				'dash2.view' => '',
				'dash2.delete' => '',
				
			]
		],
		'dash3' => [
			'label' => 'Dash. Por Rubro',
			'aPermissions' => [
				'dash3.create' => '',
				'dash3.update' => '',
				'dash3.view' => '',
				'dash3.delete' => '',
				
			]
		],
		'user' => [
			'label' => 'Usuarios',
			'aPermissions' => [
				'user.create' => 'Usuarios: Crear Usuarios',
				'user.update' => 'Usuarios: Modificar datos',
				'user.view' => 'Usuarios: Ver listado de usuarios y detalle',
				'user.delete' => 'Usuarios: Borrar usuarios',
				
			]
		],
		'role' => [
			'label' => 'Perfiles',
			'aPermissions' => [
				'role.create' => 'Perfiles: Crear',
				'role.update' => 'Perfiles: Modificar',
				'role.view' => 'Perfiles: Ver',
				'role.delete' => 'Perfiles: Borrar',
				
			]
		],
		'rubros' => [
			'label' => 'Rubros',
			'aPermissions' => [
				'rubros.create' => 'Rubros: Crear',
				'rubros.update' => 'Rubros: Modificar',
				'rubros.view' => 'Rubros: Ver',
				'rubros.delete' => 'Rubros: Borrar',
				
			]
		],
		'subRubros' => [
			'label' => 'Sub Rubros',
			'aPermissions' => [
				'subRubros.create' => 'Sub Rubros: Crear',
				'subRubros.update' => 'Sub Rubros: Modificar',
				'subRubros.view' => 'Sub Rubros: Ver',
				'subRubros.delete' => 'Sub Rubros: Borrar',
				
			]
		],
		/*'subsubRubros' => [
			'label' => 'Sub Sub Rubros',
			'aPermissions' => [
				'subsubRubros.create' => 'Sub Sub Rubros: Crear',
				'subsubRubros.update' => 'Sub Sub Rubros: Modificar',
				'subsubRubros.view' => 'Sub Sub Rubros: Ver',
				'subsubRubros.delete' => 'Sub Sub Rubros: Borrar',
				
			]
		],*/
		'deportes' => [
			'label' => 'Deportes',
			'aPermissions' => [
				'deportes.create' => 'Deportes: Crear',
				'deportes.update' => 'Deportes: Modificar',
				'deportes.view' => 'Deportes: Ver',
				'deportes.delete' => 'Deportes: Borrar',
				
			]
		],
		'etiquetas' => [
			'label' => 'Etiquetas',
			'aPermissions' => [
				'etiquetas.create' => 'Etiquetas: Crear',
				'etiquetas.update' => 'Etiquetas: Modificar',
				'etiquetas.view' => 'Etiquetas: Ver',
				'etiquetas.delete' => 'Etiquetas: Borrar',
				
			]
		],
		'productos' => [
			'label' => 'Productos',
			'aPermissions' => [
				'productos.create' => 'Productos: Crear',
				'productos.update' => 'Productos: Modificar',
				'productos.view' => 'Productos: Ver',
				'productos.delete' => 'Productos: Borrar',
				
			]
		],
		'importarProductos' => [
			'label' => 'Importar Productos',
			'aPermissions' => [
				'importarProductos.create' => 'Importar Productos: Crear',
				'importarProductos.view' => 'Importar Productos: Ver listado y detalle',
				
			]
		],
		'importarProductosMeli' => [
			'label' => 'Importar Productos',
			'aPermissions' => [
				'importarProductosMeli.create' => 'Importar Productos Meli: Crear',
				'importarProductosMeli.view' => 'Importar Productos Meli: Ver listado y detalle',
				
			]
		],

	        'sincroMeliLog' => [
	            'label' => 'Sincronizacion MELI Log',
	            'aPermissions' => [
	                'sincroMeliLog.view' => 'Ver registro de sincronizacion de productos con MELI',

	            ]
	        ],

		'marcas' => [
			'label' => 'Marcas',
			'aPermissions' => [
				'marcas.create' => 'Marcas: Crear',
				'marcas.update' => 'Marcas: Modificar',
				'marcas.view' => 'Marcas: Ver',
				'marcas.delete' => 'Marcas: Borrar',
				
			]
		],
		'exportar' => [
			'label' => 'Exportar MELI',
			'aPermissions' => [
				'exportar.create' => 'export: Crear',
				'exportar.update' => 'export: Modificar',
				'exportar.view' => 'export: Ver',
				'exportar.delete' => 'export: Borrar',
				
			]
		],
		'colores' => [
			'label' => 'Colores',
			'aPermissions' => [
				'colores.create' => 'Colores: Crear',
				'colores.update' => 'Colores: Modificar',
				'colores.view' => 'Colores: Ver',
				'colores.delete' => 'Colores: Borrar',
				
			]
		],
		'talles' => [
			'label' => 'Talles',
			'aPermissions' => [
				'talles.create' => 'Talles: Crear',
				'talles.update' => 'Talles: Modificar',
				'talles.view' => 'Talles: Ver',
				'talles.delete' => 'Talles: Borrar',
				
			]
		],
		'monedas' => [
			'label' => 'Monedas',
			'aPermissions' => [
				'monedas.create' => 'Monedas: Crear',
				'monedas.update' => 'Monedas: Modificar',
				'monedas.view' => 'Monedas: Ver',
				'monedas.delete' => 'Monedas: Borrar',
				
			]
		],
		'general' => [
			'label' => 'General',
			'aPermissions' => [
				'general.update' => 'General: Modificar',
				'general.view' => 'General: Ver',
			]
		],
		'banners' => [
			'label' => 'Banners',
			'aPermissions' => [
				'banners.create' => 'Banners: Crear',
				'banners.update' => 'Banners: Modificar',
				'banners.view' => 'Banners: Ver',
				'banners.delete' => 'Banners: Borrar',
				
			]
		],
		'bannersClientes' => [
			'label' => 'Banners Clientes',
			'aPermissions' => [
				'bannersClientes.create' => 'Banners Clientes: Crear',
				'bannersClientes.update' => 'Banners Clientes: Modificar',
				'bannersClientes.view' => 'Banners Clientes: Ver',
				'bannersClientes.delete' => 'Banners Clientes: Borrar',
				
			]
		],
		'bannersPosiciones' => [
			'label' => 'Banners Posiciones',
			'aPermissions' => [
				'bannersPosiciones.create' => 'Banners Posiciones: Crear',
				'bannersPosiciones.update' => 'Banners Posiciones: Modificar',
				'bannersPosiciones.view' => 'Banners Posiciones: Ver',
				'bannersPosiciones.delete' => 'Banners Posiciones: Borrar',
				
			]
		],
		'bannersTipos' => [
			'label' => 'Tipos de Banners',
			'aPermissions' => [
				'bannersTipos.create' => 'Tipos de Banners: Crear',
				'bannersTipos.update' => 'Tipos de Banners: Modificar',
				'bannersTipos.view' => 'Tipos de Banners: Ver',
				'bannersTipos.delete' => 'Tipos de Banners: Borrar',
				
			]
		],
		'pedidos3' => [
			'label' => 'Pedidos. A Acordar',
			'aPermissions' => [
				'pedidos3.view' => 'Pedidos: Ver',
				
			]
		],
		'pedidos2' => [
			'label' => 'Pedidos. En Carrito',
			'aPermissions' => [
				'pedidos2.view' => 'Pedidos: Ver',
				
			]
		],
		'pedidos1' => [
			'label' => 'Pedidos. A Gestionar',
			'aPermissions' => [
				'pedidos1.view' => 'Pedidos: Ver',
				'pedidos1.update' => 'Pedidos: Modificar',
				
			]
		],
		'pedidos' => [
			'label' => 'Pedidos. Todos',
			'aPermissions' => [
				'pedidos.create' => 'Pedidos: Crear',
				'pedidos.update' => 'Pedidos: Modificar',
				'pedidos.view' => 'Pedidos: Ver',
				'pedidos.delete' => 'Pedidos: Borrar',
				
			]
		],


		'pedidosMeli' => [
			'label' => 'Pedidos. Meli',
			'aPermissions' => [
				/* 'pedidos.create' => 'Pedidos: Crear', */
				'pedidosMeli.update' => 'Pedidos: Modificar',
				'pedidosMeli.view' => 'Pedidos: Ver',
				'pedidosMeli.delete' => 'Pedidos: Borrar',
				
			]
		],

		'pedidosBackup' => [
			'label' => 'Pedidos. 2014 - 2020',
			'aPermissions' => [
				/* 'pedidos.create' => 'Pedidos: Crear', */
				'pedidosBackup.update' => 'Pedidos: Modificar',
				'pedidosBackup.view' => 'Pedidos: Ver',
				'pedidosBackup.delete' => 'Pedidos: Borrar',
				
			]
		],

		'pedidosClientes' => [
			'label' => 'Pedidos Clientes',
			'aPermissions' => [
				'pedidosClientes.create' => 'Pedidos Clientes: Crear',
				'pedidosClientes.update' => 'Pedidos Clientes: Modificar',
				'pedidosClientes.view' => 'Pedidos Clientes: Ver',
				'pedidosClientes.delete' => 'Pedidos Clientes: Borrar',
				
			]
		],
		'news' => [
			'label' => 'Notas',
			'aPermissions' => [
				'news.create' => 'Notas: Crear Notas',
				'news.update' => 'Notas: Modificar datos',
				'news.view' => 'Notas: Ver listado de Notas y detalle',
				'news.delete' => 'Notas: Borrar Notas',
				
			]
		],
		'slider' => [
			'label' => 'Notas Slider',
			'aPermissions' => [
				'slider.create' => 'Notas Slider: Crear Slider',
				'slider.update' => 'Notas Slider: Modificar datos',
				'slider.view' => 'Notas Slider: Ver listado de Slider y detalle',
				'slider.delete' => 'Notas Slider: Borrar Slider',
				
			]
		],
		'sucursales' => [
			'label' => 'Sucursales',
			'aPermissions' => [
				'sucursales.create' => 'Sucursalesr: Crear Sucursales',
				'sucursales.update' => 'Sucursales: Modificar datos',
				'sucursales.view' => 'Sucursales: Ver listado de Sucursales y detalle',
				'sucursales.delete' => 'Sucursales: Borrar Sucursales',
				
			]
		],
		'newsletter' => [
            'label' => 'Newsletter',
            'aPermissions' => [
                'newsletter.create' => 'Newsletter: Crear Newsletter',
                'newsletter.update' => 'Newsletter: Modificar datos',
                'newsletter.view' => 'Newsletter: Ver listado de Newsletter y detalle',
                'newsletter.delete' => 'Newsletter: Borrar Newsletter',
                
            ]
        ],
	];
	
	protected $type;



	public function __construct() {
		parent::__construct();
		$this->type = \config('appCustom.roleType.panel');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
	$aResult = Util::getDefaultArrayResult();
        
        $item = \Sentinel::findRoleById($id);
        
        if ($item) {

            $aViewData = array(
                'mode'  => 'edit',
                'item' => $item,
				'aPermissions' => static::$aAllPermissions,
				'resourceLabel' => static::RESOURCE_LABEL,
				'resource' => static::RESOURCE,
            );
			
			$viewModule = \config('appCustom.roleType.panel') == $this->type ? 'user' : 'userPc'; 

            $aResult['html'] = \View::make($viewModule . '.' . static::RESOURCE."Edit")
                ->with('aViewData', $aViewData)
                ->render()
            ;
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
		$aResult = Util::getDefaultArrayResult();
		
		$roleResource = \config('appCustom.roleType.panel') == $this->type ? 'role' : 'rolePc'; 
        
        if (\Sentinel::hasAccess($roleResource  . '.update')) {
        
            $item = \Sentinel::findRoleById($id);

            if ($item) {
                $aPerms = $request->input('aPerms', []);
				
		$aPermsAux = [];
		array_walk($aPerms, function($value, $key) use (&$aPermsAux){
			$aPermsAux[$value] = true;
		});

		$item->permissions = $aPermsAux;

		$item->save();

            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = \config('appCustom.messages.itemNotFound');
            } 
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }
		
		return response()->json($aResult);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aResult = Util::getDefaultArrayResult();
        
        if ($role = \Sentinel::findRoleById($id)) {
			
			$roleResource = \config('appCustom.roleType.panel') == $this->type ? 'role' : 'rolePc'; 
			
			if (\Sentinel::hasAccess($roleResource  . '.delete')) { 
				
				if (!($role->users()->with('roles')->get()->count() > 0)) {
					$role->delete();
				} else {
					$aResult['status'] = 1;
					$aResult['msg'] = 'El rol tiene usuarios asignados';
				}
				 
			} else {
				$aResult['status'] = 1;
				$aResult['msg'] = \config('appCustom.messages.unauthorized');
			}
            
        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
    }
}
