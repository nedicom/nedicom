<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['speciality_one_id']);
            $table->dropForeign(['speciality_two_id']);
            $table->dropForeign(['speciality_three_id']);
            $table->dropColumn(['speciality_one_id', 'speciality_two_id', 'speciality_three_id']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('speciality_one_id')->nullable();
            $table->unsignedBigInteger('speciality_two_id')->nullable();
            $table->unsignedBigInteger('speciality_three_id')->nullable();
            $table->foreign('speciality_one_id')->references('id')->on('uslugis');
            $table->foreign('speciality_two_id')->references('id')->on('uslugis');
            $table->foreign('speciality_three_id')->references('id')->on('uslugis');
        });
    }
};
