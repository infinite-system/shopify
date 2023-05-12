<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class ProductApiController extends Controller
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
        $folder = 'images';
        $absoluteImageFolder = public_path($folder);

        $request->image->move($absoluteImageFolder, $imageName);

        return $folder. '/' .$imageName;
    }

    /**
     * Add new product.
     *
     * @param Request $request
     * @param $method
     * @return array
     */
    public function store(Request $request): array {

        $createDto = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ];

        if ($request->image) {
            $createDto['image'] = $this->uploadImage($request);
        }

        $create = Product::create($createDto);

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
    public function update(Request $request, $key): array {

        $product = Product::whereId($key)->firstOrFail();

        $updateDto = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ];

        if ($request->image) {
            $updateDto['image'] = $this->uploadImage($request);
        }

        $update = $product->updateOrFail($updateDto);

        return [
            'success' => !!$update,
            'data' => $product
        ];
    }
}