<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\AppCustom\Util;
use App\Http\Controllers\User\UserUtilController;
use Illuminate\Http\Request;

Route::get('loginMeli', 'MeliController@login');

Route::get('login', ['middleware' => ['csrf'], 'as' => 'login', 'uses' => 'HomeController@showLogin']);
Route::post('login', ['middleware' => ['csrf'],'as' => 'login', 'uses' => 'HomeController@doLogin']);

Route::get('passwordForgot', ['middleware' => ['csrf'], 'as' => 'passwordForgot', 'uses' => 'HomeController@showPasswordForgot']);
Route::get('passwordChange/{token}', ['middleware' => ['csrf'], 'as' => 'passwordChange', 'uses' => 'HomeController@showPasswordChange']);

Route::post('passwordForgot', ['middleware' => ['csrf'], 'uses' => 'HomeController@passwordForgotMail']);
Route::post('passwordChange', ['middleware' => ['csrf'], 'uses' => 'HomeController@passwordForgotChange']);

Route::get('mail', function(){
	return View::make('email.passwordForgot');
});


Route::get('logout',function(){

	//Blank REST API token from DB
	UserUtilController::clearApiTokenCurrentUser();

	Sentinel::logout();

	//Remove REST API cookie
	return 
		Response::make(
				View::make('login')
			)
			->withCookie(
				Cookie::forget(\config('appCustom.cookieRestApiWeb'))
			)
		;

});

