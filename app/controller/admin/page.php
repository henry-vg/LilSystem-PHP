<?php

namespace App\Controller\Admin;

use \App\Utils\View;

class Page
{
    /**
     * Módulos disponíveis no painel
     * @var array
     */
    private static $modules = [
        'home' => [
            'label' => 'Home',
            'link' => URL . '/admin'
        ],
        'messages' => [
            'label' => 'Messages',
            'link' => URL . '/admin/messages'
        ],
        'users' => [
            'label' => 'Users',
            'link' => URL . '/admin/users'
        ]
    ];

    /**
     * Método responsável por retornar o conteúdo da estrutura genérica de página do painel
     * @param string $title
     * @param string $content
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render('admin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }

    /**
     * Método responsável por renderizar a view do menu do painel
     * @param string $currentModule
     * @return string
     */
    private static function getMenu($currentModule)
    {
        //Links do menu
        $links = '';

        //Itera os módulos
        foreach (self::$modules as $hash => $module) {
            $links .= View::render('admin/menu/link', [
                'label' => $module['label'],
                'link' => $module['link'],
                'current_bg' => $hash == $currentModule ? 'bg-secondary' : '',
                'current_text' => $hash == $currentModule ? 'text-light' : '',
            ]);
        }

        //Retorna a renderização do menu
        return View::render('admin/menu/box', [
            'links' => $links
        ]);
    }

    /**
     * Método responsável por renderizar a view do painel de admin com conteúdos dinâmicos
     * @param string $title
     * @param string $content
     * @param string currentModule
     * @return string
     */
    public static function getPanel($title, $content, $currentModule)
    {
        //Renderiza a view do painel
        $contentPanel = View::render('admin/panel', [
            'menu' => self::getMenu($currentModule),
            'content' => $content
        ]);

        //Retorna a página renderizada
        return self::getPage($title, $contentPanel);
    }

    public static function getPagination($request, $paginationObject)
    {
        //Páginas
        $pages = $paginationObject->getPages();

        //Verifica a quantidade de páginas
        if (count($pages) <= 1) return '';

        //Links
        $links = '';

        //URL atual (sem GETS)
        $url = $request->getRouter()->getCurrentUrl();

        //GETS
        $queryParams = $request->getQueryParams();

        //Renderiza os links
        foreach ($pages as $page) {
            //Altera a página
            $queryParams['page'] = $page['page'];

            //Link
            $link = $url . '?' . http_build_query($queryParams);

            //View
            $links .= View::render(
                'admin/pagination/link',
                [
                    'page' => $page['page'],
                    'link' => $link,
                    'active' => $page['current'] ? 'active' : ''
                ]
            );
        }

        //Renderiza box de paginação
        return View::render(
            'admin/pagination/box',
            [
                'links' => $links
            ]
        );
    }
}
