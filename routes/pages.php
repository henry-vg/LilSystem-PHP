<?php

use \App\Http\Response;
use \App\Controller\Pages;

//Rota Home
$routerObject->get(
    '/',
    [
        'middlewares' => [
            'cache'
        ],
        function () {
            return new Response(200, Pages\Home::getHome());
        }
    ]
);

//Rota Sobre
$routerObject->get(
    '/about',
    [
        'middlewares' => [
            'cache'
        ],
        function () {
            return new Response(200, Pages\About::getAbout());
        }
    ]
);

//Rota Messages
$routerObject->get(
    '/messages',
    [
        'middlewares' => [
            'cache'
        ],
        function ($request) {
            return new Response(200, Pages\Messages::getMessages($request));
        }
    ]
);

//Rota Messages (POST)
$routerObject->post(
    '/messages',
    [
        function ($request) {
            return new Response(200, Pages\Messages::insertMessage($request));
        }
    ]
);
