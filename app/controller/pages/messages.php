<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Db\Pagination;
//Usa alias por causa do nome igual à esta classe
use \App\Model\Entity\Messages as EntityMessages;

class Messages extends Page
{

    /**
     * Método responsável por obter a renderização dos ítens das mensagens
     * @param Request $request
     * @param Pagination $paginationObject
     * @return string
     */
    private static function getMessageItems($request, &$paginationObject)
    {
        //Mensagens
        $messageItems = '';

        //Quantidade total de registros
        $total = EntityMessages::getMessages(null, null, null, 'COUNT(*) as num')->fetchObject()->num;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de Pagination
        $paginationObject = new Pagination($total, $currentPage, 5);

        //Resultados da página
        $results = EntityMessages::getMessages(null, 'id DESC', $paginationObject->getLimit());

        //Renderiza o item
        while ($messageObject = $results->fetchObject(EntityMessages::class)) {
            //view do item
            $messageItems .= View::render(
                'pages/messages/item',
                [
                    'name' => $messageObject->name,
                    'date' => date('d/m/Y H:i:s', strtotime($messageObject->date)),
                    'message' => $messageObject->message
                ]
            );
        }

        return $messageItems;
    }

    /**
     * Método responsável por retornar o conteúdo (view) da messages
     * @param Request $request
     * @return string
     */
    public static function getMessages($request)
    {
        //view da messages
        $content = View::render(
            'pages/messages',
            [
                'messageItems' => self::getMessageItems($request, $paginationObject),
                'pagination' => parent::getPagination($request, $paginationObject)
            ]
        );

        //retorna a view da página
        return parent::getPage('Messages - Lorem Ipsum', $content);
    }

    /**
     * Método responsável por cadastrar uma mensagem
     * @param Request $request
     * @return string
     */
    public static function insertMessage($request)
    {
        //Dados do post
        $postVars = $request->getPostVars();

        //Nova instância de mensagem
        $messageObject = new EntityMessages;

        //! Validar se os dados vieram com try-catch

        $messageObject->name = $postVars['name'];
        $messageObject->message = $postVars['message'];

        $messageObject->register();

        //Retorna a página de listagem de mensagens
        return self::getMessages($request);
    }
}
