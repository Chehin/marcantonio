<?php

namespace App\Http\Controllers;

use App\AppCustom\Models\DetalleSincroMeliLog;
use App\AppCustom\Models\Pedidos;
use App\AppCustom\Models\PedidosNotificaciones;
use App\AppCustom\Models\Productos;
use App\AppCustom\Models\SincroMeliLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\AppCustom\Util;
use Illuminate\Pagination\Paginator;

class SincroMeliLogController extends Controller
{
    use ResourceTraitController;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct(Request $request)
    {

        parent::__construct($request);

        $this->resource = 'sincroMeliLog';
        $this->resourceLabel = 'Sincro Meli Log';
        $this->modelName = 'App\AppCustom\Models\SincroMeliLog';
        $this->viewPrefix = 'productos.';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();

        if ($this->user->hasAccess($this->resource . '.view')) {
            SincroMeliLog::borrarHistorial();

            $modelName = $this->modelName;

            $pageSize = $request->input('iDisplayLength', 10);
            $offset = $request->input('iDisplayStart');
            $currentPage = ($offset / $pageSize) + 1;

            if (!$sortCol = $request->input('mDataProp_'.$request->input('iSortCol_0'))) {
                $sortCol = 'id';
                $sortDir = 'desc';
            } else {

                $sortDir = $request->input('sSortDir_0');
            }

            //Search filter
            $search = \trim($request->input('sSearch'));

            $search1 = \trim($request->input('sSearch_1'));

            $search2 = \trim($request->input('sSearch_2'));

            $search3 = \trim($request->input('sSearch_3'));


            Paginator::currentPageResolver(function() use ($currentPage) {
                return $currentPage;
            });

            $items =    $modelName::selectRaw(
                'sincro_meli_log.id_sincro_meli,
                inv_productos.nombremeli,
                inv_productos.id_meli,
                sincro_meli_log.estado,
                sincro_meli_log.mensaje,
                sincro_meli_log.error,
                sincro_meli_log.codigo_http,
                sincro_meli_log.codigo_estado,
                DATE_FORMAT(sincro_meli_log.created_at, "%d/%m/%Y %H:%i") as fecha'
            )->join('inv_productos','sincro_meli_log.id_producto','=','inv_productos.id')
                ->orderBy('sincro_meli_log.created_at', $sortDir)
            ;

//\Log::debug(print_r($items->toSql(),true));
            if ($search) {
                $items->where(function($query) use ($search){
                    $query
                        ->where('inv_productos.nombremeli','like',"%{$search}%")
                        ->orWhere('inv_productos.id_meli','like',"%{$search}%")
                    ;
                });
            }

            //Filtros fechas
            $fecha1 = $fecha2 = null;
            if ($search1) {
                $fecha1 = Carbon::createFromFormat('d/m/Y', $search1);
            }

            if ($search2) {
                $fecha2 = Carbon::createFromFormat('d/m/Y', $search2);
            }

            if ($fecha1 && $fecha2) {
                if ($fecha1->gt($fecha2)) {
                    $aResult['status'] = 1;
                    $aResult['msg'] = "El rango de Fecha es inválido";

                    return response()->json($aResult);
                }
            }

            if ($search1) {
                $items->where(function($query) use ($fecha1){
                    $query
                        ->where('sincro_meli_log.created_at', '>=', $fecha1->format('Y-m-d')  . ' 00:00')
                    ;
                });
            }

            if ($search2) {
                $items->where(function($query) use ($fecha2){
                    $query
                        ->where('sincro_meli_log.created_at', '<=', $fecha2->format('Y-m-d') . ' 23:59')
                    ;
                });
            }

            if ($search3) {
                $items->where(function($query) use ($search3){
                    $query
                        ->where('sincro_meli_log.estado',$search3)
                    ;
                });
            }

            $items = $items->paginate($pageSize)
            ;

            $aItems = $items->toArray();

           // \Log::debug(print_r($aItems,true));
            $total = $aItems['total'];
            $aItems = $aItems['data'];



            $aResult['data'] = $aItems;
            $aResult['recordsTotal'] = $total;
            $aResult['recordsFiltered'] = $total;

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.unauthorized');
        }

        return response()->json($aResult);
    }
    public function detalles($id){

        $aResult = Util::getDefaultArrayResult();

        $logSincro = SincroMeliLog::where('id_sincro_meli', $id)->first();

        if ($logSincro) {

            $detalles = DetalleSincroMeliLog::where('id_sincro_meli', $logSincro->id_sincro_meli)
                ->orderBy('created_at', 'desc')
                ->get();
            $producto = Productos::find($logSincro->id_producto);
            if (isset($detalles[0])) {
                $aViewData = array(
                    'mode'  => 'edit',
                    'detalles' => $detalles,
                    'producto' => $producto,
                    'aItem' => $logSincro,
                    'resource' => $this->resource,
                    'resourceLabel' => $this->resourceLabel
                );

            $aResult['html'] = \View::make($this->viewPrefix . $this->resource . "." . $this->resource . "Detalles")
                ->with('aViewData', $aViewData)
                ->render();
            } else {
                $aResult['status'] = 1;
                $aResult['msg'] = 'No hay detalles para mostrar.';
            }

        } else {
            $aResult['status'] = 1;
            $aResult['msg'] = \config('appCustom.messages.itemNotFound');
        }

        return response()->json($aResult);
    }


}
