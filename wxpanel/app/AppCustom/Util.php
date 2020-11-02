<?php
	/**
		* Description of Util
		*
		* @author martinm
	*/
	
	namespace App\AppCustom;
	
	use App\AppCustom\Models\ConfTallesEquivalencias;
    use App\AppCustom\Models\FrontLanguage;
	use App\AppCustom\Models\Category;
	use App\AppCustom\Models\Sentinel\User;
	use App\AppCustom\Models\Rubros;
	use App\AppCustom\Models\SubRubros;
	use App\AppCustom\Models\SubSubRubros;
	use App\AppCustom\Models\Etiquetas;
	use App\AppCustom\Models\Monedas;
	use App\AppCustom\Models\Listas;
	use App\AppCustom\Models\PedidosProductos;
	use App\AppCustom\Models\Productos;
	use App\AppCustom\Models\PreciosProductos;
	use App\AppCustom\Models\Colores;
	use App\AppCustom\Models\Talles;
	use App\AppCustom\Models\Image;
	use App\AppCustom\Models\Note;
	use App\AppCustom\Models\CodigoStock;
	use App\AppCustom\Models\Marcas;
	use App\AppCustom\Models\Deportes;
	use App\AppCustom\Models\ProductosCodigoStock;
	use App\AppCustom\Models\SucursalesStock;
	use App\AppCustom\Models\Genero;
	use App\AppCustom\Models\ConfNumeracion;
	use App\AppCustom\Models\ConfTallesEquivalencia;
	use App\AppCustom\Models\ProductosCategMeli;
	use App\AppCustom\Models\CategoriasMeli;
	use App\AppCustom\Models\CategoriasMeliValues;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	use GuzzleHttp\Client;
	use GuzzleHttp\Exception\ClientException;

