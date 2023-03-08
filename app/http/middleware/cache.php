<?php

namespace App\Http\Middleware;

use \App\Utils\Cache\File as CacheFile;

class Cache
{
    /**
     * Método responsável por verificar se a request atual pode ser cacheada
     * @param Request $request
     * @return boolean
     */
    private function isCacheable($request)
    {
        //Valida o tempo de cache
        $cacheTime = getenv('CACHE_TIME');
        if ($cacheTime <= 0 || !is_numeric($cacheTime)) return false;

        //Valida o método da requisição
        if ($request->getHttpMethod() != 'GET') return false;

        //Valida o header de cache
        //?(remover caso não seja desejável que o usuário tenha controle sobre o retorno desse método)
        $headers = $request->getHeaders();
        if (isset($headers['Cache-Control']) && $headers['Cache-Control'] == 'no-cache') return false;

        //Cacheável
        return true;
    }

    /**
     * Método responsável por retornar a hash do cache
     * @param Request $request
     * @return string
     */
    private function getHash($request)
    {
        //URI da rota
        $uri = $request->getRouter()->getUri();

        //Query Params
        $queryParams = $request->getQueryParams();
        $uri .= !empty($queryParams) ? '?' . http_build_query($queryParams) : '';

        //Remove qualquer caracter que não seja alfanumérico e retorna a hash
        return rtrim('route-' . preg_replace('/[^0-9a-zA-Z]/', '-', ltrim($uri, '/')), '-');
    }

    /**
     * Método responsável por executar o middleware
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle($request, $next)
    {
        //Verifica se a request atual é cacheável
        if (!$this->isCacheable($request)) return $next($request);

        //Hash do cache
        $hash = $this->getHash($request);

        //Retorna os dados do cache
        return CacheFile::getCache($hash, getenv('CACHE_TIME'), function () use ($request, $next) {
            return $next($request);
        });
    }
}
