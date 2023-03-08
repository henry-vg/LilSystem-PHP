<?php

namespace App\Http\Middleware;

use Exception;

class Queue
{
    /**
     * Mapeamento de middlewares
     * @var array
     */
    private static $map = [];

    /**
     * Mapeamento de middlewares que serão carregados em todas as rotas
     * @var array
     */
    private static $default = [];

    /**
     * Fila de middlewares a serem executados
     * @var array
     */
    private $middlewares = [];

    /**
     * Função de execução do controlador
     * @var Closure
     */
    private $controller;

    /**
     * Argumentos da função do controlador
     * @var array
     */
    private $controllerArgs = [];

    /**
     * Método responsável por construir a classe de fila de middlewares
     * @param array $middlewares
     * @param Closure $controller
     * @param array $controllerArgs
     */
    public function __construct($middlewares, $controller, $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    /**
     * Método responsável por definir o mapeamento de middlewares
     * @param array $map
     */
    public static function setMap($map)
    {
        self::$map = $map;
    }

    /**
     * Método responsável por definir o mapeamento de middlewares padrões
     * @param array $default
     */
    public static function setDefault($default)
    {
        self::$default = $default;
    }

    /**
     * Método responsável por executar o próximo nível da fila de middlewares
     * @param Request $request
     * @return Response
     */
    public function next($request)
    {
        //Verifica se a fila está vazia
        if (empty($this->middlewares)) {

            //? IGNORAR ERRO
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        //Middleware
        $middleware = array_shift($this->middlewares);

        //Verifica o mapeamento
        if (!isset(self::$map[$middleware])) {
            throw new Exception('Error processing requested middleware.', 500);
        }

        //Next
        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        //Executa o Middleware
        return (new self::$map[$middleware])->handle($request, $next);
    }
}