header("Content-Type: text/html;charset=utf-8");
	
	class Util {
		
		static $aMonths = [
			1 => 'Ene',
			2 => 'Feb',
			3 => 'Mar',
			4 => 'Abr',
			5 => 'May',
			6 => 'Jun',
			7 => 'Jul',
			8 => 'Ago',
			9 => 'Sep',
			10 => 'Oct',
			11 => 'Nov',
			12 => 'Dic',
		];
		//TODO: remove this
		static $messages = array(
        'unauthorized' => 'No se tiene permisos para ejecutar la operación',
        'dbError' => 'Error de BD',
        'itemNotFound' => 'No se ha encontrado el elemento',
		);

        static function getTalleEquivalente($talle,$id_marca,$id_genero,$numeracion,$id_rubro){
            $talle = ConfTallesEquivalencias::
            select(\DB::raw('equivalencia,talle'))
                ->where('id_marca', $id_marca)
                ->where('id_genero', $id_genero)
                ->where('talle', $talle)
                ->where('id_numeracion', $numeracion)
                ->where('id_categoria', $id_rubro)
                ->orderBy('id_numeracion','asc')
                ->first();

            return $talle;
        }
		static function getDefaultArrayResult() {
			return [
            'status' => 0,
            'msg'    => 'ok',
            'html'  => '',
            'data'   => [],
			];
		}
		//TODO: remove this
		static function getMenus() {
			
			$menus = 
            \DB::table('sys_menus')
			->select(
			'id_menu', 
			'menu'
			)
			->orderBy('orden', 'asc')
			->where('habilitado', 1)
			->get()
            ;
			
			foreach ($menus as &$menu) {
				$menu->aSubmenu = 
                \DB::table('sys_submenus')
				->select(
				'id_submenu', 
				'submenu'
				)
				->orderBy('orden', 'asc')
				->where('id_menu', $menu->id_menu)
				->where('habilitado', 1)
				->get()
                ;
			}
			
			return $menus;
		}
		
		static function getRubros($returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$rubros = Productos::
				selectRaw('inv_rubros.id, inv_rubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_rubros','inv_rubros.id','=','inv_productos.id_rubro')
				->where('inv_productos.habilitado',1)
				->where('inv_rubros.habilitado',1)
				->orderBy('inv_rubros.orden')
				->groupBy('inv_rubros.id')
				->get();
			}else{
				$rubros = 
				Rubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($rubros->toArray());
				}elseif ('array' == $returnAs) {
				return $rubros->toArray();
				} else {
				return true;
			}
		}

		static function getFiltroRubros() {
     	   return Rubros::where('habilitado', 1)->orderBy('nombre')->get(); 
    	}
		
		static function getFiltroSubRubros($id) {

		   if($id!=0){
		   		return SubRubros::where('habilitado', 1)
		   					 	->where('id_rubro',$id)
		   					 	->orderBy('nombre')
		   					 	->get();
		   }else {
		   		return SubRubros::where('habilitado', 1)
		   						->orderBy('nombre')
		   						->get(); 
		   }
     	   
		}
		
		static function getSubRubros($id, $returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$subrubros = Productos::
				selectRaw('inv_subrubros.id, inv_subrubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_subrubros','inv_subrubros.id','=','inv_productos.id_subrubro')
				->where('inv_productos.habilitado',1)
				->where('inv_subrubros.habilitado',1)
				->where('inv_productos.id_rubro',$id)
				->orderBy('inv_subrubros.orden')
				->groupBy('inv_subrubros.id')
				->get();
			}else{
				$subrubros = 
				SubRubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->where('id_rubro',$id)
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($subrubros->toArray());
				}elseif ('array' == $returnAs) {
				return $subrubros->toArray();
				} else {
				return true;
			}
		}
		
		static function getSubSubRubros($id, $returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$subrubros = Productos::
				selectRaw('inv_subsubrubros.id, inv_subsubrubros.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('inv_subsubrubros','inv_subsubrubros.id','=','inv_productos.id_subsubrubro')
				->where('inv_productos.habilitado',1)
				->where('inv_subsubrubros.habilitado',1)
				->where('inv_productos.id_rubro',$id)
				->orderBy('inv_subsubrubros.orden')
				->groupBy('inv_subsubrubros.id')
				->get();
			}else{
			$subrubros = 
				SubSubRubros::select('id', 'nombre as text')
				->where('habilitado','1')
				->where('id_subrubro',$id)
				->orderBy('orden')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($subrubros->toArray());
				}elseif ('array' == $returnAs) {
				return $subrubros->toArray();
				} else {
				return true;
			}
		}
		
		static function getMarcas($returnAs = 'json', $filproductos = false) {
			if($filproductos){
				$marcas = Productos::
				selectRaw('conf_marcas.id, conf_marcas.nombre as text, count(inv_productos.id) as cantidad')
				->leftJoin('conf_marcas','conf_marcas.id','=','inv_productos.id_marca')
				//->where('inv_productos.habilitado',1)
				->where('conf_marcas.habilitado',1)
				->orderBy('conf_marcas.destacado','desc')
				->groupBy('conf_marcas.id')
				->get();
			}else{
				$marcas = 
				Marcas::select('id', 'nombre as text')
				->where('habilitado','1')
				->orderBy('destacado','desc')
				->get()
				;
			}
			if ('json' == $returnAs) {
				return response()->json($marcas->toArray());
				}elseif ('array' == $returnAs) {
				return $marcas->toArray();
				} else {
				return true;
			}
		}

		static function filtroMarcas(){
			return Marcas::where('habilitado', 1)->orderBy('nombre')->get(); 
		}

		static function filtroEtiquetas(){
			return Etiquetas::where('habilitado', 1)->orderBy('nombre')->get(); 
		}

		static function getEtiquetas($returnAs = 'json') {
			
			$etiquetas = 
            Etiquetas::selectRaw('id, nombre as text')
			->where('habilitado',1)
			->get();
			
			if ('json' == $returnAs) {
				return response()->json($etiquetas->toArray());
			}elseif ('array' == $returnAs) {
				return $etiquetas->toArray();
			} else {
				return true;
			}
		}

		
		static function getDeportes($returnAs = 'json' ,  $filproductos = false) {
	
			if($filproductos){
				$etiquetas = Deportes::
				selectRaw('inv_deportes.id, inv_deportes.nombre as text, count(inv_productos_deportes.id_producto) as cantidad')
				->Join('inv_productos_deportes','inv_deportes.id','=','inv_productos_deportes.id_deporte')
				->where('inv_deportes.habilitado',1)
				->groupBy('inv_deportes.id')
				->get();
			}else{
			
				$etiquetas = 
				Deportes::select('id', 'nombre as text')
				->where('habilitado','1')
				->get()
				;
			}
			
			if ('json' == $returnAs) {
				return response()->json($etiquetas->toArray());
			}elseif ('array' == $returnAs) {
				return $etiquetas->toArray();
			} else {
				return true;
			}
		}

		static function getRangoPrecios($returnAs = 'json') {
	
		
				$precios = PreciosProductos::
				selectRaw('CASE WHEN (inv_precios.precio_venta BETWEEN 0 AND 1000) 
								THEN "Hasta 1000 ,0-1000" 
							ELSE CASE WHEN (inv_precios.precio_venta BETWEEN 1000 AND 2000) 
									THEN "1000 - 2000,1000-2000"
								ELSE CASE WHEN (inv_precios.precio_venta >= 2000)
										THEN "Más de 2000,2000-99999" 
								END 
						   END
						   END text, COUNT(*) cantidad')
				->Join('inv_productos','inv_productos.id','=','inv_precios.id_producto')
				->where('inv_productos.habilitado',1)
				->groupBy('text')
				->orderBy('text','desc')
				->get();
			
			
			if ('json' == $returnAs) {
				return response()->json($precios->toArray());
			}elseif ('array' == $returnAs) {
				return $precios -> toArray();
			} else {
				return true;
			}
		}
		
		static function orderString($string) {
			$stringParts = str_split($string);
			sort($stringParts);
			return implode('', $stringParts);
		}
		
		static function getLanguages() {
			return FrontLanguage::where('habilitado', 1)->get();
		}
		
		static function getCategories() {
			return Category::where('habilitado', 1)->get();
		}
		static function getCategorie($id) {
			return Category::where('habilitado', 1)->where('id_seccion', $id)->first();
		}
		
		static function uploadBase64File($path, $fileName, $base64File, $thumbProportion) {
			
			$data = explode(',', $base64File);
			
			$im = imagecreatefromstring(base64_decode($data[1]));
			if ($im !== false) {
                $fileNameFull = $path . $fileName;
                //image file generate
                imagejpeg($im, $fileNameFull);
                imagedestroy($im);

                if (file_exists($fileNameFull)){
                    //image file thumb generate
                    $im=imagecreatefromjpeg($fileNameFull);
                    $width=ImageSx($im);              // Original picture width is stored
                    $height=ImageSy($im);             // Original picture height is stored


                    /* $b_width = 800;
                    $b_height = 800;
                    $newimage_b=imagecreatetruecolor($b_width,$b_height);
                    imageCopyResized($newimage_b,$im,0,0,0,0,$b_width,$b_height,$width,$height);
                    imagejpeg($newimage_b,$path . '800_' .$fileName ); */

                    $n_width = $width * $thumbProportion;
                    $n_height = $height * $thumbProportion;

                    $newimage=imagecreatetruecolor($n_width,$n_height);
                    imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
                    imagejpeg($newimage,$path . 'th_' .$fileName );

                    //app img
                    $a_width = $width * 0.4;
                    $a_height = $height * 0.4;

                    $newimage_a=imagecreatetruecolor($a_width,$a_height);
                    imageCopyResized($newimage_a,$im,0,0,0,0,$a_width,$a_height,$width,$height);
                    imagejpeg($newimage_a,$path . 'app_' .$fileName );
				}
				
				} else {
				throw new Exception('imagecreatefromstring() fail');
			}
		}
		
		static function uploadBase64File1($path, $fileName, $base64File, $thumbProportion) {
			
			$data = explode(',', $base64File);
			
			if($data[1]!=''){
				$im = imagecreatefromstring(base64_decode($data[1]));
				if ($im !== false) {
				
					$fileNameFull = $path . $fileName;
					$width=ImageSx($im);              // Original picture width is stored
					$height=ImageSy($im);
						
					if(($width == 800 && $height == 800) || ($width >= 801 && $height >= 801)){				
					
						if (file_exists($fileNameFull)){							
							//image file generate
							imagejpeg($im, $fileNameFull);
							imagedestroy($im);
							//image file thumb generate
							$im=imagecreatefromjpeg($fileNameFull); 
							$width=ImageSx($im);              // Original picture width is stored
							$height=ImageSy($im);             // Original picture height is stored
							
							
							/* $b_width = 800;
							$b_height = 800;					
							$newimage_b=imagecreatetruecolor($b_width,$b_height);                 
							imageCopyResized($newimage_b,$im,0,0,0,0,$b_width,$b_height,$width,$height);
							imagejpeg($newimage_b,$path . '800_' .$fileName ); */
			
								/*$n_width = $width * $thumbProportion;
								$n_height = $height * $thumbProportion;*/
								$n_width = 300;
								$n_height = 300;
								
								$newimage=imagecreatetruecolor($n_width,$n_height);                 
								imageCopyResized($newimage,$im,0,0,0,0,$n_width,$n_height,$width,$height);
								imagejpeg($newimage,$path . '300_' .$fileName );
													
								//app img
								/*$a_width = $width * 0.4;
								$a_height = $height * 0.4;*/
								$a_width = 100;
								$a_height = 100;
								
								$newimage_a=imagecreatetruecolor($a_width,$a_height);                 
								imageCopyResized($newimage_a,$im,0,0,0,0,$a_width,$a_height,$width,$height);
								imagejpeg($newimage_a,$path . '100_' .$fileName );
								
								return true;														
						}
					}
					
				} else {
					throw new Exception('imagecreatefromstring() fail');
				}
			}else{
				\Log::info('pasa');
			}
			
			
		}

		static function truncateString($string,$length=100,$append="&hellip;") {
			$string = \trim($string);
			
			if(strlen($string) > $length) {
				$string = \wordwrap($string, $length);
				$string = \explode("\n", $string, 2);
				$string = $string[0] . $append;
			}
			
			return $string;
		}
		
		static function getForgotToken() {
			
			do {
				$tokenKey = \Hash::make(\str_random(50) . '_' . time());
			} while (User::where("forgot_token", "=", $tokenKey)->first() instanceof User);
			
			return $tokenKey;
			
		}
		
		static function getSomeToken($modelName, $field, $strSize = 50) {
			
			do {
				$tokenKey = \Hash::make(\str_random($strSize) . '_' . time());
			} while ($modelName::where($field, "=", $tokenKey)->first() instanceof $modelName);
			
			return $tokenKey;
			
		}
		
		static function getSomeString($modelName, $field, $strSize = 25) {
			
			do {
				$str = \str_random($strSize);
			} while ($modelName::where($field, "=", $str)->first() instanceof $modelName);
			
			return $str;
			
		}
		
		static function getLogos($idCompany) {
			
			return [
			'logo'        => sprintf(config('appCustom.logos.logo'), $idCompany),
			'logoEmail64' => sprintf(config('appCustom.logos.logoEmail64'), $idCompany),
			];
			
		}
		
		static function getLogosByCompanyId($idCompany) {
			
			$aLogos = static::getLogos($idCompany);
			
			return [
			'logo' => file_exists($aLogos['logo']) ? $aLogos['logo'] : config('appCustom.logos.logoDefault'),
			'logoEmailB64' => file_exists($aLogos['logoEmail64']) ? \file_get_contents($aLogos['logoEmail64']) : \file_get_contents(config('appCustom.logos.logoEmail64Default')),
			
			];
		}
		
		static function getLogosByCompany($company) {
			
			$item = 
			App\AppCustom\Models\Company::where('name', $company)
			->first()
			;
			
			if ($item) {
				$idCompany = $item->id;
				
				$aLogos = static::getLogos($idCompany);
				
				return [
				'logo' => file_exists($aLogos['logo']) ? $aLogos['logo'] : config('appCustom.logos.logoDefault'),
				'logoEmailB64' => file_exists($aLogos['logoEmail64']) ? \file_get_contents($aLogos['logoEmail64']) : \file_get_contents(config('appCustom.logos.logoEmail64Default')),
				
				];
				
				} else {
				
				return [
				'logo' => config('appCustom.logos.logoDefault'),
				'logoEmailB64' => \file_get_contents(config('appCustom.logos.logoEmail64Default')),
				
				];
			}
			
		}
		
		static function getCompanyDataByUrl($url) {
			
			$aReturn = [];
			
			if ($subdomain = static::getSubdomain($url)) {
				$company = 
				\App\AppCustom\Models\Company::where('name', $subdomain)->first();
				
				if ($company) {
					$aReturn['company'] = $company;
					$aReturn['logos'] = Util::getLogosByCompanyId($company->id);
				}
			}
			
			if (!$aReturn) {
				$aReturn['company'] = \App\AppCustom\Models\Company::find(config('appCustom.companyDefaultId'));
				$aReturn['logos'] = Util::getLogosByCompanyId(config('appCustom.companyDefaultId'));
			}
			
			return $aReturn;
		}
		
		static function getCompanyDataByThisUrl() {
			return static::getCompanyDataByUrl(\URL::to('/'));
		}
		
		static function getSubdomain($url) {
			
			$parsedUrl = parse_url($url);
			
			$host = explode('.', $parsedUrl['host']);
			
			if (count($host) > 1) {
				$subdomain = $host[0];
				
				return  $subdomain;
			}
			
			
		}
		
		static function dateOk($date, $format = 'd/m/Y') {
			$d = \DateTime::createFromFormat($format, $date);
			
			return $d && $d->format($format) === $date;
		}
		
		static function getPrecioFormat($precio) {
			$precio = number_format ($precio, 2 , ',' , '.');
			return str_replace(',00', '', $precio);
		}
		static function getMonedaDefault() {
			$moneda = Monedas::select('id','nombre','simbolo')->where('principal',1)->get()->toArray();
			return $moneda;
		}   
		static function getMonedaSimbolo($id_moneda) {
			$moneda = Monedas::select('simbolo')
			->where('id',$id_moneda)
			->first();
			$moneda = $moneda->simbolo;
			return $moneda;
		}
		static function getPrecios($id, $id_moneda) {
			$precio = PreciosProductos::
			select('precio_venta','precio_lista','descuento')
			->where('id_producto', $id)
			->where('id_moneda', $id_moneda)
			->first();
			if($precio){
				//si tiene descuento, va sobre el precio de lista
				if($precio->descuento>0 && $precio->precio_lista>0){
					$precio->precio_db = ($precio->precio_lista-$precio->descuento);
				}else{
					$precio->precio_db = $precio->precio_venta;
				}
			}else{
				$precio = false;
			}
			return $precio;
		}

		static function estadoPedido($e) {
			
			switch ($e) {
				case 'acordar':
					$estado = "Envios a acordar";
				break;
				case 'cash_on_delivery':
					$estado = "Pago contra reembolso";
				break;
				case 'payment_in_branch':
					$estado = "Pago en sucursal";
				break;
				case 'pending':
					$estado = "Pago en proceso";
				break;
				case 'approved':
					$estado = "Pago realizado con &eacute;xito!";
				break;
				case 'in_process':
					$estado = "El pago está siendo revisado";
				break;
				case 'rejected':
					$estado = "El pago fue rechazado";
				break;
				case 'cancelled':
					$estado = "El pago fue cancelado";
				break;
				case 'refunded':
					$estado = "La compra no se concretó";
				break;
				case 'in_mediation':
					$estado = "En disputa del pago";
				break;
				case 'acordar':
					$estado = "Env&iacute;o a acordar con ".\config('appCustom.clientName');
				break;
				case 'proceso':
					$estado = "Carrito";
				break;
				default:
					$estado=$e;
				break;
			}
			
			return $estado;
		}
		static function estadoPedidoDetalle ($e){
			switch ($e) {
				case "accredited":
					$detalle_estado="El pago fue acreditado.";
				break;
				case "pending_contingency":
					$detalle_estado="Pago suspendido hasta validar informacion.";
				break;
				case "pending_review_manual":
					$detalle_estado="Operación a revisar de forma manual - Antifraude.";
				break;
				case "pending_review_auto":
					$detalle_estado="Operación a revisar de forma automatica - Antifraude.";
				break;
				case "pending_waiting_payment":
					$detalle_estado="A la espera del pago.";
				break;
				case "pending_additional_info":
					$detalle_estado="A la espera de informacion adicional.";
				break;
				case "pending_online_validation":
					$detalle_estado="Validacion Online.";
				break;
				case "pending_card_validation":
					$detalle_estado="Validacion de datos.";
				break;
				case "pending_waiting_for_remedy":
					$detalle_estado="Validacion de datos.";
				break;
				case "pending_form_bad_filled_card_number":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_security_code":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_date":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending_form_bad_filled_other":
					$detalle_estado="Esperando re-ingreso de datos - Mal completados en el formulario.";
				break;
				case "pending":
					$detalle_estado="Pendiente de finalizar una operacion.";
				break;
				case "insufficent_amount":
					$detalle_estado="Monto insuficiente.";
				break;
				case "by_collector":
					$detalle_estado="Cancelado por el vendedor.";
				break;
				case "by_payer":
					$detalle_estado="Cancelado por el comprador.";
				break;
				case "expired":
					$detalle_estado="Operación vencida.";
				break;
				case "expired":
					$detalle_estado="Operación vencida.";
				break;
				case "refunded":
					$detalle_estado="Pago devuelto al comprador.";
				break;
				case "rejected":
					$detalle_estado="Rechazado por Mercado Pago - Inhabilitado.";
				break;
				case "cc_rejected_fraud":
					$detalle_estado="Rechazado de la tarjeta / Mercado Pago - Riesgo de fraude";
				break;
				case "cc_rejected_high_risk":
					$detalle_estado="Rechazado de la tarjeta / MP - Riesgo de fraude";
				break;
				case "cc_rejected_blacklist":
					$detalle_estado="Rechazado de la tarjeta - La misma se encuentra en BlackList";
				break;
				case "cc_rejected_insufficient_amount":
					$detalle_estado="Rechazado de la tarjeta - limite insuficiente para realizar la compra";
				break;
				case "cc_rejected_other_reason":
					$detalle_estado="Rechazado de la tarjeta - Rechazado por otros motivos";
				break;
				case "cc_rejected_max_attempts":
					$detalle_estado="Rechazado de la tarjeta -  Limite de intentos de compra maximo";
				break;
				case "cc_rejected_invalid_installments":
					$detalle_estado="Rechazado de la tarjeta - Configuracion de cuotas invalidas";
				break;
				case "cc_rejected_call_for_authorize":
					$detalle_estado="Rechazado de la tarjeta - Se necesita autorizacion para procesar el pago. Debe llamar a la misma y autorizar la operación.";
				break;
				case "cc_rejected_duplicated_payment":
					$detalle_estado="Rechazado de la tarjeta - El usuario registro un pago inmediatamente antes de identicas caracteristicas";
				break;
				case "cc_rejected_card_disabled":
					$detalle_estado="Rechazado de la tarjeta - Tarjeta no habilitada";
				break;
				case "cc_rejected_card_error":
					$detalle_estado="Rechazado de la tarjeta - Informacion ingresada de la tarjeta erronea";
				break;
				case "review_fail":
					$detalle_estado="Rechazado por revision de datos fallida";
				break;
				case "payer_unavailable":
					$detalle_estado="Rechazado por comprador bloqueado en Mercado Libre / Mercado Pago";
				break;
				case "collector_unavailable":
					$detalle_estado="Rechazado por vendedor bloqueado en Mercado Libre / Mercado Pago";
				break;
				default:
					$detalle_estado=$e;
				break;
			}
			return $detalle_estado;
		}
		static function estadoEnvio ($e) {
			$estado_envio = '';
			if($e){
				switch($e){
					case "pending":
						$estado_envio='Pendiente';
					break;
					case "ready_to_ship":
						$estado_envio='Listo para enviar';
					break;
					case "shipped":
						$estado_envio='Enviado';
					break;
					case "delivered":
						$estado_envio='Entregado';
					break;
					case "not_delivered":
						$estado_envio='No entregado';
					break;
					case "cancelled":
						$estado_envio='Cancelado';
					break;
					case "en_sucursal":
						$estado_envio='Retiro en sucursal';
					break;
					default:
						$estado=$e;
					break;
				}
			}
			
			return $estado_envio;
		}
		static function metodoMercado($e){
			switch ($e){
				case "account_money":
					$metodo="Cuenta de dinero";
				break;
				case "credit_card":
					$metodo="Tarjeta de crédito ";//.$f['metodo_tipo'];
				break;
				case "debit_card":
					$metodo="Tarjeta de débito ";//.$f['metodo_tipo'];
				break;
				case "ticket":
					$metodo="Pago por Pagofácil o Rapipago";//.$f['metodo_tipo'];
				break;
				case "bank_transfer":
					$metodo="Transferencia bancaria ";//.$f['metodo_tipo'];
				break;
				case "pago_contrarembolso":
					$metodo="Pago contra reembolso";
				break;
				default:
					$metodo=$e;
				break;
			}
			return $metodo;
		}
		static function getEnum($table, $column) {
			$type = \DB::select(\DB::raw("SHOW COLUMNS FROM $table WHERE Field = '{$column}'"))[0]->Type ;
			preg_match('/^enum\((.*)\)$/', $type, $matches);
			$enum = array();
			foreach( explode(',', $matches[1]) as $value )
			{
				$v = trim( $value, "'" );
				$enum = array_add($enum, $v, $v);
			}
			return $enum;
		}

		static function enviarMailRegalo($id_lista, $id_pedido) {
			$params = 'id_rubro=0';
            $params .= '&id_lista='.$id_lista;
            
            $linkRegalo = env('FE_URL');
            $linkRegalo .= 'regalos.php?' . $params;

			$lista = Listas::find($id_lista);
			$producto = PedidosProductos::select('nombre')
										->where('pedidos_productos.id_pedido','=',$id_pedido)
										->first();

			$cliente = Listas::select('listas_usuarios.mail')
								->join('listas_usuarios','listas_usuarios.id','=','listas.id_cliente_lista')
								->where('listas.habilitado',1)
								->where('listas.id','=',$id_lista)
								->first();

			$vendedor = \config('appCustom.clientName');				
			// Envio mail de compra a los mail cargados por el cliente
			$mails = explode(';', $cliente->mail);	
			foreach ($mails as $mail) {
				\Mail::send(
	                'email.compraRegalo',
	                [
	                	'titulo' => $lista->titulo,
	                    'invitado' => 'prueba',
	                    'producto' => $producto->nombre,
	                    'link' => $linkRegalo
	                ],
	                function($message) use ($mail, $vendedor)
	                {
	                    $message->to($mail)
	                        ->subject($vendedor . '. Compra de regalo');
	                }
	            );
	        }

	        // Envio de mail a Marcantonio Deportes
            \Mail::send(
                'email.compraRegalo',
                [
                	'titulo' => $lista->titulo,
                    'invitado' => 'prueba',
                    'producto' => $producto->nombre,
                    'link' => $linkRegalo
                ],
                function($message) use ($vendedor)
                {
                    $message->to('matias@webexport.com.ar')
                        ->subject($vendedor . '. Compra de regalo');
                }
            );

            return 0;
		}
		
		static function cambiaAcento($string)
		{
			$cadena=utf8_decode($string);
			$vocales = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú");
			$acentos = array("a", "e", "i", "o", "u","A", "E", "I", "O", "U" );
			$frase = str_replace($vocales, $acentos , $cadena);

			return $frase;
		}

		static function getStock($id_producto, $estado_meli='all')
		{ 
			$colores = CodigoStock::
						select('inv_producto_codigo_stock.id_producto', 'inv_producto_codigo_stock.id','inv_producto_codigo_stock.codigo','inv_producto_codigo_stock.stock','inv_producto_codigo_stock.estado_meli','inv_producto_codigo_stock.id_color','conf_colores.nombre as nombreColor','inv_producto_codigo_stock.id_talle','conf_talles.nombre as nombreTalle')
						->join('conf_colores','conf_colores.id','=','inv_producto_codigo_stock.id_color')
                        ->leftJoin('conf_talles','conf_talles.id','=','inv_producto_codigo_stock.id_talle')
						->where('inv_producto_codigo_stock.id_producto','=',$id_producto);
			
			

			if ($estado_meli != 'all') {				
				$colores = $colores->where('inv_producto_codigo_stock.estado_meli','=',$estado_meli);
			}
			$colores = $colores->get();
			return $colores;
		}
		
		public static function in_array($aArray, $field, $value) {
			if ($aArray) {
				foreach ($aArray as $item) {
					if ($item[$field] == $value) {
						return $item;
					}
				}
			}
		}
		
		public static function inArrayGetAll($aArray, $field, $value) {
			$aItems = [];
			if ($aArray) {
				foreach ($aArray as $item) {
					if (strpos(strtolower($item[$field]), strtolower($value)) !== false) {
						array_push($aItems, $item);
					}
				}
			}
			
			return $aItems;
		}


		public static function importar($row,$rowNum,$aResult) {
			$rowNum++;
			unset($talle);
			unset($cod_producto);
			unset($cod_color);
			unset($rubro);
			unset($subrubro);
			unset($marca);
			unset($genero);
			unset($color);
			unset($talle);
			$productosActualizados = 0;

			$codigo_imp = $row['codigo']; //requerido
			$articulo_imp = $row['articulo']; //requerido
			$talle_imp = $row['talle'];
			$color_imp = $row['color'];
			$precio_de_venta_imp = $row['precio_de_venta']; //requerido
			$precio_de_lista_imp = $row['precio_de_lista'];
			$genero_imp = $row['genero'];
			$rubro_imp = $row['rubro']; //requerido
			$subrubro_imp = $row['subrubro'];
			$marca_imp = $row['marca'];
			$origen_imp = $row['origen'];

			$stock_ecommerce_imp = $row['stk_ecommerce']; //requerido
			$stock_colon_imp = $row['stk_colon']; //requerido
			$stock_yerbabuena_imp = $row['stk_yerbabuena']; //requerido
			$stock_plazoleta_imp = $row['stk_plazoleta']; //requerido

			$descripcion_imp = $row['descripcion'];
			$ean_imp = $row['ean'];
			$sku_imp = $row['sku'];
			$alto_imp = $row['alto'];
			$ancho_imp = $row['ancho'];
			$largo_imp = $row['largo'];
			$peso_imp = $row['peso'];
			
			if ($codigo_imp && $articulo_imp && $precio_de_venta_imp && $rubro_imp && $stock_ecommerce_imp>=0 && $stock_colon_imp>=0 && $stock_yerbabuena_imp>=0 && $stock_plazoleta_imp>=0) {
				//formateo el codigo
				$codigo_form = explode('.', $codigo_imp);
				if(isset($codigo_form[1])){
					$talle= $codigo_form[1]; //extraigo el codigo de talle
					//extraer el codigo de color y y del producto 
					//(3 ultimos digitos color)
					$cod_producto = substr($codigo_form[0],0,-3);
					$cod_color = str_replace($cod_producto, '', $codigo_form[0]);
					
					//busco si el producto existe
					$item = ProductosCodigoStock::select('id_producto')
					->where('codigo', 'like', $cod_producto.'%')->first();

					//empiezo a crear o actualizar los productos
					
					// Verifico si el rubro existe
					if (isset($rubro_imp)) {
						$rubro_imp = ucwords(strtolower(($rubro_imp)));
						/* \Log::info($rubro_imp);
						\Log::info(utf8_encode($rubro_imp)); */
						$rubro = Rubros::select('id')->select('id')->where('nombre','=',utf8_encode($rubro_imp))->first();

						if (!$rubro) {
							// Si no existe se debe crear el rubro
							$array_rubro = array(
								'nombre' => $rubro_imp
							);
							//$request->request->add($array_rubro);
							$aResult = app('App\Http\Controllers\RubrosController')->storeImportKernel($array_rubro);
							$aResult = json_decode($aResult->getContent(),true);
							$rubro = Rubros::select('id')->where('nombre','=',$rubro_imp)->first();
						}
						if (isset($subrubro_imp)) {
							$subrubro_imp = ucwords(strtolower(($subrubro_imp)));
							// Verifico si el subrubro existe
							$subrubro = SubRubros::
							select('id')
							->where('nombre','=',$subrubro_imp)
							->where('id_rubro', $rubro->id)
							->first();
		
							if (!$subrubro) {
								// Si no existe se debe crear el subrubro
								$array_subrubro = array(
									'nombre' => $subrubro_imp,
									'id_rubro' => $rubro->id,
									'orden' => 0
								);
								//$request->request->add($array_subrubro);
								$aResult = app('App\Http\Controllers\SubRubrosController')->storeImportKernel($array_subrubro);
								$aResult = json_decode($aResult->getContent(),true);
								$subrubro = SubRubros::
								select('id')->where('nombre','=',$subrubro_imp)
								->where('id_rubro', $rubro->id)
								->first();
							}
						}
					}
					if (isset($marca_imp)) {
						$marca_imp = ucwords(strtolower(($marca_imp)));
						// Verifico si la marca existe
						$marca = Marcas::select('id')->where('nombre','=',$marca_imp)->first();
						if (!$marca) {
							// Si no existe se debe crear la marca 
							$array_marca = array(
								'nombre' => $marca_imp
							);
							//$request->request->add($array_marca);
							$aResult = app('App\Http\Controllers\MarcasController')->storeImportKernel($array_marca);
							$aResult = json_decode($aResult->getContent(),true);
							$marca = Marcas::select('id')->where('nombre','=',$marca_imp)->first();
						}
					}
					if (isset($origen_imp)) {
						// Verifico si la pais existe
						$origen = Pais::select('id_pais')->where('pais','=',$origen_imp)->first();
						if (!$origen) {
							// Si no existe se debe crear la pais
							$origen = new Pais;
							$origen->pais = $origen_imp;
							$origen->save();
						}
					}
					if (isset($genero_imp)) {
						if($genero_imp=='NO TIENE'){
							$genero_imp = "Unisex";
						}else{
							$genero_imp = ucwords(strtolower(($genero_imp)));
							if($genero_imp=='Femenino'){
								$genero_imp = 'Mujer';
							}elseif($genero_imp=='Masculino'){
								$genero_imp = 'Hombre';
							}
						}
					}else{
						$genero_imp = "Unisex";
					}
					// Verifico si el genero existe
					$genero = Genero::select('id')->where('genero','=',$genero_imp)->first();
					if(!$genero) {
						// Si no existe se debe crear la genero 
						$genero = new Genero;
						$genero->genero = $genero_imp;
						$genero->save();
					}
					if (isset($color_imp)) {
						//color
						$color = Colores::select('id')->where('nombre', $color_imp)->where('habilitado', 1)->first();
						if(!$color){
							$color = new Colores;
							$color->nombre = $color_imp;
							$color->habilitado = 1;
							$color->save();
						}
					}
					if (isset($talle_imp)) {
						$talle_imp = str_replace('-', '', $talle_imp);
						$talle_imp = ltrim($talle_imp, "0");
						//talle
						$talle = Talles::select('id')->where('nombre', $talle_imp)->where('habilitado', 1)->first();
						if(!$talle){
							$talle = new Talles;
							$talle->nombre = $talle_imp;
							$talle->habilitado = 1;
							$talle->save();
						}
					}
					$alto_imp = $alto_imp?$alto_imp:10;
					$ancho_imp = $ancho_imp?$ancho_imp:10;
					$largo_imp = $largo_imp?$largo_imp:10;
					$peso_imp = $peso_imp?$peso_imp:100;

					$stock_sucursal_imp = array(
						'stk_ecommerce' => $stock_ecommerce_imp,
						'stk_colon' => $stock_colon_imp,
						'stk_yerbabuena' => $stock_yerbabuena_imp,
						'stk_plazoleta' => $stock_plazoleta_imp
					);
					$stock_total = $stock_colon_imp+$stock_yerbabuena_imp+$stock_plazoleta_imp;
					if(!$item){
						$articulo_imp = ucwords(strtolower(($articulo_imp)));
						$descripcion_imp = ucwords(strtolower(($descripcion_imp)));

						$array_send = array(
							'nombre' => $articulo_imp,
							'ean' => $ean_imp,
							'sku' => $sku_imp,
							'orden' => 0,
							'habilitado' => 0
						);
						$array_send['alto'] = $alto_imp;
						$array_send['ancho'] = $ancho_imp;
						$array_send['largo'] = $largo_imp;
						$array_send['peso'] = $peso_imp;

						if ($descripcion_imp!=''){
							$array_send['sumario'] = $descripcion_imp;
						} else {
							$array_send['sumario'] = '';
						}
						
						if (isset($rubro)){
							$array_send['id_rubro'] = $rubro->id;
						} else {
							$array_send['id_rubro'] = '';
						}

						if (isset($subrubro)){
							$array_send['id_subrubro'] = $subrubro->id;
						} else {
							$array_send['id_subrubro'] = '';
						}

						if (isset($marca)){
							$array_send['id_marca'] = $marca->id;
						} else {
							$array_send['id_marca'] = '';
						}

						if (isset($genero)){
							$array_send['id_genero'] = $genero->id;
						} else {
							$array_send['id_genero'] = '';
						}

						if (isset($origen)){
							$array_send['id_origen'] = $origen->id_pais;
						} else {
							$array_send['id_origen'] = '';
						}
						
						//$request->request->add($array_send);
						$aResult = app('App\Http\Controllers\ProductosController')->storeImportKernel($array_send);
						$aResult = json_decode($aResult->getContent(),true);
						if ($aResult['status'] == 1) {
							$aWarns[] = "El producto no se pudo crear fila {$rowNum}. ".$aResult['msg'][0].". No Importado";
						}else{
							$id_producto = $aResult['id_producto'];
							$aWarns[] = "El producto de la fila {$rowNum} Fue Creado.";

							//CARGAR color, talle y stock
							if(isset($color->id)){
								$colores = Colores::where('id',$color->id)->first();
								if ($colores) {
									$colores->productos()
									->attach($id_producto,['id_talle' => isset($talle)?$talle->id:0,'stock' => $stock_total,'codigo' => $codigo_imp]);

									$att_id = ProductosCodigoStock::select('id')
									->where([
										'id_talle' => isset($talle)?$talle->id:0,
										'stock' => $stock_total,
										'codigo' => $codigo_imp,
										'id_producto' => $id_producto
									])->first();
									//creo el stock por cada sucursal
									foreach ($stock_sucursal_imp as $k => $v) {
										$sucursal = Note::
										select('id_nota as id')
										->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))
										->where('antetitulo', $k)
										->first();
										if($sucursal){
											$stock_sucursal = new SucursalesStock;
											$stock_sucursal->id_codigo_stock = $att_id->id;
											$stock_sucursal->id_sucursal = $sucursal->id;
											$stock_sucursal->stock = $v;
											$stock_sucursal->save();
										}
									}
								}
							}

							//CARGAR PRECIO
							// obtengo la moneda por default
							$moneda_default = Util::getMonedaDefault();
							$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
	
							// Array para guardar el precio del producto
							$array_precio = array(
								'resource_id' => $id_producto,
								'id_moneda' => $id_moneda,
								'precio_venta' => $precio_de_venta_imp,
								'precio_lista' => isset($precio_de_lista_imp)?$precio_de_lista_imp:null
							);											
							//$request->request->add($array_precio);
							$aResult = app('App\Http\Controllers\PreciosRelatedController')->storeImportKernel($array_precio);					
							$aResult = json_decode($aResult->getContent(),true);
							if ($aResult['status'] == 1) {
								$aWarns[] = "El precio no se pudo crear para la fila {$rowNum}. No Importado";
							}
							//update_import
							$update_import = Productos::find($id_producto);
							$update_import->habilitado = 0;
							$update_import->update_import = 1;
							$update_import->save();

						}
					}else{
						$id_producto = $item->id_producto;
						$item = Productos::find($id_producto);
						
						$articulo_imp = ucwords(strtolower(($articulo_imp)));
						$descripcion_imp = ucwords(strtolower(($descripcion_imp)));
						// Se debe actualizar el producto
						$array_send = array(
							'ean' => $ean_imp,
							'sku' => $sku_imp,
							'nombre' => $articulo_imp,											
							'id_rubro' => $item->id_rubro,
							'id_subrubro' => $item->id_subrubro,
							'id_marca' => $item->id_marca,
							'id_genero' => $item->id_genero,
							'alto' => $item->alto,
							'ancho' => $item->ancho,
							'largo' => $item->largo,
							'peso' => $item->peso,
							'orden' => $item->orden,
							'texto' => $item->texto,
							'habilitado' => $item->habilitado
						);
						if($descripcion_imp!=''){
							$array_send['sumario'] = $descripcion_imp;
						}else{
							$array_send['sumario'] = $item->sumario;
						}
						
						//$request->request->add($array_send);
						$aResult = app('App\Http\Controllers\ProductosController')->updateImportKernel($array_send,$item->id);
						$aResult = json_decode($aResult->getContent(),true);
						if ($aResult['status'] == 1) {
							$aWarns[] = "El producto no se pudo actualizar fila {$rowNum}. No Importado";
						}else{
							$aWarns[] = "El producto de la fila {$rowNum} Fue Actualizado.";

							//CARGAR color, talle y stock
							$colores = Colores::where('id',$color->id)->first();
							if ($colores) {
								if(isset($color->id)){
									$att_id = ProductosCodigoStock::select('id')
									->where([
										'id_color' => $color->id,
										'id_talle' => isset($talle)?$talle->id:0,
										'codigo' => $codigo_imp,
										'id_producto' => $item->id
									])->first();
									if($att_id){
										//actualizo el stock
										$att_id->stock = $stock_total;
										$att_id->save();
									}else{
										$colores->productos()
										->attach($item->id,['id_talle' => isset($talle)?$talle->id:0,'stock' => $stock_total,'codigo' => $codigo_imp]);
										$att_id = ProductosCodigoStock::select('id')
										->where([
											'id_color' => $color->id,
											'id_talle' => isset($talle)?$talle->id:0,
											'codigo' => $codigo_imp,
											'id_producto' => $item->id
										])->first();
									}
									$stock_sucursalD = SucursalesStock::
									where('id_codigo_stock', $att_id->id)
									->delete();
									foreach ($stock_sucursal_imp as $k => $v) {
										$sucursal = Note::
										select('id_nota as id')
										->where('id_edicion', \config('appCustom.MOD_SUCURSALES_FILTER'))
										->where('antetitulo', $k)
										->first();
										if($sucursal){
											$stock_sucursal = new SucursalesStock;
											$stock_sucursal->id_codigo_stock = $att_id->id;
											$stock_sucursal->id_sucursal = $sucursal->id;
											$stock_sucursal->stock = $v;
											$stock_sucursal->save();
										}
									}
								}
							}

							//Actualizar PRECIO
							// obtengo la moneda por default
							$moneda_default = Util::getMonedaDefault();
							$id_moneda = ($moneda_default?$moneda_default[0]['id']:1);
	
							// Array para guardar el precio del producto
							$array_precio = array(
								'resource_id' => $id_producto,
								'id_moneda' => $id_moneda,
								'precio_venta' => $precio_de_venta_imp,
								'precio_lista' => isset($precio_de_lista_imp)?$precio_de_lista_imp:null
							);	
							// Obtengo el id del registro en la tabla inv_precios
							$id_precio = PreciosProductos::
							select('id')
							->where('id_moneda','=',$id_moneda)
							->where('id_producto','=',$item->id)
							->first();
							//$request->request->add($array_precio);
							if ($id_precio) {
								$id_precio = $id_precio->id;
								// Si tiene un precio cargado actualizo el valor
								$aResult = app('App\Http\Controllers\PreciosRelatedController')->updateImportKernel($array_precio,$id_precio);
							} else {
								// Si no tiene un precio cargado lo creo
								$aResult = app('App\Http\Controllers\PreciosRelatedController')->storeImportKernel($array_precio);
							}
							$aResult = json_decode($aResult->getContent(),true);
							if ($aResult['status'] == 1) {
								$aWarns[] = "El precio no se pudo actualizar para la fila {$rowNum}. No Importado";
							}
						}
						//update_import
						$item->update_import = 1;
						$item->save();
					}
					$productosActualizados++;
				}else{
					$aWarns[] = "El codigo está mal formateado en la fila {$rowNum}. No Importado";	
				}
			} elseif(!$codigo_imp){
				$aWarns[] = "El codigo está vacía en la fila {$rowNum}. No Importado";
			} elseif(!$articulo_imp) {
				$aWarns[] = "El articulo está vacío en la fila {$rowNum}. No Importado";
			} elseif(!$precio_de_venta_imp) {
				$aWarns[] = "La precio_de_venta está vacía en la fila {$rowNum}. No Importado";
			} elseif(!$rubro_imp) {
				$aWarns[] = "El precio está vacío en la fila {$rowNum}. No Importado";
			} elseif(!$stock_colon_imp || !$stock_yerbabuena_imp || !$stock_plazoleta_imp) {
				$aWarns[] = "El stock está vacío en la fila {$rowNum}. No Importado";
			}
		}

		public static function getLastUpdate() {
			return  
					\App\AppCustom\Models\ProductosImportar::
						select('inv_productos_importar.created_at','a.first_name','a.last_name')
						->orderBy('inv_productos_importar.created_at', 'desc')
						->join('users as a', 'a.id','=','inv_productos_importar.id_usuario')
						->first()
					;
		}
		public static function getNumeracionPrincipal(){
			$numeracion = ConfNumeracion::select('id')->where('principal', 1)->first();
			return $numeracion->id;
		}
		public static function getIdMarca($marca) {
			$getmarca = Marcas::select('id')->where('nombre', 'like', $marca)->first();
			if($getmarca){
				return $getmarca->id;
			}else{
				return false;
			}
		}
		
		public static function getIdGenero($genero) {
			$getgenero = Genero::select('id')->where('genero', 'like', $genero)->first();
			if($getgenero){
				return $getgenero->id;
			}else{
				return false;
			}
		}
		public static function equivalencia_talle($genero,$marca,$talle,$id_rubro){
			if($id_rubro && $talle){
				$id_marca = Util::getIdMarca($marca);
				$id_genero = Util::getIdGenero($genero);
				$id_numeracion = Util::getNumeracionPrincipal();

				$equivalencia = ConfTallesEquivalencia::select('equivalencia')
				->where('id_categoria', $id_rubro)
				->where('talle', $talle);
				if($id_genero){
					$equivalencia = $equivalencia->where('id_genero', $id_genero);
				}
				if($id_marca){
					$equivalencia = $equivalencia->where('id_marca', $id_marca);
				}
				if($id_numeracion){
					$equivalencia = $equivalencia->where('id_numeracion', $id_numeracion);
				}
				$equivalencia = $equivalencia->first();
				if($equivalencia){
					return $equivalencia->equivalencia;
				}else{
					return $talle;
				}
			}
		}	

		public static function categoriasMeli(){
			$cat = Productos::select(\DB::raw("DISTINCT(categoria_meli),id,nombre"))
							->where('categoria_meli','<>','')
							->get();
			
			return $cat;
		}

		public static function categoriasMeliUnique(){
			$cat = Productos::select(\DB::raw("DISTINCT(categoria_meli)"))
							->where('categoria_meli','<>','')
							->where('categoria_meli','<>','Array')
							->get();
			
			return $cat;
		}

		public static function categoriasHeading($idcat){
			$cat = CategoriasMeliValues::select(\DB::raw("DISTINCT(categoria)"))
							->where('id_categoria','=',$idcat)
							->get();
			
			return $cat;
		}

		public static function dataAtriCategorias($idcat,$name){
			$cat = CategoriasMeliValues::select(\DB::raw("name"))
							->where('id_categoria','=',$idcat)
							->where('categoria','=',$name)
							->get();
			
			return $cat;
		}

		public static function getCategorias($idcat){
			
			$cat = ProductosCategMeli::where('idcategoriameli',$idcat)->get();

			return $cat;
		}

		public static function eliminar_espacios($cadena){
		
			 $cadena = str_replace(
				array(" "),
				'_',
				$cadena
			);
		 
			return $cadena;
		}

		public static function eliminar_espacios_inv($cadena){
			$cadena = str_replace(
			   array("-"),
			   " ",
			   $cadena
		   );
		
		   return $cadena;
	   }

		public static function eliminar_tildes($cadena){
 
			//Ahora reemplazamos las letras
			$cadena = str_replace(
				array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä','.'),
				array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A',''),
				$cadena
			);
		 
			$cadena = str_replace(
				array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë','.'),
				array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',''),
				$cadena );
		 
			$cadena = str_replace(
				array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î','.'),
				array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',''),
				$cadena );
		 
			$cadena = str_replace(
				array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','.'),
				array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O',''),
				$cadena );
		 
			$cadena = str_replace(
				array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü','.'),
				array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U',''),
				$cadena );
		 
		    $cadena = str_replace(
				array('ñ', 'Ñ', 'ç', 'Ç','.'),
				array('n', 'N', 'c', 'C',''),
				$cadena
			); 
		
			 $cadena = str_replace(
				array("_"),
				array(' '),
				$cadena
			);
		 
			return $cadena;
		}

		public static function guardarAtribCategorias($idCat,$idProd,$titulo){
			//primero creo las columnas en la tabla
		     $categorias = CategoriasMeli::where('id_meli_categoria',$idCat)->get();
					
				foreach ($categorias as $key) {   
					if (!Schema::hasColumn('inv_productos_categ_meli', $key['categoria']) )
					{  
						//creo las columnas segun categorias
						$categ = $key['categoria'];
						Schema::table('inv_productos_categ_meli', function (Blueprint $table) use($categ) {
							$table->string($categ, 200)->nullable();
						});
					}                             
				}

				
				//segundo guardo los valores por id de producto
				ProductosCategMeli::updateOrCreate(
					['idproducto' => $idProd],
					['idcategoriameli' => $idCat, 'titulo' =>  $titulo]
				);

		}

		public static function getNombreCategMeli($categoria_meli){
			$clientPost = new Client([
				'base_uri' => 'https://api.mercadolibre.com/',
			]);
			
			$response = $clientPost->request('GET', '/categories/'.$categoria_meli);
			$responsePost=$response->getBody()->getContents();
			$verificationId=json_decode($responsePost,true);

			return $verificationId;
		}

		public static function importarFotos($archivo){			
			$codigo = explode("_", $archivo);	
			$nombre = explode(".", $archivo);		
			
			$prod = CodigoStock::select('id_producto','id_color')->where('codigo','like',$codigo[0].'%')->first();
			//\Log::info($codigo[0]);
			

			$fileName = \time();
			$fileName .= '_' . \base64_encode($archivo);
			$fileName .= '.jpg';
			//guardo los datos en img
			if($prod){				
				//configuro el orden segun la letra de la foto
				$letra = explode(".", $codigo[1]);
				switch ($letra[0]) {
					case 'A':
						$orden=1;
						$destacada=1;
						break;

					case 'B':
						$orden=2;
						$destacada=0;
						break;

					case 'C':
						$orden=3;
						$destacada=0;
						break;

					case 'D':
						$orden=4;
						$destacada=0;
						break;
					
					case 'E':
						$orden=5;
						$destacada=0;
						break;
					
					case 'F':
						$orden=6;
						$destacada=0;
						break;
					
					default:
						$orden=7;
						$destacada=0;
						break;
				}

				$img = Image::where('resource_id',$prod->id_producto)->where('imagen',$nombre[0])->where('resource','productos')->first();
				$UPLOADS_BE = '../fe/public/uploads/productos/';
				$UPLOADS_FOTOS = '../fe/public/uploads/importador/';		
				
				if($img){					
						$img_update = Image::where('resource_id',$prod->id_producto)
								->where('imagen',$nombre[0])
								->where('resource','productos')
								->update(['resource'=>'productos',
										'resource_id'=>$prod->id_producto,
										'imagen'=>$nombre[0],
										'imagen_file'=>$img->imagen_file,
										'orden' => $orden,
										'destacada' => $destacada,
										'id_color'=>$prod->id_color,
										'habilitado' =>1]);

										if(!file_exists($UPLOADS_BE.$img->imagen_file) || !file_exists($UPLOADS_BE.'app_'.$img->imagen_file) || !file_exists($UPLOADS_BE.'th_'.$img->imagen_file)){
											if(file_exists($UPLOADS_FOTOS.$archivo)){
												$im = file_get_contents($UPLOADS_FOTOS.$archivo);
												$imdata = 'data:image/png;base64,'.base64_encode($im);
												
												$redim=Util::uploadBase64File1(
													$UPLOADS_BE,
													$img->imagen_file, 
													$imdata,
													0.5
												);
											}
											
										}
															
				}else{					
					//redimensiono
					if(file_exists($UPLOADS_FOTOS.$archivo)){
						$im = file_get_contents($UPLOADS_FOTOS.$archivo);
						$imdata = 'data:image/png;base64,'.base64_encode($im);
						
						//copio lo de la carpeta sync en uploads con otro nombre
						$rutaSync=$UPLOADS_FOTOS.$archivo;
						$rutaUploads=$UPLOADS_BE.$fileName;

						if (file_exists($rutaSync)){
							copy($rutaSync, $rutaUploads);
						}

						$redim=Util::uploadBase64File1(
							$UPLOADS_BE,
							$fileName, 
							$imdata,
							0.5							
						)
						;	
						
						if($redim){												
							$img = new Image();
							$img->resource='productos';
							$img->resource_id=$prod->id_producto;
							$img->imagen=$nombre[0];
							$img->imagen_file=$fileName;
							$img->orden=$orden;
							$img->destacada=$destacada;
							$img->id_color=$prod->id_color;
							$img->habilitado = 1;
							$img->save();	
						}
					}	
				}				
			}
		}
	

		public static function limpiar($cadena){

			$cad = str_replace("\n","",$cadena);
			$cad = str_replace("\t","",$cad);
			return $cad;
		}
	}
