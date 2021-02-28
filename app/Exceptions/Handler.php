<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     *
     * @return JsonResponse
     * @throws Throwable
     */
    public function render($request, Throwable $e): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return response()->json(
                [
                    'message' => 'Данные неверные',
                    'errors'  => $e->errors(),
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        if ($e instanceof HttpException) {
            return response()->json(
                [
                    'message' => 'Ошибка',
                ],
                $e->getStatusCode()
            );
        }

        return response()->json(
            [
                'message' => 'Возникла внутренняя ошибка сервера',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
