<?php

use \App\Http\Response;
use \App\Controller\Admin;

//Rota Admin de listagem de mensagens
$routerObject->get(
    '/admin/messages',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Messages::getMessages($request));
        }
    ]
);

//Rota Admin de cadastro de uma nova mensagem
$routerObject->get(
    '/admin/messages/new',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Messages::getNewMessage($request));
        }
    ]
);

//Rota Admin de cadastro de uma nova mensagem (POST)
$routerObject->post(
    '/admin/messages/new',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Messages::setNewMessage($request));
        }
    ]
);

//Rota Admin de edição de mensagem
$routerObject->get(
    '/admin/messages/{id}/change',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Messages::getChangeMessage($request, $id));
        }
    ]
);

//Rota Admin de edição de mensagem (POST)
$routerObject->post(
    '/admin/messages/{id}/change',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Messages::setChangeMessage($request, $id));
        }
    ]
);

//Rota Admin de exclusão de mensagem
$routerObject->get(
    '/admin/messages/{id}/delete',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Messages::getDeleteMessage($request, $id));
        }
    ]
);

//Rota Admin de exclusão de mensagem (POST)
$routerObject->post(
    '/admin/messages/{id}/delete',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request, $id) {
            return new Response(200, Admin\Messages::setDeleteMessage($request, $id));
        }
    ]
);
