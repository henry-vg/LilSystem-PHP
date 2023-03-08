<?php

use \App\Http\Response;
use \App\Controller\Api;

//Rota de autorização da API
$routerObject->post('/api/v1/auth', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(201, Api\Auth::generateToken($request), 'application/json');
    }
]);
