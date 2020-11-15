<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!Auth::attempt($request->all())) {
            return response()->json(
                [
                    'message' => 'Логин или пароль не верные',
                ],
                JsonResponse::HTTP_UNAUTHORIZED
            );
        }

        $token = Auth::user()
                     ->createToken(config('app.name'))
        ;

        $token->token->expires_at = Carbon::now()
                                          ->addDay()
        ;
        $token->token->save();

        return response()->json(
            [
                'token_type' => 'Bearer',
                'token'      => $token->accessToken,
                'expires_at' => Carbon::parse($token->token->expires_at)
                                      ->toDateTimeString(),
            ]
        );
    }
}
