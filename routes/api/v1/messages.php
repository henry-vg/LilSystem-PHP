<?php

use \App\Http\Response;
use \App\Controller\Api;

//Rota messages
$routerObject->get('/api/v1/messages', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function ($request) {
        return new Response(200, Api\Messages::getMessages($request), 'application/json');
    }
]);

//Rota consulta individual de mensagens
$routerObject->get('/api/v1/messages/{id}', [
    'middlewares' => [
        'api',
        'cache'
    ],
    function ($request, $id) {
        return new Response(200, Api\Messages::getSingleMessage($request, $id), 'application/json');
    }
]);

//Rota de cadastro de mensagens
$routerObject->post('/api/v1/messages', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request) {
        return new Response(201, Api\Messages::setNewMessage($request), 'application/json');
    }
]);

//Rota de atualização de mensagens
$routerObject->put('/api/v1/messages/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, Api\Messages::setEditMessage($request, $id), 'application/json');
    }
]);

//Rota de exclusão de mensagens
$routerObject->delete('/api/v1/messages/{id}', [
    'middlewares' => [
        'api',
        'user-basic-auth'
    ],
    function ($request, $id) {
        return new Response(200, Api\Messages::setDeleteMessage($request, $id), 'application/json');
    }
]);
