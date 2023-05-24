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
            $table->string('city')->comment('Город пользователя')->nullable();
            $table->string('country')->comment('Страна пользователя')->default('Россия');
            $table->string('description')->comment('Описание профиля')->nullable();
            $table->string('gender')->comment('Пол')->nullable();
            $table->date('date_of_birth')->comment('Дата рождения')->nullable();
            $table->date('last_entrance')->default(date("Y-m-d H:i:s"))->comment('Дата последнего входа');
            $table->boolean('subscribe_to_news')->default(1)->comment('Флаг подписки на новости');;
            $table->string('firebase_token')->nullable()->comment('Токен firebase');;
            $table->date('firebase_token_update')->nullable()->comment('Время обновления токена');;
            $table->integer('age')->nullable()->comment('Возраст');
            $table->boolean('status')->default(1)->comment('Статус пользователя');
            $table->boolean('verification')->default(0)->comment('Статус верификации');
            $table->string('password')->nullable()->comment('Пароль');
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
