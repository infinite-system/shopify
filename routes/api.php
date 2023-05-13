<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\ProductWebhooksController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Webhooks API for shopify
Route::match(['GET', 'POST'], 'shopify-webhooks/products/create', [ProductWebhooksController::class, 'create']);
Route::match(['GET', 'POST'], 'shopify-webhooks/products/update', [ProductWebhooksController::class, 'update']);

// Public API endpoint to list products
Route::group(['as' => 'api.'], function() {
    Orion::resource('products', ProductApiController::class)->only('index');
});

// Private Authorized API endpoints for product CRUD
Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum']], function() {
    Orion::resource('products', ProductApiController::class)->except(['index']);
});
