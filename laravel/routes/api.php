<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\VcardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\api\TransactionController;
use App\Http\Controllers\Api\DefaultCategoryController;

//Auth::routes();

Route::post('auth/login', [AuthController::class, 'login']);
Route::middleware('auth:api')->post(
    'logout',
    [AuthController::class, 'auth/logout']
);

Route::middleware('auth:api')->group(
    function () {
        Route::apiResource('categories', CategoryController::class);
        Route::post('categories/{categoryId}/restore', [CategoryController::class, 'restore']);
        Route::apiResource('vcards', VcardController::class);
        Route::put('vcards/restore/{vcardId}', [VcardController::class, 'restore']);
        Route::get('vcards/me', [VcardController::class, 'show_me']);
        Route::put('vcards/update-password/{vcard}', [VcardController::class, 'update_password']);
        Route::get('vcards/{vcard}/transactions', [TransactionController::class, 'getTransactionsForVcard']);

        Route::apiResource('admins', AdminController::class);
        Route::get('admins/me', [AdminController::class, 'show_me'])->middleware('auth:api');
        Route::put('admins/update-password/{admin}', [AdminController::class, 'update_password']);

        Route::apiResource('transactions', TransactionController::class);
        Route::put('transactions/restore/{transactionId}', [TransactionController::class, 'restore']);

        Route::apiResource('default-categories', DefaultCategoryController::class);
        Route::post('default-categories/{defaultCategoryId}/restore', [DefaultCategoryController::class, 'restore']);
    }
);


// Route::prefix('vcards')->group(function () {
//     Route::get('/', [VcardController::class, 'index']);
//     Route::get('{vcard}', [VcardController::class, 'show']);
//     Route::post('/', [VcardController::class, 'store']);
//     Route::put('{vcard}', [VcardController::class, 'update']);
//     Route::delete('{vcard}', [VcardController::class, 'delete']);
//     Route::put('restore/{vcardId}', [VcardController::class, 'restore']);
//     Route::get('me', [VcardController::class, 'show_me']);
//     Route::put('update-password/{vcard}', [VcardController::class, 'update_password']);
//     Route::get('{vcard}/transactions', [TransactionController::class, 'getTransactionsForVcard']);
// });

// Route::prefix('admins')->group(function () {
//     Route::get('/', [AdminController::class, 'index']);
//     Route::get('/{admin}', [AdminController::class, 'show']);
//     Route::post('/', [AdminController::class, 'store']);
//     Route::put('/{admin}', [AdminController::class, 'update']);
//     Route::put('/update-password/{admin}', [AdminController::class, 'update_password']);
//     Route::get('/me', [AdminController::class, 'show_me'])->middleware('auth:api');
// });

// Route::prefix('transactions')->group(function () {
//     Route::get('/', [TransactionController::class, 'index']);
//     Route::get('/{transaction}', [TransactionController::class, 'show']);
//     Route::post('/', [TransactionController::class, 'store']);
//     Route::put('/{transaction}', [TransactionController::class, 'update']);
//     Route::delete('/{transaction}', [TransactionController::class, 'delete']);
//     Route::put('/restore/{transactionId}', [TransactionController::class, 'restore']);
// });

//  Route::prefix('default-categories')->group(function () {
//     Route::get('/', [DefaultCategoryController::class, 'index']);
//     Route::get('/{defaultCategory}', [DefaultCategoryController::class, 'show']);
//     Route::post('/', [DefaultCategoryController::class, 'store']);
//     Route::put('/{defaultCategory}', [DefaultCategoryController::class, 'update']);
//     Route::delete('/{defaultCategory}', [DefaultCategoryController::class, 'destroy']);
//     Route::post('/{defaultCategoryId}/restore', [DefaultCategoryController::class, 'restore']);
// });

// Route::prefix('categories')->group(function () {
//     Route::get('/', [CategoryController::class, 'index']);
//     Route::get('/{category}', [CategoryController::class, 'show']);
//     Route::post('/', [CategoryController::class, 'store']);
//     Route::put('/{category}', [CategoryController::class, 'update']);
//     Route::delete('/{category}', [CategoryController::class, 'destroy']);
//     Route::post('/{categoryId}/restore', [CategoryController::class, 'restore']);
// });

