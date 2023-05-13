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
                    'status' => "active",
                    "variants" => [
                        [
                            "price" => $request->price,
                            "inventory_quantity" => $request->quantity
                        ]
                    ],
                    ...$images
                ]
            ];

            $shopifyProduct = Shopify::post('products.json', $data)->getDecodedBody();

            $createDto['details'] = [
                'shopify' => [
                    'product_id' => $shopifyProduct['product']['id'],
                    'variant_id' => $shopifyProduct['product']['variants'][0]['id'],
                    'image_id' => $shopifyProduct['product']['image']['id'],
                    'image_src' => $shopifyProduct['product']['image']['src'],
                ]
            ];

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

        try {

            $product = Product::whereId($key)->firstOrFail();

            $updateDto = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
            ];

            $images = [];
            if ($request->image) {
                $imageUpload = $this->uploadImage($request);
                $updateDto['image'] = $imageUpload['relative'];
                $images = [
                    'images' => [
                        ["attachment" => base64_encode(file_get_contents($imageUpload['absolute']))]
                    ]
                ];
            }

            $updateShopifyProduct = Shopify::put("products/{$product->details['shopify']['product_id']}.json",
                [
                    "product" => [
                        "id" => $product->details['shopify']['product_id'],
                        "title" => $request->name,
                        "body_html" => $request->description,
                        "variants" => [
                            [
                                "id" => $product->details['shopify']['variant_id'],
                                "price" => $request->price,
                                "inventory_quantity" => $request->quantity
                            ]
                        ],
                        ...$images
                    ]
                ])->getDecodedBody();


            $updateDto['details'] = [
                'shopify' => [
                    'product_id' => $updateShopifyProduct['product']['id'],
                    'variant_id' => $updateShopifyProduct['product']['variants'][0]['id'],
                    'image_id' => $updateShopifyProduct['product']['image']['id'],
                    'image_src' => $updateShopifyProduct['product']['image']['src'],
                ]
            ];

            $update = $product->updateOrFail($updateDto);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        return [
            'success' => !!$update,
            'data' => $product
        ];
    }
}