<?php

namespace App\Controller\Api;

use \App\Model\Entity\Users as EntityUsers;
use \App\Db\Pagination;

class Users extends Api
{

    /**
     * Método responsável por obter a renderização dos ítens de usuários
     * @param Request $request
     * @param Pagination $paginationObject
     * @return string
     */
    private static function getUserItems($request, &$paginationObject)
    {
        //Mensagens
        $userItems = [];

        //Quantidade total de registros
        $total = EntityUsers::getUsers(null, null, null, 'COUNT(*) as num')->fetchObject()->num;

        //Página atual
        $queryParams = $request->getQueryParams();
        $currentPage = $queryParams['page'] ?? 1;

        //Instância de Pagination
        $paginationObject = new Pagination($total, $currentPage, 5);

        //Resultados da página
        $results = EntityUsers::getUsers(null, 'id ASC', $paginationObject->getLimit());

        //Renderiza o item
        while ($userObject = $results->fetchObject(EntityUsers::class)) {
            //view do item
            $userItems[] = [
                'id' => $userObject->id,
                'name' => $userObject->name,
                'email' => $userObject->email
            ];
        }

        //Retorna os usuários
        return $userItems;
    }
    /**
     * Método responsável por retornar os usuários cadastrados
     * @param Request $request
     * @return array
     */
    public static function getUsers($request)
    {
        return [
            'users' => self::getUserItems($request, $paginationObject),
            'pagination' => parent::getPagination($request, $paginationObject)
        ];
    }

    /**
     * Método responsável por retornar os detalhes de um usuário
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public static function getSingleUser($request, $id)
    {
        //Valida o ID do usuário
        if (!is_numeric($id)) {
            throw new \Exception('ID = \'' . $id . '\' is not valid.', 400);
        }

        //Busca usuário
        $userObject = EntityUsers::getUsersById($id);

        //Valida se o usuário existe
        if (!$userObject instanceof EntityUsers) {
            throw new \Exception('User ' . $id . ' was not found.', 404);
        }

        //Retorna os detalhes do usuário
        return [
            'id' => $userObject->id,
            'name' => $userObject->name,
            'email' => $userObject->email
        ];
    }

    /**
     * Método responsável por retornar o usuário atualmente conectado
     * @param Request $request
     * @return array
     */
    public static function getCurrentUser($request)
    {
        //Usuário atual
        $userObject = $request->user;

        //Retorna os detalhes do usuário
        return [
            'id' => $userObject->id,
            'name' => $userObject->name,
            'email' => $userObject->email
        ];
    }

    /**
     * Método responsável por cadastrar um novo usuário
     * @param Request $request
     */
    public static function setNewUser($request)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if (!isset($postVars['name']) || !isset($postVars['email']) || !isset($postVars['password'])) {
            throw new \Exception('The fields \'name\', \'email\' and \'password\' are required.', 400);
        }

        //Valida a duplicação de usuários
        $userObjectEmail = EntityUsers::getUserByEmail($postVars['email']);
        if ($userObjectEmail instanceof EntityUsers) {
            throw new \Exception('The e-mail \'' . $postVars['email'] . '\' is already being used by another user', 400);
        }

        //Novo usuário
        $userObject = new EntityUsers;
        $userObject->name = $postVars['name'];
        $userObject->email = $postVars['email'];
        $userObject->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $userObject->register();

        //Retorna os detalhes do usuário cadastrado
        return [
            'id' => $userObject->id,
            'name' => $userObject->name,
            'email' => $userObject->email
        ];
    }

    /**
     * Método responsável por atualizar um usuário
     * @param Request $request
     */
    public static function setEditUser($request, $id)
    {
        //Post Vars
        $postVars = $request->getPostVars();

        //Valida os campos obrigatórios
        if (!isset($postVars['name']) || !isset($postVars['email']) || !isset($postVars['password'])) {
            throw new \Exception('The fields \'name\', \'email\' and \'password\' are required.', 400);
        }

        //Busca usuário
        $userObject = EntityUsers::getUsersById($id);

        //Valida se o usuário existe
        if (!$userObject instanceof EntityUsers) {
            throw new \Exception('User ' . $id . ' was not found.', 404);
        }

        //Valida a duplicação de usuários
        $userObjectEmail = EntityUsers::getUserByEmail($postVars['email']);
        if ($userObjectEmail instanceof EntityUsers && $userObjectEmail->id != $userObject->id) {
            throw new \Exception('The e-mail \'' . $postVars['email'] . '\' is already being used by another user', 400);
        }

        //Atualiza o usuário
        $userObject->name = $postVars['name'];
        $userObject->email = $postVars['email'];
        $userObject->password = password_hash($postVars['password'], PASSWORD_DEFAULT);
        $userObject->update();

        //Retorna os detalhes do usuário cadastrado
        return [
            'id' => $userObject->id,
            'name' => $userObject->name,
            'email' => $userObject->email
        ];
    }

    /**
     * Método responsável por deletar um usuário
     * @param Request $request
     */
    public static function setDeleteUser($request, $id)
    {
        //Busca usuário
        $userObject = EntityUsers::getUsersById($id);

        //Valida se o usuário existe
        if (!$userObject instanceof EntityUsers) {
            throw new \Exception('User ' . $id . ' was not found.', 404);
        }

        //Impede a exclusão do próprio cadastro
        if ($userObject->id == $request->user->id) {
            throw new \Exception('It is not possible to delete the currently connected user.', 400);
        }

        //Exclui o usuário
        $userObject->delete();

        //Retorna o sucesso da exclusão
        return [
            'success' => true
        ];
    }
}
