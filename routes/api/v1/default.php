<?php

use \App\Http\Response;
use \App\Controller\Api;

//Rota raíz da API
$routerObject->get('/api/v1', [
    'middlewares' => [
        'api'
    ],
    function ($request) {
        return new Response(200, Api\Api::getDetails($request), 'application/json');
    }
]);
