<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uslugis', function (Blueprint $table) {
            $table->id();
            $table->string('usl_name');
            $table->string('usl_desc')->default('Нет описания');
            $table->unsignedBigInteger('lawyerone_id')->nullable();
            $table->unsignedBigInteger('lawyertwo_id')->nullable();
            $table->unsignedBigInteger('lawyerthree_id')->nullable();
            $table->foreign('lawyerone_id')->references('id')->on('users');
            $table->foreign('lawyertwo_id')->references('id')->on('users');
            $table->foreign('lawyerthree_id')->references('id')->on('users');
            $table->string('url')->unique();
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
        Schema::dropIfExists('uslugis');
    }
};
