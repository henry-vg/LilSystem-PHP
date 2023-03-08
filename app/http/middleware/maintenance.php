<?php

namespace App\Http\Middleware;

use Exception;

class Maintenance
{
    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closeure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //Verifica o estado de manutenção da página
        if (getenv('MAINTENANCE') == 'true') {
            throw new Exception('Page under maintenance, please try again later.', 200);
        }

        //Executa o próximo nível do Middleware
        return $next($request);
    }
}
