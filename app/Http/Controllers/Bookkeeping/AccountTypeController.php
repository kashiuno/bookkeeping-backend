<?php

namespace App\Http\Controllers\Bookkeeping;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bookkeeping\AccountTypeRequest;
use App\Http\Resources\Bookkeeping\AccountTypeCollection;
use App\Models\Bookkeeping\AccountType;
use Exception;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;

class AccountTypeController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return AccountTypeCollection
     */
    public function index () {
        return new AccountTypeCollection(AccountType::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AccountTypeRequest $request
     *
     * @return JsonResponse
     */
    public function store (AccountTypeRequest $request) {
        AccountType::create($request->only(['name']));

        return response()->json([
            'message' => 'Тип счета успешно создан.',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AccountTypeRequest $request
     * @param AccountType $accountType
     *
     * @return JsonResponse
     */
    public function update (AccountTypeRequest $request, AccountType $accountType) {
        try {
            $this->tryFillModel($request, $accountType);

            return response()->json([
                'message' => 'Изменение типа счета произошло успешно.',
            ]);
        } catch (MassAssignmentException $e) {
            return response()->json([
                'message' => 'Ошибка сервера',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param AccountTypeRequest $request
     * @param AccountType $accountType
     */
    private function tryFillModel (AccountTypeRequest $request, AccountType $accountType) {
        $accountType->fill([
            'name' => $request->only(['name']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AccountType $accountType
     *
     * @return JsonResponse
     */
    public function destroy (AccountType $accountType) {
        try {
            $this->tryDelete($accountType);

            return response()->json([
                'message' => 'Удаление произошло успешно.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Не верный идентификатор типа счета.',
            ]);
        }
    }

    /**
     * @param AccountType $accountType
     *
     * @return bool|null
     * @throws Exception
     */
    private function tryDelete (AccountType $accountType) {
        return $accountType->delete();
    }
}
