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

    /**
     * Upload image.
     *
     * @param Request $request
     * @return string
     */
    private function uploadImage(Request $request): string {

        $imageName = time().'.'.$request->image->extension();
        $imageFolder = public_path('images');
        $imageLocation = $imageFolder. '/' .$imageName;
        $request->image->move($imageFolder, $imageName);

        return $imageLocation;
    }

    /**
     * Add new product.
     *
     * @param Request $request
     * @param $method
     * @return array
     */
    public function store(Request $request, $method = 'create'): array {

        $create = Product::$method([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $this->uploadImage($request)
        ]);

        return [
          'success' => !!$create,
          'data' => $create
        ];
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param $product
     * @return array
     */
    public function update(Request $request, $product): array {

        $update = $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'image' => $this->uploadImage($request)
        ]);

        return [
            'success' => !!$update,
            'data' => $update
        ];
    }
}