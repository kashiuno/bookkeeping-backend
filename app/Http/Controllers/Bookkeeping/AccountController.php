<?php

namespace App\Http\Controllers\Bookkeeping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookkeeping\AccountRequest;
use App\Models\Bookkeeping\Account;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    public function index()
    {
        return Auth::user()->accounts;
    }

    public function store(AccountRequest $request)
    {
        $sum = strval($request->post('sum'));
        Account::create(array_merge($request->only(['name', 'account_type_id']), ['user_id' => Auth::id(), 'sum' => $sum]));

        return response()->json(
            [
                'message' => 'Счет успешно создан',
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    public function show(Account $account): Account
    {
        if (!Gate::allows('update-account', $account)) {
            abort(403);
        }
        return $account;
    }

    public function update(AccountRequest $request, Account $account): JsonResponse
    {
        if (!Gate::allows('update-account', $account)) {
            abort(403);
        }

        $account->fill($request->only(['name', 'account_type_id', 'sum']))
                    ->save()
        ;

        return response()->json(
            [
                'message' => 'Изменение счета произошло успешно',
            ]
        );
    }

    public function destroy(Account $account): JsonResponse
    {
        if (!Gate::allows('update-account', $account)) {
            abort(403);
        }

        try {
            $account->delete();

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
}
