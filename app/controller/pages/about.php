<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page
{
    /**
     * Método responsável por retornar o conteúdo (view) da about
     * @return string
     */
    public static function getAbout()
    {
        $organizationObject = new Organization;
        //view da about
        $content = View::render(
            'pages/about',
            [
                'name' => $organizationObject->name,
                'description' => $organizationObject->description,
                'site' => $organizationObject->site
            ]
        );

        //retorna a view da página
        return parent::getPage('About - Lorem Ipsum', $content);
    }
}
