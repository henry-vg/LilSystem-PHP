<?php

namespace App\Controller\Api;

use \App\Model\Entity\Messages as EntityMessages;
use \App\Db\Pagination;

class Messages extends Api
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
        $messageItems = [];

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
            $messageItems[] = [
                'id' => $messageObject->id,
                'name' => $messageObject->name,
                'date' => $messageObject->date,
                'message' => $messageObject->message
            ];
        }

        return $messageItems;
    }
    /**
     * Método responsável por retornar as mensagens cadastradas
     * @param Request $request
     * @return array
     */
    public static function getMessages($request)
    {
        return [
            'messages' => self::getMessageItems($request, $paginationObject),
            'pagination' => parent::getPagination($request, $paginationObject)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de uma mensagem
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getSingleMessage($request, $id)
    {
        //Valida o ID da mensagem
        if (!is_numeric($id)) {
            throw new \Exception('ID = \'' . $id . '\' is not valid.', 400);
        }

        //Busca mensagem
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida se a mensagem existe
        if (!$messageObject instanceof EntityMessages) {
            throw new \Exception('Message ' . $id . ' was not found.', 404);
        }

        //Retorna os detalhes da mensagem
        return [
            'id' => $messageObject->id,
            'name' => $messageObject->name,
            'date' => $messageObject->date,
            'message' => $messageObject->message
        ];
    }

    /**
     * Método responsável por cadastrar uma nova mensagem
     * @param Request $request
     */
    public static function setNewMessage($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if (!isset($postVars['name']) || !isset($postVars['message'])) {
            throw new \Exception('The fields \'name\' and \'message\' are required.', 400);
        }

        //Nova mensagem
        $messageObject = new EntityMessages;
        $messageObject->name = $postVars['name'];
        $messageObject->message = $postVars['message'];
        $messageObject->register();

        //Retorna os detalhes da mensagem cadastrada
        return [
            'id' => $messageObject->id,
            'name' => $messageObject->name,
            'date' => $messageObject->date,
            'message' => $messageObject->message
        ];
    }

    /**
     * Método responsável por atualizar uma mensagem
     * @param Request $request
     */
    public static function setEditMessage($request, $id)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if (!isset($postVars['name']) || !isset($postVars['message'])) {
            throw new \Exception('The fields \'name\' and \'message\' are required.', 400);
        }

        //Busca a mensagem no banco
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            throw new \Exception('Message ' . $id . ' was not found.', 404);
        }

        //Atualiza a mensagem
        $messageObject->name = $postVars['name'];
        $messageObject->message = $postVars['message'];
        $messageObject->update();

        //Retorna os detalhes da mensagem atualizada
        return [
            'id' => $messageObject->id,
            'name' => $messageObject->name,
            'date' => $messageObject->date,
            'message' => $messageObject->message
        ];
    }

    /**
     * Método responsável por deletar uma mensagem
     * @param Request $request
     */
    public static function setDeleteMessage($request, $id)
    {
        //Busca a mensagem no banco
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            throw new \Exception('Message ' . $id . ' was not found.', 404);
        }

        //Exclui a mensagem
        $messageObject->delete();

        //Retorna o sucesso da exclusão
        return [
            'success' => true
        ];
    }
}
