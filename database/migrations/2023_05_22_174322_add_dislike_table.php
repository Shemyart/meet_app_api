<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDislikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dislike', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('Идентификатор пользователя');
            $table->integer('person_id')->comment('Идентификатор не понравившегося пользователя');
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
        Schema::dropIfExists('dislike');
    }
}