Route::group(['middleware' => ['web','auth.custom','csrf']], function(){
	Route::get('/', function() {
		//default index
		$response = Response::make(View::make('blank'));
		
		if (!Cookie::get(\config('appCustom.cookieRestApiWeb'))) {

			$response->withCookie(
				\config('appCustom.cookieRestApiWeb'), 
				(Sentinel::getUser()->email . ':' . Sentinel::getUser()->api_token)
			)
			;
		}

		return $response;
	});
	
	//User
	//Main View
	Route::get('user', ['as' => 'user', 'uses' => 'User\UserUtilController@showMainView']);
	
	//User Image
	Route::get('userImg/imageMain', ['as' => 'user/image', 'uses' => 'UserUtilController@showMainViewImage']);
	//Role Module
	Route::get('role', ['as' => 'role', 'uses' => 'User\RoleUtilController@showMainView']);		
	
	
	//News
	//Main View
	Route::get('news', ['as' => 'news', 'uses' => 'NewsUtilController@showMainView']);
	//Main View Image
	Route::get('news/imageMain/{id}', ['as' => 'news/image', 'uses' => 'NewsUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('news/noteRelatedMain/{id}', ['as' => 'news/noteRelated', 'uses' => 'NewsUtilController@showMainViewNoteRelated']);
	
	//Slider
	//Main View
	Route::get('slider', ['as' => 'news/slider', 'uses' => 'SliderUtilController@showMainView']);
	//Main View Image
	Route::get('slider/imageMain/{id}', ['as' => 'slider/image', 'uses' => 'SliderUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('slider/noteRelatedMain/{id}', ['as' => 'slider/noteRelated', 'uses' => 'SliderUtilController@showMainViewNoteRelated']);	
	
	//Newsletter
	//Main View
	Route::get('newsletter', ['as' => 'newsletter', 'uses' => 'NewsletterUtilController@showMainView']);
	//Main View Image
	Route::get('newsletter/imageMain/{id}', ['as' => 'newsletter/image', 'uses' => 'NewsletterUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('newsletter/noteRelatedMain/{id}', ['as' => 'newsletter/noteRelated', 'uses' => 'NewsletterUtilController@showMainViewNoteRelated']);

				
	Route::get('rubros', ['as' => 'productos/rubros', 'uses' => 'RubrosUtilController@showMainView']);
	Route::get('rubros/imageMain/{id}', ['as' => 'rubros/image', 'uses' => 'RubrosUtilController@showMainViewImage']);	

	Route::get('subRubros', ['as' => 'productos/subRubros', 'uses' => 'SubRubrosUtilController@showMainView']);
	Route::get('subsubRubros', ['as' => 'productos/subsubRubros', 'uses' => 'SubSubRubrosUtilController@showMainView']);

	Route::get('etiquetas', ['as' => 'productos/etiquetas', 'uses' => 'EtiquetasUtilController@showMainView']);
	Route::get('etiquetas/imageMain/{id}', ['as' => 'etiquetas/image', 'uses' => 'EtiquetasUtilController@showMainViewImage']);	

	Route::get('deportes', ['as' => 'productos/deportes', 'uses' => 'DeportesUtilController@showMainView']);

	Route::get('productos', ['as' => 'productos/productos', 'uses' => 'ProductosUtilController@showMainView']);
	Route::get('productos/imageMain/{id}', ['as' => 'productos/image', 'uses' => 'ProductosUtilController@showMainViewImage']);
	
	Route::get('productos/imageSliderMain/{id}', ['as' => 'productos/image', 'uses' => 'ProductosUtilController@showMainViewImage']);
	
	Route::get('productos/preciosRelatedMain/{id}', ['as' => 'productos/preciosRelated', 'uses' => 'ProductosUtilController@showMainViewPreciosRelated']);
	Route::get('productos/productosRelatedMain/{id}', ['as' => 'productos/productosRelated', 'uses' => 'ProductosUtilController@showMainViewProductosRelated']);
	Route::get('productos/preguntasRelatedMain/{id}', ['as' => 'productos/preguntasRelated', 'uses' => 'ProductosUtilController@showMainViewPreguntasRelated']);

    Route::get('sincroMeliLog', ['as' => 'productos/sincroMeliLog', 'uses' => 'SincroMeliLogUtilController@showMainView']);
	//ImportarProductos
	Route::get('importarProductos', ['as' => 'productos/importarProductos', 'uses' => 'ImportarProductosUtilController@showMainView']);
	Route::post('importarProductos', ['as' => 'productos/importarProductosProcesar', 'uses' => 'ImportarProductosController@procesar']);

	//ImportarProductosMeli
	Route::get('importarProductosMeli', ['as' => 'productos/importarProductosMeli', 'uses' => 'ImportarProductosMeliUtilController@showMainView']);
	Route::post('importarProductosMeli', ['as' => 'productos/importarProductosMeliProcesar', 'uses' => 'ImportarProductosMeliController@procesar']);

	Route::get('marcas', ['as' => 'configuracion/marcas', 'uses' => 'MarcasUtilController@showMainView']);
	Route::get('marcas/imageMain/{id}', ['as' => 'marcas/image', 'uses' => 'MarcasUtilController@showMainViewImage']);

	Route::get('colores', ['as' => 'configuracion/colores', 'uses' => 'ColoresUtilController@showMainView']);
	//Main View Image
	Route::get('colores/imageMain/{id}', ['as' => 'colores/image', 'uses' => 'ColoresUtilController@showMainViewImage']);

	Route::get('talles', ['as' => 'configuracion/talles', 'uses' => 'TallesUtilController@showMainView']);

	Route::get('monedas', ['as' => 'configuracion/monedas', 'uses' => 'MonedasUtilController@showMainView']);

	Route::get('general', ['as' => 'configuracion/general', 'uses' => 'ConfGeneralUtilController@showMainView']);

	Route::get('banners', ['as' => 'banners/banners', 'uses' => 'BannersUtilController@showMainView']);
	Route::get('bannersClientes', ['as' => 'banners/bannersClientes', 'uses' => 'BannersClientesUtilController@showMainView']);
	Route::get('bannersPosiciones', ['as' => 'banners/bannersPosiciones', 'uses' => 'BannersPosicionesUtilController@showMainView']);
	Route::get('bannersTipos', ['as' => 'banners/bannersTipos', 'uses' => 'BannersTiposUtilController@showMainView']);
	
	Route::get('pedidosMeli', ['as' => 'pedidosMeli/pedidosMeli', 'uses' => 'PedidosMeliUtilController@showMainView']);
	Route::get('pedidosBackup', ['as' => 'pedidosBackup/pedidosBackup', 'uses' => 'PedidosBackupUtilController@showMainView']);
	
	Route::get('pedidos', ['as' => 'pedidos/pedidos', 'uses' => 'PedidosUtilController@showMainView']);
	Route::get('pedidos/selectProducto', ['as' => 'pedidos/pedidos/selectProducto', 'uses' => 'PedidosUtilController@selectProducto']);
	Route::get('pedidosClientes', ['as' => 'pedidos/pedidosClientes', 'uses' => 'PedidosClientesUtilController@showMainView']);
	Route::get('pedidosClientes/selectCliente', ['as' => 'pedidos/pedidosClientes/selectCliente', 'uses' => 'PedidosClientesUtilController@selectCliente']);
	//Main View Image
	Route::get('pedidosClientes/imageMain/{id}', ['as' => 'pedidosClientes/image', 'uses' => 'PedidosClientesUtilController@showMainViewImage']);
	Route::get('pedidosClientes/direccionesRelatedMain/{id}', ['as' => 'pedidosClientes/direccionesRelated', 'uses' => 'PedidosClientesUtilController@showMainViewDireccionesRelated']);
	Route::get('filtroSubrubros', ['as' => 'fsubrubros', 'uses' => 'ProductosUtilController@filtroSubRubros']);
	
	//Dash
	Route::get('dash', ['as' => 'dash', 'uses' => 'DashUtilController@showMainView']);
	Route::get('dash2', ['as' => 'dash2', 'uses' => 'Dash2UtilController@showMainView']);
	Route::get('dash3', ['as' => 'dash3', 'uses' => 'Dash3UtilController@showMainView']);
	
	Route::get('pedidos1', ['as' => 'pedidos1', 'uses' => 'Pedidos1UtilController@showMainView']);
	Route::get('pedidos2', ['as' => 'pedidos2', 'uses' => 'Pedidos2UtilController@showMainView']);
	Route::get('pedidos3', ['as' => 'pedidos3', 'uses' => 'Pedidos3UtilController@showMainView']);
	Route::get('download/archivos/{fileName}', function($fileName){
        $fileFullName = \config('appCustom.UPLOADS_BANNERS') . $fileName;
        $aFileName = explode('_', $fileName);
        $fileDownloadName = base64_decode($aFileName[1]);
        return Response::download($fileFullName, $fileDownloadName);
	});
	//MELI
	Route::get('createPublicacion/{id}', 'MeliController@createPublicacion');
	Route::get('updatePublicacion/{id}', 'MeliController@updatePublicacion');
	Route::get('verPublicacion/{id}', 'MeliController@verPublicacion');
	Route::delete('deletePublicacion/{id}', 'MeliController@deletePublicacion');
	Route::get('updateLoteMeli', 'MeliController@updateLoteMeli');
	Route::get('generateCategoriIdMeli', 'MeliController@generateCategoriIdMeli');
	
	//Control codigos Flexxus
    Route::get("codigosFlexxus", "ProductosController@codigosFlexxus");
	//cambiar de ubicacion!!
	Route::post('codigosFlexxusPost', ['as' => 'codigosFlexxusPost', 'uses' => 'ProductosController@codigosFlexxusPost']);
	//
    

	//Sucursales
	//Main View
	Route::get('sucursales', ['as' => 'sucursales', 'uses' => 'SucursalesUtilController@showMainView']);
	//Main View Image
	Route::get('sucursales/imageMain/{id}', ['as' => 'sucursales/image', 'uses' => 'SucursalesUtilController@showMainViewImage']);
	//Main View Note related
	Route::get('sucursales/noteRelatedMain/{id}', ['as' => 'sucursales/noteRelated', 'uses' => 'SucursalesUtilController@showMainViewNoteRelated']);
	
	
	Route::get('logImagenNoExist', 'MeliController@logImagenNoExist');
	Route::get('getImagesMeli', 'MeliController@getImagesMeli');

	Route::get('procesar','CategoriasMeliController@index');
	Route::get('exportar', ['as' => 'exportar', 'uses' => 'ExportController@index']);
	Route::get('exportarAtr', ['as' => 'exportarAtr', 'uses' => 'ExportController@atributosCategorias']);
	Route::get('test','CategoriasMeliController@test');
}); 

Route::group(['prefix' => 'rest/v2'], function(){
		Route::get('categoryPredict/{nombre}', 'MeliController@categoryPredict');
});

// Route group for REST API versioning (Web app)
Route::group(['prefix' => 'rest/v1', 'middleware' => 'auth.rest'], function(){
			
	Route::resource("user", "User\UserController");
	Route::resource("role", "User\RoleController");
	Route::resource("permission", "User\PermissionController");
	Route::resource("roleAssign", "User\RoleAssignController");
	
	Route::resource("userImg", "ImageUserController");		
	
	Route::resource("news", "NewsController");
	Route::resource("newsImage", "ImageController");
	Route::resource("newsNoteRelated", "NoteRelatedController");
	Route::resource("newsNoteLanguage", "NewsNoteLanguageController");
	
    	Route::resource("sincroMeliLog", "SincroMeliLogController");
	Route::resource("rubros", "RubrosController");
	Route::resource("rubrosImage", "ImageController");
	Route::get('rubrosIds', function(){
		return Util::getRubros();
	});

	Route::resource("subRubros", "SubRubrosController");
	Route::resource("subsubRubros", "SubSubRubrosController");

	Route::resource("deportes", "DeportesController");

	Route::resource("etiquetas", "EtiquetasController");
	Route::resource("etiquetasImage", "ImageController");

	Route::resource("note", "NoteController");
	
	Route::resource("productos", "ProductosController");
	Route::resource("productosImage", "ImageController");
	Route::resource("productosImageSlider", "ImageController");
	Route::get('etiquetasIds', function(){
		return Util::getEtiquetas();
	});
	Route::get('deportesIds', function(){
		return Util::getDeportes();
	});
	Route::resource("preciosRelated", "PreciosRelatedController");
	Route::post('preciosRelated/editInLine', ['as' => 'PreciosRelatedController/editInLine', 'uses' => 'PreciosRelatedController@editInLine']);
	Route::resource("productosProductosRelated", "ProductosRelatedController");
	Route::get("productosPreguntas", "PreguntasRelatedController@index");
	Route::get("productosPreguntas/{id}/edit", "PreguntasRelatedController@edit");
	Route::post("productosPreguntas/{id}", "MeliController@publicarRespuesta");

	Route::post('createPublicacion/{id}', 'MeliController@createPublicacion');
	Route::put('updatePublicacion/{id}', 'MeliController@updatePublicacion');
	Route::get('verPublicacion/{id}', 'MeliController@verPublicacion');
	Route::delete('deletePublicacion/{id}', 'MeliController@deletePublicacion');

	Route::get('categoryPredict/{nombre}', 'MeliController@categoryPredict');
	Route::get('categoriaMeli/{id_categoria}/{array}', 'MeliController@getCategory');
	
	Route::get('editCatMeli/{id_cat}/{nivel}', 'MeliController@editCategory');

	Route::resource("marcas", "MarcasController");
	Route::resource("marcasImage", "ImageController");

	Route::resource("colores", "ColoresController");
	Route::resource("coloresImage", "ImageController");
	Route::resource("talles", "TallesController");
	Route::resource("monedas", "MonedasController");

	Route::resource("general", "ConfGeneralController");

	Route::resource("banners", "BannersController");
    Route::post('banners/upload',['as' => 'banners/upload', 'uses' => 'BannersController@upload']);
    Route::resource("bannersClientes", "BannersClientesController");
    Route::resource("bannersPosiciones", "BannersPosicionesController");
    Route::resource("bannersTipos", "BannersTiposController");

	Route::resource("pedidos", "PedidosController");
	Route::resource("pedidosMeli", "PedidosMeliController");
	Route::resource("pedidosBackup", "PedidosBackupController");

	Route::get("pedidoMetodopago/{id}/edit", "PedidosController@metodoPago");
	Route::put("pedidoMetodopago/{id}", "PedidosController@metodoPagoPut");
	
	Route::get("pedidoEstadopago/{id}/edit", "PedidosController@estadoPago");
	Route::put("pedidoEstadopago/{id}", "PedidosController@estadoPagoPut");
	
	Route::get("pedidoEstadoenvio/{id}/edit", "PedidosController@estadoEnvio");
	Route::put("pedidoEstadoenvio/{id}", "PedidosController@estadoEnvioPut");
	
	Route::get("pedidoProductos/{id}/edit", "PedidosController@productos");
	Route::get("pedidoNotificaciones/{id}/edit", "PedidosController@notificaciones");

	Route::get("pedidoMeliMetodopago/{id}/edit", "PedidosMeliController@metodoPago");
	Route::put("pedidoMeliMetodopago/{id}", "PedidosMeliController@metodoPagoPut");
	
	Route::get("pedidoMeliEstadopago/{id}/edit", "PedidosMeliController@estadoPago");
	Route::put("pedidoMeliEstadopago/{id}", "PedidosMeliController@estadoPagoPut");
	
	Route::get("pedidoMeliEstadoenvio/{id}/edit", "PedidosMeliController@estadoEnvio");
	Route::put("pedidoMeliEstadoenvio/{id}", "PedidosMeliController@estadoEnvioPut");
	
	Route::get("pedidoMeliProductos/{id}/edit", "PedidosMeliController@productos");


	Route::get("pedidoBackupMetodopago/{id}/edit", "PedidosBackupController@metodoPago");
	Route::put("pedidoBackupMetodopago/{id}", "PedidosBackupController@metodoPagoPut");
	
	Route::get("pedidoBackupEstadopago/{id}/edit", "PedidosBackupController@estadoPago");
	Route::put("pedidoBackupEstadopago/{id}", "PedidosBackupController@estadoPagoPut");
	
	Route::get("pedidoBackupEstadoenvio/{id}/edit", "PedidosBackupController@estadoEnvio");
	Route::put("pedidoBackupEstadoenvio/{id}", "PedidosBackupController@estadoEnvioPut");
	
	Route::get("pedidoBackupProductos/{id}/edit", "PedidosBackupController@productos");

	#pedidos1
	Route::resource("pedidos1", "Pedidos1Controller");
	Route::get("pedido1Metodopago/{id}/edit", "Pedidos1Controller@metodoPago");
	Route::put("pedido1Metodopago/{id}", "Pedidos1Controller@metodoPagoPut");
	
	Route::get("pedido1Estadopago/{id}/edit", "Pedidos1Controller@estadoPago");
	Route::put("pedido1Estadopago/{id}", "Pedidos1Controller@estadoPagoPut");
	
	Route::get("pedido1Estadoenvio/{id}/edit", "Pedidos1Controller@estadoEnvio");
	Route::put("pedido1Estadoenvio/{id}", "Pedidos1Controller@estadoEnvioPut");
	
	Route::get("pedido1Productos/{id}/edit", "Pedidos1Controller@productos");
	Route::get("pedido1Notificaciones/{id}/edit", "PedidosController@notificaciones");
	#pedidos2
	Route::resource("pedidos2", "Pedidos2Controller");
	Route::get("pedido2Metodopago/{id}/edit", "Pedidos2Controller@metodoPago");
	Route::put("pedido2Metodopago/{id}", "Pedidos2Controller@metodoPagoPut");
	
	Route::get("pedido2Estadopago/{id}/edit", "Pedidos2Controller@estadoPago");
	Route::put("pedido2Estadopago/{id}", "Pedidos2Controller@estadoPagoPut");
	
	Route::get("pedido2Estadoenvio/{id}/edit", "Pedidos2Controller@estadoEnvio");
	Route::put("pedido2Estadoenvio/{id}", "Pedidos2Controller@estadoEnvioPut");
	
	Route::get("pedido2Productos/{id}/edit", "Pedidos2Controller@productos");
	Route::get("pedido2Notificaciones/{id}/edit", "PedidosController@notificaciones");
	#pedidos3
	Route::resource("pedidos3", "Pedidos3Controller");
	Route::get("pedido3Metodopago/{id}/edit", "Pedidos3Controller@metodoPago");
	Route::put("pedido3Metodopago/{id}", "Pedidos3Controller@metodoPagoPut");
	
	Route::get("pedido3Estadopago/{id}/edit", "Pedidos3Controller@estadoPago");
	Route::put("pedido3Estadopago/{id}", "Pedidos3Controller@estadoPagoPut");
	
	Route::get("pedido3Estadoenvio/{id}/edit", "Pedidos3Controller@estadoEnvio");
	Route::put("pedido3Estadoenvio/{id}", "Pedidos3Controller@estadoEnvioPut");
	
	Route::get("pedido3Productos/{id}/edit", "Pedidos3Controller@productos");
	Route::get("pedido3Notificaciones/{id}/edit", "PedidosController@notificaciones");
	##
	Route::resource("pedidosClientes", "PedidosClientesController");
	Route::resource("pedidosClientesImage", "ImageController");
	Route::resource("direccionesRelated", "DireccionesRelatedController");
	

	Route::resource("slider", "SliderController");
	Route::resource("sliderImage", "ImageController");
	Route::resource("sliderNoteRelated", "NoteRelatedController");
	Route::resource("itemRelation", "ItemRelationController");
	Route::get('itemRelationRelated', ["as" => "itemRelationRelated", "uses" => "ItemRelationController@itemsRelated"]);
	
	
	
	Route::get("provincia", ['uses' => 'ProvinciaController@getProvinciaByPais']);
	
	Route::get('obtenerSubrubros', 'SubRubrosUtilController@obtenerSubrubros');
	Route::get('obtenerSubSubrubros', 'SubSubRubrosUtilController@obtenerSubSubrubros');

	Route::get("sync", ["as" => "sync", "uses" => "FotosSyncController@sync"]);
	Route::get("syncCheck", ["as" => "syncCheck", "uses" => "FotosSyncController@getLastSyncStatus"]);

	Route::resource("sucursales", "SucursalesController");
	Route::resource("sucursalesImage", "ImageController");
	Route::resource("sucursalesNoteRelated", "NoteRelatedController");
	Route::resource("sucursalesNoteLanguage", "SucursalesNoteLanguageController");
	
	Route::get("getTallesPorRubroSubrubro/{rubroId}/{subrubroId}", "TallesUtilController@getTallesPorRubroSubrubro");

	Route::resource("dash", "DashController");
	Route::resource("dash2", "Dash2Controller");
	Route::resource("dash3", "Dash3Controller");
	Route::resource("newsletter", "NewsletterController");

    //Etiquetado Masivo
    Route::get("setEtiquetas/create", "ProductosController@setEtiquetas");
    Route::post("setEtiquetas", "ProductosController@setEtiquetasPost");

    //Andreani
    	Route::post('alta_envio', ['as' => 'alta_envio', 'uses' => 'PedidosController@altaEnvio']);
	Route::post('sucursales_envio', ['as' => 'sucursales_envio', 'uses' => 'PedidosController@sucursales_envio']);

    Route::get("sincroMeliLogDetalles/{id}/edit", "SincroMeliLogController@detalles");

});

// Route group for Client REST API versioning (oAuth2 Authentication)
Route::group(['prefix' => 'client/rest/v1'], function(){
	
	
	Route::post('access_token', function() {
		$token = Authorizer::issueAccessToken();
		return response()->json($token);
	});

	Route::group(['middleware' => 'oauth'], function () {
		Route::post('sincronizacion', 'Api\ApiProductosFlexxusController@store');
		Route::post('sincProductos', 'Api\ApiProductosFlexxusController@productos');
		Route::post('importCodigos', 'Api\ApiProductosFlexxusController@importCodigos');
		
		Route::post("productosAdd", "Api\ApiProductosController@store");

		Route::get("clientes", "Api\ApiClientesController@index");

		Route::get("pedidos", "Api\ApiPedidosController@index");
		Route::put("pedidos/{id}", "Api\ApiPedidosController@update");
        
        Route::get("localidades", "Api\ApiLocalidadesController@index");
        Route::post("localidadesAdd", "Api\ApiLocalidadesController@store");
        
        Route::get("provincias", "Api\ApiProvinciasController@index");
		Route::post("provinciasAdd", "Api\ApiProvinciasController@store");		
		
	});

});

Route::group(['prefix' => 'frontClient/rest/v1'], function(){
	//Front
	Route::resource("idioma", "Fe\IdiomaController");
	Route::resource("contacto", "Fe\ContactoController");
	
	Route::get("menu", "Fe\ProductosController@menu");

	//productos
	Route::get("etiquetasMenu", "Fe\ProductosController@etiquetasMenu");
	Route::get("rubros", "Fe\ProductosController@rubros");
    Route::get("deportes", "Fe\ProductosController@deportes"); // DEPORTES
	Route::get("filtros", "Fe\ProductosController@filtros");
	Route::get("producto", "Fe\ProductosController@producto");
	Route::get("listadoProductos", "Fe\ProductosController@listado");
    Route::get("productosHome", "Fe\ProductosController@productosHome");
	Route::get("listadoProductosRelacionados", "Fe\ProductosController@relacionados");
	Route::get("cambioColor", "Fe\ProductosController@cambiarColor");
    Route::get("servicios", "Fe\ProductosController@servicios"); // SERVICIOS

	//novedades
	Route::get("nota", "Fe\NotasController@nota");
	Route::get("listadoNotas", "Fe\NotasController@listado");
	
	Route::get("slider", "Fe\SliderController@slider");
	Route::get("sliderListado", "Fe\SliderController@destacadosSlider");
    Route::get("sliderEtiquetas", "Fe\SliderController@sliderEtiquetas");
    Route::get("panelGeneros", "Fe\ProductosController@panelGeneros");
	Route::get("relacionado", "Fe\RelacionadoController@relacionado");
	//Front fin
	
	//Cart
	Route::get('cartAdd','Fe\CartController@add');
	Route::get('cartGet','Fe\CartController@get');
	Route::get('cartRemove','Fe\CartController@remove');
	Route::get('cartUpdate','Fe\CartController@update');
	Route::get('cartGetHistory','Fe\CartController@getHistory');
	Route::get('carGetPreference','Fe\CartController@carGetPreference');
	Route::get('cartCheckout','Fe\CartController@cartCheckout');
	Route::get('notificaciones_meli','Fe\CartController@notificaciones_meli');
	Route::get('notificaciones_mercadolibre','Fe\MeliController@notificaciones_mercadolibre');
	Route::get('todoPago','Fe\CartController@todoPago');
	Route::get('validarPagoTP','Fe\CartController@validarPagoTP');
	//Cart Fin

	//Marcas
	Route::get("listadoMarcas", "Fe\MarcasController@listado");

	// Envios
	Route::get('getTipoEnvio','Fe\EnvioController@getTipoEnvio');
	Route::get('setTipoEnvio','Fe\EnvioController@setTipoEnvio');
	Route::get('consultaCostoEnvio','Fe\EnvioController@consultaCostoEnvio');
	Route::get('getDireccionEnvio','Fe\EnvioController@getDireccionEnvio');
	Route::get('getCostoEnvio','Fe\EnvioController@getCostoEnvio');
	//Route::get('andreani','Fe\CartController@andreani');

	Route::get('getSucursalEnvio','Fe\EnvioController@getSucursalEnvio');
	// Envios Fin
	
	// Mercado libre
	Route::get('setAccessToken','Fe\MeliController@setAccessToken');
	
	//auth
	Route::get("login", "Fe\AuthController@login");	
	Route::get("registro", "Fe\AuthController@registro");
	Route::get("emailConfirm", "Fe\AuthController@emailConfirm");
	Route::get("recuperarPass", "Fe\AuthController@recuperarPass");
	Route::get("resetPass", "Fe\AuthController@resetPass");
	Route::get("direcciones", "Fe\AuthController@direcciones");
	Route::get("direccionesRemove", "Fe\AuthController@direccionesRemove");
	Route::get("getDireccion", "Fe\AuthController@getDireccion");
	Route::get("setDireccion", "Fe\AuthController@setDireccion");
	Route::get("updatePerfil", "Fe\AuthController@updatePerfil");
	Route::get("getLocalidad", "Fe\AuthController@getLocalidad");
	//auth fin
	Route::get("getOpiniones", "Fe\AuthController@getOpiniones");
	
	// Banners
    Route::resource("banners_front", "Fe\BannersController");
    Route::resource("banners_click", "Fe\BannersController@banners_click");
	//Front fin	

	Route::resource("newsletter","Fe\NewsletterController@store");

	Route::resource("search","Fe\ProductosController@search");
});