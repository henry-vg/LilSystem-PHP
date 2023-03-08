<?php

namespace App\Http;

class Request
{
    /**
     * Instância do Router
     * @var Router
     */
    private $router;

    /**
     * Método HTTP da requisição
     * @var string
     */
    private $httpMethod;

    /**
     * URI da página
     * @var string
     */
    private $uri;

    /**
     * Parâmetros da URL ($_GET)
     * @var array
     */
    private $queryParams = [];

    /**
     * Variáveis recebidas por POST ($_POST)
     * @var array
     */
    private $postVars = [];

    /**
     * Cabeçalho da requisição
     * @var array
     */
    private $headers = [];

    /**
     * Construtor da classe
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->queryParams = $_GET ?? [];
        $this->headers = getallheaders();
        $this->setUri();
        $this->setPostVars();
    }

    /**
     * Método responsável por definir a URI
     */
    private function setUri()
    {
        //URI completa (com GETS)
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';

        //Remove GETS da URI
        $xUri = explode('?', $this->uri);
        $this->uri = $xUri[0];
    }

    /**
     * Método responsável por definir as variáveis do POST
     */
    private function setPostVars()
    {
        //Verifica o método da requisição
        if ($this->httpMethod == 'GET') return false;

        //Post padrão
        $this->postVars = $_POST ?? [];

        //Post Json
        $inputRaw = file_get_contents('php://input');
        $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;
    }

    /**
     * Método responsável por retornar a instância de Router
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Método responsável por retornar o método HTTP da requisição
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * Método responsável por retornar a URI da página
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Método responsável por retornar os parâmetros da URL ($_GET)
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Método responsável por retornar as variáveis recebidas por POST ($_POST)
     * @return array
     */
    public function getPostVars()
    {
        return $this->postVars;
    }

    /**
     * Método responsável por retornar o cabeçalho da requisição
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
