<?php

namespace App\Controller\Admin;

use \App\Utils\View;
use \App\Model\Entity\Users as EntityUsers;
use \App\Db\Pagination;

class Users extends Page
{
    /**
     * Método responsável por obter a renderização dos ítens de usuários
     * @param Request $request
     * @param Pagination $paginationObject
     * @return string
     */
    private static function getUserItems($request, &$paginationObject)
    {
        //Usuários
        $userItems = '';

        //Quantidade total de registros
        $total = EntityUsers::getUsers(null, null, null, 'COUNT(*) as num')->fetchObject()->num;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de paginação
        $paginationObject = new Pagination($total, $currentPage, 5);

        //Resultados da página
        $results = EntityUsers::getUsers(null, 'id DESC', $paginationObject->getLimit());

        //Renderiza o item
        while ($userObject = $results->fetchObject(EntityUsers::class)) {
            //view do item
            $userItems .= View::render(
                'admin/modules/users/item',
                [
                    'id' => $userObject->id,
                    'name' => $userObject->name,
                    'email' => $userObject->email
                ]
            );
        }

        return $userItems;
    }

    /**
     * Método responsável por renderizar a view de listagem de usuários do admin
     * @param Request $request
     * @return string
     */
    public static function getUsers($request)
    {
        //Conteúdo da messages
        $content = View::render('admin/modules/users/index', [
            'items' => self::getUserItems($request, $paginationObject),
            'pagination' => parent::getPagination($request, $paginationObject),
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Users - Admin - Lorem Ipsum', $content, 'users');
    }

    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuário
     * @param Request $request
     * @return string 
     */
    public static function getNewUser($request)
    {
        //Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'New User',
            'name' => '',
            'email' => '',
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('New User - Admin - Lorem Ipsum', $content, 'users');
    }

    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $request
     * @return string 
     */
    public static function setNewUser($request)
    {
        //Postvars
        $postVars = $request->getPostVars();

        $email = $postVars['email'] ?? '';
        $name = $postVars['name'] ?? '';
        $password = $postVars['password'] ?? '';

        //Valida o e-mail do usuário

        $userObject = EntityUsers::getUserByEmail($email);

        if ($userObject instanceof EntityUsers) {
            //Redireciona o usuário
            $request->getRouter()->redirect('/admin/users/new?status=duplicated');
        }

        //Nova instância de usuário
        $userObject = new EntityUsers;
        $userObject->name = $name;
        $userObject->email = $email;
        $userObject->password = password_hash($password, PASSWORD_DEFAULT);

        $userObject->register();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/users?status=created');
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
                return Alert::getSuccess('User created successfully.');
                break;
            case 'changed':
                return Alert::getSuccess('User updated successfully.');
                break;
            case 'deleted':
                return Alert::getSuccess('User deleted successfully.');
                break;
            case 'duplicated':
                return Alert::getError('The e-mail is already being used by another user.');
                break;
        }
    }

    /**
     * Método responsável por retornar o formulário de edição de um usuário
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function getChangeUser($request, $id)
    {
        //Obtém o usuário do banco de dados
        $userObject = EntityUsers::getUsersById($id);

        //Valida a instância
        if (!$userObject instanceof EntityUsers) {
            $request->getRouter()->redirect('/admin/users');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/users/form', [
            'title' => 'Change User',
            'name' => $userObject->name,
            'email' => $userObject->email,
            'status' => self::getStatus($request)
        ]);

        //Retorna a página completa
        return parent::getPanel('Change User - Admin - Lorem Ipsum', $content, 'users');
    }

    /**
     * Método responsável por gravar a atualização de um usuário
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function setChangeUser($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $userObject = EntityUsers::getUsersById($id);

        //Valida a instância
        if (!$userObject instanceof EntityUsers) {
            $request->getRouter()->redirect('/admin/users');
        }

        //Postvars
        $postVars = $request->getPostVars();

        $name = $postVars['name'] ?? '';
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        //Valida o e-mail do usuário
        $userObjectEmail = EntityUsers::getUserByEmail($email);

        if ($userObjectEmail instanceof EntityUsers && $userObjectEmail->id != $id) {
            //Redireciona o usuário
            $request->getRouter()->redirect('/admin/users/' . $id . '/change?status=duplicated');
        }

        //Atualiza a instância
        $userObject->name = $name;
        $userObject->email = $email;
        $userObject->password = password_hash($password, PASSWORD_DEFAULT);
        $userObject->update();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/users?status=changed');
    }

    /**
     * Método responsável por retornar o formulário de exclusão de um usuário
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function getDeleteUser($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $userObject = EntityUsers::getUsersById($id);

        //Valida a instância
        if (!$userObject instanceof EntityUsers) {
            $request->getRouter()->redirect('/admin/users');
        }

        //Conteúdo do formulário
        $content = View::render('admin/modules/users/delete', [
            'name' => $userObject->name,
            'email' => $userObject->email
        ]);

        //Retorna a página completa
        return parent::getPanel('Delete User - Admin - Lorem Ipsum', $content, 'users');
    }

    /**
     * Método responsável por excluir um usuário
     * @param Request $request
     * @param integer $id
     * @return string 
     */
    public static function setDeleteUser($request, $id)
    {
        //Obtém a mensagem do banco de dados
        $userObject = EntityUsers::getUsersById($id);

        //Valida a instância
        if (!$userObject instanceof EntityUsers) {
            $request->getRouter()->redirect('/admin/users');
        }

        //Exclui o usuário
        $userObject->delete();

        //Redireciona o usuário
        $request->getRouter()->redirect('/admin/users?status=deleted');
    }
}
