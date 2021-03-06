<?php

namespace App\Http\Controllers\Bookkeeping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookkeeping\AccountTypeRequest;
use App\Models\Bookkeeping\AccountType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountTypeController extends Controller
{
    public function index()
    {
        return Auth::user()->accountTypes;
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
        if (!Gate::allows('update-account-type', $accountType)) {
            abort(403);
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

    public function destroy(AccountType $accountType)
    {
        if (!Gate::allows('update-account-type', $accountType)) {
            abort(403);
        }
        if (!$accountType->accounts->toArray()) {
            $accountType->delete();
        } else {
            return response()->json(
                [
                    'message' => "Тип счета [$accountType->name] имеет зависимые счета, удаление не возможно",
                ]
            );
        }

        return response()->json(
            [
                'message' => 'Удаление произошло успешно',
            ]
        );
    }
}
