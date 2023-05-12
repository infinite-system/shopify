<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\ProductApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['as' => 'api.'], function() {
    Orion::resource('products', ProductApiController::class)->only('index');
});

Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum']], function() {
    Orion::resource('products', ProductApiController::class)->except(['index']);
});
