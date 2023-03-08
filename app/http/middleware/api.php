<?php

namespace App\Http\Middleware;

use Exception;

class Api
{
    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closeure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //Altera o contentType para json
        $request->getRouter()->setContentType('application/json');

        //Executa o próximo nível do Middleware
        return $next($request);
    }
}
