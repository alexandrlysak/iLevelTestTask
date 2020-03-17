<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiProductController
 * @package App\Http\Controllers
 */
class ApiProductController extends Controller
{
    /**
     * Create Product
     * @param Request $request
     * @return JsonResponse
     */
    public function addProductAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'categories_ids' => 'required|array'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameter title or categories_ids]',
                    'validatorMessages' => $validator->errors()
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }

            $product = new Product();
            $product->title = $request->title;

            $categoriesIsExists = FALSE;
            foreach ($request->categories_ids as $categoryId) {
                $category = Category::find($categoryId);
                if ($category) {
                    $categoriesIsExists = TRUE;

                    $product->save();
                    $product->categories()->save($category);
                }
            }

            if (!$categoriesIsExists) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Categories with current ids not found]'
                ], 404, [], JSON_UNESCAPED_UNICODE);
            }

            return Response::json([
                'code' => 1,
                'message' => 'Product create success'
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $ex) {
            return Response::json([
                'code' => 0,
                'message' => 'Query Error',
                'data' => [
                    'error' => $ex->getMessage()
                ]
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update Product data
     * @param Request $request
     * @return JsonResponse
     */
    public function editProductAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'categories_ids' => 'array'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameter id]',
                    'validatorMessages' => $validator->errors()
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }

            if (!$request->title && !$request->categories_tds) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Nothing to update]',
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }

            $product = Product::find($request->id);
            if (!$product) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Product with current id not found]'
                ], 404, [], JSON_UNESCAPED_UNICODE);
            }

            if ($request->categories_ids) {
                $product->categories()->detach();
                $categoriesIsExists = FALSE;
                foreach ($request->categories_ids as $categoryId) {
                    $category = Category::find($categoryId);
                    if ($category) {
                        $categoriesIsExists = TRUE;

                        $product->categories()->save($category);
                    }
                }
                if (!$categoriesIsExists) {
                    return Response::json([
                        'code' => 0,
                        'message' => 'Query Error [Categories with current ids not found]'
                    ], 404, [], JSON_UNESCAPED_UNICODE);
                }
            }

            if ($request->title) {
                Product::where('id', $request->id)->update(['title' => $request->title]);
            }

            return Response::json([
                'code' => 1,
                'message' => 'Product update success'
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $ex) {
            return Response::json([
                'code' => 0,
                'message' => 'Query Error',
                'data' => [
                    'error' => $ex->getMessage()
                ]
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Delete Product
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteProductAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameters id]'
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }
            Product::destroy($request->id);
            return Response::json([
                'code' => 1,
                'message' => 'Product delete success'
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $ex) {
            return Response::json([
                'code' => 0,
                'message' => 'Query Error',
                'data' => [
                    'error' => $ex->getMessage()
                ]
            ], 500, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
