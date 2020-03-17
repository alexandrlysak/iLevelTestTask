<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ApiLoginController extends Controller
{
    /**
     * Login Action
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginAction(Request $request)
    {
        $authData = $request->only('email', 'password');
        if (!Auth::attempt($authData)) {

            return Response::json([
                'code' => 0,
                'message' => 'Wrong Email or Password',
                'data' => [
                    'errors' => 'Unauthorised'
                ]
            ], 401, [], JSON_UNESCAPED_UNICODE);
        }

        $token = Auth::user()->createToken(config('app.name'));
        $token->token->expires_at = Carbon::now()->addDay();
        $token->token->save();

        return Response::json([
            'code' => 1,
            'data' => [
                'token' => $token->accessToken,
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
