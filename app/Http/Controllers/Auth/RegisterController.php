<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterFormRequest;
use App\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  RegisterFormRequest  $request
     * @return JsonResponse
     */
    public function __invoke(RegisterFormRequest $request)
    {
        User::create(array_merge(
            $request->only('name', 'email'),
            ['password' => bcrypt($request->get('password'))]
        ));

        return response()->json([
            'message' => 'You were successfully registered. Use your email and password to sign in'
        ], JsonResponse::HTTP_OK);
    }
}
