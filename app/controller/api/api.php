<?php

namespace App\Controller\Api;

class Api
{
    /**
     * Método responsável por retornar os detalhes da API
     * @param Request $request
     * @return array
     */
    public static function getDetails($request)
    {
        return [
            'name' => 'Lorem Ipsum',
            'version' => 'v1.0.0',
            'author' => 'Henry-vg',
            'email' => 'hvg.henry@gmail.com'
        ];
    }

    /**
     * Método responsável por retornar os detalhes da paginação
     * @param Request $request
     * @param Pagination $paginationObject
     * @return array
     */
    protected static function getPagination($request, $paginationObject)
    {
        //Query Params
        $queryParams = $request->getQueryParams();

        //Páginas
        $pages = $paginationObject->getPages();

        //Retorno
        return [
            'currentPage' => isset($queryParams['page']) ? $queryParams['page'] : 1,
            'numPages' => !empty($pages) ? count($pages) : 1
        ];
    }
}
