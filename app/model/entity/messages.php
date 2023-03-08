<?php

namespace App\Model\Entity;

use \App\db\Database;

class Messages
{
    /**
     * ID da mensagem
     * @var integer
     */
    public $id;

    /**
     * Nome do usuário que fez a mensagem
     * @var string
     */
    public $name;

    /**
     * Corpo da mensagem
     * @var string
     */
    public $message;

    /**
     * Data de publicação da mensagem
     * @var string
     */
    public $date;

    /**
     * Método responsável por cadastrar a instância atual no banco de dados
     * @return bool
     */
    public function register()
    {
        //define o fuso horário do projeto (detectar o fuso do usuário com javascript de forma dinâmica)
        date_default_timezone_set('America/Sao_Paulo');

        //Define a data
        $this->date = date('Y-m-d H:i:s');

        //Insere a mensagem no banco de dados
        $this->id = (new Database('messages'))->insert([
            'name' => $this->name,
            'message' => $this->message,
            'date' => $this->date
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
        //Atualiza a mensagem no banco de dados
        return (new Database('messages'))->update('id=' . $this->id, [
            'name' => $this->name,
            'message' => $this->message
        ]);
    }

    /**
     * Método responsável por deletar uma mensagem do banco
     * @return bool
     */
    public function delete()
    {
        //Exclui a mensagem do banco de dados
        return (new Database('messages'))->delete('id=' . $this->id);
    }

    /**
     * Método responsável por retornar uma mensagem pelo seu ID
     * @param integer $id
     * @return Messages
     */
    public static function getMessagesById($id)
    {
        return self::getMessages('id = ' . $id)->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar mensagens
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getMessages($where = null, $order = null, $limit = null, $fields = '*')
    {
        return (new Database('messages'))->select($where, $order, $limit, $fields);
    }
}
