<?php

namespace App\Db;

class Pagination
{
    /**
     * Quantidade total de resultados do banco
     * @var integer
     */
    private $total;

    /**
     * Quantidade de páginas
     * @var integer
     */
    private $pages;

    /**
     * Página atual
     * @var integer
     */
    private $currentPage;

    /**
     * Número máximo de registros por página
     * @var integer
     */
    private $limit;

    /**
     * Construtor da classe
     * @param integer $total
     * @param integer $currentPage
     * @param integer $limit
     */
    public function __construct($total, $currentPage = 1, $limit = 10)
    {
        $this->total = $total;
        $this->limit = $limit;
        $this->currentPage = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
        $this->calculate();
    }

    /**
     * Método responsável por retornar a cláusula LIMIT da SQL
     * @return string offset,limit
     */
    public function getLimit()
    {
        $offset = ($this->limit * ($this->currentPage - 1));
        return $offset . ',' . $this->limit;
    }

    /**
     * Método responsável por retornar as opções de páginas disponíveis
     * @return array
     */
    public function getPages()
    {
        //Não retorna páginas
        if ($this->pages == 1) return [];

        //Páginas
        $pages = [];
        for ($i = 1; $i <= $this->pages; $i++) {
            $pages[] = [
                'page' => $i,
                'current' => $i == $this->currentPage
            ];
        }

        return $pages;
    }

    /**
     * Método responsável por calcular a paginação
     */
    private function calculate()
    {
        //Calcula o total de páginas
        $this->pages = $this->total > 0 ? ceil($this->total / $this->limit) : 1;

        //Verifica se a página atual não excede o número de páginas
        $this->currentPage = ($this->currentPage <= $this->pages) ? $this->currentPage : $this->pages;
    }
}
