<?php

require __DIR__ . '/includes/app.php';

use \App\Http\Router;

//Inicia o Router
$routerObject = new Router(URL);

//Inclui as rotas de páginas
include __DIR__ . "/routes/pages.php";

//Inclui as rotas do painel
include __DIR__ . "/routes/admin.php";

//Inclui as rotas da api
include __DIR__ . "/routes/api.php";

//Imprime o Response da rota
$routerObject->run()->sendResponse();
