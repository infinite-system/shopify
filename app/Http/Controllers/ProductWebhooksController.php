<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductWebhooksController extends Controller
{

    /**
     * Source:
     * https://shopify.dev/docs/apps/webhooks/configuration/https#step-5-verify-the-webhook
     *
     * @param $data
     * @param $hmac_header
     * @return bool
     */
    public function verifyWebhook($data, $hmac_header): bool {

        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, config('shopify.api.secret'), true));

        // for debugging / testing purposes
        $_SERVER['HTTP_X__TEST_CALC_HMAC'] = $calculated_hmac;
        $_SERVER['HTTP_X__TEST_HMAC_HEADER'] = $hmac_header;
        $_SERVER['HTTP_X__TEST_SECRET'] = config('shopify.api.secret');

        $hashEquals = hash_equals($calculated_hmac, $hmac_header);

        $_SERVER['HTTP_X__TEST_HASH_EQUALS'] = $hashEquals ? 'true' : 'false';

        return $hashEquals;
    }

    /**
     * Validate webhook.
     *
     * @return bool
     */
    public function validWebhook(): bool {

        $hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'] ?? '';

        $data = http_build_query(json_decode(file_get_contents('php://input')));

        return $this->verifyWebhook($data, $hmac_header);
    }

    /**
     * Simple webhook validation based on domain.
     *
     * @return bool
     */
    public function simpleWebhookValidation(): bool {
        return config('shopify.store_url') === ($_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'] ?? '');
    }

    /**
     * Create product webhook.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse {

        try {

            if (!$this->simpleWebhookValidation()) {
                return $this->unauthorized();
            }

            $product = Product::where('details->shopify->product_id', $request->id)->first();

            if ($product) {
                // product already exist and was created from this app
                // notify shopify of success, do nothing so we do not
                // create duplicate products in the database
                return response()->json(['success' => true], 200);
            }

            $createDto = [
                'name' => $request->title ?? ' ',
                'description' => $request->body_html ?? '',
                'price' => $request->variants[0]['price'] ?? 0,
                'quantity' => $request->variants[0]['inventory_quantity'] ?? 0,
            ];

            $createDto['details'] = [
                // store product id and shopify related data
                'shopify' => [
                    'product_id' => $request->id ?? '',
                    'variant_id' => $request->variants[0]['id'] ?? '',
                    'image_id' => $request->image['id'] ?? '',
                    'image_src' => $request->image['src'] ?? '',
                ],
                // for debugging / testing
                'server' => $_SERVER,
                'request' => $request->all()
            ];

            if (isset($request->image['src'])) {
                $createDto['image'] = $this->downloadImage($request->image['src']);
            }

            Product::create($createDto);

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }

    }


    /**
     * Update product webhook.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {

        try {

            if (!$this->simpleWebhookValidation()) {
                return $this->unauthorized();
            }

            $product = Product::where('details->shopify->product_id', $request->id)->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 400);
            }

            $updateDto = [
                'name' => $request->title ?? ' ',
                'description' => $request->body_html ?? '',
                'price' => $request->variants[0]['price'] ?? 0,
                'quantity' => $request->variants[0]['inventory_quantity'] ?? 0,
            ];

            $updateDto['details'] = [
                // store product id and shopify related data
                'shopify' => [
                    'product_id' => $request->id ?? '',
                    'variant_id' => $request->variants[0]['id'] ?? '',
                    'image_id' => $request->image['id'] ?? '',
                    'image_src' => $request->image['src'] ?? '',
                ],
                // for debugging / testing
                'server' => $_SERVER,
                'request' => $request->all()
            ];


            // check if image src exists
            if (isset($request->image['src'])
                // only download the image if it is different from the image
                // already stored on the server
                && $product->details['shopify']['image_src'] !== $request->image['src']
            ) {
                $updateDto['image'] = $this->downloadImage($request->image['src']);
            }

            // remove the image if it was removed from shopify
            if (!isset($request->image['src'])) {
                $updateDto['image'] = '';
            }

            $product->update($updateDto);

            return response()->json(['success' => true], 200);


        } catch (\Exception $e) {

            // Server error
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }

    }



    /**
     * Update product webhook.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse {

        try {

            if (!$this->simpleWebhookValidation()) {
                return $this->unauthorized();
            }

            $product = Product::where('details->shopify->product_id', $request->id)->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found.'
                ], 200);
            }

            $product->delete();

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {

            // Server error
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }

    }

    /**
     * Download image from Shopify.
     *
     * @param $url
     * @return string
     */
    private function downloadImage($url): string {

        $contents = file_get_contents($url);

        $name = strtok(substr($url, strrpos($url, '/') + 1), '?');

        $folder = 'images';

        $imageLocation = $folder . '/' . $name;

        $putFile = file_put_contents(public_path($folder) . '/' . $name, $contents);

        return $putFile ? $imageLocation : '';
    }

    /**
     * Return unauthorized.
     *
     * @return JsonResponse
     */
    private function unauthorized(): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized.'
        ], 401);
    }

}