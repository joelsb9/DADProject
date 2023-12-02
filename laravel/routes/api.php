<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\VcardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\TransactionController;

//Auth::routes();

Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post(
    'logout',
    [AuthController::class, 'auth/logout']
);

Route::middleware('auth:api')->group(
    function () {
        Route::apiResource('categories', CategoryController::class);
    }
);

Route::prefix('vcards')->group(function () {
    Route::get('/', [VcardController::class, 'index']);
    Route::get('{vcard}', [VcardController::class, 'show']);
    Route::post('/', [VcardController::class, 'store']);
    Route::put('{vcard}', [VcardController::class, 'update']);
    Route::delete('{vcard}', [VcardController::class, 'delete']);
    Route::put('restore/{vcardId}', [VcardController::class, 'restore']);
    Route::get('me', [VcardController::class, 'show_me']);
    Route::put('update-password/{vcard}', [VcardController::class, 'update_password']);
    Route::get('{vcard}/transactions', [TransactionController::class, 'getTransactionsForVcard']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{user}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::put('/update-password/{user}', [UserController::class, 'update_password']);
    Route::get('/me', [UserController::class, 'show_me'])->middleware('auth:api');
});

Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/{transaction}', [TransactionController::class, 'show']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::put('/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/{transaction}', [TransactionController::class, 'delete']);
    Route::put('/restore/{transactionId}', [TransactionController::class, 'restore']);
});


