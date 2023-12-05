<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\VcardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdminController;
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

Route::prefix('admins')->group(function () {
    Route::get('/', [AdminController::class, 'index']);
    Route::get('/{admin}', [AdminController::class, 'show']);
    Route::post('/', [AdminController::class, 'store']);
    Route::put('/{admin}', [AdminController::class, 'update']);
    Route::put('/update-password/{admin}', [AdminController::class, 'update_password']);
    Route::get('/me', [AdminController::class, 'show_me'])->middleware('auth:api');
});

Route::prefix('transactions')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('/{transaction}', [TransactionController::class, 'show']);
    Route::post('/', [TransactionController::class, 'store']);
    Route::put('/{transaction}', [TransactionController::class, 'update']);
    Route::delete('/{transaction}', [TransactionController::class, 'delete']);
    Route::put('/restore/{transactionId}', [TransactionController::class, 'restore']);
});

//    }
//);
