<?php

use App\Http\Controllers\AccountRecoverController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/user-registry', [UserController::class, 'store']);

Route::group(['prefix' => '/account-recover'], function () {
    Route::get('/', [AccountRecoverController::class, 'accountRecoverRequest']);
    Route::patch('/', [AccountRecoverController::class, 'passwordRecover']);
});

Route::group(['prefix' => '/auth'], function () {
    Route::post('/', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('/', [AuthController::class, 'logout']);
        Route::get('/', [AuthController::class, 'me']);
        Route::put('/', [AuthController::class, 'refresh']);
    });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::patch('/change-password', [UserController::class, 'changePassword']);
    Route::patch('/change-email', [UserController::class, 'changeEmail']);
});
