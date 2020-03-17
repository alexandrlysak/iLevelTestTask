<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiCategoryController
 * @package App\Http\Controllers
 */
class ApiCategoryController extends Controller
{
    /**
     * Create Category
     * @param Request $request
     * @return JsonResponse
     */
    public function addCategoryAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameter title]'
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }
            Category::create(['title' => $request->title]);
            return Response::json([
                'code' => 1,
                'message' => 'Category create success'
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
     * Category update
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategoryAction(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'title' => 'required'
            ]);
            if ($validator->fails()) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Missing required parameters id or title]'
                ], 400, [], JSON_UNESCAPED_UNICODE);
            }

            $category = Category::find($request->id);
            if (!$category) {
                return Response::json([
                    'code' => 0,
                    'message' => 'Query Error [Category with current id not found]'
                ], 404, [], JSON_UNESCAPED_UNICODE);
            }

            Category::where('id', $request->id)->update(['title' => $request->title]);
            return Response::json([
                'code' => 1,
                'message' => 'Category update success'
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
     * Delete Category
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategoryAction(Request $request)
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
            Category::destroy($request->id);
            return Response::json([
                'code' => 1,
                'message' => 'Category delete success'
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
