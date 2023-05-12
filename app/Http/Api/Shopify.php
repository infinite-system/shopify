<?php

namespace App\Http\Api;

use Psr\Http\Client\ClientExceptionInterface;
use Shopify\Clients\Graphql;
use Shopify\Clients\Rest;
use Shopify\Exception\MissingArgumentException;
use Shopify\Exception\UninitializedContextException;

class Shopify
{
    /**
     * @throws \Shopify\Exception\MissingArgumentException
     */
    public static function rest(): Rest
    {
        return new Rest(config('shopify.store_url'));
    }

    /**
     * @throws UninitializedContextException
     * @throws ClientExceptionInterface
     * @throws MissingArgumentException
     */
    public static function get(): \Shopify\Clients\HttpResponse {
        return self::rest()->get('/admin/api/' . config('shopify.api.version') . '/'.$location);
    }

    /**
     * @throws UninitializedContextException
     * @throws MissingArgumentException
     * @throws ClientExceptionInterface
     */
    public static function post($location, $data): \Shopify\Clients\HttpResponse {
        return self::rest()->post('/admin/api/' . config('shopify.api.version') . '/'.$location, $data);
    }

    /**
     * @throws UninitializedContextException
     * @throws MissingArgumentException
     * @throws ClientExceptionInterface
     */
    public static function put($location, $data): \Shopify\Clients\HttpResponse {
        return self::rest()->put('/admin/api/' . config('shopify.api.version') . '/'.$location, $data);
    }
}