<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ApiLogoutController extends Controller
{
    /**
     * Logout Action
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAction(Request $request)
    {
        $request->user()->token()->revoke();
        return Response::json([
            'code' => 1,
            'data' => [
                'message' => 'You are successfully logged out',
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);



    }
}
