<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/phone', [AuthController::class, 'phoneAuth']);
    Route::post('/verify', [AuthController::class, 'verifyCode']);

    Route::group(['middleware' => 'api','prefix' => 'auth'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::group(['middleware' => 'api','prefix' => 'profile'], function () {
            Route::post('/', [AuthController::class, 'profile']);
            Route::post('/edit', [UserController::class, 'edit']);
            Route::delete('/delete', [UserController::class, 'delete']);
        });
    });
});

