<?php

namespace App\Http\Middleware;

use \App\Model\Entity\Users;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTAuth
{
    /**
     * Método responsável por retornar uma instância de usuário autenticado
     * @param Request $request
     * @return User
     */
    private function getJWTAuthUser($request)
    {
        //Headers
        $headers = $request->getHeaders();

        //Token puro em JWT
        $jwt = isset($headers['Authorization']) ? str_replace('Bearer ', '', $headers['Authorization']) : '';

        try {
            //Decode
            $decode = (array)JWT::decode($jwt, new Key(getenv('JWT_KEY'), 'HS256'));
        } catch (\Exception $e) {
            throw new \Exception('Invalid Token.', 403);
        }

        //Email
        $email = $decode['email'] ?? '';

        //Busca o usuário pelo e-mail
        $userObject = Users::getUserByEmail($email);

        //Retorna o usuário
        return $userObject instanceof Users ? $userObject : false;
    }

    /**
     * Método responsável por validar o acesso via JWT
     * @param Request $request
     */
    private function auth($request)
    {
        //Verifica o usuário recebido
        if ($userObject = $this->getJWTAuthUser($request)) {
            $request->user = $userObject;
            return true;
        }

        //Emite o erro de senha inválida
        throw new \Exception('Access denied.', 403);
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //Realiza a validação do acesso via JWT
        $this->auth($request);

        //Executa o próximo nível do Middleware
        return $next($request);
    }
}
