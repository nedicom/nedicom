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
        Schema::table('articles', function (Blueprint $table) {
            $table->bigInteger('likes')->nullable();
            $table->bigInteger('shares')->nullable();
            $table->bigInteger('bookmarks')->nullable();
            $table->bigInteger('comments')->nullable();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->bigInteger('likes')->nullable();
            $table->bigInteger('shares')->nullable();
            $table->bigInteger('bookmarks')->nullable();
            $table->bigInteger('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('shares');
            $table->dropColumn('bookmarks');
            $table->dropColumn('comments');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('shares');
            $table->dropColumn('bookmarks');
            $table->dropColumn('comments');
        });
    }
};
