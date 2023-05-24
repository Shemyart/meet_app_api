<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {

    Route::post('auth/phone', [AuthController::class, 'phoneAuth']);
    Route::post('auth/verify', [AuthController::class, 'verifyCode']);

    Route::group(['middleware' => 'api'], function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::group(['middleware' => 'api','prefix' => 'profile'], function () {
            Route::get('/', [AuthController::class, 'profile']);
            Route::post('/', [UserController::class, 'edit']);
            Route::delete('/', [UserController::class, 'delete']);
            Route::get('/matches', [PersonController::class, 'matches']);
        });
        Route::group(['prefix' => 'persons'], function () {
            Route::get('/', [PersonController::class, 'getAll']);
            Route::get('/{id}', [PersonController::class, 'getById']);
        });

    });

});

