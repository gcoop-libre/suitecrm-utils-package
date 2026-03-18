<?php

namespace Gcoop\Cache;

use Psr\Http\Message\RequestInterface;
use Kevinrob\GuzzleCache\KeyValueHttpHeader;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;

class GcoopGreedyCacheStrategy extends GreedyCacheStrategy
{
    /**
     * Este método genera nuestro propio hash para la key
     * teniendo en cuenta el body del request.
     * Si en la config del ws, dentro de la key headers, está definida la key cache_key_filter
     * con los campos a remover de la uri, los mismos no se tiene en cuenta
     * para la generación del hash
     * Ej.:
     *      'headers' => [
     *          'Host' => 'sesb2303ax.bancocredicoop.coop',
     *          'Accept' => 'application/json',
     *          'cache_key_filter' => 'horaTx,secuencia'
     *      ],
     */
    protected function getCacheKey(RequestInterface $request, KeyValueHttpHeader $varyHeaders = null)
    {
        $uri = $request->getUri();

        if (!empty($request->getHeader('cache_key_filter'))) {

            $filter = $request->getHeader('cache_key_filter')[0];

            foreach (explode(',', $filter) as $remove) {
                $uri = $uri->withoutQueryValue($uri,$remove);
            }
        }

        return hash(
            'sha256',
            'greedy'.$request->getMethod().$uri.$request->getBody()
        );
    }
}
