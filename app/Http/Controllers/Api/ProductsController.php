<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class ProductsController extends Controller
{
    /**
     * Fully-qualified model class name
     */
    protected $model = Product::class;

    public function store(Request $request) {

    }
}