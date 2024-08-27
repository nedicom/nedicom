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
            $table->boolean('is_second')->nullable();
            $table->unsignedBigInteger('second_usluga_id')->nullable();
            $table->foreign('second_usluga_id')->references('id')->on('uslugis');
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
            $table->dropColumn('is_second');
            $table->dropForeign('second_usluga_id_foreign'); 
            $table->dropColumn('second_usluga_id');
        });
    }
};
