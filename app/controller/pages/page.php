<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page
{
    /**
     * Método responsável por renderizar o topo da página
     * @return string
     */

    private static function getHeader()
    {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o rodapé da página
     * @return string
     */

    private static function getFooter()
    {
        return View::render('pages/footer');
    }

    /**
     * Método responsável por renderizar o layout de paginação
     * @param Request $request
     * @param Pagination $paginationObject
     * @return string
     */
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
                'pages/pagination/link',
                [
                    'page' => $page['page'],
                    'link' => $link,
                    'active' => $page['current'] ? 'active' : ''
                ]
            );
        }

        //Renderiza box de paginação
        return View::render(
            'pages/pagination/box',
            [
                'links' => $links
            ]
        );
    }

    /**
     * Método responsável por retornar o conteúdo (view) da página genérica
     * @param string $title, $content
     * @return string
     */
    public static function getPage($title, $content)
    {
        return View::render(
            'pages/page',
            [
                'title' => $title,
                'header' => self::getHeader(),
                'content' => $content,
                'footer' => self::getFooter()
            ]
        );
    }
}
