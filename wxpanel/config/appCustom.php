<?php
return [
	
	'companyDefaultId' => 1,
	
	'logos' => [
		'logoDefault' => 'img/logo.png',
		'logoEmail64Default' => 'img/logoEmail.b64',
		
		'logo' => 'img/logos/%d/logo.png',
		'logoEmail64' => 'img/logos/%d/logoEmail.b64'
	],
	
	'messages' => [
        'unauthorized' => 'No cuenta con el permiso para realizar esta acción',
        'dbError' => 'Error interno de Base de Datos',
        'internalError' => 'Error interno',
        'itemNotFound' => 'El elemento no se ha encontrado (quizá fue deshabilitado o borrado)',
		'wrongRequest' => 'La solicitud es incorrecta',
		'someWarnings' => 'Se encontraron algunas advertencias'
    ],
	
	'UPLOADS_BE' => '../../fe/public/uploads/',
	'PATH_UPLOADS' => \env('FE_URL').'uploads/',
	'UPLOADS_FOTOS' => '../../fe/public/sync/',
	'UPLOADS_BE_USER' => 'uploads/user/',
	
	'PATH_BANNERS' => \env('FE_URL').'/uploads/banners/',
	'UPLOADS_BANNERS' => '../../fe/public/uploads/banners/',
	
	'MOD_WORK_FILTER' => '-2',
	'MOD_SUCURSALES_FILTER' => '-10',
	'MOD_PRODUCT_FILTER' => '-9',
	'MOD_NEWS_FILTER' => '1',
	'MOD_NEWSSLIDER_FILTER' => '2',
	'MOD_COMPANY_FILTER' => '-1',
	'MOD_PEDIDOS_FILTER' => '10',
	'MOD_NEWSLETTER_FILTER' => '-8',
	
	//Enable/Disable global Model log activity
	'modelLogFeature' => true,
	
	'clientRestPrefix' => 'client/rest/',	
	'frontClientRestPrefix' => 'frontClient/rest/',
	'frontClientRestID' => 51,//usuario de front
	
	//default image values
	'image' => [
		'cropSize' => ['w' => 700, 'h' => 700],
		'thumbProportion' => 0.7,
	],
	
	//push
	'GOOGLE_GCM' => [
		'GOOGLE_API_KEY' => 'AIzaSyCJHnaR65v5oyBQX4-VY31ODkg7D95cYiA',
		'GOOGLE_GCM_URL' => 'https://fcm.googleapis.com/fcm/send',
	],
	
	'cookieRestApiWeb' => 'CookieRestApiWeb' .  '_' . \env('APP_NAME'),
	
	'userType' => ['panel' => 1, 'pc' => 5],
	
	'roleType' => ['panel' => 1, 'pc' => 5],
	
	'idInternalFeUser' => 51,
	
	'clientName' => 'Marcantonio Deportes',
	'clientVentas' => 'franco.tacconi@webexport.com.ar',

    'ANDREANI_CLIENTE' => '0012007775',
    'ANDREANI_USUARIO' => 'MARCANTONIO_WS',
    'ANDREANI_PASS' => 'andreani',
    'ANDREANI_AMBIENTE' => 'prod',
    'ANDREANI_URL'=> 'https://api.andreani.com/',

    'TP_API_KEY'=>'TODOPAGO 9ala5516b7044ed198156754593f1b71',
    'TP_KEY'=>'9ala5516b7044ed198156754593f1b71',
    'TP_MERCHANT_ID'=>129824,


	'mercadolibre' => [
		'app_id' => \env('ML_APP_ID'), 
		'app_secret' => \env('ML_APP_SECRET'),
		'app_redirect' => \env('ML_APP_REDIRECT'),
		'app_sideid' => \env('ML_APP_SITEID')
	]
];