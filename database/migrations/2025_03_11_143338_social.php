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
            $table->bigInteger('vk')->nullable();
            $table->bigInteger('ok')->nullable();
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
            $table->dropColumn('vk');
            $table->dropColumn('ok');
        });
    }
};
