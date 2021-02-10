<?php

namespace App\Http\Controllers\Bookkeeping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookkeeping\OperationRequest;
use App\Models\Bookkeeping\Account;
use App\Models\Bookkeeping\Operation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OperationController extends Controller
{
    public function index()
    {
        return Operation::where(['user_id' => Auth::id()])
                        ->paginate(10)
            ;
    }

    public function store(OperationRequest $request): JsonResponse
    {
        $account_id = $request->post('account_id');
        $operation = new Operation();
        $operation->account_id = $account_id;
        $operation->amount = $request->post('amount');
        $operation->incoming = $request->post('incoming');
        $operation->description = $request->post('description');
        $operation->user_id = Auth::id();

        $account = Account::find($account_id);
        if ($operation->incoming) {
            $account->sum += $operation->amount;
        } else {
            if ($account->sum > $operation->amount) {
                $account->sum -= $operation->amount;
            } else {
                return response()->json(
                    [
                        'message' => 'Недостаточно средств на счете',
                    ],
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }

        $account->save();
        $operation->save();

        return response()->json(
            [
                'message' => 'Операция успешно проведена',
                'description' => "Сумма на счете составляет: $account->sum",
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    public function show(Operation $operation): Operation
    {
        if (Gate::allows('show-operation', $operation)) {
            abort(403);
        }

        return $operation;
    }

    public function enrollMoney(OperationRequest $request): JsonResponse
    {
        $account_id = $request->post('account_id');
        $operation = new Operation();
        $operation->account_id = $account_id;
        $operation->amount = $request->post('amount');
        $operation->incoming = true;
        $operation->description = $request->post('description');
        $operation->user_id = Auth::id();

        $account = Account::find($account_id);
        $account->sum += $operation->amount;

        $account->save();
        $operation->save();

        return response()->json(
            [
                'message' => 'Операция успешно проведена',
                'description' => "Сумма на счете составляет: $account->sum",
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    public function withdrawMoney(OperationRequest $request): JsonResponse
    {
        $account_id = $request->post('account_id');
        $operation = new Operation();
        $operation->account_id = $account_id;
        $operation->amount = $request->post('amount');
        $operation->incoming = false;
        $operation->description = $request->post('description');
        $operation->user_id = Auth::id();

        $account = Account::find($account_id);
        if ($account->sum > $operation->amount) {
            $account->sum -= $operation->amount;
        } else {
            return response()->json(
                [
                    'Недостаточно средств на счете'
                ],
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $operation->save();
        $account->save();

        return response()->json(
            [
                'message' => 'Операция успешно проведена',
                'description' => "Сумма на счете составляет: $account->sum",
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}