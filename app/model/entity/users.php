<?php

namespace App\Model\Entity;

use \App\db\Database;

class Users
{
    /**
     * ID do usuário
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário
     * @var string
     */
    public $name;

    /**
     * E-mail do usuário
     * @var string
     */
    public $email;

    /**
     * Senha do usuário
     * @var string
     */
    public $password;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return bool
     */
    public function register()
    {
        //Insere o usuário no banco de dados
        $this->id = (new Database('users'))->insert([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);

        //Sucesso
        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco com a instância atual
     * @return bool
     */
    public function update()
    {
        //Atualiza o usuário no banco de dados
        return (new Database('users'))->update('id=' . $this->id, [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ]);
    }

    /**
     * Método responsável por deletar um usuário do banco
     * @return bool
     */
    public function delete()
    {
        //Exclui o usuário do banco de dados
        return (new Database('users'))->delete('id=' . $this->id);
    }

    /**
     * Método responsável por retornar um usuário pelo seu ID
     * @param integer $id
     * @return Users
     */
    public static function getUsersById($id)
    {
        return self::getUsers('id = ' . $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar um usuário com base em seu e-mail
     * @param string $email
     * @return Users
     */
    public static function getUserByEmail($email)
    {
        return self::getUsers('email = "' . $email . '"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar usuários
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getUsers($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('users'))->select($where, $order, $limit, $fields);
    }
}
