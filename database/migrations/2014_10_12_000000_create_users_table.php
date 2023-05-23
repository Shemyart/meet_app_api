<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique()->comment('Идентификатор пользователя');
            $table->string('name')->comment('Имя пользователя');
            $table->string('city')->comment('Город пользователя');
            $table->string('country')->comment('Страна пользователя')->default('Россия');
            $table->string('description')->comment('Описание профиля');
            $table->string('gender')->comment('Пол');
            $table->date('date_of_birth')->comment('Дата рождения');
            $table->date('last_entrance')->default(date("Y-m-d H:i:s"))->comment('Дата последнего входа');
            $table->boolean('subscribe_to_news')->comment('Флаг подписки на новости');;
            $table->string('firebase_token')->comment('Токен firebase');;
            $table->date('firebase_token_update')->comment('Время обновления токена');;
            $table->integer('age')->comment('Возраст');
            $table->boolean('status')->comment('Статус пользователя');
            $table->boolean('verification')->comment('Статус верификации');
            $table->string('password')->comment('Пароль');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
