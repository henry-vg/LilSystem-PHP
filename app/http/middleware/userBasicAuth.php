<?php

namespace App\Http\Middleware;

use \App\Model\Entity\Users;

class UserBasicAuth
{
    /**
     * Método responsável por retornar uma instância de usuário autenticado
     * @return User
     */
    private function getBasicAuthUser()
    {
        //Verifica a existência dos dados de acesso
        if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
            return false;
        }

        //Busca o usuário pelo e-mail
        $userObject = Users::getUserByEmail($_SERVER['PHP_AUTH_USER']);

        //Verifica a instância
        if (!$userObject instanceof Users) {
            return false;
        }

        //Valida a senha e retorna o usuário
        return password_verify($_SERVER['PHP_AUTH_PW'], $userObject->password) ? $userObject : false;
    }

    /**
     * Método responsável por validar o acesso via Basic Auth
     * @param Request $request
     */
    private function basicAuth($request)
    {
        //Verifica o usuário recebido
        if ($userObject = $this->getBasicAuthUser()) {
            $request->user = $userObject;
            return true;
        }

        //Emite o erro de senha inválida
        throw new \Exception('Email or password invalid.', 403);
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closeure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //Realiza a validação do acesso via Basic Auth
        $this->basicAuth($request);

        //Executa o próximo nível do Middleware
        return $next($request);
    }
}
