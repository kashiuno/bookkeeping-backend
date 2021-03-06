<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')
     ->get(
         '/user',
         function (Request $request) {
             return $request->user();
         }
     )
;

Route::group(
    ['namespace' => 'Auth'],
    function () {
        Route::post('register', 'RegisterController');
        Route::post('login', 'LoginController');
        Route::post('logout', 'LogoutController')
             ->middleware('auth:api')
        ;
    }
);

Route::group(
    ['namespace' => 'Bookkeeping'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::apiResource('/account_types', 'AccountTypeController')
                     ->except(
                         [
                             'show',
                         ]
                     )
                ;
                Route::apiResource('/accounts', 'AccountController');
                Route::apiResource('/operations', 'OperationController')
                     ->only(['index', 'store', 'show']);
                Route::post('/operations/enroll', 'OperationController@enrollMoney');
                Route::post('/operations/withdraw', 'OperationController@withdrawMoney');
            }
        );
    }
);
