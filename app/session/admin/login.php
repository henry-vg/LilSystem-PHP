<?php

namespace App\Session\Admin;

class Login
{
    /**
     * Método responsável por iniciar a sessão
     */
    private static function init()
    {
        //Verifica se a sessão não está ativa
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Método responsável por criar o login do usuário
     * @param Users $userObject
     * @return boolean
     */
    public static function login($userObject)
    {
        //Inicia a sessão
        self::init();

        //Define a sessão do usuário
        $_SESSION['admin']['user'] = [
            'id' => $userObject->id,
            'name' => $userObject->name,
            'email' => $userObject->email
        ];

        //Successo
        return true;
    }

    /**
     * Método responsável por verificar se o usuário está logado
     * @return boolean
     * 
     */
    public static function isLogged()
    {
        //Inicia a sessão
        self::init();

        //Retorna a verificação
        return isset($_SESSION['admin']['user']['id']);
    }

    /**
     * Método responsável por deslogar o usuário
     * @return boolean
     */
    public static function logout()
    {
        //Inicia a sessão
        self::init();

        //Desloga o usuário
        unset($_SESSION['admin']['user']);

        //Sucesso
        return true;
    }
}
