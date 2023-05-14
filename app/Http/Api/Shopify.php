<?php

namespace App\Http\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shopify\Clients\Graphql;
use Shopify\Clients\HttpResponse;
use Shopify\Clients\Rest;
use Shopify\Exception\MissingArgumentException;
use Shopify\Exception\UninitializedContextException;

class Shopify
{
    /**
     * @throws MissingArgumentException
     */
    public static function rest(): Rest
    {
        return new Rest(config('shopify.store_url'));
    }

    /**
     * @param $location
     * @return HttpResponse
     * @throws ClientExceptionInterface
     * @throws MissingArgumentException
     * @throws UninitializedContextException
     */
    public static function get($location): HttpResponse {
        return self::rest()->get('/admin/api/' . config('shopify.api.version') . '/'.$location);
    }

    /**
     * @param $location
     * @param $data
     * @return HttpResponse
     * @throws ClientExceptionInterface
     * @throws MissingArgumentException
     * @throws UninitializedContextException
     */
    public static function post($location, $data): HttpResponse {
        return self::rest()->post('/admin/api/' . config('shopify.api.version') . '/'.$location, $data);
    }

    /**
     * @param $location
     * @param $data
     * @return HttpResponse
     * @throws ClientExceptionInterface
     * @throws MissingArgumentException
     * @throws UninitializedContextException
     */
    public static function put($location, $data): HttpResponse {
        return self::rest()->put('/admin/api/' . config('shopify.api.version') . '/'.$location, $data);
    }

    /**
     * @param $location
     * @return HttpResponse
     * @throws ClientExceptionInterface
     * @throws MissingArgumentException
     * @throws UninitializedContextException
     */
    public static function delete($location): HttpResponse {
        return self::rest()->delete('/admin/api/' . config('shopify.api.version') . '/'.$location);
    }
}