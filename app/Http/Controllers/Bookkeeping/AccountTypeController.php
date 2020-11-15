<?php

namespace App\Http\Controllers\Bookkeeping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookkeeping\AccountTypeRequest;
use App\Http\Resources\Bookkeeping\AccountTypeCollection;
use App\Models\Bookkeeping\AccountType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccountTypeController extends Controller
{
    public function index(): AccountTypeCollection
    {
        return new AccountTypeCollection(
            AccountType::where('user_id', Auth::id())
                       ->get()
        );
    }

    public function store(AccountTypeRequest $request): JsonResponse
    {
        AccountType::create(array_merge($request->only('name'), ['user_id' => Auth::id()]));

        return response()->json(
            [
                'message' => 'Тип счета успешно создан',
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    public function update(AccountTypeRequest $request, AccountType $accountType): JsonResponse
    {
        if (!$accountType->isMutableByCurrentUser()) {
            return $this->getForbiddenResponse();
        }

        $accountType->fill($request->only('name'))
                    ->save()
        ;

        return response()->json(
            [
                'message' => 'Изменение типа счета произошло успешно',
            ]
        );
    }

    public function destroy(AccountType $accountType): JsonResponse
    {
        if (!$accountType->isMutableByCurrentUser()) {
            return $this->getForbiddenResponse();
        }

        try {
            $accountType->delete();

            return response()->json(
                [
                    'message' => 'Удаление произошло успешно',
                ]
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => 'Не верный идентификатор типа счета',
                ],
                JsonResponse::HTTP_NOT_FOUND,
            );
        }
    }

    private function getForbiddenResponse(): JsonResponse
    {
        return response()->json(
            [
                'message' => 'Нет доступа к данному типу счета',
            ],
            JsonResponse::HTTP_FORBIDDEN
        );
    }
}
