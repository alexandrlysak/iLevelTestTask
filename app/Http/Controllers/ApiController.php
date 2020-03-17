<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Validator;

class ApiController extends Controller
{
    public function testAction()
    {
        $categories = ['Office Chairs', 'Modern Chairs', 'Home Chairs'];

        foreach ($categories as $category) {
            Category::create([
                'title' => $category,
            ]);

            $product = Product::create([
                'title' => 'Home Brixton Faux Leather Armchair'
            ]);

            $categories = Category::find([2, 3]); // Modren Chairs, Home Chairs
            $product->categories()->attach($categories);
        }
    }

    /**
     * Get All Categories
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoriesListAction(Request $request)
    {
        try {
            $categories = Category::all();
            return Response::json([
                'code' => 1,
                'message' => 'Query Success',
                'data' => [
                    'categories' => $categories
                ]
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
     * Get all Products from Category
     * @param Request $request
     * @return JsonResponse
     */
    public function getCategoryProductsListAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameter id]'
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }
            $category = Category::find($request->id);
            if (!$category) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Category with current id not found]'
                ], 404, [], JSON_UNESCAPED_UNICODE);
            }

            return Response::json([
                'code' => 1,
                'message' => 'Query Success',
                'data' => [
                    'products' => $category->products
                ]

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
