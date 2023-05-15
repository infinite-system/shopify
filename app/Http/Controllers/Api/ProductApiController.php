<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;
use \App\Http\Api\Shopify;
use Psr\Http\Client\ClientExceptionInterface;
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
     * @return JsonResponse
     * @throws MissingArgumentException
     * @throws \JsonException
     */
    public function store(Request $request): JsonResponse {

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
                    'image_id' => $shopifyProduct['product']['image']['id'] ?? '',
                    'image_src' => $shopifyProduct['product']['image']['src'] ?? '',
                ]
            ];

            $create = Product::create($createDto);

        } catch (ClientExceptionInterface $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500;
        } catch (\Exception $e) {
            return new JsonResponse([[
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'success' => !!$create,
            'data' => $create
        ], 200);
    }

    /**
     * Update existing product.
     *
     * @param Request $request
     * @param $product
     * @return JsonResponse
     */
    public function update(Request $request, $key): JsonResponse {

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
                    'image_id' => $updateShopifyProduct['product']['image']['id'] ?? '',
                    'image_src' => $updateShopifyProduct['product']['image']['src'] ?? '',
                ]
            ];

            $update = $product->updateOrFail($updateDto);

        } catch (ClientExceptionInterface $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'success' => !!$update,
            'data' => $product
        ], 200);
    }


    /**
     * Delete existing product.
     *
     * @param Request $request
     * @param $key
     * @return JsonResponse
     */
    public function destroy(Request $request, $key): JsonResponse {

        try {

            $product = Product::whereId($key)->firstOrFail();

            Shopify::delete("products/{$product->details['shopify']['product_id']}.json")->getDecodedBody();

            $delete = $product->delete();

        } catch (ClientExceptionInterface $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

        return new JsonResponse([
            'success' => !!$delete,
        ], 200);
    }

}