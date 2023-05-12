<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisteredUserController;
use \App\Models\Product;


use \App\Http\Api\Shopify;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('products');
});

// Extend default register controller to get plain token text
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest:' . config('fortify.guard')]);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {

    Route::get('/dashboard', function () { return view('dashboard'); })
        ->name('dashboard');

    Route::get('/products', function () {
        $products = Product::query()->orderBy('updated_at', 'desc')->paginate(
            $perPage = 15, $columns = ['*'], $pageName = 'p'
        );
        return view('products', ['products' => $products]);
    })
        ->name('products');

    Route::get('/products/add', function () { return view('products.add'); })
        ->name('products.add');

    Route::get('/products/edit/{id}', function ($id) {
        $product = Product::whereId($id)->first();
        return view('products.edit', ['product' => $product]);
    })->name('products.edit');

});
