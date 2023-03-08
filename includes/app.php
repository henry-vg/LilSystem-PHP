<?php

require __DIR__ . '/../autoload.php';

use \App\Common\Environment;
use \App\Utils\View;
use \App\db\Database;
use \App\Http\Middleware\Queue as MiddlewareQueue;

//Carrega variáveis de ambiente
Environment::load(__DIR__ . '/../');

//Define a constante de URL
define('URL', getenv('URL'));

//Define as configurações de banco de dados
Database::config(getenv('DB_HOST'), getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASSWORD'));

//Define o valor das variáveis padrão
View::init(
    [
        'URL' => URL
    ]
);

//Define o mapemaento de middlewares
MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Maintenance::class,
    'require-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'require-admin-login' => \App\Http\Middleware\RequireAdminLogin::class,
    'api' => \App\Http\Middleware\Api::class,
    'user-basic-auth' => \App\Http\Middleware\UserBasicAuth::class,
    'jwt-auth' => \App\Http\Middleware\JWTAuth::class,
    'cache' => \App\Http\Middleware\Cache::class
]);

//Define o mapemaento de middlewares padrões (executados em todas as rotas)
MiddlewareQueue::setDefault([
    'maintenance'
]);
