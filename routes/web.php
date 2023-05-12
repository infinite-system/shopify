<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Auth\RegisteredUserController;

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
    return view('welcome');
});

Route::get('/products/public/add', function () {
    return view('products.public.add');
})->name('products.public.add');

// Extend default register controller to get plain token text
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest:'.config('fortify.guard')]);


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })
        ->name('dashboard');

    Route::get('/products', function () { return view('products'); })
        ->name('products');
    Route::get('/products/add', function () { return view('products.add'); })
        ->name('products.add');

});
