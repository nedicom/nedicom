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
        Schema::table('uslugis', function (Blueprint $table) {
            $table->bigInteger('likes')->nullable();
            $table->bigInteger('shares')->nullable();
            $table->bigInteger('bookmarks')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('likes')->nullable();
            $table->bigInteger('shares')->nullable();
            $table->bigInteger('bookmarks')->nullable();
        });

        Schema::table('bundles_socials', function (Blueprint $table) {
            $table->bigInteger('lawyer_id')->after('question_id')->nullable();
            $table->bigInteger('uslugis_id')->after('article_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uslugis', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('shares');
            $table->dropColumn('bookmarks');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('likes');
            $table->dropColumn('shares');
            $table->dropColumn('bookmarks');
        });

        Schema::table('bundles_socials', function (Blueprint $table) {
            $table->dropColumn('lawyer_id');
            $table->dropColumn('uslugis_id');
        });
    }
};
