<?php

use \App\Http\Response;
use \App\Controller\Api;

//Rota de listagem de usuários
$routerObject->get('/api/v1/users', [
    'middlewares' => [
        'api',
        'jwt-auth',
        'cache'
    ],
    function ($request) {
        return new Response(200, Api\Users::getUsers($request), 'application/json');
    }
]);

//Rota de consulta do usuário atual
$routerObject->get('/api/v1/users/me', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function ($request) {
        return new Response(200, Api\Users::getCurrentUser($request), 'application/json');
    }
]);

//Rota consulta individual de usuários
$routerObject->get('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth',
        'cache'
    ],
    function ($request, $id) {
        return new Response(200, Api\Users::getSingleUser($request, $id), 'application/json');
    }
]);

//Rota de cadastro de usuários
$routerObject->post('/api/v1/users', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function ($request) {
        return new Response(201, Api\Users::setNewUser($request), 'application/json');
    }
]);

//Rota de atualização de usuários
$routerObject->put('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function ($request, $id) {
        return new Response(200, Api\Users::setEditUser($request, $id), 'application/json');
    }
]);

//Rota de exclusão de usuários
$routerObject->delete('/api/v1/users/{id}', [
    'middlewares' => [
        'api',
        'jwt-auth'
    ],
    function ($request, $id) {
        return new Response(200, Api\Users::setDeleteUser($request, $id), 'application/json');
    }
]);
