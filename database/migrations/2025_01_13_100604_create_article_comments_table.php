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
        Schema::create('article_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('body');
            $table->unsignedBigInteger('rating')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('parent_comment_id')->nullable();
            $table->foreign('parent_comment_id')->references('id')->on('article_comments');
            $table->foreign('users_id')->references('id')->on('users');
            $table->foreign('article_id')->references('id')->on('articles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_comments');
    }
};
