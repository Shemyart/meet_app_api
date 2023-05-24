<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {

    Route::post('auth/phone', [AuthController::class, 'phoneAuth']); //Авторизация по номеру телефона
    Route::post('auth/verify', [AuthController::class, 'verifyCode']); //Верификация кода

    Route::group(['middleware' => 'api'], function () {
        Route::post('auth/logout', [AuthController::class, 'logout']); // Выход из аккаунта
        Route::post('auth/refresh', [AuthController::class, 'refresh']); // Обновление токена
        Route::group(['middleware' => 'api', 'prefix' => 'profile'], function () {
            Route::get('/', [AuthController::class, 'profile']); // Получить профиль
            Route::post('/', [UserController::class, 'edit']); // Редактировать профиль
            Route::delete('/', [UserController::class, 'delete']); // Удалить профиль
            Route::get('/matches', [PersonController::class, 'matches']); // Получить список совпадений
            Route::get('/interests', [InterestController::class, 'getByUser']); // Получить список своих интересов
            Route::delete('/interests/{id}', [InterestController::class, 'delete']); // Удалить интерес у себя
        });
        Route::get('/interests', [InterestController::class, 'getAll']); // Получить общий список интересов
        Route::get('/interests/{id}', [InterestController::class, 'chooseInterest']); // Выбрать интерес для себя

        Route::group(['prefix' => 'persons'], function () {
            Route::get('/', [PersonController::class, 'getAll']); //Получить пользователей на выбор
            Route::get('/{id}', [PersonController::class, 'getById']); // Посмореть детальную информацию о пользователе
            Route::get('/{id}/like', [PersonController::class, 'like']); // Поставить лайк пользователю
            Route::get('/{id}/dislike', [PersonController::class, 'dislike']); // Поставить дизлайк пользователю
            Route::get('/{id}/interests', [InterestController::class, 'getByPerson']); // Посмотреть интересы пользователя
        });
        //TODO добавить чаты, добавить фильтра

    });

});

