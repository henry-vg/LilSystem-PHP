<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Home extends Page
{
    /**
     * Método responsável por renderizar a view de Home do painel de admin
     * @param Request $request
     * @return string
     */
    public static function getHome($request)
    {
        //Conteúdo da home
        $content = View::render('admin/modules/home/index', []);

        //Retorna a página completa
        return parent::getPanel('Home - Admin - Lorem Ipsum', $content, 'home');
    }
}
