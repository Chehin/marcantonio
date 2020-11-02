<?php

namespace App\Http\Controllers\Fe;

use Illuminate\Http\Request;
use App\AppCustom\Util;
use App\Http\Controllers\Controller;

class ContactoController extends Controller
{
	public function index(Request $request)
    {
        $aResult = Util::getDefaultArrayResult();
		$contacto = array(
			'nombre' => $request->input('nombre'),
			'email' => $request->input('email'),
			'telefono' => $request->input('telefono'),
			'mensaje' => $request->input('mensaje')
		);
		if(\Mail::send('email.contacto', $contacto, function($message){
			$message->to('matias@webexport.com.ar')->subject('Nuevo contacto - Marcantonio Deportes');
        })){
			$aResult['data']['status'] = 'success';
            $aResult['data']['msg'] = 'CONTACTO_EXITO';//lang
        } else {
            $aResult['data']['status'] = 'danger';
            $aResult['data']['msg'] = 'CONTACTO_ERROR';//lang
        }
        return response()->json($aResult);
    }
}