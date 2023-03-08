<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Messages as EntityMessages;
use \App\Db\Pagination;

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
                'admin/modules/messages/item',
                [
                    'id' => $messageObject->id,
                    'name' => $messageObject->name,
                    'date' => date('d/m/Y H:i:s', strtotime($messageObject->date)),
                    'message' => $messageObject->message
                ]
            );
        }

        return $messageItems;
    }

    /**
     * Método responsável por renderizar a view de listagem de mensagens do admin
     * @param Request $request
     * @return string
     */
    public static function getMessages($request)
    {
        //Conteúdo da messages
        $content = View::render('admin/modules/messages/index', [
            'items' => self::getMessageItems($request, $paginationObject),
            'pagination' => parent::getPagination($request, $paginationObject),
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Messages - Admin - Lorem Ipsum', $content, 'messages');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de uma nova mensagem
     * @param Request $request
     * @return string 
     */
    public static function getNewMessage($request)
    {
        //Conteúdo do formulário
        $content = View::render('admin/modules/messages/form', [
            'title' => 'New Message',
            'name' => '',
            'message' => ''
        ]);

        //Retorna a página completa
        return parent::getPanel('New Message - Admin - Lorem Ipsum', $content, 'messages');
    }

    /**
     * Método responsável por cadastrar uma mensagem no banco
     * @param Request $request
     * @return string 
     */
    public static function setNewMessage($request)
    {
        //Postvars
        $postVars = $request->getPostVars();

        //Nova instância de mensagem
        $messageObject = new EntityMessages;

        $messageObject->name = $postVars['name'] ?? '';
        $messageObject->message = $postVars['message'] ?? '';
        $messageObject->register();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/messages?status=created');
    }

    /**
     * Método responsável por retornar a mensagem de status
     * @param Request $request
     * @return string
     */
    private static function getStatus($request)
    {
        //Query Params
        $queryParams = $request->getQueryParams();

        //Status
        if (!isset($queryParams['status'])) return '';

        //Mensagens de status
        switch ($queryParams['status']) {
            case 'created':
                return Alert::getSuccess('Message created successfully.');
                break;
            case 'changed':
                return Alert::getSuccess('Message updated successfully.');
                break;
            case 'deleted':
                return Alert::getSuccess('Message deleted successfully.');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de uma mensagem
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function getChangeMessage($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            $request->getRouter()->redirect('/admin/messages');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/messages/form', [
            'title' => 'Change Message',
            'name' => $messageObject->name,
            'message' => $messageObject->message
        ]);

        //Retorna a página completa
        return parent::getPanel('Change Message - Admin - Lorem Ipsum', $content, 'messages');
    }

    /**
     * Método responsável por gravar a atualização de uma mensagem
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function setChangeMessage($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            $request->getRouter()->redirect('/admin/messages');
        }

        //Postvars
        $postVars = $request->getPostVars();

        //Atualiza a instância
        $messageObject->name = $postVars['name'] ?? $messageObject->name;
        $messageObject->message = $postVars['message'] ?? $messageObject->message;
        $messageObject->update();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/messages?status=changed');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de uma mensagem
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function getDeleteMessage($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            $request->getRouter()->redirect('/admin/messages');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/messages/delete', [
            'name' => $messageObject->name,
            'message' => $messageObject->message
        ]);

        //Retorna a página completa
        return parent::getPanel('Delete Message - Admin - Lorem Ipsum', $content, 'messages');
    }

    /**
     * Método responsável por excluir uma mensagem
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function setDeleteMessage($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $messageObject = EntityMessages::getMessagesById($id);

        //Valida a instância
        if (!$messageObject instanceof EntityMessages) {
            $request->getRouter()->redirect('/admin/messages');
        }

        //Exclui a mensagem
        $messageObject->delete();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/messages?status=deleted');
    }
}
