<?php

use App\Http\Controllers\AccountRecoverController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdressesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/account-recover'], function () {
    Route::get('/', [AccountRecoverController::class, 'accountRecoverRequest']);
    Route::patch('/', [AccountRecoverController::class, 'passwordRecover']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => '/auth'], function () {
        Route::post('/', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
        Route::delete('/', [AuthController::class, 'logout']);
        Route::get('/', [AuthController::class, 'me']);
        Route::put('/', [AuthController::class, 'refresh']);
    });

    Route::group(['prefix' => '/user'], function () {
        Route::post('/', [UserController::class, 'store'])->withoutMiddleware('auth:sanctum');
        Route::delete('/', [UserController::class, 'destroy']);
    });

    Route::get('/adress', [AdressesController::class, 'getAddressByCEP']);
    Route::get('/cep', [AdressesController::class, 'getCepByAdress']);

    Route::group(['prefix' => '/contact'], function () {
        Route::get('/', [ContactController::class, 'index']);
        Route::get('/{contact}', [ContactController::class, 'show']);
        Route::get('/search/query', [ContactController::class, 'search']);
        Route::post('/', [ContactController::class, 'store']);
        Route::put('/{contact}', [ContactController::class, 'update']);
        Route::delete('/{contact}', [ContactController::class, 'destroy']);
    });
});
