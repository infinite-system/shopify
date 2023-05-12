<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shopify\Context;
use Shopify\Exception\MissingArgumentException;
use App\Actions\Shared\ShopifySessionStorage;

class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * @throws MissingArgumentException
     */
    public function boot(): void
    {
        Context::initialize(
            config('shopify.api.key'),
            config('shopify.api.token'),
            [ '*' ],
            config('shopify.store_url'),
            new ShopifySessionStorage(),
            config('shopify.api.version'),
            false,
            true,
        );

    }
}
