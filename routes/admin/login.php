<?php

use \App\Http\Response;
use \App\Controller\Admin;

//Rota Login
$routerObject->get(
    '/admin/login',
    [
        'middlewares' => [
            'require-admin-logout'
        ],
        function ($request) {
            return new Response(200, Admin\Login::getLogin($request));
        }
    ]
);

//Rota Login (POST)
$routerObject->post(
    '/admin/login',
    [
        'middlewares' => [
            'require-admin-logout'
        ],
        function ($request) {
            return new Response(200, Admin\Login::setLogin($request));
        }
    ]
);

//Rota Logout
$routerObject->get(
    '/admin/logout',
    [
        'middlewares' => [
            'require-admin-login'
        ],
        function ($request) {
            return new Response(200, Admin\Login::setLogout($request));
        }
    ]
);
