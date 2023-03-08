<?php

namespace App\Controller\Api;

use \App\Model\Entity\Users;
use \Firebase\JWT\JWT;

class Auth extends Api
{
    /**
     * Método responsável por gerar um token JWT
     * @param Request $request
     * @return array
     */
    public static function generateToken($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if (!isset($postVars['email']) || !isset($postVars['password'])) {
            throw new \Exception('The fields \'email\' and \'password\' are required.', 400);
        }

        //Busca o usuário pelo email
        $userObject = Users::getUserByEmail($postVars['email']);

        //Valida o email do usuário
        if (!$userObject instanceof Users) {
            throw new \Exception('Invalid email.', 400);
        }

        //Valida a senha do usuário
        if (!password_verify($postVars['password'], $userObject->password)) {
            throw new \Exception('Invalid password.', 400);
        }

        //Payload
        $payload = [
            'email' => $userObject->email
        ];

        //Retorna o token gerado
        return [
            'token' => JWT::encode($payload, getenv('JWT_KEY'), 'HS256')
        ];
    }
}
