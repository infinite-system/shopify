<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use \App\Http\Api\Shopify;
use Shopify\Exception\MissingArgumentException;

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
    private function uploadImage(Request $request): array {

        $imageName = time() . '.' . $request->image->extension();
        $folder = 'images';
        $absoluteImageFolder = public_path($folder);

        $request->image->move($absoluteImageFolder, $imageName);

        return [
            'relative' => $folder . '/' . $imageName,
            'absolute' => $absoluteImageFolder . '/' . $imageName
        ];
    }

    /**
     * Add new product.
     *
     * @param Request $request
     * @param $method
     * @return array
     * @throws MissingArgumentException
     * @throws \JsonException
     */
    public function store(Request $request): array {

        try {

            $createDto = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ];

            $images = [];
            if ($request->image) {
                $imageUpload = $this->uploadImage($request);
                $createDto['image'] = $imageUpload['relative'];
                $images = [
                    'images' => [
                        ["attachment" => base64_encode(file_get_contents($imageUpload['absolute']))]
                    ]
                ];
            }

            $data = [
                'product' => [
                    'title' => $request->name,
                    'body_html' => $request->description,
                    'vendor' => "",
                    'product_type' => "",
                    'status' => "active",
                    ...$images
                ]
            ];

            $shopifyProduct = Shopify::post('products.json', $data)->getDecodedBody();

            $variants = Shopify::get("products/{$shopifyProduct['product']['id']}/variants.json")
                ->getDecodedBody();

            $setVariant = Shopify::put("variants/{$variants['variants'][0]['id']}",
                [
                    "variant" => [
                        "option1" => "Default",
                        "price" => $request->price
                    ]
                ])->getDecodedBody();;

            $create = Product::create($createDto);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
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