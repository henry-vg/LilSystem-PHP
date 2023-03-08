<?php

use \App\Http\Response;
use \App\Controller\Admin;

//Rota Admin de listagem de usuários
$routerObject->get(
    '/admin/users',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Users::getUsers($request));
        }
    ]
);

//Rota Admin de cadastro de um novo usuário
$routerObject->get(
    '/admin/users/new',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Users::getNewUser($request));
        }
    ]
);

//Rota Admin de cadastro de um novo usuário (POST)
$routerObject->post(
    '/admin/users/new',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Users::setNewUser($request));
        }
    ]
);

//Rota Admin de edição de usuário
$routerObject->get(
    '/admin/users/{id}/change',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Users::getChangeUser($request, $id));
        }
    ]
);

//Rota Admin de edição de usuário (POST)
$routerObject->post(
    '/admin/users/{id}/change',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Users::setChangeUser($request, $id));
        }
    ]
);

//Rota Admin de exclusão de usuário
$routerObject->get(
    '/admin/users/{id}/delete',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Users::getDeleteUser($request, $id));
        }
    ]
);

//Rota Admin de exclusão de usuário (POST)
$routerObject->post(
    '/admin/users/{id}/delete',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Users::setDeleteUser($request, $id));
        }
    ]
);
