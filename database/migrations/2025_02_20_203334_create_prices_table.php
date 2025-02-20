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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('value')->nullable();
            $table->integer('price')->nullable();
            $table->string('img')->nullable();             
        });

        Schema::create('uslugis_prices', function (Blueprint $table) {
            $table->integer('users_id')->nullable();
            $table->integer('uslugis_id')->nullable();
            $table->integer('prices_id')->nullable();
            $table->string('value')->nullable();
            $table->integer('price')->nullable();           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
        Schema::dropIfExists('uslugis_prices');
    }
};
