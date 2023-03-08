<?php

namespace App\db;

use \PDO;
use \PDOException;

class Database
{
    /**
     * Nome da tabela a ser manipulada
     * @var string
     */
    private $table;

    /**
     * Nome do host
     * @var string
     */
    private static $dbHost;

    /**
     * Nome do banco de dados
     * @var string
     */
    private static $dbName;

    /**
     * Nome do usuário
     * @var string
     */
    private static $dbUser;

    /**
     * Senha de acesso
     * @var string
     */
    private static $dbPassword;

    /**
     * Instância de conexão com banco de dados
     * @var PDO
     */
    private $connection;

    /**
     * Método responsável por definir a tabela e instanciar a conexão
     * @param string $table
     */
    public function __construct($table = null)
    {
        $this->table = $table;
        $this->setConnection();
    }

    /**
     * Método responsável por definir os dados estáticos para conexão com o banco de dados
     * @param string $dbHost 
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPassword
     */
    public static function config($dbHost, $dbName, $dbUser, $dbPassword)
    {
        self::$dbHost = $dbHost;
        self::$dbName = $dbName;
        self::$dbUser = $dbUser;
        self::$dbPassword = $dbPassword;
    }

    /**
     * Método responsável por criar uma conexão com o banco de dados
     */
    private function setConnection()
    {
        try {
            //Monta o DSN
            $dsn = 'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName;

            //Estabelece a instância de PDO e a conexão
            $this->connection = new PDO($dsn, self::$dbUser, self::$dbPassword);

            //Configura o PDO para, no caso de erro, lançar uma exception (por padrão, lançaria apenas um warning)
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
            //! gravar a mensagem real no log e mostrar mensagem amigável ao usuário
        }
    }

    /**
     * Método responsável por executar queries dentro do banco de dados
     * @param string $query
     * @param array $params
     * @return PDOStatement
     */
    public function execute($query, $params = [])
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);

            return $statement;
        } catch (PDOException $e) {
            die('ERROR: ' . $e->getMessage());
            //! gravar a mensagem real no log e mostrar mensagem amigável ao usuário
        }
    }

    /**
     * Método responsável por inserir dados no banco
     * @param array $values [field => value]
     * @return integer ID inserido
     */
    public function insert($values)
    {
        //Dados da query
        $fields = array_keys($values);
        $binds = array_fill(0, count($fields), '?');

        //Monta a query
        $query = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';

        //Executa o insert
        $this->execute($query, array_values($values));

        //Retorna o ID inserido
        return $this->connection->lastInsertId();
    }

    /**
     * Método responsável por consultar os dados do banco
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public function select($where = null, $order = null, $limit = null, $fields = '*')
    {
        //Dados da query
        $where = strlen($where) ? 'WHERE ' . $where . ' ' : '';
        $order = strlen($order) ? 'ORDER BY ' . $order . ' ' : '';
        $limit = strlen($limit) ? 'LIMIT ' . $limit . ' ' : '';

        //Monta a query
        $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . $order . $limit;

        //Executa a query
        return $this->execute($query);
    }

    /**
     * Método responsável por executar atualizações no banco de dados
     * @param string $where
     * @param array $values [field => value]
     * @return boolean
     */
    public function update($where, $values)
    {
        //Dados da query
        $fields = array_keys($values);

        //Monta a query
        $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;

        //Executa a query
        $this->execute($query, array_values($values));

        //Sucesso
        return true;
    }

    /**
     * Método responsável por excluir dados do banco
     * @param string $where
     * @return boolean
     */
    public function delete($where)
    {
        //Monta a query
        $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;

        //Executa a query
        $this->execute($query);

        //Sucesso
        return true;
    }
}
