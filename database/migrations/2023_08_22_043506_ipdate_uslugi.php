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
            $table->boolean('is_main')->nullable();
            $table->unsignedBigInteger('main_usluga_id')->nullable();
            $table->foreign('main_usluga_id')->references('id')->on('uslugis');
        });
    }

    public function down()
    {
        Schema::table('uslugis', function (Blueprint $table) {
            // Для PostgreSQL используем массив
            $table->dropForeign(['main_usluga_id']);
            // Удаляем колонки
            $table->dropColumn(['is_main', 'main_usluga_id']);
        });
    }
};