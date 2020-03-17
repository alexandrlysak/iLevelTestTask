<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Response;

class ApiAuthController extends Controller
{
    public function login(Request $request)
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
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {

        return response()->json($request->user());
    }
}
