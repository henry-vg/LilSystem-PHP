<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Users;
use \App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page
{
    /**
     * Método responsável por retornar a renderização da página de login
     * @param Request $request
     * @param string $errorMessage
     * @return string
     */
    public static function getLogin($request, $errorMessage = null)
    {
        //Status
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

        //Conteúdo da página de login
        $content = View::render('admin/login', [
            'status' => $status
        ]);

        //Retorna a página completa
        return parent::getPage('Login - Lorem Ipsum', $content);
    }

    /** Método responsável por definir o login do usuário
     * @param Request $request
     */
    public static function setLogin($request)
    {
        //Variáveis do post
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';
        //! Jogar erro se tiver vazio?

        //Busca o usuário pelo e-mail
        $userObject = Users::getUserByEmail($email);

        if (!$userObject instanceof Users) {
            return self::getLogin($request, 'Invalid email.');
        }

        //Verifica a senha do usuário
        if (!password_verify($password, $userObject->password)) {
            return self::getLogin($request, 'Invalid password.');
        }

        //Cria a sessão de login
        SessionAdminLogin::login($userObject);

        //Redireciona o usuário para a home do admin
        $request->getRouter()->redirect('/admin');
    }

    /**
     * Método responsável por deslogar o usuário
     * @param Request $request
     */
    public static function setLogout($request)
    {
        //Destrói a sessão de login
        SessionAdminLogin::logout();

        //Redireciona o usuário para a página de login do admin
        $request->getRouter()->redirect('/admin/login');
    }
}
